<script setup lang="ts">
import { FormKit } from '@formkit/vue';
import type { FormKitNode } from '@formkit/core';
import axios from 'axios';
import router from '@/router';
import { AxiosError } from 'axios';
import axiosInstance from '@/lib/axios';

type TaskForm = {
    title: string;
    description: string;
    status: string;
    category: string;
};



const createTask = async (payload: TaskForm, node?: FormKitNode) => {
    try {
        
        await axiosInstance.post('/tasks', payload);
        router.push('/dashboard/tasks');
    } catch (e) {
        if (e instanceof AxiosError && e.response?.status === 422) {
            console.error("Validation Errors:", e.response.data.errors);
            node?.setErrors([], e.response?.data.errors);
        } else {
            console.error("API Error:", e);
        }
    }
};
</script>

<template>
    <h1>Create Task</h1>
    <div class="max-w-[24em] mx-auto rounded-lg p-4 bg-white shadow-md">
        <FormKit type="form" submit-label="Create Task" @submit="createTask">
            
            <FormKit type="text" label="Task Title" name="title" validation="required" placeholder="Enter task title" />

            <FormKit type="textarea" label="Description" name="description" placeholder="Enter task description" />

            <FormKit
                type="radio"
                label="Status"
                name="status"
                :options="['pending', 'completed']"
                validation="required"
                value="pending"
            />

            <FormKit
                type="select"
                label="Category"
                name="category"
                validation="required"
                :options="['Work', 'Personal', 'Urgent']"
                placeholder="Select category"
            />
        </FormKit>
    </div>
</template>
