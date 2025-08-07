import { createApp } from 'vue'
import App from './App.vue'

// Importar Bootstrap CSS
import 'bootstrap/dist/css/bootstrap.min.css'

// Crear y montar la aplicación
const app = createApp(App)

app.mount('#app')

// Opcional: Importar Bootstrap JS para componentes interactivos
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
