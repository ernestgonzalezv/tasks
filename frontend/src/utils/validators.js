// Validators for input data
export const validateTask = (taskData) => {
  const errors = []

  // Validate title
  if (!taskData || typeof taskData !== 'object') {
    errors.push('Invalid task data')
    return { isValid: false, errors }
  }

  if (!taskData.title || typeof taskData.title !== 'string') {
    errors.push('Title is required')
  } else if (taskData.title.trim().length === 0) {
    errors.push('Title cannot be empty')
  } else if (taskData.title.trim().length > 255) {
    errors.push('Title cannot exceed 255 characters')
  }

  // Validate keywords if present
  if (taskData.keyword_ids !== undefined) {
    if (!Array.isArray(taskData.keyword_ids)) {
      errors.push('Keywords must be an array')
    } else {
      const invalidKeywords = taskData.keyword_ids.filter(id =>
          !Number.isInteger(id) || id <= 0
      )
      if (invalidKeywords.length > 0) {
        errors.push('All keywords must have valid IDs')
      }
    }
  }

  // Validate is_done if present
  if (taskData.is_done !== undefined && typeof taskData.is_done !== 'boolean') {
    errors.push('Task status must be true or false')
  }

  return {
    isValid: errors.length === 0,
    errors
  }
}

export const validateKeyword = (keywordData) => {
  const errors = []

  if (!keywordData || typeof keywordData !== 'object') {
    errors.push('Invalid keyword data')
    return { isValid: false, errors }
  }

  if (!keywordData.name || typeof keywordData.name !== 'string') {
    errors.push('Name is required')
  } else if (keywordData.name.trim().length === 0) {
    errors.push('Name cannot be empty')
  } else if (keywordData.name.trim().length > 255) {
    errors.push('Name cannot exceed 255 characters')
  }

  return {
    isValid: errors.length === 0,
    errors
  }
}

// Validator for IDs
export const validateId = (id) => {
  return Number.isInteger(id) && id > 0
}

// String sanitizer
export const sanitizeString = (str, maxLength = 255) => {
  if (typeof str !== 'string') return ''
  return str.trim().substring(0, maxLength)
}

// Array validator
export const validateArray = (arr, itemValidator = null) => {
  if (!Array.isArray(arr)) return false
  if (itemValidator) {
    return arr.every(itemValidator)
  }
  return true
}
