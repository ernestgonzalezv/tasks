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
    // Request interceptor
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

    // Response interceptor
    this.client.interceptors.response.use(
      (response) => {
        console.log(`✅ ${response.config.method?.toUpperCase()} ${response.config.url}`, response.data)
        
        // Validar estructura de respuesta Laravel
        if (response.data && typeof response.data === 'object') {
          // Si la respuesta tiene la estructura esperada de Laravel
          if (response.data.hasOwnProperty('success')) {
            return response
          }
          // Si es una respuesta directa, envolver en estructura Laravel
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
    // Error de red o timeout
    if (!error.response) {
      if (error.code === 'ECONNABORTED') {
        error.message = 'La solicitud tardó demasiado tiempo. Verifica tu conexión a internet.'
      } else if (error.code === 'ERR_NETWORK') {
        error.message = 'No se pudo conectar con el servidor. Verifica que la API esté ejecutándose en http://127.0.0.1:8000'
      } else {
        error.message = 'Error de conexión. Verifica tu conexión a internet y que el servidor esté disponible.'
      }
      return error
    }

    // Errores HTTP
    const { status, data } = error.response

    switch (status) {
      case 400:
        error.message = data?.message || 'Solicitud inválida. Verifica los datos enviados.'
        break
      
      case 401:
        error.message = 'No autorizado. Verifica tus credenciales.'
        break
      
      case 403:
        error.message = 'Acceso denegado. No tienes permisos para realizar esta acción.'
        break
      
      case 404:
        error.message = data?.message || 'Recurso no encontrado.'
        break
      
      case 422:
        // Errores de validación Laravel
        if (data?.errors && typeof data.errors === 'object') {
          const validationErrors = Object.values(data.errors).flat()
          error.message = validationErrors.join(', ')
        } else {
          error.message = data?.message || 'Error de validación. Verifica los datos enviados.'
        }
        break
      
      case 429:
        error.message = 'Demasiadas solicitudes. Espera un momento antes de intentar nuevamente.'
        break
      
      case 500:
        error.message = data?.message || 'Error interno del servidor. Contacta al administrador si el problema persiste.'
        break
      
      case 502:
        error.message = 'El servidor no está disponible temporalmente. Intenta más tarde.'
        break
      
      case 503:
        error.message = 'Servicio no disponible. El servidor está en mantenimiento.'
        break
      
      default:
        error.message = data?.message || `Error del servidor (${status}). Contacta al administrador.`
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

  // Método para verificar conectividad
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
