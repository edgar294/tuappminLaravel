<template>
    <div class="main-header-notification">
        <a href="#" class="header-icon notification-icon" data-toggle="dropdown">
            <span class="count" :data-bg-img="asset + 'assets/img/count-bg.png'">{{ count }}</span>
            <img :src="asset + 'assets/img/svg/notification-icon.svg'" alt="" class="svg">
        </a>
        <div class="dropdown-menu style--two dropdown-menu-right">
            <!-- Dropdown Header -->
            <div class="dropdown-header d-flex align-items-center justify-content-between" v-if="count > 0">
                <h5>{{ count }} {{ count > 1 ? 'nuevas notificaciones' : 'nueva notificación' }}</h5>
            </div>
            <div class="dropdown-header d-flex align-items-center justify-content-between" v-else>
                <h5>No hay notificaciones</h5>
            </div>
            <!-- End Dropdown Header -->

            <!-- Dropdown Body -->
            <div class="dropdown-body">
                <!-- Item Single -->
                <a class="item-single d-flex align-items-center" v-for="item in data" @click="_marcarVista(item)" style="cursor: pointer;">
                    <div class="content">
                        <div class="mb-2">
                            <p class="time"><dateformat-component :date="item.created_at"></dateformat-component></p>
                        </div>
                        <p class="main-text">{{ _replaceText(item) }}</p>
                    </div>
                </a>
                <!-- End Item Single -->
            </div>
            <!-- End Dropdown Body -->
        </div>
    </div>
</template>

<script>
    export default {
        props: ['asset', 'url'],
        data() {
            return {
                data: {},
                count: 0
            }
        },
        methods: {
            _getData() {
                axios
                    .get('notificaciones/get-take')
                    .then(response => {
                        this.count = response.data.count;
                        this.data = response.data.notificaciones;
                    })
            },
            configureNotification: async (messaging) => {
                await Notification.requestPermission()
            },
            _replaceText(data) {
                if (data.user) {
                    var text = data.message.replace(':user', data.user);
                }
                else {
                    var text = data.message.replace(':user', 'Desconocido');
                }

                return text;
            },
            _marcarVista(item){
                axios
                    .post('notificaciones/marcar-vista', {
                        notificacion_id: item.id
                    })
                    .then(response => {
                        window.location.href = this.url + '/' + item.accion;
                    })
            }
        },
        mounted() {
            this._getData();

            var firebaseConfig = {
                apiKey: "AIzaSyDEVhFjV6MPb6KkxayrpJTjP3PIWdIf5Ys",
                authDomain: "proyectos-71f86.firebaseapp.com",
                projectId: "proyectos-71f86",
                storageBucket: "proyectos-71f86.appspot.com",
                messagingSenderId: "596735195923",
                appId: "1:596735195923:web:40dc40ebaf5e1704ddb8e6",
                measurementId: "G-GFHTZ1YDXX"
            };
            // Initialize Firebase
            !firebase.apps.length ? firebase.initializeApp(firebaseConfig) : firebase.app();

            var messaging = null;
            if (firebase.messaging.isSupported()) {
                messaging = firebase.messaging();
            }

            this.configureNotification(messaging).then(function () {
                return messaging.getToken();
            })
                .then(token => {
                    $("#token_firebase").val(token);
                    return token;
                })
                .catch(function (err) {
                    console.log(err);
                    return err;
                })

            messaging.onMessage((payload) => {
                var notification = new Notification(payload.notification.title, {
                    icon: this.asset + 'assets/img/favicon.png',
                    image: payload.notification.icon,
                    body: payload.notification.body                    
                });

                this._getData();
            });
        }
    }

</script>