<template>
  <q-page class="row q-pa-md row justify-center content-start">
    <div v-if="isLoggedIn" class="col-auto row">
      <div class="col-12">
        <h4 class="q-my-sm">
          Profil: <q-icon name="account_circle" size="xl" class="float-right" />
        </h4>
        <p>
          Name:
          <span class="text-bold">{{ user != null ? user.name : '' }}</span>
        </p>
        <p>
          Email:
          <span class="text-bold">{{ user != null ? user.email : '' }}</span>
        </p>
      </div>
      <div class="col-12 row justify-center">
        <q-btn
          color="blue"
          icon-right="logout"
          label="Ausloggen"
          @click="logout"
        ></q-btn>
      </div>
    </div>
    <div v-else class="col-12 col-md-8 q-pa-md">
      <q-form @submit="login">
        <div class="row q-py-md">
          <q-input
            filled
            v-model="email"
            label="E-Mail Address"
            class="col-12"
            lazy-rules
            :rules="[
              val => (val && val.length > 0) || 'Feld darf nicht leer sein',
              val => val.includes('@') || 'Email muss ein @ enthalten'
            ]"
          ></q-input>
        </div>
        <div class="row q-py-md">
          <q-input
            filled
            :type="isPwd ? 'password' : 'text'"
            v-model="password"
            label="Password"
            class="col-12"
            :rules="[
              val =>
                (val && val.length >= 8) ||
                'Passwort muss mindestens 8 Zeichen enthalten'
            ]"
          >
            <template v-slot:append>
              <q-icon
                :name="isPwd ? 'visibility_off' : 'visibility'"
                class="cursor-pointer"
                @click="isPwd = !isPwd"
              ></q-icon>
            </template>
          </q-input>
        </div>
        <div class="row q-py-md">
          <div class="justify-center col-12">
            <q-btn
              type="submit"
              color="blue"
              icon-right="send"
              label="Einloggen"
              @click="login"
            ></q-btn>
            <q-btn
              class="float-right"
              color="grey"
              icon-right="mail"
              label="Passwort vergessen"
              type="a"
              href="#/password/send_mail"
            ></q-btn>
          </div>
        </div>
      </q-form>
    </div>
  </q-page>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { mapActions, mapState } from 'vuex';

export default defineComponent({
  data: function() {
    return {
      email: '' as string,
      password: '' as string,
      isPwd: true as boolean
    };
  },
  methods: {
    login: async function() {
      await this.bindLogin({ password: this.password, email: this.email });
    },
    ...mapActions('authModule', {
      bindLogin: 'login',
      logout: 'logout'
    })
  },
  computed: {
    ...mapState('authModule', {
      isLoggedIn: 'isLoggedIn',
      user: 'user'
    })
  }
});
</script>
