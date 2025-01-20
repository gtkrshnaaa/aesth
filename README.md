# aesth


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
├── /public
│   └── index.php
└── /Views
    ├── /data
    │   ├── index.php
    │   ├── create.php
    │   └── edit.php
```

### 1. **File `autoload.php`** (`core`)

Require handling using `require`.

```php
// /core/autoload.php
require __DIR__ . '/../app/Models/Data.php';
require __DIR__ . '/../core/Security/CSRF.php';
require __DIR__ . '/../core/Security/XSS.php';
require __DIR__ . '/../core/Aesth/Router.php';
require __DIR__ . '/../app/Controllers/DataController.php';
require __DIR__ . '/../app/Routes/web.php';
```

### 2. **Router (`Router.php`)** (`core/Aesth`)

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

### 3. **CSRF (`CSRF.php`)** (`core/Security`)

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

### 4. **XSS (`XSS.php`)** (`core/Security`)

```php
// /core/Security/XSS.php
class XSS {
    public static function sanitize($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}
```

### 5. **Controller (`DataController.php`)** (`app/Controllers`)

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

### 6. **Model (`Data.php`)** (`app/Models`)

```php
// /app/Models/Data.php
class Data {

    public static function getAll() {
        $pdo = new PDO('mysql:host=localhost;dbname=test', 'root', '');
        $stmt = $pdo->query('SELECT * FROM data');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $pdo = new PDO('mysql:host=localhost;dbname=test', 'root', '');
        $stmt = $pdo->prepare('SELECT * FROM data WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($name) {
        $pdo = new PDO('mysql:host=localhost;dbname=test', 'root', '');
        $stmt = $pdo->prepare('INSERT INTO data (name) VALUES (?)');
        $stmt->execute([$name]);
    }

    public static function update($id, $name) {
        $pdo = new PDO('mysql:host=localhost;dbname=test', 'root', '');
        $stmt = $pdo->prepare('UPDATE data SET name = ? WHERE id = ?');
        $stmt->execute([$name, $id]);
    }

    public static function delete($id) {
        $pdo = new PDO('mysql:host=localhost;dbname=test', 'root', '');
        $stmt = $pdo->prepare('DELETE FROM data WHERE id = ?');
        $stmt->execute([$id]);
    }
}
```

### 7. **Routes (`web.php`)** (`app/Routes`)

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

### 8. **Views** (`Views/data`)


```php
<!-- /Views/data/create.php -->
<form method="POST" action="/data">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required>
    <button type="submit">Create</button>
</form>
```

### 9. **`index.php`**

```php
// /public/index.php
require __DIR__ . '/../core/autoload.php';
```

