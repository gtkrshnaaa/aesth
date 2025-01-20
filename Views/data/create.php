<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create</title>
</head>
<body>
    <!-- /Views/data/create.php -->
    <form method="POST" action="/data">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
        <button type="submit">Create</button>
    </form>
</body>
</html>