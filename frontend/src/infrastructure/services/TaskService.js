import { ApiClient } from '../http/ApiClient'

export class TaskService {
  constructor() {
    this.apiClient = new ApiClient()
  }

  async getAllTasks() {
    return await this.apiClient.get('/tasks')
  }
  async createTask(taskData) {
    return await this.apiClient.post('/tasks', taskData)
  }

  async toggleTask(taskId) {
    return await this.apiClient.patch(`/tasks/${taskId}/toggle`)
  }
}
