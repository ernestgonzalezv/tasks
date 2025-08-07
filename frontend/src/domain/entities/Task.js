export class Task {
  constructor({ id, title, is_done = false, keywords = [], created_at, updated_at }) {
    this.id = id
    this.title = title
    this.is_done = is_done
    this.keywords = keywords
    this.created_at = created_at
    this.updated_at = updated_at
  }

  static fromApiResponse(data) {
    return new Task(data)
  }

  toggle() {
    return new Task({
      ...this,
      is_done: !this.is_done
    })
  }

  isCompleted() {
    return this.is_done
  }

  isPending() {
    return !this.is_done
  }

  hasKeywords() {
    return this.keywords && this.keywords.length > 0
  }

  getKeywordNames() {
    return this.keywords.map(keyword => keyword.name)
  }
}
