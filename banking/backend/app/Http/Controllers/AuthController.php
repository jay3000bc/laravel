<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register','verifyOTP','sendOTP']]);
    }
 
 
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register() {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'agree' => 'required',
        ]);
 
        if($validator->fails()){
            // return response()->json($validator->errors()->toJson(), 400);
            return response()->json([
                'error' => $validator->errors()->toJson(),
                'status' => 0,
            ]);
        }


        // Save the OTP to the user's record

        $otp = $this->generateOTP();
        $otpExpiration = Carbon::now()->addMinutes(1);

        $user = new User;
        $user->name = request()->name;
        $user->email = request()->email;
        $user->password = bcrypt(request()->password);
        $user->otp = $otp;
        $user->otp_expires_at = $otpExpiration;
        $user->save();

        // dd($user['email']);

        // Send the OTP via email
        Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
            $message->to(request()->email)->subject('OTP for Email Verification');
        });

        return response()->json([
            'message' => 'OTP sent successfully',
            'status' =>1,
        ]);

        // return response()->json($user, 201);
    }
 
 
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $password = $request->input('password');
        $key = 42; 
    
        $decryptedPassword = $this->xorDecrypt($password, $key);
    
        $credentials = [
            'email' => $request->email,
            'password' => $decryptedPassword,
        ];
     
        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                'error' => 'Invalid Credentials',
                'status'=>0
            ]);
        }

        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                'error' => 'Invalid Credentials',
                'status'=>0
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if ($user->otp_verified_at) {
            return $this->respondWithToken($token);
        }
        else{
            // if email verification is not done, resend otp for verification

            $user = User::where('email', $request->email)->first();

            if($user){
                $otp = $this->generateOTP();
    
                // Save the OTP to the user's record
                $user->otp = $otp;
                $user->otp_expires_at = Carbon::now()->addMinutes(1);
                $user->save();
        
                // Send the OTP via email
                Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
                    $message->to($user->email)->subject('OTP for Email Verification');
                });
        
                return response()->json([
                    'message' => 'OTP sent successfully',
                    'status'=>2,
                ]);
            }
            else{
                return response()->json([
                    'message' => 'Invalid Email ID',
                    'status' =>0,
                ]);
            }
        }
    }


    private function xorDecrypt($text, $key)
    {
        $decryptedText = '';
        for ($i = 0; $i < strlen($text); $i++) {
            $decryptedText .= chr(ord($text[$i]) ^ $key);
        }
        return $decryptedText;
    }
 
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {
            // $user = auth()->userOrFail();
            return response()->json(auth()->user());
        } catch (\PHPOpenSourceSaver\JWTAuth\Exceptions\UserNotDefinedException $e) {
            echo 'Token Expired';
        }
    
    }
 
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
 
        return response()->json(['message' => 'Successfully logged out']);
    }
 
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
 
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 3600
        ]);
    }

    public function generateOTP($length = 6)
    {
        return str_pad(rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }

    public function sendOTP(Request $request)
    {
        
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email',
        ]);
 

        $user = User::where('email', $request->email)->first();

        if($user){
            $otp = $this->generateOTP();

            // Save the OTP to the user's record
            $user->otp = $otp;
            $user->otp_expires_at = Carbon::now()->addMinutes(1);
            $user->save();
    
            // Send the OTP via email
            Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
                $message->to($user->email)->subject('OTP for Email Verification');
            });
    
            return response()->json(['message' => 'OTP sent successfully']);
        }
        else{
            return response()->json(['message' => 'Invalid Email ID'], 422);
        }

    }

   
    public function verifyOTP(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);
 
        if($validator->fails()){
            // return response()->json($validator->errors()->toJson(), 400);

            return response()->json([
                'error' => $validator->errors()->toJson(),
                'status' => 2,
            ]);
        }

        // $user = $request->user();

        $user = User::where('email', $request->email)->first();


        if($user){

            if ($user->otp_verified_at) {
                $token = auth()->login($user);
                return response()->json([
                    'message' => 'OTP is already verified',
                    'status' =>1
                ]);
            }
    
            // Check if OTP has expired
            if (Carbon::now()->gt($user->otp_expires_at)) {
                return response()->json([
                    'message' => 'OTP has expired',
                    'status' =>0
                ]);
            }
    
            if ($request->otp == $user->otp) {
                // Mark the email and OTP as verified
                $user->email_verified_at = now();
                $user->otp_verified_at = now();
                $user->save();
    
                // Generate JWT token
                // $token = auth()->login($user);
    
                return response()->json([
                    'message' => 'Email and OTP verified successfully', 
                    // 'token' => $token,
                    'status' =>1
                ]);
            }
    
            return response()->json([
                'message' => 'Invalid OTP',
                'status' => 0,
            ]);
        }
        else{
            return response()->json([
                'message' => 'Invalid Email ID',
                'status' => 0,
            ]);
        }
    }


}