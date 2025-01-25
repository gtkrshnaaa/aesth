<!-- app/views/layouts/header.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'My Mini Framework' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0d1117] text-[#c9d1d9] flex flex-col min-h-screen">

<header class="bg-[#161b22] text-[#c9d1d9] p-4">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold">My Mini Framework</h1>
        <nav>
            <ul class="flex space-x-6">
                <li><a href="/" class="hover:text-[#58a6ff]">Home</a></li>
                <li><a href="/data" class="hover:text-[#58a6ff]">Data</a></li>
            </ul>
        </nav>
    </div>
</header>
