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

window.moment = require('moment');

function rutas(){
  return axios.get(url_base+'/rutas?rol='+store.state.cuenta.rol).then(res=>res.data).catch(res=>res);
}

var auth=async (to, from,next)=>{
  if(store.state.cuenta===null){
      next('/login');
  }else{
    var listaRutas=await rutas();
    store.state.rutas=listaRutas;
    if (listaRutas.indexOf(to.path)>-1) {
      next();
    }else{
      next('/');
    }
  }
}

const routes = [
    { path: '/', component: require('./view/home.vue').default,beforeEnter: auth },
    { path: '/atencion', component: require('./view/pedido.vue').default,beforeEnter: auth },
    { path: '/reporte-fecha', component: require('./view/reporte-fecha.vue').default,beforeEnter: auth },
    { path: '/reporte-personal', component: require('./view/reporte-personal.vue').default,beforeEnter: auth },
    { path: '/reporte-tiempo', component: require('./view/reporte-tiempo.vue').default,beforeEnter: auth },
    { path: '/empresa', component: require('./view/empresa.vue').default,beforeEnter: auth },
    { path: '/planilla', component: require('./view/planilla.vue').default,beforeEnter: auth },
    { path: '/personal', component: require('./view/personal.vue').default,beforeEnter: auth },
    { path: '/servicio', component: require('./view/servicio.vue').default,beforeEnter: auth },
    { path: '/login', component: require('./view/login.vue').default, meta:{layout: "empty"}},
  ];

const router = new VueRouter({
  routes,
  mode: 'history'
})

import Vuex from 'vuex'
Vue.use(Vuex)

window.store=new Vuex.Store({
  state: {
    cuenta: JSON.parse(localStorage.getItem('cuenta_sistema'))||null,
    rutas: []
  },
  mutations: {        
    auth_success(state,cuenta){
      state.cuenta=cuenta;
      localStorage.setItem('cuenta_sistema',JSON.stringify(state.cuenta));
      axios.defaults.headers.common['Authorization'] = state.cuenta.api_token;
    },
    auth_close(state){
      state.cuenta=null;
      state.rutas=[];
      localStorage.removeItem('cuenta_sistema');
    }
  },
  actions: {}
});
if (store.state.cuenta!=null) {
    axios.defaults.headers.common['Authorization'] = store.state.cuenta.api_token;
}

import Dashboard from './App.vue';
Vue.component('empty',require("./capas/empty.vue").default);
Vue.component('panel',require("./capas/panel.vue").default);
Vue.component("masivo", require("./view/masivo.vue").default);
const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
    router,
    store,
    render: h => h(Dashboard)
});
