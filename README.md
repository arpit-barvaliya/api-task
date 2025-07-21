# Laravel Blog API

A RESTful API for a blogging platform built with Laravel. This project provides user authentication, blog CRUD operations, image uploads, blog likes, search, and sorting features.

## Features
- User registration and login (API authentication via Laravel Sanctum)
- Create, update, delete, and list blogs (with optional image upload)
- Like/unlike blogs
- Search and sort blogs (by most liked, latest)
- Pagination for blog listing

## API Endpoints

| Method | Endpoint           | Description                        | Auth Required |
|--------|--------------------|------------------------------------|--------------|
| POST   | /api/login         | User login                         | No           |
| POST   | /api/register      | User registration                  | No           |
| GET    | /api/blogs         | List blogs (search, sort, paginate)| Yes          |
| POST   | /api/blogs         | Create a new blog                  | Yes          |
| POST   | /api/blogs/{id}    | Update a blog (owner only)         | Yes          |
| DELETE | /api/blogs/{id}    | Delete a blog (owner only)         | Yes          |

> **Note:** All blog endpoints require authentication via Bearer token (Sanctum). Obtain a token from `/api/login` or `/api/register`.

## Setup Instructions

1. **Clone the repository:**
   ```bash
   git clone <repo-url>
   cd <project-directory>
   ```
2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```
3. **Copy and configure environment:**
   ```bash
   cp .env.example .env
   # Edit .env to set DB and other variables
   ```
4. **Generate application key:**
   ```bash
   php artisan key:generate
   ```
5. **Run migrations and seeders:**
   ```bash
   php artisan migrate --seed
   ```
6. **Start the development server:**
   ```bash
   php artisan serve
   ```
7. **(Optional) Build frontend assets:**
   ```bash
   npm run dev
   ```

## Environment Variables

Set the following variables in your `.env` file as needed:
- `APP_KEY`, `APP_URL`
- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `FILESYSTEM_DISK` (for image uploads)
- `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD` (for email)

## Testing

Run the test suite with:
```bash
php artisan test
# or
vendor/bin/pest
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
