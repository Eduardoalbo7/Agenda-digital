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

// Obtener todas las salas disponibles
$query = "SELECT * FROM salas WHERE estado = 'disponible'";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Sala</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
    <style>
        .salas-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }
        .sala-boton {
            width: 200px;
            height: 150px;
            border: 2px solid #2868c7;
            border-radius: 10px;
            background-color: #f0f8ff;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #333;
        }
        .sala-boton:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .sala-nombre {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .sala-info {
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h1>Seleccione una Sala de Computación</h1>
        
        <div class="salas-container">
            <?php
            if (mysqli_num_rows($resultado) > 0) {
                while ($sala = mysqli_fetch_assoc($resultado)) {
                    echo '<a href="formulario_reserva.php?id_sala=' . $sala['id'] . '" class="sala-boton">';
                    echo '<div class="sala-nombre">' . $sala['nombre'] . '</div>';
                    echo '<div class="sala-info">Capacidad: ' . $sala['capacidad'] . ' equipos</div>';
                    echo '</a>';
                }
            } else {
                echo '<p>No hay salas disponibles en este momento.</p>';
            }
            ?>
        </div>
        
        <div class="botones">
            <a href="bienvenida.php" class="boton">Volver</a>
        </div>
    </div>
</body>
</html>