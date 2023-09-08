<template>
  <div id="app" class="container-fluid">
    <nav class="navbar navbar-expand-sm navbar-light bg-light">
      <div class="mx-auto d-sm-flex d-block flex-sm-nowrap">
        <a class="navbar-brand mr-md-5 mr-lg-5" href="#">Sample</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
          data-bs-target="#navbarsExample11"
          aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse text-center" id="navbarsExample11">
            <router-link class="btn btn-primary" to="/">Company</router-link>
            <router-link class="btn btn-info" to="/upload-csv" >Upload CSV</router-link>
            <!--
            <router-link to="/employees" >Employees</router-link>
            <router-link to="/edit-employee" >Edit Employee</router-link>
            -->
        </div>
      </div>
    </nav>
    <div class="container">
      <router-view />
    </div>
  </div>
</template>

<script>
export default {
  name: "App",
};
</script>
