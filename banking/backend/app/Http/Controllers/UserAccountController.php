<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Statement;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class UserAccountController extends Controller
{
    //

    public function initiateTransaction(Request $request){

        $validator = Validator::make(request()->all(), [
            'type' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }


        if($request->type == 'Credit'){
            $validator = Validator::make(request()->all(), [
                'amount' => 'required|numeric',
                // 'transaction_otp' => 'required|digits:6',
            ]);

            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }
        }
        else if($request->type == 'Debit'){
            $validator = Validator::make(request()->all(), [
                'amount' => 'required|numeric',
            ]);
        }
        else if($request->type == 'Transfer'){
            $validator = Validator::make(request()->all(), [
                'amount' => 'required|numeric',
                'email' => 'required|email',
            ]);

            $userData = auth()->user();
            if($request->email == $userData->email){
                return response()->json([
                    'email_exist' => 'Transfer to same account is not possible',
                    'status' => 0],
                );
            }

            $checkEmailExist = User::where('email', $request->email)->first();

            if(!$checkEmailExist){
                return response()->json([
                    'email_exist' => 'Sorry Email Does not exsits.',
                    'status' => 0],
                );
            }
        }


        $userData = auth()->user();
        $transaction_otp = $this->generateOTP();

        // Save the OTP to the user's record
        $userData->transaction_otp = $transaction_otp;
        $userData->transaction_otp_expires_at = Carbon::now()->addMinutes(1);
        $userData->save();

        // Send the OTP via email
        $mailStatus = Mail::raw("Your OTP is: $transaction_otp", function ($message) use ($userData) {
            $message->to($userData->email)->subject('OTP for Transaction');
        });

        if ($mailStatus) {
            return response()->json([
                'message' => 'Transaction OTP sent. Please check your email',
                'status' => 1,
            ]);
        }
        else{
            return response()->json([
                'message' => 'Sorry !! Something Wrong. Please try again after sometime',
                'status' => 0,
            ]);
        }
    }

    public function processTransaction(Request $request){

        $validator = Validator::make(request()->all(), [
            'type' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        if($request->type == 'Credit'){
            $validator = Validator::make(request()->all(), [
                'amount' => 'required|numeric',
                'transaction_otp' => 'required|digits:6',
            ]);

            if($validator->fails()){
                // return response()->json($validator->errors()->toJson(), 400);
                return response()->json([
                    'error' => $validator->errors()->toJson(),
                    'status' => 0,
                ]);
            }

            $userData = auth()->user();
            // Check if OTP has expired
            if (Carbon::now()->gt($userData->transaction_otp_expires_at)) {
                return response()->json([
                    'otp_expire' => 'OTP has expired',
                    'status' => 0],
                );
            }

            if ($request->transaction_otp == $userData->transaction_otp) {
       
                $user = User::find($userData->id);
                $user->balance = (double)$userData->balance + (double)$request->amount;
                $user->transaction_otp_verified_at = now();
                $user->save();
    
                $statement = $user->statements()->create([
                    'balance' => $request->amount,
                    'current_balance'=>$user->balance,
                    'type' => 'Credit',
                    'details' =>'Deposit'
                ]);
    
                return response()->json([
                    'message' => 'Amount Successfully Deposited',
                    'status'=>1,
                ]);
            }
            else{
                return response()->json([
                    'invalid_otp' => 'Invalid Transaction OTP',
                    'status'=>0,
                ]);
            }

        }
        else if($request->type == 'Debit'){
            $validator = Validator::make(request()->all(), [
                'amount' => 'required|numeric',
                'transaction_otp' => 'required|digits:6',
            ]);

            if($validator->fails()){
                // return response()->json($validator->errors()->toJson(), 400);
                return response()->json([
                    'error' => $validator->errors()->toJson(),
                    'status' => 0,
                ]);
            }

            $userData = auth()->user();
            // Check if OTP has expired
            if (Carbon::now()->gt($userData->transaction_otp_expires_at)) {
                return response()->json([
                    'otp_expire' => 'OTP has expired',
                    'status' => 0],
                );
            }

            if ($request->transaction_otp == $userData->transaction_otp) {

                if($request->amount > $userData->balance){
                    return response()->json([
                        'shortest_money' => 'You have balance of RS.'.$userData->balance.' Transaction cannot be processed',
                        'status'=>0,
                    ]);
                }
       
                $user = User::find($userData->id);
                $user->balance = (double)$userData->balance - (double)$request->amount;
                $user->transaction_otp_verified_at = now();
                $user->save();
    
                $statement = $user->statements()->create([
                    'balance' => $request->amount,
                    'current_balance'=>$user->balance,
                    'type' => 'Debit',
                    'details' =>'Withdraw'
                ]);
    
                return response()->json([
                    'message' => 'Amount Successfully Withdraw',
                    'status'=>1,
                ]);
            }
            else{
                return response()->json([
                    'invalid_otp' => 'Invalid Transaction OTP',
                    'status'=>0,
                ]);
            }

        }
        else if($request->type == 'Transfer'){
             $validator = Validator::make(request()->all(), [
                'amount' => 'required|numeric',
                'transaction_otp' => 'required|digits:6',
                'email' => 'required|email',
            ]);

            if($validator->fails()){
                // return response()->json($validator->errors()->toJson(), 400);
                return response()->json([
                    'error' => $validator->errors()->toJson(),
                    'status' => 0,
                ]);
            }

            $userData = auth()->user();
            // Check if OTP has expired
            if (Carbon::now()->gt($userData->transaction_otp_expires_at)) {
                return response()->json([
                    'otp_expire' => 'OTP has expired',
                    'status' => 0],
                );
            }

            if ($request->transaction_otp == $userData->transaction_otp) {

                if($request->amount > $userData->balance){
                    return response()->json([
                        'shortest_money' => 'You have balance of RS.'.$userData->balance.' Transaction cannot be processed',
                        'status'=>0,
                    ]);
                }
       
                $user = User::find($userData->id);
                $user->balance = (double)$userData->balance - (double)$request->amount;
                $user->transaction_otp_verified_at = now();
                $user->save();

                // credit balance to recepient account
                $recepientUser = User::where('email', $request->email)->first();
                $recepientUser->balance = (double)$recepientUser->balance + (double)$request->amount;
                $recepientUser->save();
    
                $statement = $user->statements()->create([
                    'balance' => $request->amount,
                    'current_balance'=>$user->balance,
                    'type' => 'Debit',
                    'details' =>'Transfer to '.$recepientUser->email,
                    'transfer_to' => $request->email,
                ]);

                $recepientUserStatement =  $recepientUser->statements()->create([
                    'balance' => $request->amount,
                    'current_balance'=>$recepientUser->balance,
                    'type' => 'Credit',
                    'details' =>'Transfer from '.$user->email,
                    'transfer_to' => $request->email,
                ]);
    
                return response()->json([
                    'message' => 'Amount Successfully Transferred',
                    'status'=>1,
                ]);
            }
            else{
                return response()->json([
                    'invalid_otp' => 'Invalid Transaction OTP',
                    'status'=>0,
                ]);
            }
        }


    }

    public function generateOTP($length = 6)
    {
        return str_pad(rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }


    public function statementsData(Request $request){
        $user = auth()->user();

        // $statementsData = $user->statements;
        $statementsData = $user->statements()->orderBy('created_at', 'desc')->get();


        return response()->json([
            'statementsData' =>$statementsData,
        ]);
        
    }

}
