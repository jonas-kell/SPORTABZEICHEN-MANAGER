import { RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      {
        path: '',
        components: {
          default: () => import('pages/Index.vue'),
          'sidebar-attachment': () => import('components/Manager/Sidebar.vue'),
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
          default: () => import('pages/List.vue'),
          'sidebar-attachment': () => import('components/Manager/Sidebar.vue'),
        },
      },
    ],
  },
  {
    path: '/auth',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/Auth.vue') }],
  },
  {
    path: '/password/reset',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/Auth.vue') }],
  },
  {
    path: '/password/reset/:token',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/Auth.vue') }],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/Error404.vue'),
  },
];

export default routes;
