# Task Management API System

A RESTful API-based web application built with **Laravel 12** and **PHP 8.2+** that allows users to register, login, and manage personal tasks. Authentication is handled via **Laravel Passport** (OAuth2 token-based).

---

## Tech Stack

- **PHP**: 8.2 or above
- **Framework**: Laravel 12
- **Database**: MySQL
- **Authentication**: Laravel Passport (OAuth2)
- **ORM**: Eloquent
- **Architecture**: MVC

---

## Prerequisites

- PHP >= 8.2
- Composer
- MySQL 

---

## Project Setup

### 1. Clone the Repository

```bash
git clone http://github.com/deepakdhiman123/laravel_assignment_task
cd laravel_assignment_task

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Configure Environment

Copy the `.env.example` file and update values:


Edit `.env` with your settings:

```env
APP_NAME="Task Management API"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_LEVEL=debug

# ─── Database ──────────────────────────────────────────────
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=your_password


# ─── Passport ──────────────────────────────────────────────
PASSPORT_PERSONAL_ACCESS_CLIENT_ID=
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Create the Database

Make sure the database defined in `DB_DATABASE` exists:

```sql
CREATE DATABASE task_management;
```

### 6. Run Migrations

```bash
php artisan migrate
```

### 7. Install Laravel Passport

```bash
php artisan passport:install
```

This will output a **Client ID** and **Client Secret**. Copy the **Personal Access** client credentials back into your `.env`:

```env
PASSPORT_PERSONAL_ACCESS_CLIENT_ID=1
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=your_secret_here
```

### 9. Start the Development Server

```bash
php artisan serve
```

The API will be available at: `http://localhost:8000`

---

## Authentication Flow

This application uses **Laravel Passport** with Personal Access Tokens.

```
1. Register   → POST /api/register  → returns user info
2. Login      → POST /api/login     → returns { access_token, token_type, expires_at }
3. Use Token  → Add Header: Authorization: Bearer {access_token}
4. Logout     → POST /api/logout    → revokes the token
```

All task routes are protected and require a valid Bearer token.

---

## API Endpoints

### Auth Routes (Public)

| Method | Endpoint         | Description         |
|--------|-----------------|---------------------|
| POST   | /api/v1/register   | Register a new user |
| POST   | /api/v1/login      | Login and get token |

### Auth Routes (Protected)



### Task Routes (Protected — requires Bearer Token)
| Method | Endpoint         | Description            |
|--------|-----------------|------------------------|
| POST   | /api/v1/logout     | Logout (revoke token)  |

| Method | Endpoint              | Description                          |
|--------|-----------------------|--------------------------------------|
| GET    | /api/v1/tasks            | List all tasks (with filters & pagination) |
| POST   | /api/v1/tasks            | Create a new task                    |
| GET    | /api/v1/tasks/{id}       | Get a single task                    |
| PUT    | /api/v1/tasks/{id}       | Update a task                        |
| DELETE | /api.v1/tasks/{id}       | Soft delete a task                   |

#### Task Filtering Query Parameters (GET /api/tasks)

| Parameter  | Type   | Description                                      |
|------------|--------|--------------------------------------------------|
| status     | string | Filter by status: `pending`, `in-progress`, `completed` |
| per_page   | int    | Number of results per page (default: 10)         |

**Example:** `GET /api/tasks?status=pending&per_page=5&page=1`

---

## Request & Response Examples

### Register

**Request:**
```json
POST /api/register
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  }
}
```

### Login

**Request:**
```json
POST /api/login
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "message": "Login successful",
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
  "token_type": "Bearer",
  "expires_at": "2025-12-31 23:59:59"
}
```

### Create Task

**Request:**
```json
POST /api/tasks
Authorization: Bearer {token}

{
  "title": "Complete project documentation",
  "description": "Write all necessary docs for the API",
  "status": "pending",
  "due_date": "2025-03-15"
}
```

**Response (201):**
```json
{
  "message": "Task created successfully",
  "task": {
    "id": 1,
    "title": "Complete project documentation",
    "description": "Write all necessary docs for the API",
    "status": "pending",
    "due_date": "2025-03-15",
    "created_at": "2025-02-20T10:00:00.000000Z",
    "updated_at": "2025-02-20T10:00:00.000000Z"
  }
}
```

---

## Database Design

### users table

| Column       | Type         | Description               |
|--------------|--------------|---------------------------|
| id           | bigint (PK)  | Auto-increment primary key |
| name         | varchar(255) | User's full name           |
| email        | varchar(255) | Unique email address       |
| password     | varchar(255) | Hashed password            |
| created_at   | timestamp    |                            |
| updated_at   | timestamp    |                            |

### tasks table

| Column       | Type         | Description                              |
|--------------|--------------|------------------------------------------|
| id           | bigint (PK)  | Auto-increment primary key               |
| user_id      | bigint (FK)  | References users.id                      |
| title        | varchar(255) | Task title                               |
| description  | text         | Task description (nullable)              |
| status       | enum         | `pending`, `in-progress`, `completed`    |
| due_date     | date         | Task due date (nullable)                 |
| deleted_at   | timestamp    | Soft delete timestamp (nullable)         |
| created_at   | timestamp    |                                          |
| updated_at   | timestamp    |                                          |


---

## HTTP Status Codes

| Code | Meaning                |
|------|------------------------|
| 200  | OK                     |
| 201  | Created                |
| 204  | No Content             |
| 400  | Bad Request            |
| 401  | Unauthorized           |
| 403  | Forbidden              |
| 404  | Not Found              |
| 422  | Unprocessable Entity (Validation Error) |
| 500  | Internal Server Error  |

---

## Running Tests

```bash
php artisan test
```

To run a specific test file:

```bash
php artisan test --filter=AuthTest
php artisan test --filter=TaskTest
```

---

## Logging

API errors are logged via Laravel's built-in logging system. Logs are stored in:

```
storage/logs/laravel.log
```

The log channel is configured in `.env` via `LOG_CHANNEL`. Default is `stack`.

---

## Postman Collection

A Postman collection (`Laravel_assignment_apis.postman_collection.json`) is included in the repository root. Import it into Postman to quickly test all endpoints.

**Steps:**
1. Open Postman
2. Click **Import**
3. Select `Task_Management_API.postman_collection.json`
4. Set the environment variable `base_url` to `http://localhost:8000`
5. Run **Register** then **Login** — the token is auto-captured for subsequent requests

