<?php
session_start();
include 'conexion_be.php';

// Verificar si hay sesión activa
if (!isset($_SESSION['usuario']) || !isset($_SESSION['id'])) {
    echo '<script>
        alert("Sesión no válida, por favor inicie sesión nuevamente");
        window.location = "../index.php";
    </script>';
    exit();
}

// Obtener el ID del usuario directamente de la sesión
$id_usuario = $_SESSION['id'];

// Verificar que se reciban todos los datos necesarios
if (!isset($_POST['id_sala'], $_POST['fecha'], $_POST['periodo_seccion'], 
          $_POST['curso'], $_POST['asignatura'], $_POST['objetivo'])) {
    echo '<script>
        alert("Faltan datos necesarios para la reserva");
        window.location = "../agenda.php";
    </script>';
    exit();
}

// Obtener y sanitizar datos
$id_sala = (int)$_POST['id_sala'];
$fecha = mysqli_real_escape_string($conexion, $_POST['fecha']);
$periodo_seccion = mysqli_real_escape_string($conexion, $_POST['periodo_seccion']);
$curso = mysqli_real_escape_string($conexion, $_POST['curso']);
$asignatura = mysqli_real_escape_string($conexion, $_POST['asignatura']);
$objetivo = mysqli_real_escape_string($conexion, $_POST['objetivo']);

// Verificar disponibilidad
$query_verificar = "SELECT COUNT(*) as total FROM reservas 
                   WHERE id_sala = ? AND fecha_reserva = ? 
                   AND periodo_seccion = ? AND estado != 'cancelada'";

$stmt_verificar = mysqli_prepare($conexion, $query_verificar);

if (!$stmt_verificar) {
    echo '<script>
        alert("Error al preparar la consulta: ' . mysqli_error($conexion) . '");
        window.location = "../agenda.php";
    </script>';
    exit();
}

mysqli_stmt_bind_param($stmt_verificar, "iss", $id_sala, $fecha, $periodo_seccion);
mysqli_stmt_execute($stmt_verificar);
$resultado_verificar = mysqli_stmt_get_result($stmt_verificar);
$row = mysqli_fetch_assoc($resultado_verificar);

if ($row['total'] > 0) {
    echo '<script>
        alert("Este horario ya está reservado");
        window.location = "../agenda.php";
    </script>';
    exit();
}

// Insertar reserva
$query_insertar = "INSERT INTO reservas (id_sala, id_usuario, fecha_reserva, 
                   periodo_seccion, curso, asignatura, objetivo, estado) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, 'confirmada')";

$stmt_insertar = mysqli_prepare($conexion, $query_insertar);

if (!$stmt_insertar) {
    echo '<script>
        alert("Error al preparar la inserción: ' . mysqli_error($conexion) . '");
        window.location = "../agenda.php";
    </script>';
    exit();
}

mysqli_stmt_bind_param($stmt_insertar, "iisssss", 
    $id_sala, $id_usuario, $fecha, $periodo_seccion, 
    $curso, $asignatura, $objetivo);

if (mysqli_stmt_execute($stmt_insertar)) {
    echo '<script>
        alert("Reserva realizada con éxito");
        window.location = "../mis_reservas.php";
    </script>';
} else {
    echo '<script>
        alert("Error al realizar la reserva: ' . mysqli_error($conexion) . '");
        window.location = "../agenda.php";
    </script>';
}

// Cerrar conexiones
if (isset($stmt_verificar)) mysqli_stmt_close($stmt_verificar);
if (isset($stmt_insertar)) mysqli_stmt_close($stmt_insertar);
mysqli_close($conexion);
?>