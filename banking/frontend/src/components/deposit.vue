<template>
     <loader :isLoading="isLoading"  />
    <div class="flex items-center py-3 h-screen flex-col">

        <!-- Card Container -->
        <div class="bg-white p-8 rounded shadow-md w-1/2">

            <!-- Heading inside the card (left-aligned) -->
            <h2 class="text-xl font-normal mb-4">Deposit Money</h2>
            <hr class="py-3" />
            <!-- Card Content (Login Form) -->
            <form @submit.prevent="depositMoney">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-bold text-gray-600">
                        Amount
                    </label>
                    <input v-model="amount" type="text" id="amount" name="amount"
                        class="mt-1 p-2 w-full border rounded-md" placeholder="Enter amount to deposit" />
                    <div v-if="amount_error" class="text-red-500 mt-2">{{ amount_error }}</div>
                </div>
                <div class="mb-4" v-if="isVisible">
                    <label for="email" class="block text-sm font-bold text-gray-600">
                        Transaction OTP
                    </label>
                    <input v-model="transaction_otp" type="text" id="transaction_otp" name="transaction_otp"
                        class="mt-1 p-2 w-full border rounded-md" placeholder="Enter OTP" />
                    <div v-if="transaction_otp_error" class="text-red-500 mt-2">{{ transaction_otp_error }}</div>
                </div>
                <div class="mb-4 text-green-500" v-if="successMessage">
                    {{ successMessage }}
                </div>
                <div class="mb-4" v-if="isVisibleButton">
                    <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md">Deposit</button>
                </div>
                <div class="mb-4 items-center" v-if="isVisibleButton1">
                    <button type="button" @click="processTransaction"
                        class="w-2/6 bg-blue-500 text-white p-2 rounded-md">Submit</button>
                    <button type="button" @click="reset"
                        class="w-2/6 bg-red-500 text-white p-2 rounded-md">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</template>

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
    import loader from '../components/loader.vue';
    export default {
        name: 'Deposit',
        components:{
            loader
        },
        setup() {
            const amount = ref(0);
            const transaction_otp = ref(null);
            const amount_error = ref(null);
            const transaction_otp_error = ref(null);
            const successMessage = ref(null);

            const isVisible = ref(false);
            const isVisibleButton = ref(true);
            const isVisibleButton1 = ref(false);
            const initiateTransactionResponse = ref({});
            const router = useRouter();
            const isLoading = ref(false);

            const depositMoney = async () => {
                if (isNaN(amount.value)) {

                    amount_error.value = 'Invalid amount. Please enter a valid number.';
                    // console.log(amount_error.value);
                    return;
                } else if (amount.value == 0) {
                    amount_error.value = 'Please add amount greater than 0';
                    // console.log(amount_error.value);
                    return;
                } else if (amount.value < 0) {
                    amount_error.value = 'Please add amount greater than 0';
                    // console.log(amount_error.value);
                    return;
                }

                isLoading.value = true;
                amount_error.value = null;
                initiateTransactionResponse.value = await axios.post('/initiate-transaction', {
                    'type': 'Credit',
                    amount: amount.value,
                });

                if(initiateTransactionResponse.value.data.status == 1){
                    successMessage.value = initiateTransactionResponse.value.data.message
                }
                else{
                    successMessage.value = initiateTransactionResponse.value.data.message
                }
                isVisibleButton.value=false;
                isVisibleButton1.value=true;
                isVisible.value = true;
                isLoading.value = false;
            };

            const processTransaction = async () => {
                // console.log('initiateTransactionResponse',initiateTransactionResponse.value)
                isLoading.value = true;
                if(initiateTransactionResponse.value.data.status == 1){
                    const processResponse = await axios.post('/process-transaction', {
                        'type': 'Credit',
                        amount: amount.value,
                        transaction_otp: transaction_otp.value,
                    });

                    // console.log('processResponse',processResponse);

                    if(processResponse.data.status == 1){
                        amount.value = 0;
                        transaction_otp.value = null;

                        transaction_otp_error.value = null;
                        amount_error.value= null;
                        successMessage.value = processResponse.data.message;

                        isVisibleButton.value=true;
                        isVisibleButton1.value=false;
                        isVisible.value = false;
                        isLoading.value = false;
                    }
                    else{
                        successMessage.value=null;
                
                        if(processResponse.data.error){
                            const error = JSON.parse(processResponse.data.error);

                            if(error.transaction_otp){
                            transaction_otp_error.value = error.transaction_otp[0];
                            }
                            if( error.amount ){
                                amount_error.value = error.amount[0];
                            }
                        }
                      

                        if(processResponse.data.invalid_otp){
                            transaction_otp_error.value = processResponse.data.invalid_otp;
                        }

                        if(processResponse.data.otp_expire){
                            transaction_otp_error.value = processResponse.data.otp_expire;
                        }

                        isLoading.value = false;
                        return;
                    }

                }
            };
            const reset = async () => {
                isVisibleButton.value=true;
                isVisibleButton1.value=false;
                isVisible.value = false;
                transaction_otp_error.value = null;
                amount_error.value = null;
                successMessage.value = null;
                amount.value = 0;
                transaction_otp.value = null;

            };

            onMounted(() => {
                isVisibleButton.value = true;
            });

            return {
                amount,
                amount_error,
                transaction_otp,
                transaction_otp_error,
                successMessage,
                isVisible,
                isVisibleButton,
                isVisibleButton1,
                depositMoney,
                processTransaction,
                reset,
                initiateTransactionResponse,
                isLoading
            };
        },
    };
</script>