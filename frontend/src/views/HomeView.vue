<script setup lang="ts">
</script>

<template>
  <main class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-indigo-50 to-purple-50 py-12 px-4">
    
    <div class="max-w-4xl w-full text-center">
      
      <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
        Organize Your Life with
        <span class="text-indigo-600">TO-DO List</span>
      </h1>

      
      <p class="text-lg text-gray-600 mb-8">
        Stay productive and never miss a task again. Our simple and intuitive TO-DO List app helps you manage your tasks efficiently, whether it's for work, study, or personal goals.
      </p>

      
      <div class="flex justify-center space-x-4">
        <RouterLink
          to="/register"
          class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition duration-300"
        >
          Get Started
        </RouterLink>
        <RouterLink
          to="/login"
          class="px-6 py-3 border border-indigo-600 text-indigo-600 rounded-lg font-semibold hover:bg-indigo-50 transition duration-300"
        >
          Login
        </RouterLink>
      </div>
    </div>

    
    <div class="mt-12 max-w-4xl w-full text-center">
      <img
        src="https://cdn.pixabay.com/photo/2021/08/04/13/06/software-developer-6521720_1280.jpg"
        alt="TO-DO List App"
        class="rounded-lg shadow-2xl mx-auto"
      />
    </div>
  </main>
</template>