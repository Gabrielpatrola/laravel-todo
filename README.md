# To-Do List Application

A modern Laravel application to manage tasks (to-do list) with a dynamic Vue.js frontend.

## Features

- Create, Read, Update, and Delete tasks
- Task status management using PHP 8.1 Enums
- Backend validations
- Dynamic frontend using Vue.js
- RESTful API integration
- Responsive design with Bootstrap

## Requirements

- PHP >= 8.1
- Composer
- Node.js and npm
- Database (MySQL, PostgreSQL, etc.)
- Git

## Installation

1. **Clone the Repository:**

   ```bash
   git clone https://github.com/yourusername/todo-app.git
   cd todo-app
   ```

2. **Install Dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Configure Database:**
   - Create a new database in your MySQL server.
   - Update the `.env` file with your database credentials.

4. **Run Migrations:**
   ```bash
   php artisan migrate
   ```

5. **Start the Development Server:**
   ```bash
   php artisan serve
   ```

6. **Access the Application:**
   - Open your browser and navigate to `http://localhost:8000`.

7. **Run the Vue.js Development Server:**
   ```bash
   npm run dev
   ```

8. **Access the Vue.js Application:**
   - Open your browser and navigate to `http://localhost:3000`.

9. **Run tests:**
   ```bash
   php artisan test
   ```
