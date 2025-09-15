<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "final_project";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Create table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255)
)");

$conn->query("CREATE TABLE IF NOT EXISTS tasks(
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100),
    description TEXT
)");

// Register
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users(username, password) VALUES(?,?)");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    echo "Registration successful! Please login.";
}

// Login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    if ($result && password_verify($password, $result['password'])) {
        $_SESSION['user'] = $username;
    } else {
        echo "Invalid login!";
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
}

// CRUD: Add Task
if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $desc  = $_POST['description'];
    $stmt = $conn->prepare("INSERT INTO tasks(title, description) VALUES(?,?)");
    $stmt->bind_param("ss", $title, $desc);
    $stmt->execute();
}

// CRUD: Update Task
if (isset($_POST['update'])) {
    $id    = $_POST['id'];
    $title = $_POST['title'];
    $desc  = $_POST['description'];
    $stmt = $conn->prepare("UPDATE tasks SET title=?, description=? WHERE id=?");
    $stmt->bind_param("ssi", $title, $desc, $id);
    $stmt->execute();
}

// CRUD: Delete Task
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Search + Pagination
$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page-1)*$limit;
$search = isset($_GET['search']) ? $_GET['search'] : "";

$query = "SELECT * FROM tasks WHERE title LIKE ? OR description LIKE ? LIMIT $start,$limit";
$stmt = $conn->prepare($query);
$param = "%$search%";
$stmt->bind_param("ss", $param, $param);
$stmt->execute();
$tasks = $stmt->get_result();

$totalRes = $conn->query("SELECT COUNT(*) as count FROM tasks WHERE title LIKE '%$search%' OR description LIKE '%$search%'")->fetch_assoc()['count'];
$pages = ceil($totalRes/$limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Final Project</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .auth, .task-form { margin-bottom: 20px; }
        .pagination a { margin: 0 5px; text-decoration: none; }
    </style>
</head>
<body>

<h2>Final Project Web Application</h2>

<?php if (!isset($_SESSION['user'])): ?>
<div class="auth">
    <h3>Register</h3>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required> 
        <input type="password" name="password" placeholder="Password" required>
        <button name="register">Register</button>
    </form>

    <h3>Login</h3>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required> 
        <input type="password" name="password" placeholder="Password" required>
        <button name="login">Login</button>
    </form>
</div>
<?php else: ?>
    <p>Welcome, <?php echo $_SESSION['user']; ?> | <a href="?logout=1">Logout</a></p>

    <div class="task-form">
        <h3>Add Task</h3>
        <form method="POST">
            <input type="text" name="title" placeholder="Title" required>
            <input type="text" name="description" placeholder="Description" required>
            <button name="add">Add</button>
        </form>
    </div>

    <form method="GET">
        <input type="text" name="search" placeholder="Search..." value="<?php echo $search; ?>">
        <button type="submit">Search</button>
    </form>

    <table>
        <tr><th>ID</th><th>Title</th><th>Description</th><th>Actions</th></tr>
        <?php while($row = $tasks->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= $row['title']; ?></td>
                <td><?= $row['description']; ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                        <input type="text" name="title" value="<?= $row['title']; ?>">
                        <input type="text" name="description" value="<?= $row['description']; ?>">
                        <button name="update">Update</button>
                    </form>
                    <a href="?delete=<?= $row['id']; ?>" onclick="return confirm('Delete this task?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <div class="pagination">
        <?php for($i=1;$i<=$pages;$i++): ?>
            <a href="?page=<?= $i; ?>&search=<?= $search; ?>"><?= $i; ?></a>
        <?php endfor; ?>
    </div>
<?php endif; ?>

</body>
</html>
