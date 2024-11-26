<template>
  <v-container fluid class="mx-auto px-4 py-8">
    <v-card outlined rounded>
      <v-card-title class="text-h5 font-weight-bold mb-4">Login</v-card-title>
      <v-card-text>
        <v-form @submit.prevent="login">
          <v-text-field
          v-model="form.email"
          label="Email address"
          type="email"
          required
          />
          <v-text-field
          v-model="form.password"
          label="Password"
          type="password"
          required
          />
          <v-btn type="submit" :loading="form.isLoading" color="primary">
            Login
          </v-btn>
          <v-btn color="primary" variant="outlined" @click="$router.push('/register')" class="ml-2">
            Register
          </v-btn>
        </v-form>
      </v-card-text>
    </v-card>
  </v-container>
</template>
<script>
import ApiService from '../services/ApiService';

export default {
  data() {
    return {
      form: {
        email: '',
        password: '',
        isLoading: false,
      },
    };
  },
  methods: {
    async login() {
      try {
        this.form.isLoading = true;
        await ApiService.login({
          email: this.form.email,
          password: this.form.password,
        });
        location.reload();
      } catch (error) {
        alert(error);
      } finally {
        this.form.isLoading = false;
      }
    },
  },
};
</script>