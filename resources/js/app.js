import './bootstrap';

import { createApp } from 'vue'
import App from './vue/app.vue'

const app = createApp({})

app.component('welcome', App);

app.mount('#app')