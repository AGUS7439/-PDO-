<?php
require_once "conexion.php"; // Asegúrate de que el archivo de conexión está en la misma carpeta

try {
    // Conexión a la base de datos
    $pdo = connection();

    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Capturar los valores del formulario
        $nombre = $_POST['nombre'];
        $edad = $_POST['edad'];
        $clase = $_POST['clase'];
        $email = $_POST['email'];

        // Validar el formato del correo electrónico
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('La dirección de correo electrónico no es válida');
        }

        // Consulta para insertar el nuevo alumno
        $sql = "INSERT INTO alumnos (nombre, edad, clase, email) VALUES (:nombre, :edad, :clase, :email)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nombre' => $nombre,
            'edad' => $edad,
            'clase' => $clase,
            'email' => $email
        ]);

        // Redireccionar a la página principal
        header("Location: index.php");
        exit();
    }

    // Cerrar la conexión a la base de datos
    $pdo = null;

} catch (Exception $e) {
    // Manejo de errores generales, como problemas de validación
    echo "Error: " . $e->getMessage();
    die();
} catch (PDOException $e) {
    // Manejo de errores relacionados con la base de datos
    echo "Error de base de datos: " . $e->getMessage();
    die();
}
?>



