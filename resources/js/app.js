import './bootstrap';
import { createApp } from 'vue';
import App from './app/components/App.vue'
import router from './app/routes';
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import '@mdi/font/css/materialdesignicons.css' // Ensure you are using css-loader 

const vuetify = createVuetify({
    components,
    directives,
    icons: {
        defaultSet: 'mdi',
    }
})

createApp(App)
    .use(router)
    .use(vuetify)
    .mount('#app');
