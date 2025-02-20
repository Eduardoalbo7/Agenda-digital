<?php
session_start();
include 'conexion_be.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Verificar si se proporcionó un ID de reserva
if (!isset($_GET['id'])) {
    echo '<script>
            alert("No se ha especificado ninguna reserva");
            window.location = "mis_reservas.php";
          </script>';
    exit();
}

$id_reserva = $_GET['id'];
$email = $_SESSION['usuario'];

// Obtener el ID del usuario
$query_usuario = "SELECT id FROM usuarios WHERE correo = '$email'";
$resultado_usuario = mysqli_query($conexion, $query_usuario);
$usuario = mysqli_fetch_assoc($resultado_usuario);
$id_usuario = $usuario['id'];

// Verificar que la reserva pertenezca al usuario y obtener sus datos
$query_reserva = "SELECT r.*, s.nombre AS nombre_sala 
                  FROM reservas r 
                  JOIN salas s ON r.id_sala = s.id 
                  WHERE r.id = $id_reserva AND r.id_usuario = $id_usuario";
$resultado_reserva = mysqli_query($conexion, $query_reserva);

if (mysqli_num_rows($resultado_reserva) == 0) {
    echo '<script>
            alert("No tienes permiso para modificar esta reserva");
            window.location = "mis_reservas.php";
          </script>';
    exit();
}

$reserva = mysqli_fetch_assoc($resultado_reserva);

// Procesar el formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST['fecha'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $proposito = $_POST['proposito'];
    
    // Verificar disponibilidad (excluyendo la reserva actual)
    $query_disponibilidad = "SELECT * FROM reservas 
                            WHERE id_sala = {$reserva['id_sala']} 
                            AND fecha_reserva = '$fecha' 
                            AND estado != 'cancelada'
                            AND id != $id_reserva
                            AND ((hora_inicio <= '$hora_inicio' AND hora_fin > '$hora_inicio')
                                OR (hora_inicio < '$hora_fin' AND hora_fin >= '$hora_fin')
                                OR ('$hora_inicio' <= hora_inicio AND '$hora_fin' >= hora_fin))";
    
    $resultado_disponibilidad = mysqli_query($conexion, $query_disponibilidad);
    
    if (mysqli_num_rows($resultado_disponibilidad) > 0) {
        echo '<script>
                alert("La sala ya está reservada para ese horario. Por favor, selecciona otro horario.");
              </script>';
    } else {
        // Actualizar la reserva
        $query_actualizar = "UPDATE reservas 
                            SET fecha_reserva = '$fecha', 
                                hora_inicio = '$hora_inicio', 
                                hora_fin = '$hora_fin', 
                                proposito = '$proposito' 
                            WHERE id = $id_reserva";
        
        if (mysqli_query($conexion, $query_actualizar)) {
            echo '<script>
                    alert("Reserva modificada exitosamente");
                    window.location = "mis_reservas.php";
                  </script>';
            exit();
        } else {
            echo '<script>
                    alert("Error al modificar la reserva: ' . mysqli_error($conexion) . '");
                  </script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Reserva</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
    <style>
        .formulario-reserva {
            max-width: 600px;
            margin: 0 auto;
        }
        .campo {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="date"],
        input[type="time"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .info-sala {
            background-color: #f0f8ff;
            border: 1px solid #2868c7;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h1>Modificar Reserva</h1>
        
        <div class="info-sala">
            <h2><?php echo $reserva['nombre_sala']; ?></h2>
            <p><strong>Reserva actual:</strong> <?php echo date('d/m/Y', strtotime($reserva['fecha_reserva'])); ?>, 
            de <?php echo substr($reserva['hora_inicio'], 0, 5); ?> a <?php echo substr($reserva['hora_fin'], 0, 5); ?></p>
        </div>
        
        <form method="POST" class="formulario-reserva">
            <div class="campo">
                <label for="fecha">Fecha de reserva:</label>
                <input type="date" id="fecha" name="fecha" required min="<?php echo date('Y-m-d'); ?>" 
                       value="<?php echo $reserva['fecha_reserva']; ?>">
            </div>
            
            <div class="campo">
                <label for="hora_inicio">Hora de inicio:</label>
                <input type="time" id="hora_inicio" name="hora_inicio" required 
                       value="<?php echo $reserva['hora_inicio']; ?>">
            </div>
            
            <div class="campo">
                <label for="hora_fin">Hora de fin:</label>
                <input type="time" id="hora_fin" name="hora_fin" required 
                       value="<?php echo $reserva['hora_fin']; ?>">
            </div>
            
            <div class="campo">
                <label for="proposito">Propósito de la reserva:</label>
                <select id="proposito" name="proposito" required>
                    <option value="">Seleccione un propósito</option>
                    <option value="clase" <?php echo ($reserva['proposito'] == 'clase') ? 'selected' : ''; ?>>Clase regular</option>
                    <option value="examen" <?php echo ($reserva['proposito'] == 'examen') ? 'selected' : ''; ?>>Examen</option>
                    <option value="taller" <?php echo ($reserva['proposito'] == 'taller') ? 'selected' : ''; ?>>Taller</option>
                    <option value="proyecto" <?php echo ($reserva['proposito'] == 'proyecto') ? 'selected' : ''; ?>>Trabajo en proyecto</option>
                    <option value="otro" <?php echo ($reserva['proposito'] == 'otro') ? 'selected' : ''; ?>>Otro</option>
                </select>
            </div>
            
            <div class="botones">
                <button type="submit" class="boton">Guardar Cambios</button>
                <a href="mis_reservas.php" class="boton">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>