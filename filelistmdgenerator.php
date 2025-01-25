<?php

// Function to write a file and its content to Markdown
function writeFileToMd(string $filePath, string $outputFile): void {
    // Read the file content
    $content = file_get_contents($filePath);
    if ($content === false) {
        return; // Skip if the file cannot be read
    }

    // Create a relative path
    $relativePath = str_replace(getcwd() . DIRECTORY_SEPARATOR, '', $filePath);

    // Use the file extension directly for the code block
    $ext = pathinfo($filePath, PATHINFO_EXTENSION);
    $mdContent = "## $relativePath\n```$ext\n$content\n```\n\n";

    // Write to the Markdown file (append mode)
    file_put_contents($outputFile, $mdContent, FILE_APPEND | LOCK_EX);
}

// Function to scan a directory and process each file
function scanDirectory(string $dirPath, string $outputFile, array $excludeFiles, array $excludeFolders): void {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dirPath));
    foreach ($files as $file) {
        // Skip folders that are in the exclusion list
        $folderPath = $file->getPath();
        foreach ($excludeFolders as $excludedFolder) {
            if (str_contains($folderPath, $excludedFolder)) {
                continue 2; // Skip to the next file
            }
        }

        // Skip files that are in the exclusion list
        $fileName = $file->getFilename();
        if (in_array($fileName, $excludeFiles) || in_array($file->getExtension(), $excludeFiles)) {
            continue; // Skip this file
        }

        if ($file->isFile()) {
            writeFileToMd($file->getRealPath(), $outputFile);
        }
    }
}

// Main program
$dirPath = './'; // Specify the directory path to scan
$outputFile = 'file_list.md'; // Specify the name of the output Markdown file

// List of files or extensions to exclude
$excludeFiles = ['filelistmdgenerator.php', 'file_list.md', 'README.md', 'exe']; // Can be file names or extensions
$excludeFolders = ['node_modules', '.git', 'vendor']; // List of folders to exclude

// Create a header for the Markdown file
file_put_contents($outputFile, "# Project File List\n\n");

// Call the function to scan the directory
scanDirectory($dirPath, $outputFile, $excludeFiles, $excludeFolders);

echo "Done! The file list has been saved to $outputFile\n";
