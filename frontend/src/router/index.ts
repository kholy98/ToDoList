import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import Register from '@/views/auth/Register.vue'
import Login from '@/views/auth/Login.vue'
import Dashboard from '@/views/auth/Dashboard.vue'
import TasksIndex from '@/views/tasks/TasksIndex.vue'
import TaskCreate from '@/views/tasks/TaskCreate.vue'
import { useAuthStore } from '@/store/auth'
import TaskEdit from '@/views/tasks/TaskEdit.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Home',
      component: HomeView,
    },

    {
      path: '/404',
      name: '404',
      component: () => import("@/views/404.vue"),
    },

    {
      path: '/500',
      name: '500',
      component: () => import("@/views/500.vue"),
    },
    
    {
      path: '/register',
      name: 'Register',
      component: Register,
      meta: {requiresGuest: true},
    },
    {
      path: '/login',
      name: 'Login',
      component: Login,
      meta: {requiresGuest: true},
    },
    {
      path: '/dashboard',
      name: 'Dashboard',
      component: Dashboard,
      meta: {requiresAuth: true},
    },
    {
      path: '/dashboard/tasks',
      name: 'TasksIndex',
      component: TasksIndex,
      meta: {requiresAuth: true},
    },
    {
      path: '/dashboard/tasks/create',
      name: 'TaskCreate',
      component: TaskCreate,
      meta: {requiresAuth: true},
    },
    {
      path: '/dashboard/tasks/:id/edit',
      name: 'TaskEdit',
      component: TaskEdit,
      meta: {requiresAuth: true},
    },

  ],
})

router.beforeEach((to, from, next)=> {
  const auth = useAuthStore();
  if(to.matched.some((record) => record.meta.requiresAuth) && !auth.isLoggedIn){
    next({ name: 'Login'});
  }else if(to.matched.some((record) => record.meta.requiresGuest) && auth.isLoggedIn){
    next({ name: 'Dashboard'});
  }
  else{
    next();
  }
});

export default router
