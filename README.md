## To-Do Laravel
<h1 align="center">
    <br/>
 <a href="https://www.php.net/downloads" target="_blank" rel="noopener">PHP</a> | <a href="https://www.slimframework.com/docs/v4/" target="_blank" rel="noopener">Laravel</a> | <a href="https://vuejs.org/guide/introduction.html" target="_blank" rel="noopener">Vue</a> 
</h1>

<h3 align="center">
  <a href="#-about">About</a>
  <span> · </span>
  <a href="#-stack-used">Stack used</a>
  <span> · </span>
  <a href="#-how-to-use">How to Use</a>
  <span> · </span>
</h3>

## 💭 About

### Objective
A modern Laravel application to manage tasks (to-do list) with a dynamic Vue.js frontend.

## 👨‍💻 Stack Used
- <a href="https://www.php.net/downloads" target="_blank" rel="noopener">PHP ^8.x</a>
- <a href="https://laravel.com/docs/11.x" target="_blank" rel="noopener">Laravel 11.x</a>
- <a href="https://vuejs.org/guide/introduction.html" target="_blank" rel="noopener">Vue 3</a>
- <a href="https://docs.docker.com/" target="_blank" rel="noopener">Docker</a>

## ⁉ How to use

### 🤔 Requirements

To be able to run this project, first you will need to have in your machine:

- **<a href="https://getcomposer.org" target="_blank" rel="noopener">Composer</a>** to be able to manage the project's dependencies and autoload
- **<a href="https://www.php.net/downloads" target="_blank" rel="noopener">PHP</a>** on version ^8.x
- **<a href="https://git-scm.com/downloads" target="_blank" rel="noopener">Git</a>** to be able to clone this repository
- **<a href="https://docs.docker.com/" target="_blank" rel="noopener">Docker</a>** to be able to run the application
- **<a href="https://www.npmjs.com/" target="_blank" rel="noopener">NPM</a>** or **<a href="https://yarnpkg.com/" target="_blank" rel="noopener">Yarn</a>** for the frontend package management.

### 📝 Steps

## Installation

1. **Clone the Repository:**

   ```bash
   git clone git@github.com:Gabrielpatrola/laravel-todo.git
   cd laravel-todo
   ```

2. **Install Dependencies:**
   ```bash
   composer install
   npm install # or yarn install 
   ```

3. **Configure Environment:**
   - Copy the `.env.example` file to `.env`
   ```bash
   cp .env.example .env 
   ```
   - Update the `.env` file if necessary.

4. **Start Laravel sail:**
   ```bash
   sail up -d # or ./vendor/bin/sail up -d
   ```
   
5. **Run migrations:**
   ```bash
   sail artisan migrate # or ./vendor/bin/sail artisan migrate
   ```
   
7. **Run the Vue.js Development Server:**
   ```bash
    sail npm run dev # or ./vendor/bin/sail npm run dev
   ```

8. **Access the Vue.js Application:**
   - Open your browser and navigate to `http://localhost`.

9. **Run tests:**
   ```bash
   sail artisan test # or ./vendor/bin/sail artisan test
   # for coverage:
   php artisan test --coverage # or ./vendor/bin/sail artisan test --coverage
   ```

---

<sup>Made with 💙 by <a href="https://github.com/gabrielpatrola" target="_blank" rel="noopener">Gabriel Patrola</a>.</sup>
