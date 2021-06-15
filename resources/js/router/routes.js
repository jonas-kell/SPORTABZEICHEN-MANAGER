import ManagerIndex from "../components/Manager/ManagerIndex.vue";

const Foo = { template: "<div>foo</div>" };
const Bar = { template: "<div>bar</div>" };

const routes = [
    { path: "/", component: ManagerIndex },
    { path: "/bar", component: Bar }
];

export default routes;
