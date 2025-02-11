import axiosInstance from '@/lib/axios';
import router from '@/router';
import type { LoginForm, RegisterForm, User } from '@/types';
import { AxiosError } from 'axios';
import { defineStore } from 'pinia'
import { reactive, ref } from 'vue';
import type {FormKitNode} from '@formkit/core'

export const useAuthStore = defineStore("auth", ()=> {
    const user = ref<User | null>(null);
    const isLoggedIn = ref<boolean>(false);

    const errors = reactive({
        name: [],
        email: [],
        password: [],
    });

    const register = async (payload: RegisterForm)=> {
        
        await axiosInstance.get("/sanctum/csrf-cookie", {
       baseURL: "http://localhost:8000",
        });
        errors.name = [];
        errors.email = [];
        errors.password = [];
        try{
            await axiosInstance.post('/register', payload);
            await getUser();
            router.push('/dashboard');
        } catch(e){
            if(e instanceof AxiosError && e.response?.status === 422){
                errors.name = e.response.data.errors.name;
                errors.email = e.response.data.errors.email;
                errors.password = e.response.data.errors.password;
            }
        }
    };

    const login = async (payload: LoginForm, node?: FormKitNode)=> {
        await axiosInstance.get("/sanctum/csrf-cookie", {
       baseURL: "http://localhost:8000",
        });
        try{
            const response = await axiosInstance.post('/login', payload);
            await getUser();
            router.push('/dashboard');
        } catch(e){
            if(e instanceof AxiosError && e.response?.status === 422){
                node?.setErrors([], e.response?.data.errors);
            }
        }
    };

    const getUser = async () => {
        try {
    
            const response = await axiosInstance.get('/user');
            user.value = response.data;
            isLoggedIn.value = true;
        } catch (error) {
            console.error(error);
        }
    };

    const cleanState = () => {
        user.value = null;
        isLoggedIn.value = false;
    }
    const logout = async () => {
        try {
            const response = await axiosInstance.post('/logout');
            user.value = null;
            isLoggedIn.value = false;
            router.push('/login');
        } catch (error) {
            console.error(error);
        }
    };

    return {
        user,
        isLoggedIn,
        login,
        register,
        getUser,
        logout,
        cleanState
    };


},
{
    persist: {
        storage: sessionStorage,
        pick: ["user", "isLoggedIn"],
    }
}
); 