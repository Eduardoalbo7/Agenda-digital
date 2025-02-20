<?php
session_start();
include 'conexion_be.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: ../mis_reservas.php");
    exit();
}

$id_reserva = (int)$_GET['id'];
$email = $_SESSION['usuario'];

// Verificar que la reserva pertenezca al usuario
$query_verificar = "SELECT r.* FROM reservas r 
                   INNER JOIN usuarios u ON r.id_usuario = u.id 
                   WHERE r.id_reserva = ? AND u.correo = ?";

$stmt = mysqli_prepare($conexion, $query_verificar);
mysqli_stmt_bind_param($stmt, "is", $id_reserva, $email);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($resultado) == 0) {
    echo '<script>
        alert("No tienes permiso para cancelar esta reserva");
        window.location = "../mis_reservas.php";
    </script>';
    exit();
}

// Cancelar la reserva
$query_cancelar = "UPDATE reservas SET estado = 'cancelada' WHERE id_reserva = ?";
$stmt = mysqli_prepare($conexion, $query_cancelar);
mysqli_stmt_bind_param($stmt, "i", $id_reserva);

if (mysqli_stmt_execute($stmt)) {
    echo '<script>
        alert("Reserva cancelada exitosamente");
        window.location = "../mis_reservas.php";
    </script>';
} else {
    echo '<script>
        alert("Error al cancelar la reserva");
        window.location = "../mis_reservas.php";
    </script>';
}
?>