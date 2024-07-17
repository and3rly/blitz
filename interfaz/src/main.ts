import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { Vue3ProgressPlugin } from '@marcoschulte/vue3-progress';
import PerfectScrollbar from 'vue3-perfect-scrollbar';
import mitt from 'mitt';
import 'vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css';
import '@marcoschulte/vue3-progress/dist/index.css';
import '@fortawesome/fontawesome-free/scss/fontawesome.scss';
import '@fortawesome/fontawesome-free/scss/regular.scss';
import '@fortawesome/fontawesome-free/scss/solid.scss';
import '@fortawesome/fontawesome-free/scss/brands.scss';
import '@fortawesome/fontawesome-free/scss/v4-shims.scss';
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'bootstrap';
import './scss/styles.scss';

import App from './App.vue';
import router from './router';
import axiosClient from "@/plugins/axios.ts"

import Card from '@/components/bootstrap/Card.vue';
import CardBody from '@/components/bootstrap/CardBody.vue';
import CardHeader from '@/components/bootstrap/CardHeader.vue';
import CardFooter from '@/components/bootstrap/CardFooter.vue';
import CardGroup from '@/components/bootstrap/CardGroup.vue';
import CardImgOverlay from '@/components/bootstrap/CardImgOverlay.vue';
import CardExpandToggler from '@/components/bootstrap/CardExpandToggler.vue';
import vueSelect from '@/components/plugins/vueSelect.vue';

import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
import { PiniaSharedState } from 'pinia-shared-state';
import { Modal } from 'bootstrap'
import Vue3Toastify, { type ToastContainerOptions } from 'vue3-toastify';
import { toast, type ToastOptions } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import Helper from "@/mixins/Helper.js"

const emitter = mitt();
const app = createApp(App);
let tmpModal  = null

const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)
pinia.use(
  PiniaSharedState({
    enable: true,
    initialize: false,
    type: 'localstorage',
  }),
);

app.component('Card', Card);
app.component('CardBody', CardBody);
app.component('CardHeader', CardHeader);
app.component('CardFooter', CardFooter);
app.component('CardGroup', CardGroup);
app.component('CardImgOverlay', CardImgOverlay);
app.component('CardExpandToggler', CardExpandToggler);
app.component('vueSelect', vueSelect)

app.use(pinia);
app.use(router);
app.use(Vue3ProgressPlugin);
app.use(PerfectScrollbar);

app.mixin(Helper);

app.use(Vue3Toastify, {
  autoClose: 3000,
  "theme": "colored",
} as ToastContainerOptions);

function setModal(modalId) {
  return tmpModal = new Modal(document.getElementById(modalId));
}

let url = 'index.php';
let piniaPlugin = (context: PiniaPluginContext) => {
  return {
    urlBase: url
  };
};

app.config.globalProperties.$toast = toast
app.config.globalProperties.$baseUrl = url
app.config.globalProperties.setModal = setModal
app.config.globalProperties.emitter  = emitter;
app.config.globalProperties.$http    = axiosClient

app.mount('#app');
