require('./bootstrap');
window.Vue = require('vue');
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

Vue.component('test-app', require('./components/TestApp.vue').default);
Vue.component('experts-list', require('./components/ExpertsList.vue').default);
Vue.component('clients-form', require('./components/ClientsForm.vue').default);
Vue.component('experts-form', require('./components/ExpertsForm.vue').default);
Vue.component('experts-schedule-form', require('./components/ExpertsScheduleForm.vue').default);
Vue.component('booking-list', require('./components/BookingList.vue').default);

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

const app = new Vue({
    el: '#app',
});


