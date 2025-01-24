<!-- Views/data/edit.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
</head>
<body>
    <!-- /Views/data/edit.php -->
    <h1>Edit Data</h1>
    <form method="POST" action="/data/<?= $dataItem['id']; ?>/update">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($dataItem['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
        <button type="submit">Update</button>
    </form>
    <a href="/data">Back to List</a>
</body>
</html>