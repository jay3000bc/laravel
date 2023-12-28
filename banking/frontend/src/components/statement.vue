<template>
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
            <td class="py-2 px-4 border-b">{{ item.created_at }}</td>
            <td class="py-2 px-4 border-b">{{ item.balance }}</td>
            <td class="py-2 px-4 border-b">{{ item.type }}</td>
            <td class="py-2 px-4 border-b">{{ item.details }}</td>
            <td class="py-2 px-4 border-b">{{ item.current_balance }}</td>
          </tr>
        </tbody>
      </table>

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
  import VueCookies from 'vue-cookies';
  import loader from '../components/loader.vue';
  export default {
    name: 'Statement',
    components: {
      loader,
    },
    setup() {
      const statements = ref(null);
      const token = ref('');
      const refreshToken = ref('');
      const router = useRouter();
      const isLoading = ref(false);
      const currentPage = ref(1);
      let totalPages = 1; // Declare totalPages variable
      const itemsPerPage = 3; // Set your desired items per page


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
        try {

          token.value = VueCookies.get('token');

          // console.log('token', token.value);

          if (!checkTokenExpiration(token.value)) {
            router.push('/');
            // console.log('token expired');
          }

          const response = await axios.post('/statements', {
            params: {
              page: currentPage.value,
              limit: itemsPerPage,
            },
            headers: {
              Authorization: `Bearer ${token.value}`,
            },
          });


          statements.value = response.data.statementsData;
          isLoading.value = false;

          // Calculate total pages based on the total count from the server
          const totalCount = response.data.totalCount;
          totalPages = Math.ceil(totalCount / itemsPerPage);
          // console.log('totalCount', totalPages)

          // Ensure currentPage is within valid range
          if (currentPage.value > totalPages) {
            currentPage.value = totalPages;
          }

        } catch (error) {
          console.error('Fetch user data error:', error);
          router.push('/');
        }
      };

      const changePage = (increment) => {
        const newPage = currentPage.value + increment;
        if (newPage >= 1 && newPage <= totalPages) {
          currentPage.value = newPage;
          fetchUserData();
        }
        // console.log('currentPage', currentPage.value);
      };


      onMounted(() => {
        fetchUserData();
      });

      return {
        statements,
        token,
        isLoading,
        currentPage,
        totalPages, // Expose totalPages
        changePage,
      };
    },
  };
</script>