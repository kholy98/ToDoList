<script setup lang="ts">
import axiosInstance from '@/lib/axios';
import { onMounted, ref, watch } from 'vue';
import { RouterLink } from 'vue-router';

type Task = {
    id: number; 
    title: string;
    description: string;
    status: string;
    category: string;
    due_date: string | null;
    deleted_at: string | null;
} 
const tasks = ref<Task[]>([]);
const searchQuery = ref<string>('');
const pagination = ref({ current_page: 1, last_page: 1 });
const sortBy = ref<string>('created_at'); 
const sortOrder = ref<string>('asc'); 

// Filter Variables
const filterStatus = ref<string>(''); 
const filterCategory = ref<string>(''); 
const showDeleted = ref<boolean>(false); 
const filterDueDateFrom = ref<string>(''); 
const filterDueDateTo = ref<string>('');   

// Modal Control
const showFilterModal = ref<boolean>(false);

const fetchTasks = async (page = 1) => {
    try {
        const response = await axiosInstance.get(`/tasks`, {
            params: {
                page,
                search: searchQuery.value,
                sortBy: sortBy.value,
                sortOrder: sortOrder.value,
                status: filterStatus.value,
                category: filterCategory.value,
                due_date_from: filterDueDateFrom.value, 
                due_date_to: filterDueDateTo.value,
                deleted: showDeleted.value ? 1 : 0,
            },
        });

        tasks.value = response.data.data;
        pagination.value = {
            current_page: response.data.current_page,
            last_page: response.data.last_page,
        };
    } catch (error) {
        console.error("Error fetching tasks:", error);
    }
};


// Fetch tasks on mount
onMounted(() => fetchTasks());

// Watch for search or sort changes
watch([searchQuery, sortBy, sortOrder, filterStatus, filterCategory, showDeleted], () => fetchTasks(1));


const toggleFilterModal = () => {
    showFilterModal.value = !showFilterModal.value;
};


const clearFilters = () => {
    searchQuery.value = '';
    filterStatus.value = '';
    filterCategory.value = '';
    filterDueDateFrom.value = ''; 
    filterDueDateTo.value = ''; 
    showDeleted.value = false;
    sortBy.value = 'created_at';
    sortOrder.value = 'asc';
    fetchTasks(1);
};


const changePage = (page: number) => {
    if (page > 0 && page <= pagination.value.last_page) {
        fetchTasks(page);
    }
};

const deleteTask = async (id: number) => {
    if (!confirm("Are you sure you want to delete this task?")) return;
    try {
        await axiosInstance.delete(`/tasks/${id}`);
        await fetchTasks();
    } catch (error) {
        console.error("Error deleting task:", error);
    }
};

const restoreTask = async (id: number) => {
    try {
        await axiosInstance.patch(`/tasks/${id}/restore`);
        await fetchTasks(); 
        console.log(`Task ${id} restored successfully!`);
    } catch (error) {
        console.error("Error restoring task:", error);
    }
};



const categoryClass = (category: string) => `category-${category}`;

const statusClass = (status: string) => `status-${status}`;

</script>

<template>
    <div class="flex p-4 justify-between p-4">

        
        
        <input v-model="searchQuery" type="text" placeholder="Search tasks..." 
            class="px-4 py-2 border-2 border-blue-500 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-200">


         
         <button @click="toggleFilterModal"
            class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition">
            Filters
        </button>

        <!-- Sorting Dropdowns -->
        <div class="flex items-center space-x-4">
            <!-- Sort By -->
            <div>
                <label class="block text-sm font-medium">Sort By</label>
                <select v-model="sortBy" class="px-3 py-2 border rounded-md shadow-sm">
                    <option value="title">Title</option>
                    <option value="description">Description</option>
                    <option value="status">Status</option>
                    <option value="category">Category</option>
                    <option value="due_date">Due Date</option>
                    <option value="created_at">Created At</option>
                    <option value="updated_at">Updated At</option>
                </select>
            </div>

            <!-- Sort Order -->
            <div>
                <label class="block text-sm font-medium">Order</label>
                <select v-model="sortOrder" class="px-3 py-2 border rounded-md shadow-sm">
                    <option value="asc">Ascending</option>
                    <option value="desc">Descending</option>
                </select>
            </div>
        </div>

        <!--ilter Modal -->
        <transition name="fade">
            <div v-if="showFilterModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-96 relative">
                    <h2 class="text-lg font-bold mb-4">Filters</h2>

                    <!-- Status Filter -->
                    <label class="block mb-2 font-medium">Status</label>
                    <select v-model="filterStatus" class="w-full px-3 py-2 border rounded-md shadow-sm mb-4">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                    </select>

                    <!-- Category Filter -->
                    <label class="block mb-2 font-medium">Category</label>
                    <select v-model="filterCategory" class="w-full px-3 py-2 border rounded-md shadow-sm mb-4">
                        <option value="">All Categories</option>
                        <option value="Work">Work</option>
                        <option value="Personal">Personal</option>
                        <option value="Urgent">Urgent</option>
                    </select>

                    <!-- Show Deleted Checkbox -->
                    <label class="flex items-center space-x-2 cursor-pointer mb-4">
                        <input type="checkbox" v-model="showDeleted" class="cursor-pointer">
                        <span>Show Deleted</span>
                    </label>

                    <!-- Buttons -->
                    <div class="flex justify-between">
                        <button @click="clearFilters" 
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                            Clear Filters
                        </button>

                        <button @click="toggleFilterModal" 
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </transition>
        
        <RouterLink :to="{ name: 'TaskCreate' }" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Create Task</RouterLink>
    </div>

    <section>
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Task Title</th>
                        <th scope="col" class="px-6 py-3">Description</th>
                        <th scope="col" class="px-6 py-3">Category</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th>Due Date</th> 
                        <th scope="col" class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="task in tasks" :key="task.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ task.title }}
                        </th>
                        <td class="px-6 py-4">
                            {{ task.description || 'No description' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-md" :class="categoryClass(task.category)">
                                {{ task.category }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-md font-bold" :class="statusClass(task.status)">
                                {{ task.status }}
                            </span>
                        </td>
                        <td>
                            {{ task.due_date ? new Date(task.due_date).toLocaleDateString() : 'No due date' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center space-x-2 justify-center">
                                
                            <RouterLink 
                                :to="{ name: 'TaskEdit', params: { id: task.id } }"
                                class="px-3 py-1 rounded-md transition duration-300"
                                :class="task.deleted_at ? 'bg-gray-400 text-gray-300 cursor-not-allowed pointer-events-none' : 'bg-yellow-500 text-white hover:bg-yellow-600'"
                            >
                                Edit
                            </RouterLink>
                                
                                <button v-if="!task.deleted_at" @click="deleteTask(task.id)"
                                    class="px-3 py-1 text-white bg-red-500 rounded-md">Delete</button>

                                
                                <button v-else @click="restoreTask(task.id)"
                                    class="px-3 py-1 text-white bg-green-500 rounded-md">Restore</button>
                            </div>
                        </td>
                    </tr>

                    
                    <div class="flex justify-center space-x-2 mt-4">
                        <button @click="changePage(pagination.current_page - 1)" 
                            :disabled="pagination.current_page === 1"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 disabled:opacity-50">
                            Previous
                        </button>

                        <span class="px-4 py-2 bg-blue-500 text-white rounded-md">
                            {{ pagination.current_page }} of {{ pagination.last_page }}
                        </span>

                        <button @click="changePage(pagination.current_page + 1)" 
                            :disabled="pagination.current_page === pagination.last_page"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 disabled:opacity-50">
                            Next
                        </button>
                    </div>
                </tbody>
            </table>
        </div>
    </section>
</template>

<style scoped>
.category-Work {
    background-color: #fbbf24;
    color: #000;
}

.category-Personal {
    background-color: #34d399;
    color: #fff;
}

.category-Urgent {
    background-color: #ef4444;
    color: #fff;
}


.status-pending {
    background-color: #facc15;
    color: #000;
}

.status-completed {
    background-color: #10b981;
    color: #fff;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>
