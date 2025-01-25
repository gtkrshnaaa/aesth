<?php
// app/views/data/index.php

// Include Header
include __DIR__ . '/../layouts/header.php';
?>

<main class="container mx-auto p-6 flex-grow">
    <h2 class="text-2xl font-semibold mb-6">Data List</h2>

    <a href="/data/create" class="bg-[#238636] text-white px-4 py-2 rounded mb-4 inline-block">Add New Data</a>

    <table class="min-w-full table-auto bg-[#161b22] rounded-lg shadow-md">
        <thead>
            <tr class="border-b border-[#30363d]">
                <th class="px-4 py-2 text-left">ID</th>
                <th class="px-4 py-2 text-left">Name</th>
                <th class="px-4 py-2 text-left">Value</th>
                <th class="px-4 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $item): ?>
                <tr class="border-b border-[#30363d]">
                    <td class="px-4 py-2"><?= $item['id'] ?></td>
                    <td class="px-4 py-2"><?= $item['name'] ?></td>
                    <td class="px-4 py-2"><?= $item['value'] ?></td>
                    <td class="px-4 py-2">
                        <a href="/data/edit/<?= $item['id'] ?>" class="text-[#58a6ff] hover:text-[#c9d1d9]">Edit</a> |
                        <a href="/data/delete/<?= $item['id'] ?>" class="text-red-500 hover:text-red-700">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>


<?php
// Include Footer
include __DIR__ . '/../layouts/footer.php';
?>
