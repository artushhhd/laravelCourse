# laravelCourse

REST API for a course platform built with Laravel + Sanctum. Handles auth, course CRUD, likes/comments and role-based admin moderation. Frontend counterpart: [NextCourse](https://github.com/artushhhd/NextCourse).

## Stack

- PHP 8.3, Laravel 13
- Sanctum (token auth)
- Eloquent + migrations

## What's implemented

**Auth**
- Register / login / logout with Sanctum tokens
- Accounts can be blocked by an admin (`is_active`), blocked users can't log in

**Courses**
- CRUD with image upload, route model binding
- Likes (toggle, many-to-many)
- Comments

**Roles**
Four roles: `user`, `moderator`, `admin`, `superadmin`, enforced through an `AdminCheck` middleware on the `/admin/*` group. Permission rules baked into `AdminController`:
- moderators can't touch courses/users belonging to admins or superadmin
- admins can't block/delete other admins
- only superadmin can act on a superadmin account

## Structure

```
app/
├── Http/
│   ├── Controllers/   # UserController, CourseController, AdminController
│   ├── Middleware/    # AdminCheck
│   └── Requests/      # form request validation
├── Models/             # User, Course, CourseComment
└── UserRole.php
routes/api.php
database/migrations/
```

## Endpoints

```
POST   /api/register
POST   /api/login
POST   /api/logout                (auth)
GET    /api/profile               (auth)

GET    /api/courses
GET    /api/courses/{id}
POST   /api/courses               (auth)
PUT    /api/courses/{id}          (auth, owner)
DELETE /api/courses/{id}          (auth, owner)
POST   /api/courses/{id}/like     (auth)
POST   /api/courses/{id}/comment  (auth)

GET    /api/admin/courses         (admin/moderator)
PUT    /api/admin/courses/{id}
DELETE /api/admin/courses/{id}
GET    /api/admin/users
POST   /api/admin/users/{id}/toggle-block
DELETE /api/admin/users/{id}
```

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Runs on `http://127.0.0.1:8000`.

## Known gaps

- `update`/`delete` on courses call `Gate::authorize()` but there's no policy registered yet — needs a `CoursePolicy`
- no pagination on `GET /api/courses`
- test suite is still the default Laravel skeleton, no coverage on auth/roles yet
