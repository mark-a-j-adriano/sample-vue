<!-- eslint-disable no-console -->
<template>
  <div>
    <h1>Update Employee Email</h1>
    <p>Company ID: {{ id }}</p>
    <p>Name: {{ name }}</p>
    <p>Employee ID: {{ employee }}</p>
    <label for="newEmail">New Email:</label>
    <input placeholder="Enter your email" v-model="newEmail" required type="email"><br>
    <p v-if="errorMessage" class="alert alert-danger" role="alert">
      {{ errorMessage }}
    </p>
    <br>

    <button @click="updateEmail(employee)">Update Email</button>

    <!-- Display success message when 'transactionSuccessful' is true -->
    <p v-if="transactionSuccessful" class="alert alert-success" role="alert">
      Transaction was successful!
    </p>

    <!-- Back button to go back the list of Employees for the particular Company -->
    <hr>
    <a class="btn btn-info" :href="'/employees/' + id + '/' + name">
      Back to Employee List
    </a>
  </div>
</template>

<script>
// import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

export default {
  setup() {
    const route = useRoute();
    const { id, name, employee } = route.params;

    return {
      id,
      name,
      employee,
    };
  },
  data() {
    return {
      transactionSuccessful: false,
      employeeId: '',
      newEmail: '',
      errorMessage: '',
    };
  },
  methods: {
    async updateEmail(id) {
      if (this.newEmail && this.errorMessage === '') {
        try {
          const response = await axios.post(
            'https://localhost/index.php?action=editEmployee',
            {
              employeeId: id,
              emailAddress: this.newEmail,
            }
          );

          this.transactionSuccessful = true;

          console.log('API Response:', response.data);
        } catch (error) {
          this.errorMessage = 'Error updating email';
          console.error('Error updating email:', error);
        }
      } else {
        this.errorMessage = 'Invalid Email';
        console.error('Error updating email');
      }
    },
    validateEmail(email) {
      if (/^[\w.-]+@[a-zA-Z\d.-]+\.[a-zA-Z]{2,}$/.test(email)) {
        this.errorMessage = ''
      } else {
        this.errorMessage = 'Invalid Email'
      }
    }
  },
  watch: {
    newEmail(value) {
      this.newEmail = value;
      this.validateEmail(value);
    }
  },
};
</script>
