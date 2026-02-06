<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') | Society Management System</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .page-card {
            background: #fff;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .page-title {
            font-weight: 600;
            margin-bottom: 20px;
        }
        footer {
            margin-top: 60px;
            padding: 15px 0;
            text-align: center;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Society Management</a>
    </div>
</nav>

<div class="container my-5">
    @yield('content')
</div>

<footer>
    © {{ date('Y') }} Society Management System. All rights reserved.
</footer>

</body>
</html>
