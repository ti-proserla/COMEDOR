require('./bootstrap');


window.Vue = require('vue');
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css'
Vue.use(Vuetify);
// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

import VueRouter from 'vue-router'
Vue.use(VueRouter);

import Notifications from 'vue-notification'
Vue.use(Notifications)

const routes = [
    { path: '/', component: require('./view/pedido.vue').default },
    { path: '/reporte', component: require('./view/reporte.vue').default },
    { path: '/empresa', component: require('./view/empresa.vue').default },
    { path: '/planilla', component: require('./view/planilla.vue').default },
    { path: '/personal', component: require('./view/personal.vue').default },
  ]
  
  // 3. Create the router instance and pass the `routes` option
  // You can pass in additional options here, but let's
  // keep it simple for now.
const router = new VueRouter({
  routes, // short for `routes: routes`,
  mode: 'history'
})

import Dashboard from './App.vue';

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
    router,
    render: h => h(Dashboard)
});
