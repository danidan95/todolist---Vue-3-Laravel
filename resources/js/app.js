import './bootstrap';

import { createApp } from 'vue'
import App from './vue/app.vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from "@fortawesome/fontawesome-svg-core";
import { fas } from '@fortawesome/free-solid-svg-icons';

library.add(fas)

createApp(App)
  .component('fa', FontAwesomeIcon)
  .mount('#app')