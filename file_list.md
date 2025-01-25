# Project File List

## tree.txt
```txt
.
├── app
│   ├── controllers
│   │   └── HomeController.php
│   └── views
│       └── home.php
├── core
│   ├── Render.php
│   └── Router.php
├── file_list.md
├── mdgenerator.php
├── public
│   └── index.php
├── README.md
├── routes
│   └── web.php
└── tree.txt

```

## public/index.php
```php
<?php

// public/index.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../routes/web.php';

$router->run();


```

## core/Render.php
```php
<?php

// core/Render.php

class Render {
    public static function view($viewName, $data = []) {
        extract($data);
        require_once __DIR__ . '/../app/views/' . $viewName . '.php';

    }
}

```

## core/Router.php
```php
<?php

// core/Router.php

class Router {
    private $routes = [];

    public function get($url, $callback) {
        $this->routes['GET'][$url] = $callback;
    }

    public function post($url, $callback) {
        $this->routes['POST'][$url] = $callback;
    }

    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];
        $url = rtrim($url, '/'); // Remove trailing slash for consistency
    
        foreach ($this->routes[$method] as $route => $callback) {
            $routePattern = preg_replace('/{[a-zA-Z0-9_]+}/', '([a-zA-Z0-9_]+)', $route);
            if (preg_match('#^' . $routePattern . '$#', $url, $matches)) {
                array_shift($matches); // Remove the full match
                call_user_func_array($callback, $matches);
                return;
            }
        }
        
        echo '404 Not Found';
    }
    
}

```

## .htaccess
```htaccess
RewriteEngine On
RewriteBase /

# Redirect all requests to index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

```

## filelistmdgenerator.php
```php
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
$excludeFiles = ['mdgenerator.php', 'file_list.md', 'README.md', 'exe']; // Can be file names or extensions
$excludeFolders = ['node_modules', '.git', 'vendor']; // List of folders to exclude

// Create a header for the Markdown file
file_put_contents($outputFile, "# Project File List\n\n");

// Call the function to scan the directory
scanDirectory($dirPath, $outputFile, $excludeFiles, $excludeFolders);

echo "Done! The file list has been saved to $outputFile\n";

```

## db/connection.php
```php
<?php
// db/connection.php

class Database {
    private $host = 'localhost'; // Ganti dengan host database kamu
    private $db_name = 'aesth'; // Ganti dengan nama database kamu
    private $username = 'root'; // Ganti dengan username database kamu
    private $password = ''; // Ganti dengan password database kamu
    private $conn;

    // Koneksi ke database
    public function connect() {
        $this->conn = null;

        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}

```

## db/migrations/migrateup.php
```php
<?php
// db/migrations/migrateup.php

require_once __DIR__ . '/../connection.php';

class MigrateUp {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function up() {
        $sql = "
        CREATE TABLE IF NOT EXISTS data (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            value VARCHAR(255) NOT NULL
        );
        ";

        try {
            $this->conn->exec($sql);
            echo "Migration up successful: 'data' table created.\n";
        } catch (PDOException $e) {
            echo "Error during migration: " . $e->getMessage() . "\n";
        }
    }
}

// Create a new instance of the Database connection
$db = new Database();
$conn = $db->connect();

// Run the migration
$migration = new MigrateUp($conn);
$migration->up();

```

## db/migrations/migratedown.php
```php
<?php
// db/migrations/migratedown.php

require_once __DIR__ . '/../connection.php';


class MigrateDown {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function down() {
        $sql = "DROP TABLE IF EXISTS data;";

        try {
            $this->conn->exec($sql);
            echo "Migration down successful: 'data' table dropped.\n";
        } catch (PDOException $e) {
            echo "Error during migration: " . $e->getMessage() . "\n";
        }
    }
}

// Create a new instance of the Database connection
$db = new Database();
$conn = $db->connect();

// Run the migration
$migration = new MigrateDown($conn);
$migration->down();

```

## routes/web.php
```php
<?php

// routes/web.php

require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../app/controllers/HomeController.php';
require_once __DIR__ . '/../app/controllers/DataController.php';

$router = new Router();

$router->get('/', function() { (new HomeController())->index(); });
$router->get('/data', function() { (new DataController())->index(); });
$router->get('/data/create', function() { (new DataController())->create(); });
$router->post('/data/create', function() { (new DataController())->store(); });
$router->get('/data/edit/{id}', function($id) { (new DataController())->edit($id); });
$router->post('/data/edit/{id}', function($id) { (new DataController())->update($id); });
$router->get('/data/delete/{id}', function($id) { (new DataController())->delete($id); });

```

## app/views/layouts/footer.php
```php
<!-- app/views/layouts/footer.php -->

<footer class="bg-gray-800 text-white p-4 text-center">
    <p>&copy; 2025 My Mini Framework. All Rights Reserved.</p>
</footer>

</body>
</html>

```

## app/views/layouts/header.php
```php
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

```

## app/views/home.php
```php
<!-- app/views/home.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
</head>
<body>
    <h1><?= $title ?></h1>
</body>
</html>

```

## app/views/data/index.php
```php
<?php
// app/views/data/index.php

// Include Header
include __DIR__ . '/../layouts/header.php';
?>

<h2 class="text-2xl font-semibold mb-6">Data List</h2>

<a href="/data/create" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Add New Data</a>

<table class="min-w-full table-auto bg-white rounded-lg shadow-md">
    <thead>
        <tr class="border-b">
            <th class="px-4 py-2 text-left">ID</th>
            <th class="px-4 py-2 text-left">Name</th>
            <th class="px-4 py-2 text-left">Value</th>
            <th class="px-4 py-2 text-left">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $item): ?>
            <tr class="border-b">
                <td class="px-4 py-2"><?= $item['id'] ?></td>
                <td class="px-4 py-2"><?= $item['name'] ?></td>
                <td class="px-4 py-2"><?= $item['value'] ?></td>
                <td class="px-4 py-2">
                    <a href="/data/edit/<?= $item['id'] ?>" class="text-yellow-500 hover:text-yellow-700">Edit</a> |
                    <a href="/data/delete/<?= $item['id'] ?>" class="text-red-500 hover:text-red-700">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
// Include Footer
include __DIR__ . '/../layouts/footer.php';
?>

```

## app/views/data/create.php
```php
<?php
// app/views/data/create.php

// Include Header
include __DIR__ . '/../layouts/header.php';
?>

<h2 class="text-2xl font-semibold mb-6">Create New Data</h2>

<form action="/data/create" method="POST" class="bg-white p-6 rounded-lg shadow-md">
    <div class="mb-4">
        <label for="name" class="block text-lg font-medium text-gray-700">Name</label>
        <input type="text" id="name" name="name" class="w-full p-2 border border-gray-300 rounded-lg" required>
    </div>
    <div class="mb-4">
        <label for="value" class="block text-lg font-medium text-gray-700">Value</label>
        <input type="text" id="value" name="value" class="w-full p-2 border border-gray-300 rounded-lg" required>
    </div>
    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg">Create</button>
</form>

<a href="/data" class="mt-4 inline-block text-blue-500">Back to Data List</a>

<?php
// Include Footer
include __DIR__ . '/../layouts/footer.php';
?>

```

## app/views/data/edit.php
```php
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

```

## app/controllers/DataController.php
```php
<?php
// app/controllers/DataController.php

require_once __DIR__ . '/../../app/models/Data.php';
require_once __DIR__ . '/../../core/Render.php';

class DataController {

    public function index() {
        $dataModel = new Data();
        $data = $dataModel->getAll();
        Render::view('data/index', ['data' => $data]);
    }

    public function create() {
        Render::view('data/create');
    }

    public function store() {
        if ($_POST) {
            $name = $_POST['name'];
            $value = $_POST['value'];
            $dataModel = new Data();
            $dataModel->store($name, $value);
            header("Location: /data");
        }
    }

    public function edit($id) {
        $dataModel = new Data();
        $data = $dataModel->getById($id);
        Render::view('data/edit', ['data' => $data]);
    }

    public function update($id) {
        if ($_POST) {
            $name = $_POST['name'];
            $value = $_POST['value'];
            $dataModel = new Data();
            $dataModel->update($id, $name, $value);
            header("Location: /data");
        }
    }

    public function delete($id) {
        $dataModel = new Data();
        $dataModel->delete($id);
        header("Location: /data");
    }
}

```

## app/controllers/HomeController.php
```php
<?php

// app/controllers/HomeController.php

require_once __DIR__ . '/../../core/Render.php';

class HomeController {
    public function index() {
        Render::view('home', ['title' => 'Welcome to My Mini Framework']);
    }
}

```

## app/models/Data.php
```php
<?php
// app/models/Data.php

require_once __DIR__ . '/../../db/connection.php';

class Data {
    private $conn;
    private $table = 'data';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function store($name, $value) {
        $query = "INSERT INTO " . $this->table . " (name, value) VALUES (:name, :value)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':value', $value);
        return $stmt->execute();
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $name, $value) {
        $query = "UPDATE " . $this->table . " SET name = :name, value = :value WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':value', $value);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}

```

