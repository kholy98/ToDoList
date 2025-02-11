<script setup lang="ts">
import { FormKit } from '@formkit/vue';
import { useAuthStore } from '@/store/auth';

const { login } = useAuthStore();
</script>

<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
      <!-- Title -->
      <h1 class="text-3xl font-bold text-center text-indigo-600 dark:text-indigo-400 mb-6">Login</h1>

      <!-- Form -->
      <FormKit type="form" submit-label="Login" @submit="login" class="space-y-4">
        <FormKit type="email" label="Email" name="email" validation="required|email" 
          placeholder="Enter your email" class="w-full" />
        <FormKit type="password" label="Password" name="password" validation="required" 
          placeholder="Enter your password" class="w-full" />
      </FormKit>

      <!-- Footer Links -->
      <div class="flex justify-between items-center mt-4 text-sm text-gray-600 dark:text-gray-300">
        <RouterLink to="/register" class="hover:text-indigo-600 dark:hover:text-indigo-400">Register</RouterLink>
      </div>
    </div>
  </div>
</template>
