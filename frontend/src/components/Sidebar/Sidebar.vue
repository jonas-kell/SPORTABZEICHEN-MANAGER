<template>
  <q-item-label class="q-py-sm" header>
    {{ $t('sidebar.menu') }}
  </q-item-label>

  <SidebarLink
    v-for="link in linksToRender"
    :key="link.title"
    v-bind="link"
    :active="isCurrentRoute(link.link)"
  />
</template>

<script lang="ts">
import SidebarLink from './SidebarLink.vue';
import { defineComponent } from 'vue';
import { mapState } from 'vuex';

interface LinkThing {
  title: string;
  caption: string;
  icon: string;
  link: string;
  auth: boolean;
}
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
          auth: false,
        },
        {
          title: this.$t('sidebar.home'),
          caption: this.$t('sidebar.homeSubtext'),
          icon: 'home',
          link: '/#/',
          auth: false,
        },
        {
          title: this.$t('sidebar.list'),
          caption: this.$t('sidebar.listSubtext'),
          icon: 'list',
          link: '/#/list',
          auth: true,
        },
      ] as LinkThing[],
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
    linksToRender: function (): LinkThing[] {
      return this.essentialLinks.filter((element) => {
        return !element.auth || this.isLoggedIn;
      });
    },
    ...mapState('authModule', {
      isLoggedIn: 'isLoggedIn',
    }),
  },
});
</script>
