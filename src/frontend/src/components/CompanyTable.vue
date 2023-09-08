<template>
  <div>
    <h1>Company List</h1>
    <ul>
      <li v-for="company in companies" :key="company.id">
        <a :href="'/employees/' + company.id + '/' + company.name">
          {{ company.id }}  - {{ company.name }}
        </a>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      companies: [],
    };
  },
  async created() {
    try {
      const response = await axios.get('https://localhost/index.php?action=getCompanies');

      // Update the companies data with the API response
      this.companies = response.data.data;

      console.log('companies', this.companies);
    } catch (error) {
      console.error('Error fetching companies:', error);
    }
  },
};
</script>
