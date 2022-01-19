require('./bootstrap');

require('alpinejs');

import Vue from 'vue';
import axiosApi from 'axios';
import moment from "moment";

const axios = axiosApi.create({
    //baseURL: `http://127.0.0.1:8000/`,
    baseURL: `https://tuappmin.com/`,
});

moment.locale('es');
window.Vue = require('vue');
window.axios = axios;
Vue.prototype.moment = moment;

/** Paginacion */
Vue.component('pagination', require('laravel-vue-pagination'));

/** Partials */
Vue.component('dateformat-component', require('./components/Partials/DateFormat.vue').default);
Vue.component('number-format-component', require('./components/Partials/NumberFormat.vue').default);

/** Usuario */
Vue.component('users-component', require('./components/user/lista.vue').default);
Vue.component('user-modal-crear-editar-component', require('./components/user/modal/crear_editar.vue').default);

/** Residente */
Vue.component('residentes-component', require('./components/residente/lista.vue').default);
Vue.component('residente-modal-crear-editar-component', require('./components/residente/modal/crear_editar.vue').default);

/** Noticia */
Vue.component('noticias-component', require('./components/noticia/lista.vue').default);
Vue.component('noticia-modal-crear-editar-component', require('./components/noticia/modal/crear_editar.vue').default);

/** Notificaciones */
Vue.component('notificaciones-component', require('./components/notificacion/lista.vue').default);
Vue.component('notificacion-modal-crear-editar-component', require('./components/notificacion/modal/crear_editar.vue').default);

/** Chats */
Vue.component('chats-component', require('./components/chat/chat.vue').default);
Vue.component('chat-content-component', require('./components/chat/chat_content.vue').default);
Vue.component('nueva-conversacion-component', require('./components/chat/modal/nueva_conversacion.vue').default);
Vue.component('preview-image-component', require('./components/chat/modal/preview_image.vue').default);

/** Apartamento */
Vue.component('apartamentos-component', require('./components/administracion/apartamento/lista.vue').default);
Vue.component('apartamento-modal-crear-editar-component', require('./components/administracion/apartamento/modal/crear_editar.vue').default);

/** Casa */
Vue.component('casas-component', require('./components/administracion/casa/lista.vue').default);
Vue.component('casa-modal-crear-editar-component', require('./components/administracion/casa/modal/crear_editar.vue').default);

/** Zona comun */
Vue.component('zonas-comunes-component', require('./components/administracion/zona_comun/lista.vue').default);
Vue.component('zona-comun-modal-crear-editar-component', require('./components/administracion/zona_comun/modal/crear_editar.vue').default);

Vue.mixin({
    methods: {
        _showAlert(status, message, duration = 5000){
            Lobibox.notify(status, {
                pauseDelayOnHover: true,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                icon: status == 'success' ? 'bx bx-check-circle' : 'bx bx-error-circle',
                title: status == 'success' ? 'Bien hecho' : 'Error',
                msg: message,
                sound: false,
                delay: duration
            });
        },
        _confirmAccion: async function(title, text, type = "warning") {
            return await Swal.fire({
                title: title ? title : "¿Estas seguro?",
                text: text,
                type: type,
                showCancelButton: !0,
                confirmButtonColor: "#673ab7",
                cancelButtonColor: "#5a7684",
                confirmButtonText: "Confirmar",
                confirmButtonClass: "btn long btn-primary",
                cancelButtonClass: "btn long btn-secondary ml-1",
                cancelButtonText: "Cancelar",
                buttonsStyling: !1,
            }).then((t) => {
                return t;
            });
        }
    },
})

const app = new Vue({
    el: '#app',
});
