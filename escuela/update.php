<?php
require_once "conexion.php"; // Asegúrate de que el archivo de conexión está en la misma carpeta

try {
    // Conexión a la base de datos
    $pdo = connection();

    // Verificar si se ha enviado el ID del alumno a actualizar
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Consultar los datos del alumno
        $sql = "SELECT * FROM alumnos WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $alumno = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = $_POST['nombre'];
        $edad = $_POST['edad'];
        $clase = $_POST['clase'];
        $email = $_POST['email'];

        // Consulta para actualizar los datos del alumno
        $sql = "UPDATE alumnos SET nombre = :nombre, edad = :edad, clase = :clase, email = :email WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nombre' => $nombre, 'edad' => $edad, 'clase' => $clase, 'email' => $email, 'id' => $id]);

        // Redireccionar a la página principal
        header("Location: index.php");
        exit();
    }

    // Cerrar la conexión
    $pdo = null;

} catch (PDOException $e) {
    // Manejo de errores
    echo "Error: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> <!-- Archivo CSS -->
    <title>Actualizar Alumno</title>
</head>
<body>
    <div class="wrapper">
        <header>
            <h1 class="titulo">Actualizar Alumno</h1>
        </header>
        <div class="users-form">
            <form action="" method="POST">
                <input type="text" name="nombre" placeholder="Nombre" value="<?= $alumno['nombre'] ?>" required>
                <input type="number" name="edad" placeholder="Edad" value="<?= $alumno['edad'] ?>" required>
                <input type="text" name="clase" placeholder="Clase" value="<?= $alumno['clase'] ?>" required>
                <input type="email" name="email" placeholder="Email" value="<?= $alumno['email'] ?>" required>
                <input type="submit" value="Actualizar Alumno">
            </form>
            <a href="index.php" class="back-btn">Volver</a>
        </div>
    </div>
</body>
</html>
