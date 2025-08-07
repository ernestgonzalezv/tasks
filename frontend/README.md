# 🗂️ Tasks Frontend – Vue 3 + Composition API + Bootstrap

Este proyecto es el **frontend** de una aplicación de gestión de tareas, desarrollado en **Vue 3** usando `<script setup>` y Composition API.  
Consume una API REST para:

- Ver tareas
- Crear nuevas tareas
- Cambiar el estado de las tareas (completada / pendiente)
- Asignar palabras clave reutilizables a las tareas

---

## 🚀 Requisitos

Antes de comenzar, asegúrate de tener instalado:

- [Node.js 18+](https://nodejs.org/)
- [npm 9+](https://www.npmjs.com/)

---

## 📂 Estructura del proyecto

```plaintext
src/
├── components/
│   └── TaskList.vue          # Componente principal para gestión de tareas
├── composables/
│   └── useTaskManagement.js  # Lógica de manejo de tareas y API
├── domain/
│   └── entities/             # Modelos de dominio (Task, Keyword)
├── infrastructure/
│   ├── http/
│   │   └── ApiClient.js      # Configuración axios para llamadas HTTP
│   └── services/             # Servicios para tareas y palabras clave
├── utils/
│   └── validators.js         # Validadores para inputs
├── App.vue
└── main.js                   # Punto de entrada Vue
````

---

## ⚙️ Instalación y ejecución

### 1. Clonar el repositorio

```bash
git clone <URL-del-repositorio>
cd frontend
```

### 2. Instalar dependencias

```bash
npm install
```

### 3. Configurar la URL base de la API

Edita el archivo `src/infrastructure/http/ApiClient.js` y actualiza la URL base de la API según tu backend:

```js
const apiClient = axios.create({
  baseURL: 'http://localhost:8000/api', // Cambia aquí con la APP_URL de api/.env
  ...
})
```

### 4. Ejecutar el servidor en modo desarrollo

```bash
npm run dev
```

Esto levantará el proyecto en:

```
http://localhost:5173
```

Abre esa URL en tu navegador para probar la app.

### 5. Compilar para producción

```bash
npm run build
```

### 6. Previsualizar el build

```bash
npm run preview
```

---

## 🎯 Funcionalidades

* Listar todas las tareas con:

   * Título
   * Estado (completada / pendiente)
   * Palabras clave asociadas
* Crear tareas nuevas con:

   * Input de texto para el título
   * Selección múltiple de palabras clave reutilizables
* Alternar estado de tareas con un botón (completada ↔ pendiente)
* Validaciones en tiempo real para títulos y palabras clave
* Manejo básico de errores y estados de carga

---

## 🛠️ Tecnologías usadas

* Vue 3 + `<script setup>` + Composition API
* Axios para llamadas HTTP
* Bootstrap 5 para estilos y layout
* Vite como bundler y dev server

---

## 📌 Notas importantes

* Este frontend está diseñado para consumir una API REST compatible (backend Laravel desarrollado por separado).
* Asegúrate de que el backend esté corriendo y accesible en la URL configurada.
* La interfaz está pensada para ser funcional y clara, con foco en usabilidad mínima.

---

## 📜 Licencia

Este proyecto está bajo la licencia MIT.

```
