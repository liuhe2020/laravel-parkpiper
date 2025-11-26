# ParkPiper

ParkPiper is a web application for checking and issuing parking permits. It provides a user-friendly interface and APIs to verify permit coverage for vehicles, create new permits, and view existing permits. The app supports both real-time and duration-based coverage checks.

**Live demo:** [https://parkpiper.vercel.app](https://parkpiper.vercel.app)

> ⚠️ **Caveats for the live demo:**
>
> -   HTTPS is not available for pages with forms, so the "Issue Permit" form does not work.
> -   API routes are server cached until a purge occurs, which may delay updates.
> -   These limitations are due to deployment in a Vercel serverless environment.
> -   Otherwise, all other features work as expected.

## Stack Overview

-   **Laravel**: PHP web framework (v12)
-   **PostgreSQL**: Primary database (can also use SQLite/MySQL for local/dev)
-   **Composer**: PHP dependency manager
-   **Tailwind CSS**: Utility-first CSS framework
-   **Vite**: Frontend build tool
-   **Pest**: Testing framework for PHP
-   **Axios**: HTTP client for JavaScript
-   **Vercel**: Serverless deployment (vercel-php runtime)
-   **Docker**: (optional) for local development

## Key Features

-   Parking permit CRUD API (`/api/permits`)
-   Permit coverage check API (`/api/permits/check`)
-   Web UI for checking, issuing, and viewing permits
-   API documentation at `/documentation`
-   Responsive design with Tailwind CSS
-   Dark mode support

## Getting Started

### Requirements

-   PHP >= 8.2
-   Composer
-   Node.js & npm
-   PostgreSQL (or SQLite/MySQL)
-   Vercel CLI (for deployment)

### Setup

```sh
# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Copy environment file and set credentials
cp [.env.example](http://_vscodecontentref_/0) .env

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed

# Build frontend assets
npm run build

# Start local server with Composer
composer run dev

# Start local server
php artisan serve
```
