<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >
    <!-- Font Awesome for Icons -->
    <link 
    rel="stylesheet" 
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" 
    integrity="sha512-1ycn6Ica9999...X+mP4q" 
    crossorigin="anonymous" 
    referrerpolicy="no-referrer"
/>


    <style>
        body {
            background: #f8f9fa; /* Subtle grey background */
        }
        .navbar {
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .task-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
        }
        .task-header h1 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 600;
        }
        .badge-completed {
            background-color: #28a745!important; 
        }
        .badge-pending {
            background-color: #ffc107!important; 
            color: #212529!important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-check-square text-success me-2"></i>To-Do List
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" 
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto fw-semibold">
                    <li class="nav-item me-2">
                        <router-link to="/tasks" class="nav-link">
                            <i class="fas fa-tasks me-1"></i>Tasks
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/tasks/create" class="nav-link">
                            <i class="fas fa-plus-circle me-1"></i>Add Task
                        </router-link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container" id="app">
        <router-view></router-view>
    </div>

    <script 
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>
</body>
</html>
