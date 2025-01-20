# AESTH

### Folder Structure

```
/project
├── /app
│   ├── /Controllers
│   │   └── DataController.php
│   ├── /Models
│   │   └── Data.php
│   └── /Routes
│       └── web.php
├── /core
│   ├── /Aesth
│   │   └── Router.php
│   ├── /Security
│   │   ├── CSRF.php
│   │   └── XSS.php
│   └── autoload.php
├── /db
│   ├── connection.php
│   └── /migrations
│       ├── migrate_up.php
│       └── migrate_down.php
├── /public
│   └── index.php
└── /Views
    ├── /data
    │   ├── index.php
    │   ├── create.php
    │   └── edit.php
```

### 1. **`db/connection.php`** (Database Connection)

```php
// /db/connection.php
class DatabaseConnection {
    private static $connection;

    public static function connect() {
        if (self::$connection === null) {
            self::$connection = new mysqli('localhost', 'root', '', 'test'); // Update DB credentials
            if (self::$connection->connect_error) {
                die('Connection failed: ' . self::$connection->connect_error);
            }
        }
        return self::$connection;
    }
}
```

### 2. **Router (`Router.php`)** (in `/core/Aesth`)

```php
// /core/Aesth/Router.php
class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->addRoute('GET', $path, $callback);
    }

    public function post($path, $callback) {
        $this->addRoute('POST', $path, $callback);
    }

    public function addRoute($method, $path, $callback) {
        $this->routes[$method][$path] = $callback;
    }

    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (isset($this->routes[$method][$path])) {
            $callback = $this->routes[$method][$path];
            call_user_func($callback);
        } else {
            echo "404 Not Found";
        }
    }
}
```

### 3. **CSRF (`CSRF.php`)** (in `/core/Security`)

```php
// /core/Security/CSRF.php
class CSRF {
    public static function generateToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function validateToken($token) {
        return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
    }
}
```

### 4. **XSS (`XSS.php`)** (in `/core/Security`)

```php
// /core/Security/XSS.php
class XSS {
    public static function sanitize($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}
```

### 5. **Controller (`DataController.php`)** (in `/app/Controllers`)

```php
// /app/Controllers/DataController.php
class DataController {

    public function index() {
        $dataItems = Data::getAll();
        return $this->render('data/index.php', ['dataItems' => $dataItems]);
    }

    public function create() {
        $csrfToken = CSRF::generateToken();
        return $this->render('data/create.php', ['csrf_token' => $csrfToken]);
    }

    public function store() {
        if (!CSRF::validateToken($_POST['csrf_token'])) {
            die('CSRF token validation failed.');
        }

        $name = XSS::sanitize($_POST['name']);
        Data::create($name);
        header('Location: /data');
    }

    public function edit($id) {
        $dataItem = Data::getById($id);
        $csrfToken = CSRF::generateToken();
        return $this->render('data/edit.php', ['dataItem' => $dataItem, 'csrf_token' => $csrfToken]);
    }

    public function update($id) {
        if (!CSRF::validateToken($_POST['csrf_token'])) {
            die('CSRF token validation failed.');
        }

        $name = XSS::sanitize($_POST['name']);
        Data::update($id, $name);
        header('Location: /data');
    }

    public function delete($id) {
        Data::delete($id);
        header('Location: /data');
    }

    private function render($view, $data = []) {
        extract($data); // Extracting the data array into variables
        ob_start();
        require __DIR__ . '/../Views/' . $view;
        return ob_get_clean();
    }
}
```

### 6. **Model (`Data.php`)** (in `/app/Models`)

```php
// /app/Models/Data.php
class Data {

    // Get all data
    public static function getAll() {
        $connection = DatabaseConnection::connect();
        $result = $connection->query('SELECT * FROM data');
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get data by ID
    public static function getById($id) {
        $connection = DatabaseConnection::connect();
        $stmt = $connection->prepare('SELECT * FROM data WHERE id = ?');
        $stmt->bind_param('i', $id); // Bind the ID parameter
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Create new data
    public static function create($name) {
        $connection = DatabaseConnection::connect();
        $stmt = $connection->prepare('INSERT INTO data (name) VALUES (?)');
        $stmt->bind_param('s', $name); // Bind the name parameter
        $stmt->execute();
    }

    // Update data
    public static function update($id, $name) {
        $connection = DatabaseConnection::connect();
        $stmt = $connection->prepare('UPDATE data SET name = ? WHERE id = ?');
        $stmt->bind_param('si', $name, $id); // Bind the name and ID parameters
        $stmt->execute();
    }

    // Delete data
    public static function delete($id) {
        $connection = DatabaseConnection::connect();
        $stmt = $connection->prepare('DELETE FROM data WHERE id = ?');
        $stmt->bind_param('i', $id); // Bind the ID parameter
        $stmt->execute();
    }
}
```

### 7. **Routes (`web.php`)** (in `/app/Routes`)

```php
// /app/Routes/web.php
$router = new Router();

$router->get('/data', [new DataController(), 'index']);
$router->get('/data/create', [new DataController(), 'create']);
$router->post('/data', [new DataController(), 'store']);
$router->get('/data/{id}/edit', [new DataController(), 'edit']);
$router->post('/data/{id}/update', [new DataController(), 'update']);
$router->get('/data/{id}/delete', [new DataController(), 'delete']);

$router->run();
```

### 8. **Views** (in `/Views/data`)

Example `index.php` view:

```php
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

```

Example `create.php` view:

```php
<!-- /Views/data/create.php -->
<form method="POST" action="/data">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required>
    <button type="submit">Create</button>
</form>
```

Example `edit.php` view:

```php
<!-- /Views/data/edit.php -->
<h1>Edit Data</h1>
<form method="POST" action="/data/<?= $dataItem['id']; ?>/update">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" value="<?= htmlspecialchars($dataItem['name'], ENT_QUOTES, 'UTF-8'); ?>" required>
    <button type="submit">Update</button>
</form>
<a href="/data">Back to List</a>

```

### 9. **`autoload.php`** (in `/core`)

This file will handle all the necessary `require` statements for autoloading classes.

```php
// /core/autoload.php
require __DIR__ . '/../db/connection.php';  // Add this require for DB connection
require __DIR__ . '/../app/Models/Data.php';
require __DIR__ . '/../core/Security/CSRF.php';
require __DIR__ . '/../core/Security/XSS.php';
require __DIR__ . '/../core/Aesth/Router.php';
require __DIR__ . '/../app/Controllers/DataController.php';
require __DIR__ . '/../app/Routes/web.php';
```

### 10. **`index.php`** (in `/public`)

This is the entry point, where the autoload file is included.

```php
// /public/index.php
require __DIR__ . '/../core/autoload.php';
```

---

### **Migration Files**

#### 1. `migrate_up.php`
This file is used to create the necessary tables and schema for your application.

```php
// /db/migrations/migrate_up.php
require __DIR__ . '/../connection.php';

$connection = DatabaseConnection::connect();

// SQL query to create the "data" table
$query = "CREATE TABLE IF NOT EXISTS data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($connection->query($query)) {
    echo "Table 'data' created successfully.\n";
} else {
    echo "Error creating table: " . $connection->error . "\n";
}
```

#### 2. `migrate_down.php`
This file is used to delete tables or roll back changes.

```php
// /db/migrations/migrate_down.php
require __DIR__ . '/../connection.php';

$connection = DatabaseConnection::connect();

// SQL query to drop the "data" table
$query = "DROP TABLE IF EXISTS data";

if ($connection->query($query)) {
    echo "Table 'data' dropped successfully.\n";
} else {
    echo "Error dropping table: " . $connection->error . "\n";
}
```

---

### Running the Migrations

To execute the migrations, you can use the command line:

1. Run the **migrate_up.php** file to create the tables:
   ```bash
   php db/migrations/migrate_up.php
   ```

2. Run the **migrate_down.php** file to delete the tables:
   ```bash
   php db/migrations/migrate_down.php
   ```


---

