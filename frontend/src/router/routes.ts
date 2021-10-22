import { RouteRecordRaw } from 'vue-router';

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/Index.vue') }],
  },
  {
    path: '/list',
    component: () => import('layouts/MainLayout.vue'),
    children: [{ path: '', component: () => import('pages/List.vue') }],
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
