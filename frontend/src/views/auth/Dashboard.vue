<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useAuthStore } from '@/store/auth';
import axiosInstance from '@/lib/axios';
import { RouterLink } from 'vue-router';

const auth = useAuthStore();


const totalTasks = ref(0);
const pendingTasks = ref(0);
const completedTasks = ref(0);
const overdueTasks = ref(0);


const fetchTaskSummary = async () => {
    try {
        const response = await axiosInstance.get('/summary');
        totalTasks.value = response.data.total;
        pendingTasks.value = response.data.pending;
        completedTasks.value = response.data.completed;
        overdueTasks.value = response.data.overdue;
    } catch (error) {
        console.error("Error fetching task summary:", error);
    }
};

onMounted(() => {
    if (!auth.isLoggedIn) {
        auth.getUser();
    }
    fetchTaskSummary(); 
});
</script>

<template>
  <div class="flex flex-col items-center justify-center min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="w-full max-w-4xl bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 text-center">
     
      <h1 class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">Welcome Back!</h1>
      <p class="text-gray-600 dark:text-gray-300 mt-2">We're glad to see you again, <span class="font-semibold text-indigo-500">{{ auth.user?.name }}</span>!</p>

      <!-- User Info Section -->
      <div class="mt-6 grid grid-cols-2 gap-4 p-4 border border-gray-200 dark:border-gray-700 rounded-md">
        <div class="flex flex-col items-center">
          <p class="text-md font-medium text-gray-700 dark:text-gray-300">Your Email</p>
          <p class="text-indigo-500 font-semibold">{{ auth.user?.email }}</p>
        </div>
        <div class="flex flex-col items-center">
          <p class="text-md font-medium text-gray-700 dark:text-gray-300">Total Tasks</p>
          <p class="text-indigo-500 font-semibold">{{ totalTasks }}</p>
        </div>
      </div>

      <!-- Dashboard Actions -->
      <div class="mt-6 grid grid-cols-2 gap-4">
        <RouterLink to="/dashboard/tasks" 
          class="px-4 py-3 bg-indigo-600 text-white font-medium rounded-md shadow-md hover:bg-indigo-700 transition duration-300 flex items-center justify-center">
          View Tasks
        </RouterLink>
        
        <RouterLink to="/dashboard/tasks/create" 
          class="px-4 py-3 bg-green-600 text-white font-medium rounded-md shadow-md hover:bg-green-700 transition duration-300 flex items-center justify-center">
           Create Task
        </RouterLink>
      </div>

      <!-- Task Summary Section -->
      <div class="mt-6 grid grid-cols-3 gap-4">
        
        <div class="bg-yellow-100 dark:bg-yellow-800 p-4 rounded-md text-center">
          <p class="text-lg font-semibold text-yellow-600 dark:text-yellow-300">Pending</p>
          <p class="text-2xl font-bold text-yellow-700 dark:text-yellow-200">{{ pendingTasks }}</p>
        </div>

        
        <div class="bg-green-100 dark:bg-green-800 p-4 rounded-md text-center">
          <p class="text-lg font-semibold text-green-600 dark:text-green-300">Completed</p>
          <p class="text-2xl font-bold text-green-700 dark:text-green-200">{{ completedTasks }}</p>
        </div>

        
        <div class="bg-red-100 dark:bg-red-800 p-4 rounded-md text-center">
          <p class="text-lg font-semibold text-red-600 dark:text-red-300">Overdue</p>
          <p class="text-2xl font-bold text-red-700 dark:text-red-200">{{ overdueTasks }}</p>
        </div>
      </div>

      
      <button @click="auth.logout"
        class="mt-6 w-full px-4 py-2 bg-red-600 text-white font-medium rounded-md shadow-md hover:bg-red-700 transition duration-300">
        Logout
      </button>
    </div>
  </div>
</template>
