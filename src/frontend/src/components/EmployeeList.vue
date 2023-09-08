<!-- eslint-disable no-unused-vars -->
<!-- eslint-disable no-console -->
<template>
  <div>
    <h1>Company Information</h1>
    <p>ID: {{ id }}</p>
    <p>Name: {{ name }}</p>
    <p>Ave. Salary: {{ formatPrice(salary.data) }}</p>
    <hr />
    <h2>Employee List</h2>
    <table class="table table-striped table-bordered table-hover">
      <thead class="thead-dark">
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Salary</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="employee in item" :key="employee.id">
          <th scope="row">
            <a :href="'/edit-employee/' + id + '/' + name + '/' + employee.id">
              {{ employee.id }}
            </a>
          </th>
          <td>{{ employee.employee_name }}</td>
          <td>{{ employee.email_address }}</td>
          <td>{{ employee.salary }}</td>
        </tr>
      </tbody>
    </table>
    <!-- Back button to go back the list of Employees for the particular Company -->
    <hr>
    <a class="btn btn-info" href="/">
      Back to Company List
    </a>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

export default {
  setup() {
    const route = useRoute();
    const { id, name } = route.params; // Access the 'id' route parameter
    const item = ref([]);
    const salary = ref([]);
    const itemRefs = ref([]);

    const fetchEmployees = async () => {
      try {
        const response = await axios.get(
          `https://localhost/index.php?action=getEmployees&companyID=${id}`
        );

        // Update the employees data with the API response
        item.value = response.data.data;
      } catch (error) {
        console.error('Error fetching employees:', error);
      }
    };

    const fetchAverageSalary = async () => {
      try {
        const response = await axios.get(
          `https://localhost/index.php?action=getAverageSalary&companyID=${id}`
        );

        // Update the averageSalary data with the API response
        salary.value = response.data;
      } catch (error) {
        console.error('Error fetching average salary:', error);
      }
    };

    const fetchData = () => {
      fetchAverageSalary();
      fetchEmployees();
    }

    onMounted(() => {
      fetchData()
    });

    return {
      id,
      name,
      item,
      salary,
      itemRefs
    };
  },
  methods: {
    formatPrice(value) {
      const formatCurrency = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        currencyDisplay: 'symbol',
        currencySign: 'standard',
        maximumFractionDigits: 2
      });

      return formatCurrency.format(value);
    }
  }
};
</script>
