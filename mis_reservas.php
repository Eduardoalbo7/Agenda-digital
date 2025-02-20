<?php
session_start();
include 'php/conexion_be.php';  // Ruta corregida

// Verificar sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Obtener reservas del usuario actual
$email = $_SESSION['usuario'];
$query = "SELECT r.*, s.nombre as nombre_sala 
          FROM reservas r 
          INNER JOIN salas s ON r.id_sala = s.id_sala 
          INNER JOIN usuarios u ON r.id_usuario = u.id 
          WHERE u.correo = ? 
          ORDER BY r.fecha_reserva DESC";

$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas - Sistema de Agendamiento</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
    <style>
        .reservas-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .reserva-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn-volver {
            display: inline-block;
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .estado {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
        }
        .estado-confirmada { background: #4CAF50; color: white; }
        .estado-cancelada { background: #f44336; color: white; }
    </style>
</head>
<body>
    <div class="reservas-container">
        <h1>Mis Reservas</h1>
        <a href="agenda.php" class="btn-volver">Volver a Agenda</a>

        <?php if(mysqli_num_rows($resultado) > 0): ?>
            <?php while($reserva = mysqli_fetch_assoc($resultado)): ?>
                <div class="reserva-card">
                    <h3><?php echo htmlspecialchars($reserva['nombre_sala']); ?></h3>
                    <p><strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($reserva['fecha_reserva'])); ?></p>
                    <p><strong>Periodo:</strong> <?php echo htmlspecialchars($reserva['periodo_seccion']); ?></p>
                    <p><strong>Curso:</strong> <?php echo htmlspecialchars($reserva['curso']); ?></p>
                    <p><strong>Asignatura:</strong> <?php echo htmlspecialchars($reserva['asignatura']); ?></p>
                    <p><strong>Objetivo:</strong> <?php echo htmlspecialchars($reserva['objetivo']); ?></p>
                    <span class="estado estado-<?php echo $reserva['estado']; ?>">
                        <?php echo ucfirst($reserva['estado']); ?>
                    </span>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No tienes reservas registradas.</p>
        <?php endif; ?>
    </div>
</body>
</html>