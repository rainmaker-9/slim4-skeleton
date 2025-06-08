# Slim 4 Skeleton

A minimal [Slim Framework 4](https://www.slimframework.com/) starter application.

## Features

- PSR-7 HTTP message support
- PSR-15 middleware support
- Dependency Injection via PHP-DI
- Ready for RESTful APIs or web apps

## Requirements

- PHP 7.4 or higher
- [Composer](https://getcomposer.org/)

## Getting Started

### 1. Clone the repository

```bash
git clone https://github.com/rainmaker-9/slim4-skeleton.git
cd slim4-skeleton
```

### 2. Install dependencies

```bash
composer install
```

### 3. Run the application

Start the built-in PHP server:

```bash
php -S localhost:8080
```

Visit [http://localhost:8080](http://localhost:8080) in your browser.

## Project Structure

```
.
├── app/            # Application source code
├── bootstrap/      # Required Files for Containerization & bootstrapping the application
├── routes/         # For defining application routes
├── vendor/         # Composer dependencies
├── .env.example    # For defining Environment Variables used by application
├── composer.json
└── README.md
```

## License

MIT
