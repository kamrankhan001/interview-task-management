# Task Manager

A Laravel-based task management application with project organization and priority sorting.

## Features

- Create and manage tasks with priorities
- Organize tasks by projects
- Drag-and-drop task prioritization
- Filter tasks by project
- Responsive UI with clean design

## Technologies

- Laravel 12
- MySQL
- jQuery (for interactive elements)
- Tailwind CSS

## Installation

### Prerequisites

- PHP 8.3.20+
- Composer
- MySQL
- Node.js (for development)

### Setup Steps

1. **Clone the repository**:
   ```bash
   git clone https://github.com/kamrankhan001/interview-task-management.git
   cd interview-task-management
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Configure environment**:

   - Copy `.env.example` to `.env`:
     ```bash
     cp .env.example .env
     ```

   - Update database credentials in `.env`:
     ```ini
     DB_DATABASE=task_manager
     DB_USERNAME=your_db_user
     DB_PASSWORD=your_db_password
     ```

4. **Generate application key**:
   ```bash
   php artisan key:generate
   ```

5. **Run migrations and seed data**:
   ```bash
   php artisan migrate --seed
   ```

6. **Build assets (for development)**:
   ```bash
   npm run dev
   ```

## Running the Application

Start the development server:

```bash
php artisan serve
```

Then open [http://localhost:8000](http://localhost:8000) in your browser.

## Database Seeding

The database seeder creates:

- 10 projects
- 15–20 tasks for each project
- Random priorities assigned to tasks

To reseed the database:

```bash
php artisan migrate:fresh --seed
```

## GitHub Repository

Find the full source code at:  
[https://github.com/kamrankhan001/interview-task-management](https://github.com/kamrankhan001/interview-task-management)

## License

This project is open-source under the MIT License.
