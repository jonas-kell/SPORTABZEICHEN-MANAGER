import ManagerIndex from "../components/Manager/ManagerIndex.vue";
import ListIndex from "../components/List/ListIndex.vue";

const routes = [
    { path: "/", name: "manager", component: ManagerIndex },
    { path: "/list", component: ListIndex },
    { path: "*", redirect: "/" }
];

export default routes;
