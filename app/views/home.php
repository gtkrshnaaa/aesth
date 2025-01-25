<!-- app/views/home.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0d1117] text-[#c9d1d9] h-screen flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-4xl font-bold text-[#c9d1d9] mb-4"><?= $title ?></h1>
        <p class="text-lg text-[#8b949e] mb-6">
            This is the home page. Click the button below to explore the CRUD demo.
        </p>
        <a href="/data" class="bg-[#238636] text-white px-4 py-2 rounded mb-4 inline-block">
            See CRUD Demo
        </a>
    </div>
</body>
</html>
