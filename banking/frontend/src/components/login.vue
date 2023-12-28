<script>
  import {
    ref,
    onMounted
  } from 'vue';
  import axios from 'axios';
  import {
    useRouter
  } from 'vue-router';
  import Cookies from 'js-cookie';
  import VueCookies from 'vue-cookies';

  import loader from '../components/loader.vue';

  import CryptoJS from 'crypto-js';

  export default {
    name: 'Login',
    components: {
      loader
    },
    setup() {
      const email = ref('');
      const password = ref('');
      const rememberMe = ref(false);
      const loading = ref(false);
      const router = useRouter();
      const isLoading = ref(false);
      const login_error = ref('');

      const formDiv = ref(true);
      const otpDiv = ref(false);

      const successDiv = ref(false);
      const successMessage = ref('');

      const otp = ref('');
      const otp_error = ref('');

      // Load email and password from cookies when the component is created
      onMounted(() => {
        const storedEmail = Cookies.get('user_email');
        const storedPassword = Cookies.get('user_password');

        if (storedEmail && storedPassword) {
          email.value = storedEmail;
          password.value = storedPassword;
          rememberMe.value = true;
        }
      });


      const xorEncrypt = (text, key) => {
        let result = '';
        for (let i = 0; i < text.length; i++) {
          result += String.fromCharCode(text.charCodeAt(i) ^ key);
        }
        return result;
      };

      const submit = async () => {
        isLoading.value = true;

         // Use XOR encryption on the password
         const encryptedPassword = xorEncrypt(password.value, 42);

        const response = await axios.post('/login', {
          email: email.value,
          password: encryptedPassword,
        });

        // console.log('response', response.data);

        if (response.data.status == 0) {

          login_error.value = response.data.error
          isLoading.value = false;

        } 

        else if(response.data.status == 2){
          formDiv.value = false;
          otpDiv.value = true;
          isLoading.value = false;
        }
        
        else if (response.data.access_token) {
          login_error.value = '';
          // Set a cookie if "Remember Me" is checked
          if (rememberMe.value) {
            Cookies.set('user_email', email.value, {
              expires: 7
            });
            Cookies.set('user_password', password.value, {
              expires: 7
            });
            Cookies.set('rememberMe', password.value, {
              expires: 7
            });
          } else {
            // Clear cookies if "Remember Me" is unchecked
            Cookies.remove('user_email');
            Cookies.remove('user_password');
            Cookies.remove('rememberMe');
          }
          VueCookies.set('token', response.data.access_token, '7d');
          // console.log('response', VueCookies.get('token'));
          axios.defaults.headers['Authorization'] = `Bearer ${response.data.access_token}`;
          // await router.push({ path: '/profile' });
          isLoading.value = false;
          router.push('/profile');
        }

      };

      const verifyOTP = async () => {
        isLoading.value = true;
        const response = await axios.post('/verify-otp', {
          email: email.value,
          otp: otp.value,
        });


        if(response.data.status == 2){
          isLoading.value = false;
          const error = JSON.parse(response.data.error);
          if (error.otp) {
            otp_error.value = error.otp[0];
          } else {
            otp_error.value = null;
          }
        }

        else if(response.data.status == 0){
          isLoading.value = false;
          otp_error.value = response.data.message;
        }
        else if(response.data.status == 1){
          formDiv.value = false;
          otpDiv.value = false;
          successDiv.value = true;
          successMessage.value = response.data.message;
        }
        isLoading.value = false;

      };

      // Load rememberMe value from the cookie when the component is created
      const loadRememberMe = () => {
        const rememberMeCookie = Cookies.get('rememberMe');
        rememberMe.value = rememberMeCookie === 'true';
        // console.log('cookies loaded');
      };

      // Call the function to load rememberMe when the component is created
      loadRememberMe();

      return {
        email,
        password,
        rememberMe,
        submit,
        verifyOTP,
        isLoading,
        login_error,
        formDiv,
        otpDiv,
        otp,
        otp_error,
        successMessage,
        successDiv,
      };
    },
  };
</script>

<template>
  <div class="flex items-center justify-center h-screen flex-col">

    <!-- Heading outside the card (center-aligned) -->
    <h1 class="text-2xl font-bold mb-8">ABC BANK</h1>

    <!-- Card Container -->
    <div class="bg-white p-8 rounded shadow-md w-96" v-if="formDiv">

      <!-- Heading inside the card (left-aligned) -->
      <h2 class="text-xl font-normal mb-4">Login to your account</h2>

      <!-- Card Content (Login Form) -->
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label for="email" class="block text-sm font-bold text-gray-600">Email Address</label>
          <input v-model="email" type="email" id="email" name="email" class="mt-1 p-2 w-full border rounded-md"
            placeholder="Enter email" />
        </div>

        <div class="mb-4">
          <label for="password" class="block text-sm font-bold text-gray-600">Password</label>
          <input v-model="password" type="password" id="password" name="password"
            class="mt-1 p-2 w-full border rounded-md" placeholder="Enter password" />
        </div>
        
        <div class="mb-4">
          <div v-if="login_error" class="text-red-500 mt-2">{{ login_error }}</div>
        </div>



        <div class="mb-4">
          <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md">Sign in</button>
        </div>
      </form>
    </div>

    <div class="bg-white p-8 rounded shadow-md w-96" v-if="otpDiv">
      <h2 class="text-xl font-normal mb-4">Email Verification</h2>

      <div class="mb-4">
        <label for="otp" class="block text-sm font-bold text-gray-600">Enter OTP</label>
        <input v-model="otp" type="text" id="otp" name="otp"
          class="mt-1 p-2 w-full border rounded-md" placeholder="Enter OTP" />
        <div v-if="otp_error" class="text-red-500 mt-2">{{ otp_error }}</div>
      </div>

      <div class="mb-4">
        <button @click="verifyOTP" class="w-full bg-blue-500 text-white p-2 rounded-md">Verify</button>
      </div>

    </div>

    <div class="bg-white p-8 rounded shadow-md w-96" v-if="successDiv">
      <div v-if="successMessage" class="text-green-500 mt-2">{{ successMessage }}</div>
      <!-- <router-link class="text-blue-500 mt-2" to="/">Click here to login</router-link> -->
      <a href="/banking/frontend1/" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">
        Click here to login
      </a>
    </div>
    
    <p class="mt-10 text-center text-sm text-gray-500">
      Don't have an account?
      <a href="/banking/frontend1/register" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Sign
        up</a>
    </p>
  </div>
  <loader :isLoading="isLoading" />

</template>