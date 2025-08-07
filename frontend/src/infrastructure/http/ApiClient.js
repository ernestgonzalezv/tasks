import axios from 'axios'

export class ApiClient {
  constructor() {
    this.client = axios.create({
      baseURL: 'http://127.0.0.1:8000/api',
      timeout: 15000,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })

    this.setupInterceptors()
  }

  setupInterceptors() {
    this.client.interceptors.request.use(
        (config) => {
          console.log(`🚀 ${config.method?.toUpperCase()} ${config.url}`, config.data || 'No data')
          return config
        },
        (error) => {
          console.error('❌ Request error:', error)
          return Promise.reject(this.handleError(error))
        }
    )

    this.client.interceptors.response.use(
        (response) => {
          console.log(`✅ ${response.config.method?.toUpperCase()} ${response.config.url}`, response.data)
          if (response.data && typeof response.data === 'object') {
            if (response.data.hasOwnProperty('success')) {
              return response
            }
            return {
              ...response,
              data: {
                success: true,
                message: null,
                data: response.data
              }
            }
          }

          return response
        },
        (error) => {
          console.error('❌ Response error:', error.response?.data || error.message)
          return Promise.reject(this.handleError(error))
        }
    )
  }

  handleError(error) {
    // Network error or timeout
    if (!error.response) {
      if (error.code === 'ECONNABORTED') {
        error.message = 'The request took too long. Please check your internet connection.'
      } else if (error.code === 'ERR_NETWORK') {
        error.message = 'Could not connect to the server. Make sure the API is running at http://127.0.0.1:8000'
      } else {
        error.message = 'Connection error. Check your internet connection and that the server is available.'
      }
      return error
    }
    const { status, data } = error.response

    switch (status) {
      case 400:
        error.message = data?.message || 'Bad request. Please check the submitted data.'
        break

      case 401:
        error.message = 'Unauthorized. Please check your credentials.'
        break

      case 403:
        error.message = 'Access denied. You do not have permission to perform this action.'
        break

      case 404:
        error.message = data?.message || 'Resource not found.'
        break

      case 422:
        if (data?.errors && typeof data.errors === 'object') {
          const validationErrors = Object.values(data.errors).flat()
          error.message = validationErrors.join(', ')
        } else {
          error.message = data?.message || 'Validation error. Please review the submitted data.'
        }
        break

      case 429:
        error.message = 'Too many requests. Please wait a moment and try again.'
        break

      case 500:
        error.message = data?.message || 'Internal server error. Contact the administrator if the issue persists.'
        break

      case 502:
        error.message = 'The server is temporarily unavailable. Please try again later.'
        break

      case 503:
        error.message = 'Service unavailable. The server is under maintenance.'
        break

      default:
        error.message = data?.message || `Server error (${status}). Please contact the administrator.`
    }

    return error
  }

  async get(url, config = {}) {
    try {
      return await this.client.get(url, config)
    } catch (error) {
      throw error
    }
  }

  async post(url, data = {}, config = {}) {
    try {
      return await this.client.post(url, data, config)
    } catch (error) {
      throw error
    }
  }

  async put(url, data = {}, config = {}) {
    try {
      return await this.client.put(url, data, config)
    } catch (error) {
      throw error
    }
  }

  async patch(url, data = {}, config = {}) {
    try {
      return await this.client.patch(url, data, config)
    } catch (error) {
      throw error
    }
  }

  async delete(url, config = {}) {
    try {
      return await this.client.delete(url, config)
    } catch (error) {
      throw error
    }
  }

  async healthCheck() {
    try {
      const response = await this.get('/health')
      return response.data
    } catch (error) {
      console.warn('Health check failed:', error.message)
      return { healthy: false, error: error.message }
    }
  }
}
