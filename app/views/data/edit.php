<?php
// app/views/data/edit.php

// Include Header
include __DIR__ . '/../layouts/header.php';
?>

<h2 class="text-2xl font-semibold mb-6">Edit Data</h2>

<form action="/data/edit/<?= $data['id'] ?>" method="POST" class="bg-white p-6 rounded-lg shadow-md">
    <div class="mb-4">
        <label for="name" class="block text-lg font-medium text-gray-700">Name</label>
        <input type="text" id="name" name="name" value="<?= $data['name'] ?>" class="w-full p-2 border border-gray-300 rounded-lg" required>
    </div>
    <div class="mb-4">
        <label for="value" class="block text-lg font-medium text-gray-700">Value</label>
        <input type="text" id="value" name="value" value="<?= $data['value'] ?>" class="w-full p-2 border border-gray-300 rounded-lg" required>
    </div>
    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg">Update</button>
</form>

<a href="/data" class="mt-4 inline-block text-blue-500">Back to Data List</a>

<?php
// Include Footer
include __DIR__ . '/../layouts/footer.php';
?>
