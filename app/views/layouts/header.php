<!-- app/views/layouts/header.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'My Mini Framework' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

<header class="bg-blue-600 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold">My Mini Framework</h1>
        <nav>
            <ul class="flex space-x-6">
                <li><a href="/" class="hover:text-gray-300">Home</a></li>
                <li><a href="/data" class="hover:text-gray-300">Data</a></li>
            </ul>
        </nav>
    </div>
</header>

<main class="container mx-auto p-6">
