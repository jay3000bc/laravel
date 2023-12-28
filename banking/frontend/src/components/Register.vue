<script>
  import {
    ref
  } from 'vue';
  import axios from 'axios';
  import {
    useRouter
  } from 'vue-router';
  import loader from '../components/loader.vue';
  export default {
    name: 'Register',
    components:{
      loader
    },
    setup() {
      const name = ref('');
      const email = ref('');
      const password = ref('');
      const password_confirmation = ref('');
      const agree = ref('');
      const router = useRouter();
      const validation = ref([]);

      const name_error = ref('');
      const email_error = ref('');
      const password_error = ref('');
      const password_confirmation_error = ref('');
      const agree_error = ref('');
      const formDiv = ref(true);
      const otpDiv = ref(false);

      const successDiv = ref(false);
      const successMessage = ref('');

      const otp = ref('');
      const otp_error = ref('');
      const isLoading = ref(false);

      const submit = async () => {
        isLoading.value = true;
        const response = await axios.post('/register', {
          name: name.value,
          email: email.value,
          password: password.value,
          password_confirmation: password_confirmation.value,
          agree: agree.value,
        });

        if (response.data.status == 0) {
          isLoading.value = false;
          const error = JSON.parse(response.data.error);

          if (error.name) {
            name_error.value = error.name[0];
          } else {
            name_error.value = null;
          }

          if (error.email) {
            email_error.value = error.email[0];
          } else {
            email_error.value = null;
          }

          if (error.password) {
            password_error.value = error.password[0];
          } else {
            password_error.value = null;
          }

          if (error.password_confirmation) {
            password_confirmation_error.value = error.password_confirmation[0];
          } else {
            password_confirmation_error.value = null;
          }

          if (error.agree) {
            agree_error.value = error.agree[0];
          } else {
            agree_error.value = null;
          }

        }

        if (response.data.status == 1) {
          name_error.value = null;
          email_error.value = null;
          password_error.value = null;
          password_confirmation_error.value = null;
          agree_error.value = null;

          formDiv.value = false;
          otpDiv.value = true;
        }
        isLoading.value = false;

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

      return {
        name,
        email,
        password,
        password_confirmation,
        agree,
        name_error,
        email_error,
        password_error,
        password_confirmation_error,
        agree_error,
        submit,
        verifyOTP,
        validation,
        formDiv,
        otpDiv,
        otp,
        otp_error,
        successMessage,
        successDiv,
        isLoading,
      };
    },
  };
</script>

<template>
  <loader :isLoading="isLoading"  />
  <div class="flex items-center justify-center h-screen flex-col">

    <!-- Heading outside the card (center-aligned) -->
    <h1 class="text-2xl font-bold mb-8">ABC BANK</h1>

    <!-- Card Container -->
    <div class="bg-white p-8 rounded shadow-md w-96" v-if="formDiv">

      <!-- Heading inside the card (left-aligned) -->
      <h2 class="text-xl font-normal mb-4">Create new account</h2>

      <!-- Card Content (Login Form) -->
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label for="name" class="block text-sm font-bold text-gray-600">Name</label>
          <input v-model="name" type="text" id="name" name="name" class="mt-1 p-2 w-full border rounded-md"
            placeholder="Enter email" />
          <div v-if="name_error" class="text-red-500 mt-2">{{ name_error }}</div>
        </div>

        <div class="mb-4">
          <label for="email" class="block text-sm font-bold text-gray-600">Email Address</label>
          <input v-model="email" type="email" id="email" name="email" class="mt-1 p-2 w-full border rounded-md"
            placeholder="Enter email" />
          <div v-if="email_error" class="text-red-500 mt-2">{{ email_error }}</div>
        </div>

        <div class="mb-4">
          <label for="password" class="block text-sm font-bold text-gray-600">Password</label>
          <input v-model="password" type="password" id="password" name="password"
            class="mt-1 p-2 w-full border rounded-md" placeholder="Enter password" />
          <div v-if="password_error" class="text-red-500 mt-2">{{ password_error }}</div>
        </div>

        <div class="mb-4">
          <label for="password_confirmation" class="block text-sm font-bold text-gray-600">Confirm Password</label>
          <input v-model="password_confirmation" type="password" id="password_confirmation" name="password_confirmation"
            class="mt-1 p-2 w-full border rounded-md" placeholder="Enter Confirm password" />
          <div v-if="password_confirmation_error" class="text-red-500 mt-2">{{ password_confirmation_error }}</div>
        </div>

        <div class="mb-2 flex items-center">
          <input v-model="agree" type="checkbox" id="rememberMe" class="mr-2">
          <label for="rememberMe" class="text-sm text-gray-600">Agree the
            <a href="#" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">terms and policy </a>
          </label>
        </div>
        <div v-if="agree_error" class="text-red-500 mb-4">{{ agree_error }}</div>
        <div class="mb-4">
          <button class="w-full bg-blue-500 text-white p-2 rounded-md">Create new account</button>
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
      <router-link class="text-blue-500 mt-2" to="/">Click here to login</router-link>
    </div>

    <p class="mt-10 text-center text-sm text-gray-500">
      Already have account?
      <a href="/banking/frontend1/" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Sign in</a>
    </p>

  </div>
</template>