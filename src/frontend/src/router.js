// eslint-disable-next-line import/no-extraneous-dependencies
import { createRouter, createWebHistory } from "vue-router";
import CompanyTable from "./components/CompanyTable.vue";
import EmployeeList from "./components/EmployeeList.vue";
import EmployeeUpdate from "./components/EmployeeUpdate.vue";
import UploadCSV from "./components/UploadCSV.vue";

const routes = [
    { path: "/", component: CompanyTable },
    { path: "/employees/:id/:name", component: EmployeeList },
    { path: "/edit-employee/:id/:name/:employee", component: EmployeeUpdate },
    { path: "/upload-csv", component: UploadCSV },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
