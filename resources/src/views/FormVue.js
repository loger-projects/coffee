import Vue from 'vue'
import axios from 'axios'
import buefy from 'buefy'
import 'buefy/dist/buefy.css'
import FormVue from '../ViewComponents/FormVue.vue'

window.Vue = Vue
window.axios = axios
Vue.use(buefy)

new Vue({
    el: '#root',
    components: {
        FormVue
    }
})