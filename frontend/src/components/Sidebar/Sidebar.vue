<template>
  <SidebarLink
    v-for="link in essentialLinks"
    :key="link.title"
    v-bind="link"
    :active="isCurrentRoute(link.link)"
  />
</template>

<script lang="ts">
import SidebarLink from './SidebarLink.vue';
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'Sidebar',
  components: {
    SidebarLink,
  },
  data() {
    return {
      essentialLinks: [
        {
          title: this.$t('sidebar.account'),
          caption: this.$t('sidebar.accountSubtext'),
          icon: 'account_circle',
          link: '/#/auth',
        },
        {
          title: this.$t('sidebar.home'),
          caption: this.$t('sidebar.homeSubtext'),
          icon: 'home',
          link: '/#/',
        },
        {
          title: this.$t('sidebar.list'),
          caption: this.$t('sidebar.listSubtext'),
          icon: 'list',
          link: '/#/list',
        },
      ],
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
