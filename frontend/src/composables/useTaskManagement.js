import { ref } from 'vue'
import { TaskService } from '../infrastructure/services/TaskService'
import { KeywordService } from '../infrastructure/services/KeywordService'
import { validateTask, validateKeyword } from '../utils/validators'

/**
 * Parses various input types into boolean consistently.
 * @param {boolean|string|number} val
 * @returns {boolean}
 */
const parseBoolean = (val) => {
  if (typeof val === 'boolean') return val
  if (typeof val === 'string') {
    return val.toLowerCase() === 'true' || val === '1'
  }
  if (typeof val === 'number') {
    return val === 1
  }
  return false
}

/** Sanitize and validate raw tasks data from API */
function sanitizeTasks(rawTasks) {
  if (!Array.isArray(rawTasks)) {
    console.warn('Tasks data is not an array:', rawTasks)
    return []
  }

  return rawTasks
      .filter(task => task && typeof task === 'object' && task.id)
      .map(task => ({
        id: parseInt(task.id) || 0,
        title: String(task.title || '').trim(),
        is_done: parseBoolean(task.is_done ?? task.isDone),
        keywords: Array.isArray(task.keywords)
            ? task.keywords.filter(k => k && k.id).map(k => ({
              id: parseInt(k.id) || 0,
              name: String(k.name || '').trim()
            }))
            : []
      }))
      .filter(task => task.id > 0 && task.title.length > 0)
}

/** Sanitize and validate raw keywords data from API */
function sanitizeKeywords(rawKeywords) {
  if (!Array.isArray(rawKeywords)) {
    console.warn('Keywords data is not an array:', rawKeywords)
    return []
  }

  return rawKeywords
      .filter(keyword => keyword && typeof keyword === 'object' && keyword.id)
      .map(keyword => ({
        id: parseInt(keyword.id) || 0,
        name: String(keyword.name || '').trim()
      }))
      .filter(keyword => keyword.id > 0 && keyword.name.length > 0)
}

/** Use Case: Load tasks */
async function loadTasksUseCase(taskService) {
  const response = await taskService.getAllTasks()

  if (!response || !response.data) {
    throw new Error('Invalid server response')
  }

  const { success, data: rawTasks, message } = response.data

  if (!success) {
    throw new Error(message || 'Failed to load tasks')
  }

  return sanitizeTasks(rawTasks)
}

/** Use Case: Load keywords */
async function loadKeywordsUseCase(keywordService) {
  const response = await keywordService.getAllKeywords()

  if (!response || !response.data) {
    throw new Error('Invalid server response')
  }

  const { success, data: rawKeywords, message } = response.data

  if (!success) {
    throw new Error(message || 'Failed to load keywords')
  }

  return sanitizeKeywords(rawKeywords)
}

/** Use Case: Create a new task */
async function createTaskUseCase(taskService, taskData) {
  const validation = validateTask(taskData)
  if (!validation.isValid) {
    throw new Error(validation.errors.join(', '))
  }

  const payload = {
    title: taskData.title.trim(),
    keywords: Array.isArray(taskData.keyword_ids) ? taskData.keyword_ids : [],
    isDone: Boolean(taskData.is_done || false)
  }

  const response = await taskService.createTask(payload)

  if (!response || !response.data) {
    throw new Error('Invalid server response')
  }

  const { success, message } = response.data

  if (!success) {
    throw new Error(message || 'Failed to create task')
  }
}

/** Use Case: Create a new keyword */
async function createKeywordUseCase(keywordService, keywordData) {
  const validation = validateKeyword(keywordData)
  if (!validation.isValid) {
    throw new Error(validation.errors.join(', '))
  }

  const response = await keywordService.createKeyword({
    name: keywordData.name.trim()
  })

  if (!response || !response.data) {
    throw new Error('Invalid server response')
  }

  const { success, message } = response.data

  if (!success) {
    throw new Error(message || 'Failed to create keyword')
  }
}

/** Use Case: Toggle task completion status */
async function toggleTaskUseCase(taskService, taskId) {
  if (!taskId || taskId <= 0) {
    throw new Error('Invalid task ID')
  }

  const response = await taskService.toggleTask(taskId)

  if (!response || !response.data) {
    throw new Error('Invalid server response')
  }

  const { success, data: updatedTask, message } = response.data

  if (!success) {
    throw new Error(message || 'Failed to update task')
  }

  return updatedTask
}


/** Vue composable managing reactive state and exposing actions */
export function useTaskManagement() {
  const tasks = ref([])
  const keywords = ref([])
  const isLoading = ref(false)
  const error = ref(null)
  const successMessage = ref(null)

  const taskService = new TaskService()
  const keywordService = new KeywordService()

  function clearMessages() {
    error.value = null
    successMessage.value = null
  }

  function showSuccess(message) {
    successMessage.value = message
    setTimeout(() => (successMessage.value = null), 4000)
  }

  function showError(message) {
    error.value = message
    setTimeout(() => (error.value = null), 6000)
  }

  // Load tasks with error handling
  async function loadTasks() {
    try {
      clearMessages()
      isLoading.value = true

      const sanitizedTasks = await loadTasksUseCase(taskService)
      tasks.value = sanitizedTasks

      console.log(`✅ Loaded ${sanitizedTasks.length} tasks successfully`)
    } catch (err) {
      showError(`❌ ${err.message || 'Unknown error loading tasks'}`)
      tasks.value = []
      console.error('Error loading tasks:', err)
    } finally {
      isLoading.value = false
    }
  }

  // Load keywords with error handling
  async function loadKeywords() {
    try {
      clearMessages()
      isLoading.value = true

      const sanitizedKeywords = await loadKeywordsUseCase(keywordService)
      keywords.value = sanitizedKeywords

      console.log(`✅ Loaded ${sanitizedKeywords.length} keywords successfully`)
    } catch (err) {
      console.error('Error loading keywords:', err)
      keywords.value = []
      // Avoid showing error to not interfere with other UI messages
    } finally {
      isLoading.value = false
    }
  }

  // Create task and reload list
  async function createTask(taskData) {
    try {
      clearMessages()
      await createTaskUseCase(taskService, taskData)
      await loadTasks()
      showSuccess('✨ Task created successfully!')
    } catch (err) {
      showError(`❌ ${err.message || 'Unknown error creating task'}`)
      console.error('Error creating task:', err)
      throw err
    }
  }

  // Create keyword and reload list
  async function createKeyword(keywordData) {
    try {
      clearMessages()
      await createKeywordUseCase(keywordService, keywordData)
      await loadKeywords()
      showSuccess('✨ Keyword created successfully!')
    } catch (err) {
      showError(`❌ ${err.message || 'Unknown error creating keyword'}`)
      console.error('Error creating keyword:', err)
      throw err
    }
  }

  // Toggle task status locally and remotely
  async function toggleTask(taskId) {
    try {
      clearMessages()
      const updatedTask = await toggleTaskUseCase(taskService, taskId)

      if (updatedTask && updatedTask.id) {
        const index = tasks.value.findIndex(t => t.id === parseInt(taskId))
        if (index !== -1) {
          const sanitizedTask = sanitizeTasks([updatedTask])[0]
          if (sanitizedTask) {
            tasks.value[index] = sanitizedTask
          }
        }
      } else {
        await loadTasks()
      }

      const status = updatedTask?.isDone ? 'completed' : 'marked as pending'
      showSuccess(`✨ Task ${status} successfully!`)
    } catch (err) {
      showError(`❌ ${err.message || 'Unknown error updating task'}`)
      console.error('Error toggling task:', err)
      throw err
    }
  }

  return {
    // State
    tasks,
    keywords,
    isLoading,
    error,
    successMessage,

    // Actions
    loadTasks,
    loadKeywords,
    createTask,
    createKeyword,
    toggleTask,

    // Utilities
    clearMessages
  }
}
