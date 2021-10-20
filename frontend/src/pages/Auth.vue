<template>
  <q-page class="row q-pa-md row justify-center content-start">
    <!-- Logged In -->
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
    <!-- Not Logged In -->
    <div v-else class="col-12 col-md-8 q-pa-md">
      <q-form @submit="handleSubmit">
        <div class="row q-py-md">
          <q-input
            filled
            v-model="email"
            label="E-Mail Address"
            class="col-12"
            id="email"
            name="email"
            lazy-rules
            :rules="[
              val => (val && val.length > 0) || 'Feld darf nicht leer sein',
              val => val.includes('@') || 'Email muss ein @ enthalten'
            ]"
          ></q-input>
        </div>
        <div
          class="row q-py-md"
          v-if="pageMode == 'login' || pageMode == 'setNew'"
        >
          <q-input
            filled
            :type="isPwd ? 'password' : 'text'"
            v-model="password"
            label="Password"
            class="col-12"
            id="password"
            name="password"
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
        <div class="row q-py-md" v-if="pageMode == 'setNew'">
          <q-input
            filled
            :type="isPwd ? 'password' : 'text'"
            v-model="passwordConfirmation"
            label="Password Wiederholen"
            class="col-12"
            id="password-confirmation"
            name="password-confirmation"
            :rules="[
              val =>
                (val && val.length >= 8) ||
                'Passwort muss mindestens 8 Zeichen enthalten',
              val =>
                (val && val == password) ||
                'Passwort und Bestätigung müssen übereinstimmen'
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
              v-if="pageMode == 'login' || pageMode == 'setNew'"
              type="submit"
              color="blue"
              icon-right="send"
              :label="
                pageMode == 'login' ? 'Einloggen' : 'Passwort Zurücksetzen'
              "
            ></q-btn>
            <q-btn
              v-if="pageMode == 'login'"
              class="float-right"
              color="grey"
              icon-right="mail"
              label="Passwort vergessen"
              type="a"
              href="#/password/reset"
            ></q-btn>
            <q-btn
              v-if="pageMode == 'requestMail'"
              type="submit"
              class="float-right"
              color="blue"
              icon-right="mail"
              label="Schicke Wiederherstellungs Mail"
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
import { Status } from './../store/auth/actions';

export default defineComponent({
  data: function() {
    return {
      pageMode: 'login' as string,
      email: '' as string,
      password: '' as string,
      passwordConfirmation: '' as string,
      isPwd: true as boolean,
      token: '' as string
    };
  },
  methods: {
    handleSubmit: async function() {
      switch (this.pageMode) {
        case 'setNew':
          if (
            this.notify(
              await this.bindSetPassword({
                password: this.password,
                passwordConfirmation: this.passwordConfirmation,
                email: this.email,
                token: this.token
              })
            )
          ) {
            void this.$router.push('/auth');
          }
          break;
        case 'requestMail':
          this.notify(
            await this.bindRequestReset({
              email: this.email
            })
          );
          break;
        case 'login':
        default:
          if (
            this.notify(
              await this.bindLogin({
                password: this.password,
                email: this.email
              })
            )
          ) {
            void this.$router.push('/');
          }
          break;
      }

      // clean password values
      this.password = '';
      this.passwordConfirmation = '';
    },
    recomputeRouteOptions: function() {
      this.token = '';

      // parse parameters
      let params = this.$route.params as { token: string };
      if (params.token != undefined) {
        this.token = params.token;
      }

      // parse query
      let query = this.$route.query as { email: string };
      if (query.email != undefined) {
        this.email = query.email;
      }

      // set page mode
      if (this.$route.path.includes('password')) {
        if (this.token == '') {
          this.pageMode = 'requestMail';
        } else {
          this.pageMode = 'setNew';
        }
      } else {
        this.pageMode = 'login';
      }
    },
    notify: function(status: Status[]) {
      let success = true;

      status.forEach(element => {
        if (element.type != 'success') {
          success = false;
        }
        this.$q.notify({
          type: element.type,
          message: element.message
        });
      });

      return success;
    },
    logout: async function() {
      this.notify(await this.bindLogout());
    },
    ...mapActions('authModule', {
      bindLogin: 'login',
      bindSetPassword: 'setPassword',
      bindRequestReset: 'requestReset',
      bindLogout: 'logout'
    })
  },
  computed: {
    ...mapState('authModule', {
      isLoggedIn: 'isLoggedIn',
      user: 'user'
    })
  },
  created: function() {
    this.recomputeRouteOptions();
  },
  watch: {
    $route() {
      this.recomputeRouteOptions();
    }
  }
});
</script>
