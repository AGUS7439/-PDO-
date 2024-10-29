<?php
require_once "conexion.php"; // Asegúrate de que el archivo de conexión está en la misma carpeta

try {
    // Conexión a la base de datos
    $pdo = connection();

    // Verificar si se ha enviado el formulario para agregar un alumno
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitización de entradas para evitar inyecciones SQL
        $nombre = htmlspecialchars($_POST['nombre']);
        $edad = intval($_POST['edad']);
        $clase = htmlspecialchars($_POST['clase']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        // Consulta para insertar el nuevo alumno
        $sql = "INSERT INTO alumnos (nombre, edad, clase, email) VALUES (:nombre, :edad, :clase, :email)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nombre' => $nombre, 'edad' => $edad, 'clase' => $clase, 'email' => $email]);

        // Redireccionar a la página principal después de agregar el alumno
        header("Location: index.php");
        exit();
    }

    // Consulta para obtener los alumnos
    $sqlAlumnos = "SELECT * FROM alumnos";
    $stmtAlumnos = $pdo->query($sqlAlumnos);
    $alumnos = $stmtAlumnos->fetchAll(PDO::FETCH_ASSOC);

    // Cerrar la conexión
    $pdo = null;

} catch (PDOException $e) {
    // Manejo de errores con un mensaje más específico
    echo "Error en la conexión a la base de datos: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="./Img/favicon.ico" />
    <title>CRUD PHP y MySQL - Alumnos</title>
    <link rel="stylesheet" href="style.css"> <!-- Archivo CSS externo -->
    <style>
        /* Asegúrate de que el modal esté oculto al cargar la página */
        .modal {
            display: none; /* Ocultar el modal por defecto */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Fondo semitransparente */
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <header>
            <div class="titulo">C.R.U.D. RESPONSIVE <br/> CON PHP (PDO), MySQL</div>
            <p>
                <button id="open-modal" class="open-modal-button">+ Añadir alumno</button>
            </p>
        </header>
        <main>
            <br />
            <!-- Tabla de Alumnos -->
            <div class="users-table">
                <h2>Alumnos registrados</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Edad</th>
                            <th>Clase</th>
                            <th>Email</th>
                            <th colspan="2">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Mostrar los datos de los alumnos -->
                        <?php if (!empty($alumnos)) : ?>
                            <?php foreach ($alumnos as $alumno) : ?>
                                <tr>
                                    <td><?= $alumno['id'] ?></td>
                                    <td><?= $alumno['nombre'] ?></td>
                                    <td><?= $alumno['edad'] ?></td>
                                    <td><?= $alumno['clase'] ?></td>
                                    <td><?= $alumno['email'] ?></td>
                                    <td><a href="update.php?id=<?= $alumno['id'] ?>" class="users-table--edit">Editar</a></td>
                                    <td><a href="#" onclick="confirmDelete(<?= $alumno['id'] ?>)" class="users-table--delete">Eliminar</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7">No hay alumnos registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
        <footer>
            <script>
                // Función para eliminar el alumno
                function confirmDelete(alumnoId) {
                    if (confirm("¿Está seguro de que desea eliminar este alumno?")) {
                        window.location.href = "delete_alumno.php?id=" + alumnoId;
                    }
                }

                // Obtener el botón para abrir la ventana modal
                var btn = document.getElementById("open-modal");

                // Crear la ventana modal
                var modal = document.createElement("div");
                modal.className = "modal";
                modal.innerHTML = `
                    <div class="users-form">
                        <h2>Añadir Nuevo Alumno</h2>
                        <form action="index.php" method="post">
                            <input type="text" name="nombre" placeholder="Nombre" required>
                            <input type="number" name="edad" placeholder="Edad" required>
                            <input type="text" name="clase" placeholder="Clase" required>
                            <input type="email" name="email" placeholder="Email" required>
                            <input type="submit" value="Añadir Alumno">
                        </form>
                        <button class="close-button">Cerrar</button>
                    </div>
                `;
                document.body.appendChild(modal);

                // Funcionalidad para abrir la ventana modal
                btn.onclick = function () {
                    modal.style.display = "flex"; // Solo mostrar el modal al hacer clic en el botón
                };

                // Funcionalidad para cerrar la ventana modal
                modal.querySelector(".close-button").onclick = function () {
                    modal.style.display = "none"; // Cerrar el modal cuando se hace clic en el botón de cerrar
                };
                
                // Cerrar la ventana modal si se hace clic fuera de ella
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                };
            </script>
        </footer>
    </div>
</body>
</html>












