import { RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      {
        path: '',
        components: {
          default: () => import('src/pages/IndexPage.vue'),
          'sidebar-attachment': () =>
            import('src/components/Manager/ManagerSidebar.vue'),
        },
        props: {
          'sidebar-attachment': { supportsCreation: true },
        },
      },
    ],
  },
  {
    path: '/list',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      {
        path: '',
        components: {
          default: () => import('src/pages/ListPage.vue'),
          'sidebar-attachment': () =>
            import('src/components/Manager/ManagerSidebar.vue'),
        },
      },
    ],
  },
  {
    path: '/overview',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      {
        path: '',
        components: {
          default: () => import('src/pages/OverviewPage.vue'),
          'sidebar-attachment': () =>
            import('src/components/Manager/ManagerSidebar.vue'),
        },
      },
    ],
  },
  {
    path: '/auth',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('src/pages/AuthPage.vue') }],
  },
  {
    path: '/password/reset',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('src/pages/AuthPage.vue') }],
  },
  {
    path: '/password/reset/:token',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('src/pages/AuthPage.vue') }],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/Error404Page.vue'),
  },
];

export default routes;
