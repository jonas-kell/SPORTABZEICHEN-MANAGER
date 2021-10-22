<template>
  <SidebarLink
    v-for="link in essentialLinks"
    :key="link.title"
    v-bind="link"
    :active="isCurrentRoute(link.link)"
  />
</template>

<script lang="ts">
const linksList = [
  {
    title: 'Account',
    caption: 'Verwalte deinen Login',
    icon: 'account_circle',
    link: '/#/auth',
  },
  {
    title: 'Home',
    caption: 'Home Seite',
    icon: 'home',
    link: '/#/',
  },
  {
    title: 'Liste',
    caption: 'Favoriten Übersichts Liste ',
    icon: 'list',
    link: '/#/list',
  },
];

import SidebarLink from './SidebarLink.vue';
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'Sidebar',
  components: {
    SidebarLink,
  },
  data() {
    return {
      essentialLinks: linksList,
    };
  },
  methods: {
    isCurrentRoute: function (route: string) {
      return route == this.currentRoute;
    },
  },
  computed: {
    currentRoute: function (): string {
      return '/#' + this.$route.path;
    },
  },
});
</script>
