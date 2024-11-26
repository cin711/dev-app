<template>
  <v-app>
    <v-app-bar color="deep-purple" dark v-if="isAuth">
      <v-app-bar-title><router-link to="/">Dev App</router-link></v-app-bar-title>
      <v-spacer/>
      <router-link to="/departments" class="mr-4">Departments</router-link>
      <a href="#" v-on:click="logout()" class="mr-4">Log Out</a>
    </v-app-bar>

    <v-main>
      <v-container>
        <router-view />
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import { useRoute } from 'vue-router';
import ApiService from '../services/ApiService';

export default {
  setup() {
    const route = useRoute();
  },
  data: () => ({
    isAuth: localStorage.getItem('api_token') !== null,
  }),
  methods: {
    logout() {
      ApiService.logout();
      location.reload();
    }
  }
};
</script>