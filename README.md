# Schoop / HafizPlus School Platform

This is a multi-tenant school management platform built with **Laravel**.

## Tech Stack

- **Backend:** Laravel 12, PHP 8.2+
- **Frontend:** Vite, Tailwind CSS, Alpine.js, Preline
- **Database:** MySQL / MariaDB

## Architecture Notes

- **Multi-Tenant Architecture:** The Laravel backend uses a multi-tenant architecture where many models are automatically scoped by `school_id`. This is achieved using a `BelongsToTenant` trait and a global scope.
- **Language:** The UI text is primarily in Indonesian.
- **Package Manager:** Strictly use **pnpm** for managing frontend dependencies. Do not use npm or yarn.

## Requirements

- PHP >= 8.2
- Composer
- Node.js (LTS Version)
- **pnpm** (Frontend Package Manager)
- MySQL / MariaDB

## Setup & Installation

Follow these steps to set up the project locally:

### 1. Clone the repository
```bash
git clone <repository-url>
cd <repository-directory>
```

### 2. Environment Configuration
Copy the `.env.example` file to create your local environment configuration:
```bash
cp .env.example .env
```
Update your database configuration in the `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hafizplus_school_platform
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Install Backend Dependencies
Use Composer to install the required PHP packages:
```bash
composer install
```

### 4. Install Frontend Dependencies
Use **pnpm** to install Tailwind CSS, Alpine.js, and Vite:
```bash
pnpm install
```

### 5. Generate Application Key
Generate the Laravel encryption key:
```bash
php artisan key:generate
```

### 6. Database Migration and Seeding
Ensure your local database server is running and the database specified in your `.env` (e.g., `hafizplus_school_platform`) is created. Then run:
```bash
php artisan migrate --seed
```

### 7. Storage Link
Create a symbolic link for the storage directory so uploaded files are publicly accessible:
```bash
php artisan storage:link
```

### 8. Compile Frontend Assets
- **For Development (with Hot Module Replacement):**
  ```bash
  pnpm dev
  ```
- **For Production (Build):**
  ```bash
  pnpm build
  ```

## Running the Development Server

To access the application locally:
```bash
php artisan serve
```
Then visit `http://127.0.0.1:8000` in your browser.

## Testing

To run the backend tests (PHPUnit), execute:
```bash
php artisan test
```
