<?php
require_once "conexion.php";

try {

    $pdo = connection();

    // Verificar si se ha enviado el ID del alumno a eliminar y validar que sea un número entero
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    
    // Si el ID es inválido, mostrar mensaje de error y detener la ejecución
    if (!$id) {
        echo "SE HA PRODUCIDO UN ERROR EN EL ID DEL USUARIO QUE SE QUIERE BORRAR";
        die();
    }

    // Consulta para eliminar el alumno
    $sql = "DELETE FROM alumnos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Redireccionar a la página principal después de eliminar
    header("Location: index.php");
    exit();

} catch (PDOException $e) {
    
    echo "SE HA PRODUCIDO UN ERROR AL ELIMINAR EL USUARIO: " . $e->getMessage();
    die();
} finally {
    
    $pdo = null;
}
?>



