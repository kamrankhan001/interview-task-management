<!DOCTYPE html>
<html class="h-full">
<head>
    <title>Task Manager</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
<body class="h-full bg-gray-100 dark:bg-gray-900">
    <div class="container mx-auto px-4 py-8">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    @stack('scripts')
</body>
</html>