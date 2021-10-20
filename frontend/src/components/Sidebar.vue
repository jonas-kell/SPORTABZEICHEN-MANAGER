<template>
  <SidebarLink v-for="link in essentialLinks" :key="link.title" v-bind="link" />
</template>

<script lang="ts">
const linksList = [
  {
    title: 'Account',
    caption: 'Verwalte deinen Login',
    icon: 'account_circle',
    link: '/#/auth'
  }
];

import SidebarLink from './../components/SidebarLink.vue';
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'Sidebar',
  components: {
    SidebarLink
  },
  data() {
    return {
      essentialLinks: linksList
    };
  }
});
</script>
