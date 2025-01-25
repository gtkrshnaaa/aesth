# **Aesth Framework - Documentation**

## **Description**
Aesth is a simple PHP framework that follows the MVC (Model-View-Controller) pattern with easy-to-use routing. This framework allows for the development of web applications with clear and organized structure.

---

# **Directory Structure**

## tree.txt
```txt
aesth
├── app
│   ├── controllers
│   │   ├── DataController.php
│   │   └── HomeController.php
│   ├── models
│   │   └── Data.php
│   └── views
│       ├── data
│       │   ├── create.php
│       │   ├── edit.php
│       │   └── index.php
│       ├── home.php
│       └── layouts
│           ├── footer.php
│           └── header.php
├── core
│   ├── Render.php
│   └── Router.php
├── db
│   ├── connection.php
│   └── migrations
│       ├── migratedown.php
│       └── migrateup.php
├── file_list.md
├── filelistmdgenerator.php
├── public
│   └── index.php
├── README.md
├── routes
│   └── web.php
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
        
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = rtrim($url, '/'); 
    
        if ($url === '') {
            $url = '/';
        }
        
        foreach ($this->routes[$method] as $route => $callback) {
            $routePattern = preg_replace('/{[a-zA-Z0-9_]+}/', '([a-zA-Z0-9_]+)', $route);
            if (preg_match('#^' . $routePattern . '$#', $url, $matches)) {
                array_shift($matches); 
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

## db/connection.php
```php
<?php
// db/connection.php

class Database {
    private $host = 'localhost'; 
    private $db_name = 'aesth'; 
    private $username = 'root'; 
    private $password = ''; 
    private $conn;

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

<footer class="bg-[#161b22] text-[#c9d1d9] p-4 text-center mt-auto">
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

```

## app/views/data/index.php
```php
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

```

## app/views/data/create.php
```php
<?php
// app/views/data/create.php

// Include Header
include __DIR__ . '/../layouts/header.php';
?>

<main class="container mx-auto p-6 flex-grow">
    <h2 class="text-2xl font-semibold mb-6">Create New Data</h2>

    <form action="/data/create" method="POST" class="bg-[#161b22] p-6 rounded-lg shadow-md">
        <div class="mb-4">
            <label for="name" class="block text-lg font-medium text-[#c9d1d9]">Name</label>
            <input type="text" id="name" name="name" class="w-full p-2 border border-[#30363d] rounded-lg bg-[#0d1117] text-[#c9d1d9]" required>
        </div>
        <div class="mb-4">
            <label for="value" class="block text-lg font-medium text-[#c9d1d9]">Value</label>
            <input type="text" id="value" name="value" class="w-full p-2 border border-[#30363d] rounded-lg bg-[#0d1117] text-[#c9d1d9]" required>
        </div>
        <button type="submit" class="bg-[#238636] text-white px-6 py-2 rounded-lg">Create</button>
    </form>

    <a href="/data" class="mt-4 inline-block text-[#58a6ff] hover:text-[#c9d1d9]">Back to Data List</a>
</main>

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
        Render::view('home', ['title' => 'Welcome to Aesth Framework']);
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

