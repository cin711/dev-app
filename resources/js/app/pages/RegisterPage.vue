<template>
  <v-container fluid class="mx-auto px-4 py-8">
    <v-card outlined rounded>
      <v-card-title class="text-h5 font-weight-bold mb-4">Register</v-card-title>
      <v-card-text>
        <v-form @submit.prevent="register">
          <v-text-field
          v-model="form.name"
          label="Name"
          required
          />
          <v-text-field
          v-model="form.email"
          label="Email"
          type="email"
          required
          />
          <v-text-field
          v-model="form.password"
          label="Password"
          type="password"
          required
          />
          <v-btn
          type="submit" :loading="form.isLoading" color="primary">
          Register
        </v-btn>
        <v-btn color="primary" variant="outlined" to="/login" class="ml-2">
          Login
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
        name: '',
        email: '',
        password: '',
        isLoading: false,
      },
    };
  },
  methods: {
    async register() {
      try {
        this.form.isLoading = true;
        await ApiService.register(this.form);
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