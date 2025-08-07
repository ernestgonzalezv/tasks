import { ApiClient } from '../http/ApiClient'

export class KeywordService {
  constructor() {
    this.apiClient = new ApiClient()
  }

  async getAllKeywords() {
    return await this.apiClient.get('/keywords')
  }

  async createKeyword(keywordData) {
    return await this.apiClient.post('/keywords', keywordData)
  }
}
