<script setup lang="ts">
import { RouterLink, RouterView } from "vue-router";
</script>

<template>
  <header class="fixed top-0 left-0 w-full z-50 bg-white dark:bg-gray-900 shadow-lg">
    <nav class="container mx-auto flex items-center justify-between p-4">
      
      <div class="flex items-center space-x-4">
        <RouterLink to="/" class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300">
          TO-DO LIST
        </RouterLink>
      </div>


      <div class="flex items-center space-x-6">
        
        <RouterLink 
          class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition duration-300" 
          to="/dashboard"
        >
          Dashboard
        </RouterLink>
        <RouterLink 
          class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition duration-300" 
          to="/dashboard/tasks"
        >
          Tasks
        </RouterLink>
      </div>

      
      <div class="flex items-center space-x-4">
        <RouterLink 
          class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition duration-300" 
          to="/login"
        >
          Login
        </RouterLink>
        <RouterLink 
          class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-300" 
          to="/register"
        >
          Register
        </RouterLink>
      </div>
    </nav>
  </header>

  
  <main class="relative w-full min-h-screen flex justify-center items-start pt-24 bg-gray-50 dark:bg-gray-800">
    <div class="w-full max-w-6xl px-4 py-8">
      <RouterView />
    </div>
  </main>
</template>

<style scoped>
</style>