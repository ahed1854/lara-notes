# Lara Notes - A Simple Elegant way to manage notes and reminders seemlesly

A modern note-taking and reminder application built with Laravel, featuring a clean and responsive design.

## Features

- User authentication (signup and login)
- Create, edit, and delete notes
- Pin important notes
- Colored labels system to manage notes and reminders
- Set reminders with dates and times
- Mark reminders as complete
- Simple and easy to use Trash system
- Responsive design for all screen sizes

## Requirements

- PHP >= 8.0
- Composer
- Node.js & NPM
- MySQL or any other Laravel-supported database

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd laravel-notes
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install and compile frontend dependencies:
```bash
npm install
npm run dev
```

4. Create a copy of the `.env` file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in the `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_notes
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run database migrations:
```bash
php artisan migrate
```

8. Start the development server:
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`.

## Usage

1. Register a new account or login with existing credentials
2. Create notes from the Notes page
3. Set reminders from the Reminders page
4. Use the color picker to categorize notes
5. Pin important notes to keep them at the top
6. Access deleted items in the trash
7. View upcoming reminders on the dashboard

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
