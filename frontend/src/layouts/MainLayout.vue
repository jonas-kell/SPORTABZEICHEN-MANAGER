<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />

        <q-toolbar-title class="cursor-pointer" @click="rediredctHome">
          {{ appName }}
        </q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="leftDrawerOpen" show-if-above bordered>
      <q-list>
        <q-item-label header>
          Menu
        </q-item-label>

        <Sidebar />
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script lang="ts">
import Sidebar from './../components/Sidebar.vue';
import { mapActions } from 'vuex';
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'MainLayout',
  components: {
    Sidebar
  },
  created: async function() {
    await this.checkCORS().then(async () => {
      await this.checkAuthenticated();
    });
  },
  data() {
    return {
      leftDrawerOpen: false,
      appName: process.env.APP_NAME
    };
  },
  methods: {
    toggleLeftDrawer() {
      this.leftDrawerOpen = !this.leftDrawerOpen;
    },
    rediredctHome: function() {
      void this.$router.push('/');
    },
    ...mapActions('authModule', {
      checkAuthenticated: 'checkAuthenticated',
      checkCORS: 'checkCORSCookies'
    })
  }
});
</script>
