<!-- eslint-disable prefer-destructuring -->
<!-- eslint-disable no-console -->
<!-- eslint-disable no-alert -->
<template>
  <div>
    <input type="file" ref="fileInput" @change="handleFileUpload" accept=".csv" />
    <p v-if="errorMessage" class="alert alert-danger" role="alert">
      {{ errorMessage }}
    </p>
    <br>
    <button @click="uploadCsv">Upload CSV</button>

    <!-- Display success message when 'transactionSuccessful' is true -->
    <p v-if="transactionSuccessful" class="alert alert-success" role="alert">
      {{ transactionSuccessful }}
    </p>

    <!-- Back button to go back the list of Employees for the particular Company -->
    <hr>
    <a class="btn btn-info" href="/">
      Back to Company List
    </a>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      transactionSuccessful: '',
      errorMessage: '',
      selectedFile: null,
    };
  },
  methods: {
    handleFileUpload() {
      // eslint-disable-next-line prefer-destructuring
      this.selectedFile = this.$refs.fileInput.files[0];
    },
    uploadCsv() {
      if (!this.selectedFile) {
        alert("Please select a CSV file.");
        return;
      }

      const formData = new FormData();
      formData.append("csv", this.selectedFile);

      console.log('selectedFile', this.selectedFile);
      console.log('formData', formData);

      axios
        .post("https://localhost/index.php?action=uploadCSV", formData)
        .then((response) => {
          console.log('response', response.data);

          if (response.data.count) {
            this.transactionSuccessful = response.data.message;
          } else {
            this.errorMessage = response.data.message;
          }
        })
        .catch((error) => {
          this.errorMessage = `Error uploading CSV file. -> ${error}`;
        });
    },
  }
};
</script>
