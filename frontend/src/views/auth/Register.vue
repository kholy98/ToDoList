<script setup lang="ts">
import axiosInstance from '@/lib/axios';
import router from '@/router';
import { useAuthStore } from '@/store/auth';
import type { RegisterForm } from '@/types';
import { AxiosError } from 'axios';
import { reactive } from 'vue';

// Reactive form data
const form = reactive<RegisterForm>({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

// Validation errors
const errors = reactive({
    name: [] as string[],
    email: [] as string[],
    password: [] as string[],
});

// Authentication store
const auth = useAuthStore();

const register = async (payload: RegisterForm) => {
    await axiosInstance.get("/sanctum/csrf-cookie", { baseURL: "http://localhost:8000" });
    
    // Reset errors before new request
    errors.name = [];
    errors.email = [];
    errors.password = [];

    try {
        await axiosInstance.post('/register', payload);
        await auth.getUser();
        router.push('/dashboard');
    } catch (e) {
        if (e instanceof AxiosError && e.response?.status === 422) {
            errors.name = e.response.data.errors.name || [];
            errors.email = e.response.data.errors.email || [];
            errors.password = e.response.data.errors.password || [];
        }
    }
};
</script>

<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
      <!-- Title -->
      <h1 class="text-3xl font-bold text-center text-indigo-600 dark:text-indigo-400 mb-6">Register</h1>

      <!-- Form -->
      <form @submit.prevent="register(form)" class="space-y-4">
        
        <!-- Name Input -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
          <input type="text" v-model="form.name" id="name"
                 class="w-full mt-1 px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                 placeholder="Your name" required />
          <span v-for="error in errors.name" :key="error" class="text-red-500 text-xs italic">{{ error }}</span>
        </div>

        <!-- Email Input -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
          <input type="email" v-model="form.email" id="email"
                 class="w-full mt-1 px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                 placeholder="Your email" required />
          <span v-for="error in errors.email" :key="error" class="text-red-500 text-xs italic">{{ error }}</span>
        </div>

        <!-- Password Input -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
          <input type="password" v-model="form.password" id="password"
                 class="w-full mt-1 px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                 placeholder="Your password" required />
          <span v-for="error in errors.password" :key="error" class="text-red-500 text-xs italic">{{ error }}</span>
        </div>

        <!-- Confirm Password Input -->
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
          <input type="password" v-model="form.password_confirmation" id="password_confirmation"
                 class="w-full mt-1 px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                 placeholder="Confirm password" required />
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md shadow-md hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:focus:ring-indigo-800">
          Register
        </button>
      </form>

      <!-- Footer Links -->
      <div class="flex justify-between items-center mt-4 text-sm text-gray-600 dark:text-gray-300">
        <RouterLink to="/login" class="hover:text-indigo-600 dark:hover:text-indigo-400">Already have an account? Login</RouterLink>
      </div>
    </div>
  </div>
</template>
