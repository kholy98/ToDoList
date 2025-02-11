<script setup lang="ts">
    import axiosInstance from '@/lib/axios';
    import type { User } from '@/types';
    import { onMounted, ref } from 'vue';
    import { useAuthStore } from '@/store/auth';

    const auth = useAuthStore();

    onMounted(()=> {
        if(!auth.isLoggedIn){
            auth.getUser();
        }
    });

</script>

<template>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900 p-6">
        <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-6">Dashboard</h1>

        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 w-full max-w-md text-center">
            <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">
                Welcome Back, <span class="text-blue-500">{{ auth.user?.name }}</span>
            </p>
            <p class="text-md text-gray-600 dark:text-gray-400 mb-4">
                Email: <span class="font-medium">{{ auth.user?.email }}</span>
            </p>

            <button @click="auth.logout" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 shadow-md">
                Logout
            </button>
        </div>
    </div>
</template>