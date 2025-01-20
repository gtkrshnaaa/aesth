<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index</title>
</head>
<body>
    <!-- /Views/data/index.php -->
    <h1>Data List</h1>
    <ul>
        <?php foreach ($dataItems as $dataItem): ?>
            <li>
                <?= htmlspecialchars($dataItem['name'], ENT_QUOTES, 'UTF-8'); ?>
                <a href="/data/<?= $dataItem['id']; ?>/edit">Edit</a>
                <a href="/data/<?= $dataItem['id']; ?>/delete">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="/data/create">Create New</a>

</body>
</html>