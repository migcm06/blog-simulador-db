<?php
session_start();

if (isset($_SESSION['user']) && !empty($_SESSION['user'])) { 
    $username = $_SESSION['user']['name'];
} else {
    header('Location: index.php');
    exit;
}

// Verificar si ya existen posts en la cookie
$posts = isset($_COOKIE['posts']) ? json_decode($_COOKIE['posts'], true) : [];


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['titulo']) && isset($_POST['contenido'])) {
    $nuevo_post = [
        'titulo' => $_POST['titulo'],
        'contenido' => $_POST['contenido'],
        'autor' => $username,
        'fecha' => date('Y-m-d H:i:s')
    ];

    $posts[] = $nuevo_post;


    setcookie('posts', json_encode($posts), time() + (7 * 24 * 60 * 60), "/"); // 1 semana
}

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

    <h2>Posts Recientes</h2>
    <ul>
        <?php
        if (!empty($posts)) {
            $posts_hecho_por_usuario = array_filter($posts, function($post) use ($username) {
                return $post['autor'] == $username;
            });

            // Mostrar los posts almacenados
            if (!empty($posts_hecho_por_usuario)) {
                foreach ($posts_hecho_por_usuario as $post) {
                echo "<li>";
                echo "<h3>" . htmlspecialchars($post['titulo']) . "</h3>";
                echo "<p>" . htmlspecialchars($post['contenido']) . "</p>";
                echo "<small>Por: " . htmlspecialchars($post['autor']) . " el " . $post['fecha'] . "</small>";
                echo "</li>";
            }
        } else {
            echo "<p>No hay posts aún.</p>";
        }
        }
            
        ?>
    </ul>

    <form action="logout.php" method="post">
        <input type="submit" value="Cerrar sesión">
    </form>
</div>

<?php include './includes/footer.php'; ?>

</body>
</html>

