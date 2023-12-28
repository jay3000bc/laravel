<template>

  <loader :isLoading="isLoading" />
  <div class="flex flex-wrap">
    <div class="w-full">
      <ul class="flex mb-0 list-none flex-wrap pt-3 pb-4 flex-row">
        <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
          <a class="tab-link" v-on:click="toggleTabs(1)" v-bind:class="{ 'active': openTab === 1 }">
            <span class="icon">🏠</span> Home
          </a>
        </li>
        <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
          <a class="tab-link" v-on:click="toggleTabs(2)" v-bind:class="{ 'active': openTab === 2 }">
            <span class="icon">💰</span> Deposit
          </a>
        </li>
        <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
          <a class="tab-link" v-on:click="toggleTabs(3)" v-bind:class="{ 'active': openTab === 3 }">
            <span class="icon">💸</span> Withdraw
          </a>
        </li>
        <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
          <a class="tab-link" v-on:click="toggleTabs(4)" v-bind:class="{ 'active': openTab === 4 }">
            <span class="icon">➡️</span> Transfer
          </a>
        </li>
        <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
          <a class="tab-link" v-on:click="toggleTabs(5)" v-bind:class="{ 'active': openTab === 5 }">
            <span class="icon">📜</span> Statement
          </a>
        </li>
        <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
          <a class="tab-link" v-on:click="logout" v-bind:class="{ 'active': openTab === 6 }">
            <span class="icon">🚪</span> Logout
          </a>
        </li>
      </ul>
      <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
        <div class="px-4 py-5 flex-auto">
          <div class="tab-content tab-space">
            <div class="bg-gray-300" v-bind:class="{'hidden': openTab !== 1, 'block': openTab === 1}">
              <div class="flex items-center py-3 h-screen flex-col">
                <!-- Card Container -->
                <div class="bg-white p-8 rounded shadow-md">

                  <!-- Heading inside the card (left-aligned) -->
                  <!-- <h2 class="text-xl font-normal mb-4">Welcome John Doe</h2> -->

                  <div class="mb-4">
                    <table class="min-w-full divide-y divide-gray-200">
                      <tbody class="divide-y divide-gray-200">
                        <tr>
                          <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap">
                            <h2 class="text-xl font-normal">Welcome John Doe</h2>
                          </td>

                        </tr>

                        <tr>
                          <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap">
                            Your ID
                          </td>
                          <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap">
                            {{ user['email'] }}
                          </td>

                        </tr>
                        <tr>
                          <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap">
                            Your Balance
                          </td>
                          <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap">
                            {{ user['balance'] }} INR
                          </td>
                        </tr>

                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-300" v-bind:class="{'hidden': openTab !== 2, 'block': openTab === 2}">
              <deposit />
            </div>
            <div class="bg-gray-300" v-bind:class="{'hidden': openTab !== 3, 'block': openTab === 3}">
              <withdraw />
            </div>
            <div class="bg-gray-300" v-bind:class="{'hidden': openTab !== 4, 'block': openTab === 4}">
              <transfer />
            </div>
            <div class="bg-gray-300" v-bind:class="{'hidden': openTab !== 5, 'block': openTab === 5}">
              <!-- <statement /> -->
              <div class="flex items-center py-3 h-screen flex-col">

                <!-- Card Container -->
                <div class="bg-white p-8 rounded shadow-md">

                  <!-- Heading inside the card (left-aligned) -->
                  <h2 class="text-xl font-normal mb-4">Statement of account</h2>
                  <hr class="py-3" />
                  <!-- Card Content (Login Form) -->

                  <table class="min-w-full bg-white border border-gray-300">
                    <thead>
                      <tr>
                        <th class="py-2 px-4 border-b">ID</th>
                        <th class="py-2 px-4 border-b">DATETIME</th>
                        <th class="py-2 px-4 border-b">AMOUNT</th>
                        <th class="py-2 px-4 border-b">TYPE</th>
                        <th class="py-2 px-4 border-b">DETAILS</th>
                        <th class="py-2 px-4 border-b">BALANCE</th>
                        <!-- Add more headers as needed -->
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="item in statements" :key="item.id">
                        <td class="py-2 px-4 border-b">{{ item.id }}</td>
                        <td class="py-2 px-4 border-b">{{ formatDate(item.created_at) }}</td>
                        <td class="py-2 px-4 border-b">{{ item.balance }}</td>
                        <td class="py-2 px-4 border-b">{{ item.type }}</td>
                        <td class="py-2 px-4 border-b">{{ item.details }}</td>
                        <td class="py-2 px-4 border-b">{{ item.current_balance }}</td>
                      </tr>
                    </tbody>
                  </table>

                </div>
              </div>
            </div>
            <div v-bind:class="{'hidden': openTab !== 6, 'block': openTab === 6}">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import deposit from '../components/deposit.vue';
  import withdraw from '../components/withdraw.vue';
  import transfer from '../components/transfer.vue';
  import statement from '../components/statement.vue';
  import loader from '../components/loader.vue';
  import {
    ref,
    onMounted
  } from 'vue';
  import axios from 'axios';
  import {
    useRouter
  } from 'vue-router';
  import VueCookies from 'vue-cookies';
  // import Cookies from 'js-cookie';

  export default {
    name: 'Profile',
    data() {
      return {
        //  openTab: 1,
        formData: {
          username: '',
          // Add more form fields as needed
        },
      };
    },
    components: {
      deposit,
      withdraw,
      transfer,
      statement,
      loader
    },
    methods: {

      formatDate(rawDate) {
        const date = new Date(rawDate);

        const day = date.getDate().toString().padStart(2, '0');
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const year = date.getFullYear();
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';

        // Convert to 12-hour time format
        const formattedTime = `${((hours % 12) || 12)}:${minutes} ${ampm}`;

        return `${day}-${month}-${year} ${formattedTime}`;
      }

    },
    setup() {
      const user = ref({});
      const token = ref('');
      const refreshToken = ref('');
      const router = useRouter();
      const isLoading = ref(false);
      const openTab = ref(1);
      const statements = ref(null);

      const checkTokenExpiration = (token) => {
        if (!token) {
          return true; // Token doesn't exist
        }

        const decodedToken = JSON.parse(atob(token.split('.')[1]));
        const expirationTimestamp = decodedToken.exp * 1000; // Convert to milliseconds

        return expirationTimestamp > Date.now();
      };

      const fetchUserData = async () => {
        isLoading.value = true;
        // console.log('isLoading', isLoading.value);
        try {

          token.value = VueCookies.get('token');

          // console.log('token', token.value);

          if (!checkTokenExpiration(token.value)) {
            // Token is expired, redirect to login
            router.push('/');
            // console.log('token expired');
          }

          // console.log('tokenValue',token.value);

          const response = await axios.post('/me', {
            headers: {
              Authorization: `Bearer ${token.value}`,
              // 'Accept': 'application/json',
            },
          });
          user.value = response.data;
          // console.log('user', user.value);
          isLoading.value = false;
        } catch (error) {
          // console.error('Fetch user data error:', error);
          router.push('/');
        }
      };

      const regenerateToken = async () => {
        try {
          // token.value = localStorage.getItem('token');
          token.value = VueCookies.get('token');

          if (!token.value) {
            // Handle the case where there is no token stored in cookies
            console.error('No token found in cookies.');
            // Redirect to login or handle appropriately
            router.push('/');
            return;
          }

          // console.log('access token', token.value);
          const response = await axios.post('/refresh', {
            headers: {
              Authorization: `Bearer ${token.value}`,
              // 'Accept': 'application/json',
            },
          });

          // console.log('refresh token', response);

          VueCookies.set('token', response.data.access_token, '7d');

          token.value = response.data.access_token;
          refreshToken.value = response.data.access_token;
          axios.defaults.headers['Authorization'] = `Bearer ${response.data.access_token}`;

          // console.log('Token regenerated successfully.', newToken);
        } catch (error) {
          // console.error('Token regeneration error:', error);
          // Handle regeneration error, e.g., redirect to login
          router.push('/');
        }
      };

      const fetchUserStatement = async () => {
        isLoading.value = true;
        try {

          token.value = VueCookies.get('token');

          // console.log('token', token.value);

          if (!checkTokenExpiration(token.value)) {
            router.push('/');
            // console.log('token expired');
          }

          const response = await axios.post('/statements', {
            headers: {
              Authorization: `Bearer ${token.value}`,
            },
          });


          statements.value = response.data.statementsData;
          isLoading.value = false;



        } catch (error) {
          console.error('Fetch user data error:', error);
          router.push('/');
        }
      };

      const logout = async () => {

        // console.log('existingToken',token.value)

        try {
          // token.value = localStorage.getItem('token');
          token.value = VueCookies.get('token');
          // console.log('existingToken', token.value)

          if (!token.value) {
            // Handle the case where there is no token stored in cookies
            // console.error('No token found in cookies.');
            // Redirect to login or handle appropriately
            router.push('/');
            return;
          }

          const response = await axios.post('/logout', {
            headers: {
              Authorization: `Bearer ${refreshToken.value}`,
              // 'Accept': 'application/json',
            },
          });
          // localStorage.removeItem('token');
          VueCookies.remove('token');
          token.value = '';
          axios.defaults.headers['Authorization'] = '';
          // console.log('response', token.value);

          await router.push({
            path: '/'
          });
          // window.location.reload();

        } catch (error) {
          // Handle regeneration error, e.g., redirect to login
          router.push('/');
        }

      };

      const toggleTabs = async (tabNumber) => {
        if (tabNumber == 1) {
          fetchUserData();
        }

        if (tabNumber == 5) {
          fetchUserStatement();
        }


        openTab.value = tabNumber;
      };

      onMounted(() => {
        isLoading.value = true;
        fetchUserData();
        regenerateToken(); // Regenerate token on each visit to the profile page
      });

      return {
        user,
        token,
        refreshToken,
        logout,
        isLoading,
        toggleTabs,
        openTab,
        statements,
      };
    },
  };
</script>

<style scoped>
  .tab-link {
    text-decoration: none;
    cursor: pointer;
    display: inline-block;
    position: relative;
    padding: 10px 20px;
  }

  .tab-link .icon {
    margin-right: 5px;
  }

  .tab-link.active {
    color: black;
  }

  .tab-link.active::after {
    content: "";
    display: block;
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background-color: pink;
    /* Change this color as needed */
  }
</style>