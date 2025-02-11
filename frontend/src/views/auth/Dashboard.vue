<script setup lang="ts">
import { onMounted } from 'vue';
import { useAuthStore } from '@/store/auth';

const auth = useAuthStore();

onMounted(() => {
    if (!auth.isLoggedIn) {
        auth.getUser();
    }
});
</script>

<template>
  <div class="flex flex-col items-center justify-center min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="w-full max-w-lg bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 text-center">
      <!-- Welcome Message -->
      <h1 class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">Welcome Back!</h1>
      <p class="text-gray-600 dark:text-gray-300 mt-2">We're glad to see you again, <span class="font-semibold text-indigo-500">{{ auth.user?.name }}</span>!</p>

      <!-- User Info Section -->
      <div class="mt-6 p-4 border border-gray-200 dark:border-gray-700 rounded-md">
        <p class="text-md text-gray-700 dark:text-gray-300">
          <span class="font-medium text-gray-900 dark:text-gray-100">Email:</span> {{ auth.user?.email }}
        </p>
      </div>

      <!-- Dashboard Actions -->
      <div class="mt-6 flex flex-col space-y-3">
        <RouterLink to="/dashboard/tasks" 
          class="w-full px-4 py-2 bg-indigo-600 text-white font-medium rounded-md shadow-md hover:bg-indigo-700 transition duration-300">
          View Tasks
        </RouterLink>
        
        <button @click="auth.logout"
          class="w-full px-4 py-2 bg-red-600 text-white font-medium rounded-md shadow-md hover:bg-red-700 transition duration-300">
          Logout
        </button>
      </div>
    </div>
  </div>
</template>
