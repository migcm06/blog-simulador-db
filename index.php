<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividad Manejo de PHP</title>
    <link rel="stylesheet" href="./estilos.css">
</head>
<body>

<!-- formulario para login -->
<form action="login.php" method="post">
    <h1>LOGIN</h1>
    <input type="text" name="user" placeholder="Usuario" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <input type="submit" value="Iniciar sesión">
</form>

<?php include './includes/footer.php'; ?>

</body>
</html>