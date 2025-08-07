<template>
  <div id="app" class="app-container">
    <!-- Background animado -->
    <div class="animated-background">
      <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
      </div>
    </div>

    <!-- Contenido principal -->
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-12">
          <!-- Header principal -->
          <header class="app-header text-center mb-5">
            <div class="header-content">
              <h1 class="app-title">
                <span class="title-icon">📋</span>
                <span class="title-text">TaskMaster Pro</span>
              </h1>
              <p class="app-subtitle">
                ✨ Gestiona tus tareas con estilo y eficiencia
              </p>
            </div>
          </header>

          <!-- Componente principal -->
          <main class="main-content">
            <TaskList />
          </main>

          <!-- Footer -->
          <footer class="app-footer text-center mt-5">
            <p class="footer-text">
              Hecho con 💜 y Vue 3 | 
              <small class="text-muted">Clean Architecture & Modern Design</small>
            </p>
          </footer>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import TaskList from './components/TaskList.vue'
import { onMounted } from 'vue'

onMounted(() => {
  console.log('🚀 TaskMaster Pro iniciado correctamente')
  console.log('🔗 API URL: http://127.0.0.1:8000/api')
})
</script>

<style>
/* Reset y variables globales */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

:root {
  --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
  --background-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
  --glass-bg: rgba(255, 255, 255, 0.1);
  --glass-border: rgba(255, 255, 255, 0.2);
  --text-primary: #2d3748;
  --text-secondary: #4a5568;
  --shadow-soft: 0 10px 40px rgba(0, 0, 0, 0.1);
  --shadow-strong: 0 20px 60px rgba(0, 0, 0, 0.2);
  --border-radius: 20px;
  --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Estilos globales */
body {
  font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  line-height: 1.6;
  color: var(--text-primary);
  background: var(--background-gradient);
  min-height: 100vh;
  overflow-x: hidden;
}

#app {
  min-height: 100vh;
  position: relative;
}

/* Background animado */
.app-container {
  position: relative;
  min-height: 100vh;
}

.animated-background {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: -1;
  background: var(--background-gradient);
  background-size: 400% 400%;
  animation: gradientShift 15s ease infinite;
}

@keyframes gradientShift {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

/* Formas flotantes */
.floating-shapes {
  position: absolute;
  width: 100%;
  height: 100%;
  overflow: hidden;
}

.shape {
  position: absolute;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  animation: float 20s infinite linear;
}

.shape-1 {
  width: 80px;
  height: 80px;
  top: 20%;
  left: 10%;
  animation-delay: 0s;
}

.shape-2 {
  width: 120px;
  height: 120px;
  top: 60%;
  right: 10%;
  animation-delay: -5s;
}

.shape-3 {
  width: 60px;
  height: 60px;
  top: 80%;
  left: 20%;
  animation-delay: -10s;
}

.shape-4 {
  width: 100px;
  height: 100px;
  top: 30%;
  right: 30%;
  animation-delay: -15s;
}

.shape-5 {
  width: 140px;
  height: 140px;
  top: 10%;
  left: 60%;
  animation-delay: -7s;
}

@keyframes float {
  0% {
    transform: translateY(0px) rotate(0deg);
    opacity: 0.7;
  }
  33% {
    transform: translateY(-30px) rotate(120deg);
    opacity: 0.4;
  }
  66% {
    transform: translateY(30px) rotate(240deg);
    opacity: 0.7;
  }
  100% {
    transform: translateY(0px) rotate(360deg);
    opacity: 0.7;
  }
}

/* Header principal */
.app-header {
  padding: 2rem 0;
  position: relative;
}

.header-content {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: var(--border-radius);
  padding: 2rem;
  box-shadow: var(--shadow-soft);
  display: inline-block;
  transition: var(--transition);
}

.header-content:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow-strong);
}

.app-title {
  font-size: 3rem;
  font-weight: 800;
  margin-bottom: 0.5rem;
  background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
}

.title-icon {
  font-size: 3.5rem;
  filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
  animation: bounce 2s infinite;
}

@keyframes bounce {
  0%, 20%, 50%, 80%, 100% {
    transform: translateY(0);
  }
  40% {
    transform: translateY(-10px);
  }
  60% {
    transform: translateY(-5px);
  }
}

.title-text {
  background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.app-subtitle {
  font-size: 1.2rem;
  color: rgba(255, 255, 255, 0.9);
  font-weight: 500;
  margin: 0;
}

/* Contenido principal */
.main-content {
  position: relative;
  z-index: 1;
}

/* Footer */
.app-footer {
  padding: 2rem 0;
  position: relative;
  z-index: 1;
}

.footer-text {
  color: rgba(255, 255, 255, 0.8);
  font-size: 0.9rem;
  margin: 0;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border-radius: 50px;
  padding: 1rem 2rem;
  display: inline-block;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Scrollbar personalizado */
::-webkit-scrollbar {
  width: 12px;
}

::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.1);
  border-radius: 6px;
}

::-webkit-scrollbar-thumb {
  background: var(--primary-gradient);
  border-radius: 6px;
  border: 2px solid transparent;
  background-clip: content-box;
}

::-webkit-scrollbar-thumb:hover {
  background: var(--secondary-gradient);
  background-clip: content-box;
}

/* Responsive */
@media (max-width: 768px) {
  .app-title {
    font-size: 2rem;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .title-icon {
    font-size: 2.5rem;
  }
  
  .app-subtitle {
    font-size: 1rem;
  }
  
  .header-content {
    padding: 1.5rem;
    margin: 0 1rem;
  }
  
  .floating-shapes .shape {
    display: none; /* Ocultar formas en móvil para mejor rendimiento */
  }
}

@media (max-width: 480px) {
  .app-title {
    font-size: 1.8rem;
  }
  
  .header-content {
    padding: 1rem;
  }
}

/* Animaciones de entrada */
.app-header {
  animation: fadeInDown 1s ease-out;
}

.main-content {
  animation: fadeInUp 1s ease-out 0.3s both;
}

.app-footer {
  animation: fadeIn 1s ease-out 0.6s both;
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Mejoras de accesibilidad */
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
  
  .animated-background {
    animation: none;
    background: var(--primary-gradient);
  }
  
  .floating-shapes {
    display: none;
  }
}

/* Focus styles para accesibilidad */
button:focus,
input:focus,
select:focus {
  outline: 2px solid rgba(255, 255, 255, 0.8);
  outline-offset: 2px;
}
</style>
