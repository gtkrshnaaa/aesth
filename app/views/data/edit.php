<?php
// app/views/data/edit.php

// Include Header
include __DIR__ . '/../layouts/header.php';
?>

<main class="container mx-auto p-6 flex-grow">
    <h2 class="text-2xl font-semibold mb-6">Edit Data</h2>

    <form action="/data/edit/<?= $data['id'] ?>" method="POST" class="bg-[#161b22] p-6 rounded-lg shadow-md">
        <div class="mb-4">
            <label for="name" class="block text-lg font-medium text-[#c9d1d9]">Name</label>
            <input type="text" id="name" name="name" value="<?= $data['name'] ?>" class="w-full p-2 border border-[#30363d] rounded-lg bg-[#0d1117] text-[#c9d1d9]" required>
        </div>
        <div class="mb-4">
            <label for="value" class="block text-lg font-medium text-[#c9d1d9]">Value</label>
            <input type="text" id="value" name="value" value="<?= $data['value'] ?>" class="w-full p-2 border border-[#30363d] rounded-lg bg-[#0d1117] text-[#c9d1d9]" required>
        </div>
        <button type="submit" class="bg-[#238636] text-white px-6 py-2 rounded-lg">Update</button>
    </form>

    <a href="/data" class="mt-4 inline-block text-[#58a6ff] hover:text-[#c9d1d9]">Back to Data List</a>
</main>

<?php
// Include Footer
include __DIR__ . '/../layouts/footer.php';
?>
