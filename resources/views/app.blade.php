<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Default Title')</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('content')
    @stack('scripts')
</body>
</html>