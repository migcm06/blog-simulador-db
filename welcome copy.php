<?php
session_start();
if (isset($_SESSION['user']) && !empty($_SESSION['user'])) { //obteniendo datos del usuario
    $username = $_SESSION['user']['name'];
} else {
    header('Location: index.php');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'blog_db'); //conectando la base de datos


if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);  // verificando que la base de datosa conecte
}

// Comprobar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['titulo']) && isset($_POST['contenido'])) { //insertando los post en la base de datos
        $titulo = $_POST['titulo'];  // obteneindo titulo
        $contenido = $_POST['contenido'];  // obteniendo contenido

        if (!empty($titulo) && !empty($contenido)) { //los campos no pueden estar vaviones y de esta manera se verifica
            $stmt = $conn->prepare("INSERT INTO posts (titulo, contenido) VALUES (?, ?)");  // haciendo la consulta
            $stmt->bind_param("ss", $titulo, $contenido);  // dando los valores a los campos
            $stmt->execute();  
            $stmt->close(); 
            
            echo "<p>Post creado exitosamente y guardado en la base de datos.</p>"; // todo bien
        } else {
            echo "<p>Error: Título y contenido no pueden estar vacíos.</p>";
        }
    }
}


$result = $conn->query("SELECT * FROM posts"); // Obteniendo  todos los posts sin orden porque no hice el campo de created_at

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="./estilos.css"> 
</head>
<body>

<div class="container">
    <h1>WELCOME</h1>
    <p><?php echo "Bienvenido: " . $username; ?></p>

    <form action="welcome.php" method="post">
        <label for="titulo">Título del Post:</label>
        <input type="text" id="titulo" name="titulo" placeholder="Ingresa el título del post" required>

        <label for="contenido">Contenido del Post:</label>
        <textarea id="contenido" name="contenido" placeholder="Escribe el contenido aquí" rows="5" required></textarea>

        <input type="submit" value="Crear Post">
    </form>

    <h2>Posts</h2>
    <div class="posts">
        <?php
        // aqui se comenzarana moistar los posts
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='post'>";
                echo "<h3>" . htmlspecialchars($row['titulo']) . "</h3>";
                echo "<p>" . nl2br(htmlspecialchars($row['contenido'])) . "</p>"; // Convertir saltos de línea a <br>
                echo "</div>";
            }
        } else {
            echo "<p>No hay posts disponibles.</p>";
        }
        ?>
    </div>

    <form action="logout.php" method="post">
        <input type="submit" value="Cerrar sesión">
    </form>
</div>

<?php include './includes/footer.php'; ?>

</body>
</html>

<?php
$conn->close();  // Cerramos la conexión con la base de datos
?>
