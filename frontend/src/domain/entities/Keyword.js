export class Keyword {
  constructor({ id, name, created_at, updated_at }) {
    this.id = id
    this.name = name
    this.created_at = created_at
    this.updated_at = updated_at
  }

  static fromApiResponse(data) {
    return new Keyword(data)
  }

  toString() {
    return this.name
  }
}
