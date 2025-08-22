# 📚 Laravel Blog API

A modern, modular Laravel 12 blog application built with clean architecture principles and comprehensive API endpoints.

## 🚀 Features

### Core Functionality
- **User Authentication & Authorization** with Laravel Sanctum
- **Role-Based Access Control (RBAC)** - User & Super Admin roles
- **Blog Management** - Posts and Categories
- **Image Upload** support for posts
- **Slug Generation** automatic for SEO-friendly URLs
- **Form Data & JSON** API support

### Technical Features
- **Modular Architecture** using `nwidart/laravel-modules`
- **Clean Architecture** with Service Layer pattern
- **SOLID Principles** implementation
- **Comprehensive Testing** with PHPUnit
- **API Resources/Transformers** for clean JSON responses
- **Form Request Validation** with custom error messages
- **Database Migrations** with proper relationships

## 🛠️ Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- MySQL

### 1. Clone Repository
```bash
git clone <repository-url>
cd blog
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
```bash
# Configure database in .env
php artisan migrate
php artisan db:seed
```

### 5. Storage Setup
```bash
php artisan storage:link
```

### 6. Start Development Server
```bash
php artisan serve
```

## 🗄️ Database Seeders

### Super Admin Seeder
Creates a super admin user with full access:
```bash
php artisan db:seed --class=SuperAdminSeeder
```

**Default Super Admin:**
- **Email:** `admin@example.com`
- **Password:** `password123`
- **Role:** `super_admin`

### Category Seeder
Populates initial categories:
```bash
php artisan db:seed --class=CategorySeeder
```

**Default Categories:**
- Technology
- Programming
- Design
- Business
- Marketing
- Education
- Health
- Travel
- Food
- Sports

## 🔐 Authentication

### Sanctum Configuration
The application uses Laravel Sanctum for API authentication with proper middleware configuration.

### Token Generation
**Postman Collection:**

#### Login Request:
- **Method:** `POST`
- **URL:** `http://127.0.0.1:8000/api/auth/login`
- **Headers:** `Content-Type: application/json`
- **Body (raw JSON):**
```json
{
  "email": "admin@example.com",
  "password": "password123"
}
```

#### Response:
```json
{
  "status": "success",
  "result": {
    "user": {
      "id": 1,
      "name": "Super Admin",
      "email": "admin@example.com",
      "role": "super_admin"
    },
    "token": "1|abc123..."
  },
  "message": "ورود موفقیت‌آمیز"
}
```

### Sample Token Usage
**Authorization Header:**
- **Key:** `Authorization`
- **Value:** `Bearer {YOUR_TOKEN}`

**Example Request:**
- **Method:** `GET`
- **URL:** `http://127.0.0.1:8000/api/v1/categories`
- **Headers:** 
  - `Authorization: Bearer {YOUR_TOKEN}`
  - `Accept: application/json`

## 📡 API Endpoints

### Authentication Routes
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| `POST` | `/api/auth/register` | User registration | ❌ |
| `POST` | `/api/auth/login` | User login | ❌ |
| `POST` | `/api/auth/logout` | User logout | ✅ |

### Category Routes
| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| `GET` | `/api/v1/categories` | List all categories | ❌ | - |
| `GET` | `/api/v1/categories/{id}` | Show category | ❌ | - |
| `POST` | `/api/v1/categories` | Create category | ✅ | `super_admin` |
| `PUT` | `/api/v1/categories/{id}` | Update category | ✅ | `super_admin` |
| `DELETE` | `/api/v1/categories/{id}` | Delete category | ✅ | `super_admin` |

### Post Routes
| Method | Endpoint | Description | Auth Required | Role Required |
|--------|----------|-------------|---------------|---------------|
| `GET` | `/api/v1/posts` | List published posts | ❌ | - |
| `GET` | `/api/v1/posts/{id}` | Show post | ❌ | - |
| `POST` | `/api/v1/posts` | Create post | ✅ | `user` |
| `POST` | `/api/v1/posts/{id}/update` | Update post | ✅ | `owner` or `super_admin` |
| `DELETE` | `/api/v1/posts/{id}` | Delete post | ✅ | `owner` or `super_admin` |

## 🔍 API Features

### Post Filtering

**Filter by Category:**
- **Method:** `GET`
- **URL:** `http://127.0.0.1:8000/api/v1/posts?category=Technology`

**Search in Content:**
- **Method:** `GET`
- **URL:** `http://127.0.0.1:8000/api/v1/posts?search=laravel`

**Combine Filters:**
- **Method:** `GET`
- **URL:** `http://127.0.0.1:8000/api/v1/posts?category=Technology&search=laravel`

**Postman:** Add query parameters in the "Params" tab

### Image Upload
Posts support image uploads via Form Data:

**Postman Setup:**
- **Method:** `POST`
- **URL:** `http://127.0.0.1:8000/api/auth/login`
- **Headers:** `Authorization: Bearer {YOUR_TOKEN}`
- **Body:** `form-data`
- **Fields:**
  - `title`: `My Post`
  - `content`: `Post content`
  - `category_ids[]`: `1`
  - `img`: `File` (select image file)

**Note:** In Postman, use `form-data` body type and set `img` field type to `File`

### Status Management
- **Draft:** Default status for new posts
- **Published:** Only super admins can change status
- **Auto-published:** `published_at` automatically set when status changes

## 🧪 Testing

### Run Tests
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter it_can_list_categories

# Run with coverage
php artisan test --coverage
```

### Postman Testing
**Recommended Testing Flow:**
1. **Register/Login** → Get token
2. **Test Public Routes** (categories, posts)
3. **Test Protected Routes** with token
4. **Test Super Admin Routes** with admin token
5. **Test Image Upload** with form-data
6. **Test Filtering** with query parameters

### Test Structure
- **Feature Tests:** API endpoint testing
- **Unit Tests:** Service layer testing
- **Factory Classes:** Test data generation
- **Database Refresh:** Clean state for each test

## 🏗️ Architecture

### Module Structure
```
Modules/
├── Auth/
│   ├── Controllers/
│   ├── Requests/
│   ├── Services/
│   └── routes/
└── Blog/
    ├── Controllers/
    ├── Models/
    ├── Services/
    ├── Transformers/
    ├── Requests/
    └── routes/
```

### Service Layer Pattern
Controllers delegate business logic to dedicated service classes:
```php
public function store(CreatePostRequest $request)
{
    $result = app(CreateService::class)->create($request);
    return response()->json($result, 201);
}
```

### API Resources
Structured JSON responses using Laravel API Resources:
```php
'img' => $this->img ? url('storage/' . $this->img) : null,
'status' => [
    'value' => $this->status->value,
    'label' => $this->status->label(),
    'color' => $this->status->color(),
],
```

## 🔧 Configuration

### Middleware
- **`web`:** Session-based authentication
- **`api`:** Stateless API authentication
- **`auth:sanctum`:** Token-based authentication
- **`super.admin`:** Super admin role verification

### Custom Authentication
Custom middleware handles API authentication without redirects:
```php
protected function redirectTo(Request $request): ?string
{
    if ($request->expectsJson() || $request->is('api/*')) {
        return null; // No redirect for API
    }
    return route('login');
}
```

## 📊 Database Schema

### Users Table
- `id`, `name`, `email`, `password`, `role`, `email_verified_at`, `timestamps`

### Categories Table
- `id`, `name`, `slug`, `timestamps`

### Posts Table
- `id`, `title`, `slug`, `content`, `img`, `status`, `published_at`, `user_id`, `timestamps`

### Category Post Table (Pivot)
- `category_id`, `post_id`, `timestamps`
