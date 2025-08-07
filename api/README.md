````markdown
# 🗂️ Tasks API – Backend (Laravel + Clean Architecture)

Este proyecto es una API desarrollada en **Laravel** siguiendo principios de **Clean Architecture**.  
Incluye casos de uso bien definidos, separación por capas y soporte para documentación automática con **Swagger**.

---

## 🚀 Requisitos

Antes de comenzar, asegúrate de tener instalado:

- [PHP 8.2+](https://www.php.net/downloads)
- [Composer 2.x](https://getcomposer.org/)
- [MySQL 8.x o MariaDB 10.x](https://dev.mysql.com/downloads/)
- [Node.js 20.x (opcional para Swagger UI y tareas front-end)](https://nodejs.org/)
- [XAMPP](https://www.apachefriends.org/index.html) o equivalente (para servidor local de MySQL)
- [Git](https://git-scm.com/)

---

## 📂 Estructura del proyecto

```plaintext
api/
├── Application/          # Casos de uso (UseCases)
│   ├── Keywords/
│   └── Tasks/
├── Domain/               # Entidades y contratos de repositorios
│   ├── Entities/
│   └── Repositories/
├── Http/                 # Controladores, Requests y Responses
│   ├── Controllers/
│   ├── Requests/
│   └── Responses/
├── Infrastructure/       # Implementaciones de repositorios (Eloquent)
├── Models/               # Modelos de Eloquent
├── Providers/            # Providers de Laravel
````

---

## ⚙️ Configuración

### 1. Clonar el repositorio

```bash
git clone <https://github.com/ernestgonzalezv/tasks.git>
cd api
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar archivo `.env`

Copia el archivo `.env.example` y renómbralo:

```bash
cp .env.example .env
```

Luego edítalo y ajusta la configuración de tu base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tasks
DB_USERNAME=root
DB_PASSWORD=
```

> ⚠️ Asegúrate de que tu servidor MySQL (XAMPP o similar) esté corriendo.

### 4. Generar clave de la aplicación

```bash
php artisan key:generate
```

### 5. Ejecutar migraciones

```bash
php artisan migrate
```

### 6. (Opcional) Generar documentación Swagger

```bash
php artisan l5-swagger:generate
```

La documentación estará disponible en:
[http://127.0.0.1:8000/api/documentation]

---

## ▶️ Ejecutar el proyecto

```bash
php artisan serve
```

Por defecto, el proyecto correrá en:
[http://127.0.0.1:8000]

---

## ✅ Tests

Para ejecutar los tests:

```bash
php artisan test
```

---

## 📌 Notas importantes

* La arquitectura está basada en **Clean Architecture**:

    * **Application**: lógica de negocio en casos de uso.
    * **Domain**: contratos y entidades puras.
    * **Infrastructure**: implementaciones concretas (repositorios Eloquent).
    * **Http**: controladores, requests y responses.
* La documentación de la API se gestiona con **L5-Swagger**.
* Si tienes errores de permisos en MySQL, revisa el servicio desde XAMPP y asegúrate de que las tablas del sistema no estén corruptas.

---

## 🛠️ Scripts útiles

* Limpiar cachés:

  ```bash
  php artisan cache:clear
  php artisan config:clear
  php artisan route:clear
  php artisan view:clear
  ```

* Refrescar base de datos:

  ```bash
  php artisan migrate:fresh --seed
  ```

---

## 📦 Pipeline CI/CD

Este backend incluye un workflow de GitHub Actions para:

* Ejecutar tests y coverage.
* Compilar y generar artefactos.
* Publicar releases automáticamente al hacer push en `main`.

---

## 📜 Licencia

Este proyecto está bajo la licencia MIT.

```
