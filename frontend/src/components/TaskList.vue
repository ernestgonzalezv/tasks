<template>
  <div class="task-list">
    <!-- Formulario para crear nueva tarea -->
    <div class="glass-card mb-4 create-task-card">
      <div class="card-header gradient-header">
        <h5 class="mb-0 text-white">
          <i class="fas fa-plus-circle me-2"></i>
          ✨ New Task
        </h5>
      </div>
      <div class="card-body p-4">
        <form @submit.prevent="handleCreateTask" novalidate>
          <!-- Título tarea -->
          <div class="mb-3">
            <label for="taskTitle" class="form-label fw-bold">
              <i class="fas fa-edit me-2 text-primary"></i>
              Task Title
            </label>
            <div class="input-group">
              <input
                  id="taskTitle"
                  v-model="newTaskTitle"
                  type="text"
                  class="form-control modern-input"
                  :class="{ 'is-invalid': titleError }"
                  placeholder="Write something amazing..."
                  maxlength="255"
                  required
                  :disabled="isCreating"
                  @input="validateTitle"
                  @blur="validateTitle"
              />
              <span class="input-group-text bg-primary text-white">
                <i class="fas fa-keyboard"></i>
              </span>
            </div>
            <div v-if="titleError" class="invalid-feedback d-block">
              <i class="fas fa-exclamation-triangle me-1"></i>
              {{ titleError }}
            </div>
            <div class="form-text">
              {{ newTaskTitle.length }}/255 characters
            </div>
          </div>

          <!-- Palabras clave -->
          <div class="mb-3">
            <label for="taskKeywords" class="form-label fw-bold">
              <i class="fas fa-tags me-2 text-success"></i>
              Keywords
            </label>
            <select
                id="taskKeywords"
                v-model="selectedKeywords"
                class="form-select modern-select"
                multiple
                size="4"
                :disabled="isCreating || keywords.length === 0"
            >
              <option
                  v-for="keyword in keywords"
                  :key="`keyword-${keyword.id}`"
                  :value="keyword.id"
                  class="keyword-option"
              >
                🏷️ {{ keyword.name }}
              </option>
            </select>
            <div class="form-text">
              <i class="fas fa-info-circle me-1"></i>
              Hold Ctrl (Cmd on Mac) to select multiple
            </div>
          </div>

          <!-- Botones -->
          <div class="d-flex gap-2 flex-wrap">
            <button
                type="submit"
                class="btn btn-primary btn-modern"
                :disabled="isCreating || !isFormValid"
            >
              <span v-if="isCreating" class="spinner-border spinner-border-sm me-2"></span>
              <i v-else class="fas fa-rocket me-2"></i>
              {{ isCreating ? 'Creating...' : 'Create Task' }}
            </button>

            <button
                type="button"
                class="btn btn-outline-secondary btn-modern"
                @click="showKeywordForm = !showKeywordForm"
            >
              <i class="fas fa-plus me-2"></i>
              {{ showKeywordForm ? 'Cancel' : 'New Keyword' }}
            </button>
          </div>
        </form>

        <!-- Formulario nueva palabra clave con transición -->
        <transition name="slide-down">
          <div v-if="showKeywordForm" class="mt-4 p-3 keyword-form-container">
            <h6 class="mb-3">
              <i class="fas fa-tag me-2 text-success"></i>
              New Keyword
            </h6>
            <form @submit.prevent="handleCreateKeyword" novalidate>
              <div class="input-group">
                <input
                    v-model="newKeywordName"
                    type="text"
                    class="form-control modern-input"
                    :class="{ 'is-invalid': keywordError }"
                    placeholder="E.g.: Urgent, Personal, Work..."
                    maxlength="255"
                    required
                    :disabled="isCreatingKeyword"
                    @input="validateKeywordRT"
                    @blur="validateKeywordRT"
                />
                <button
                    type="submit"
                    class="btn btn-success"
                    :disabled="isCreatingKeyword || !isKeywordFormValid"
                >
                  <span v-if="isCreatingKeyword" class="spinner-border spinner-border-sm me-2"></span>
                  <i v-else class="fas fa-check me-2"></i>
                  {{ isCreatingKeyword ? 'Creating...' : 'Create' }}
                </button>
              </div>
              <div v-if="keywordError" class="invalid-feedback d-block mt-1">
                <i class="fas fa-exclamation-triangle me-1"></i>
                {{ keywordError }}
              </div>
            </form>
          </div>
        </transition>
      </div>
    </div>

    <!-- Mensajes de estado con transición -->
    <transition name="fade" mode="out-in">
      <div v-if="error" class="alert alert-danger alert-modern" role="alert" key="error">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ error }}
      </div>
      <div v-else-if="successMessage" class="alert alert-success alert-modern" role="alert" key="success">
        <i class="fas fa-check-circle me-2"></i>
        {{ successMessage }}
      </div>
    </transition>

    <!-- Lista de tareas -->
    <div class="glass-card">
      <div
          class="card-header gradient-header d-flex justify-content-between align-items-center"
      >
        <h5 class="mb-0 text-white">
          <i class="fas fa-list-check me-2"></i>
          📝 My Tasks ({{ tasks.length }})
        </h5>
        <button
            class="btn btn-light btn-sm btn-modern"
            @click="handleRefreshTasks"
            :disabled="isLoading"
        >
          <span v-if="isLoading" class="spinner-border spinner-border-sm me-2"></span>
          <i v-else class="fas fa-sync-alt me-2"></i>
          {{ isLoading ? 'Loading...' : 'Refresh' }}
        </button>
      </div>
      <div class="card-body p-4">
        <!-- Control de estados: carga, vacío, lista -->
        <div>
          <transition name="fade" mode="out-in">
            <div v-if="isLoading && tasks.length === 0" class="loading-container" key="loading">
              <div class="loading-spinner">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </div>
              <p class="mt-3 text-muted">✨ Loading your awesome tasks...</p>
            </div>

            <div
                v-else-if="!isLoading && tasks.length === 0"
                class="empty-state"
                key="empty"
            >
              <div class="empty-icon">
                <i class="fas fa-clipboard-list"></i>
              </div>
              <h4 class="mt-3 mb-2">Start your adventure!</h4>
              <p class="text-muted mb-4">
                No tasks created yet. Create your first task and start being more productive!
              </p>
              <button class="btn btn-primary btn-modern" @click="focusNewTaskInput">
                <i class="fas fa-plus me-2"></i>
                Create First Task
              </button>
            </div>

            <transition-group
                v-else
                name="task-list"
                tag="div"
                class="row"
                key="list"
            >
              <div
                  v-for="task in tasks"
                  :key="`task-${task.id}`"
                  class="col-12 col-md-6 col-lg-4 mb-4"
              >
                <div
                    class="task-card h-100"
                    :class="{ 'task-completed': task.is_done }"
                >
                  <div class="task-card-body">
                    <div
                        class="d-flex justify-content-between align-items-start mb-3"
                    >
                      <h6 class="task-title mb-0" :class="{ completed: task.is_done }">
                        {{ task.title }}
                      </h6>
                      <span
                          class="badge task-status"
                          :class="task.is_done ? 'bg-success' : 'bg-warning'"
                      >
                        {{ task.is_done ? '✅ Completed' : '⏳ Pending' }}
                      </span>
                    </div>

                    <div
                        v-if="task.keywords && task.keywords.length > 0"
                        class="mb-3"
                    >
                      <small class="text-muted d-block mb-2">
                        <i class="fas fa-tags me-1"></i> Keywords:
                      </small>
                      <div class="keywords-container">
                        <span
                            v-for="keyword in task.keywords"
                            :key="`task-${task.id}-keyword-${keyword.id}`"
                            class="badge keyword-badge"
                        >
                          🏷️ {{ keyword.name }}
                        </span>
                      </div>
                    </div>

                    <div class="task-footer">
                      <button
                          class="btn btn-sm task-toggle-btn"
                          :class="task.is_done ? 'btn-outline-warning' : 'btn-outline-success'"
                          @click="handleToggleTask(task.id)"
                          :disabled="isToggling === task.id"
                      >
                        <span
                            v-if="isToggling === task.id"
                            class="spinner-border spinner-border-sm me-1"
                        ></span>
                        <i
                            v-else
                            :class="task.is_done ? 'fas fa-undo' : 'fas fa-check'"
                            class="me-1"
                        ></i>
                        {{ task.is_done ? 'Reopen' : 'Complete' }}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </transition-group>
          </transition>
        </div>
      </div>
    </div>
  </div>
</template>




<style scoped>
/* Tus estilos quedan igual */
</style>



<script setup>
import { ref, computed, onMounted, nextTick } from 'vue'
import { useTaskManagement } from '../composables/useTaskManagement'
import { validateTask, validateKeyword, sanitizeString } from '../utils/validators'

// Composable para gestión de tareas
const {
  tasks,
  keywords,
  isLoading,
  error,
  successMessage,
  loadTasks,
  loadKeywords,
  createTask,
  createKeyword,
  toggleTask
} = useTaskManagement()

// Estado del formulario
const newTaskTitle = ref('')
const selectedKeywords = ref([])
const newKeywordName = ref('')
const showKeywordForm = ref(false)

// Estados de carga específicos
const isCreating = ref(false)
const isCreatingKeyword = ref(false)
const isToggling = ref(null)

// Estados de validación
const titleError = ref('')
const keywordError = ref('')

// Validaciones en tiempo real
const validateTitle = () => {
  titleError.value = ''
  const title = newTaskTitle.value.trim()

  if (!title) {
    titleError.value = 'Title is required'
  } else if (title.length > 255) {
    titleError.value = 'Title cannot exceed 255 characters'
  }
}

const validateKeywordRT = () => {
  keywordError.value = ''
  const name = newKeywordName.value.trim()

  if (!name) {
    keywordError.value = 'Name is required'
  } else if (name.length > 255) {
    keywordError.value = 'Name cannot exceed 255 characters'
  }
}


// Computed para validación de formularios
const isFormValid = computed(() => {
  return newTaskTitle.value.trim().length > 0 && !titleError.value
})

const isKeywordFormValid = computed(() => {
  return newKeywordName.value.trim().length > 0 && !keywordError.value
})

// Manejar creación de tarea
const handleCreateTask = async () => {
  try {
    validateTitle()
    if (!isFormValid.value) return

    isCreating.value = true

    await createTask({
      title: sanitizeString(newTaskTitle.value),
      keyword_ids: [...selectedKeywords.value], // Crear copia del array
      is_done: false
    })

    // Limpiar formulario solo si fue exitoso
    newTaskTitle.value = ''
    selectedKeywords.value = []
    titleError.value = ''

  } catch (err) {
    console.error('Error in handleCreateTask:', err)
  } finally {
    isCreating.value = false
  }
}

// Manejar creación de palabra clave
const handleCreateKeyword = async () => {
  try {
    validateKeyword()
    if (!isKeywordFormValid.value) return

    isCreatingKeyword.value = true

    await createKeyword({
      name: sanitizeString(newKeywordName.value)
    })

    // Limpiar formulario solo si fue exitoso
    newKeywordName.value = ''
    keywordError.value = ''
    showKeywordForm.value = false

  } catch (err) {
    console.error('Error in handleCreateKeyword:', err)
  } finally {
    isCreatingKeyword.value = false
  }
}

// Manejar cambio de estado de tarea
const handleToggleTask = async (taskId) => {
  if (!taskId || isToggling.value === taskId) return

  try {
    isToggling.value = taskId
    await toggleTask(taskId)
  } catch (err) {
    console.error('Error in handleToggleTask:', err)
  } finally {
    isToggling.value = null
  }
}

// Refrescar tareas manualmente
const handleRefreshTasks = async () => {
  try {
    await loadTasks()
  } catch (err) {
    console.error('Error refreshing tasks:', err)
  }
}

// Enfocar input de nueva tarea
const focusNewTaskInput = async () => {
  await nextTick()
  const input = document.getElementById('taskTitle')
  if (input) {
    input.focus()
    input.scrollIntoView({ behavior: 'smooth', block: 'center' })
  }
}

// Cargar datos iniciales con manejo de errores
onMounted(async () => {
  try {
    await Promise.allSettled([
      loadTasks(),
      loadKeywords()
    ])
  } catch (err) {
    console.error('Error loading initial data:', err)
  }
})
</script>

<style scoped>
/* Variables CSS para colores y animaciones */
:root {
  --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
  --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  --glass-bg: rgba(255, 255, 255, 0.1);
  --glass-border: rgba(255, 255, 255, 0.2);
  --shadow-soft: 0 8px 32px rgba(0, 0, 0, 0.1);
  --shadow-hover: 0 12px 40px rgba(0, 0, 0, 0.15);
  --border-radius: 16px;
  --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Estilos generales */
.task-list {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

/* Tarjetas con efecto glass */
.glass-card {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border: 1px solid var(--glass-border);
  border-radius: var(--border-radius);
  box-shadow: var(--shadow-soft);
  transition: var(--transition);
}

.glass-card:hover {
  box-shadow: var(--shadow-hover);
  transform: translateY(-2px);
}

/* Headers con gradiente */
.gradient-header {
  background: var(--primary-gradient);
  border-radius: var(--border-radius) var(--border-radius) 0 0;
  padding: 1rem 1.5rem;
  border: none;
}

/* Inputs modernos */
.modern-input, .modern-select {
  border: 2px solid #e9ecef;
  border-radius: 12px;
  padding: 12px 16px;
  transition: var(--transition);
  background: rgba(255, 255, 255, 0.9);
}

.modern-input:focus, .modern-select:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  background: white;
}

.modern-input.is-invalid {
  border-color: #dc3545;
  box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

/* Botones modernos */
.btn-modern {
  border-radius: 12px;
  padding: 10px 20px;
  font-weight: 600;
  transition: var(--transition);
  border: none;
  position: relative;
  overflow: hidden;
}

.btn-modern::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s;
}

.btn-modern:hover::before {
  left: 100%;
}

.btn-primary.btn-modern {
  background: var(--primary-gradient);
}

.btn-success.btn-modern {
  background: var(--success-gradient);
}

/* Tarjetas de tareas */
.task-card {
  background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
  border: 1px solid #e9ecef;
  border-radius: var(--border-radius);
  box-shadow: var(--shadow-soft);
  transition: var(--transition);
  overflow: hidden;
  position: relative;
}

.task-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: var(--primary-gradient);
  transform: scaleX(0);
  transition: transform 0.3s ease;
}

.task-card:hover::before {
  transform: scaleX(1);
}

.task-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-hover);
}

.task-card.task-completed {
  background: linear-gradient(145deg, #f8f9fa 0%, #e9ecef 100%);
  opacity: 0.8;
}

.task-card.task-completed::before {
  background: var(--success-gradient);
  transform: scaleX(1);
}

.task-card-body {
  padding: 1.5rem;
}

.task-title {
  font-weight: 600;
  color: #2d3748;
  line-height: 1.4;
}

.task-title.completed {
  text-decoration: line-through;
  color: #718096;
}

.task-status {
  font-size: 0.75rem;
  padding: 0.4rem 0.8rem;
  border-radius: 20px;
  font-weight: 600;
}

.task-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #e9ecef;
}

.task-toggle-btn {
  border-radius: 20px;
  padding: 0.4rem 1rem;
  font-size: 0.875rem;
  font-weight: 600;
  transition: var(--transition);
}

/* Keywords */
.keywords-container {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.keyword-badge {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-radius: 20px;
  padding: 0.3rem 0.8rem;
  font-size: 0.75rem;
  font-weight: 500;
  border: none;
}

.keyword-option {
  padding: 0.5rem;
}

/* Formulario de keyword */
.keyword-form-container {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-radius: 12px;
  border: 1px solid #dee2e6;
}

/* Estados especiales */
.loading-container, .empty-state {
  text-align: center;
  padding: 3rem 1rem;
}

.loading-spinner {
  display: inline-block;
}

.empty-state .empty-icon {
  font-size: 4rem;
  color: #cbd5e0;
  margin-bottom: 1rem;
}

.empty-state h4 {
  color: #2d3748;
  font-weight: 600;
}

/* Alertas modernas */
.alert-modern {
  border: none;
  border-radius: 12px;
  padding: 1rem 1.5rem;
  font-weight: 500;
  box-shadow: var(--shadow-soft);
}

/* Animaciones */
.fade-enter-active, .fade-leave-active {
  transition: all 0.3s ease;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

.slide-down-enter-active, .slide-down-leave-active {
  transition: all 0.3s ease;
}

.slide-down-enter-from, .slide-down-leave-to {
  opacity: 0;
  transform: translateY(-20px);
  max-height: 0;
}

.task-list-enter-active, .task-list-leave-active {
  transition: all 0.4s ease;
}

.task-list-enter-from {
  opacity: 0;
  transform: translateX(30px) scale(0.9);
}

.task-list-leave-to {
  opacity: 0;
  transform: translateX(-30px) scale(0.9);
}

.task-list-move {
  transition: transform 0.4s ease;
}

.card-header.gradient-header {
  border-top-left-radius: var(--border-radius);
  border-top-right-radius: var(--border-radius);
}
/* Responsive */
@media (max-width: 768px) {
  .task-list {
    padding: 10px;
  }

  .task-card-body {
    padding: 1rem;
  }

  .d-flex.gap-2 {
    flex-direction: column;
  }

  .btn-modern {
    width: 100%;
    margin-bottom: 0.5rem;
  }
}
.gradient-header {
  border-top-left-radius: 0.75rem;  /* igual que el border-radius del card */
  border-top-right-radius: 0.75rem;
}


/* Animaciones adicionales */
@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.05); }
}

/* Scrollbar personalizado */
.modern-select::-webkit-scrollbar {
  width: 8px;
}

.modern-select::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.modern-select::-webkit-scrollbar-thumb {
  background: var(--primary-gradient);
  border-radius: 4px;
}

.modern-select::-webkit-scrollbar-thumb:hover {
  background: #5a67d8;
}
</style>
