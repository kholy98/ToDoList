import './assets/main.css'
import "./assets/tailwind.css";
import '@formkit/themes/genesis'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import { plugin, defaultConfig } from '@formkit/vue'
import router from './router'
import config from './../formkit.config.ts'
import piniaPluginPersistedState from "pinia-plugin-persistedstate"; 




const app = createApp(App)
const pinia = createPinia()
pinia.use(piniaPluginPersistedState);

app.use(router)
app.use(pinia)


app.use(plugin, defaultConfig(config))

app.mount('#app')
