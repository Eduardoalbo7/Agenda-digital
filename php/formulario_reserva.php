<?php
session_start();
include 'conexion_be.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    echo '<script>
            alert("Por favor, inicia sesión para hacer reservas");
            window.location = "index.php";
          </script>';
    exit();
}

// Verificar si se proporcionó un ID de sala
if (!isset($_GET['id_sala'])) {
    echo '<script>
            alert("No se ha seleccionado ninguna sala");
            window.location = "reservar.php";
          </script>';
    exit();
}

$id_sala = $_GET['id_sala'];

// Obtener información de la sala
$query = "SELECT * FROM salas WHERE id = $id_sala AND estado = 'disponible'";
$resultado = mysqli_query($conexion, $query);

if (mysqli_num_rows($resultado) == 0) {
    echo '<script>
            alert("La sala seleccionada no está disponible");
            window.location = "reservar.php";
          </script>';
    exit();
}

$sala = mysqli_fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Reserva</title>
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
        <h1>Reservar Sala de Computación</h1>
        
        <div class="info-sala">
            <h2><?php echo $sala['nombre']; ?></h2>
            <p><strong>Capacidad:</strong> <?php echo $sala['capacidad']; ?> equipos</p>
            <p><strong>Descripción:</strong> <?php echo $sala['descripcion']; ?></p>
        </div>
        
        <form action="procesar_reserva.php" method="POST" class="formulario-reserva">
            <input type="hidden" name="id_sala" value="<?php echo $id_sala; ?>">
            
            <div class="campo">
                <label for="fecha">Fecha de reserva:</label>
                <input type="date" id="fecha" name="fecha" required min="<?php echo date('Y-m-d'); ?>">
            </div>
            
            <div class="campo">
                <label for="hora_inicio">Hora de inicio:</label>
                <input type="time" id="hora_inicio" name="hora_inicio" required>
            </div>
            
            <div class="campo">
                <label for="hora_fin">Hora de fin:</label>
                <input type="time" id="hora_fin" name="hora_fin" required>
            </div>
            
            <div class="campo">
                <label for="proposito">Propósito de la reserva:</label>
                <select id="proposito" name="proposito" required>
                    <option value="">Seleccione un propósito</option>
                    <option value="clase">Clase regular</option>
                    <option value="examen">Examen</option>
                    <option value="taller">Taller</option>
                    <option value="proyecto">Trabajo en proyecto</option>
                    <option value="otro">Otro</option>
                </select>
            </div>
            
            <div class="botones">
                <button type="submit" class="boton">Confirmar Reserva</button>
                <a href="reservar.php" class="boton">Volver</a>
            </div>
        </form>
    </div>
</body>
</html>