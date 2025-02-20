<?php
session_start();
include 'php/conexion_be.php';

// Verificar sesión
if (!isset($_SESSION['usuario']) || !isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Debug para verificar la sesión
error_log("Datos de sesión: " . print_r($_SESSION, true));

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'php/conexion_be.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Debug para ver los datos de sesión
error_log("Sesión actual: " . print_r($_SESSION, true));

// Verificar la conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtener salas disponibles con manejo de errores
$query_salas = "SELECT * FROM salas WHERE estado = 'disponible'";
$resultado_salas = mysqli_query($conexion, $query_salas);

if (!$resultado_salas) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

// Verificar si hay salas
if (mysqli_num_rows($resultado_salas) == 0) {
    die("No hay salas disponibles en este momento.");
}
// Obtener salas disponibles
$query_salas = "SELECT * FROM salas WHERE estado = 'disponible'";
$resultado_salas = mysqli_query($conexion, $query_salas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Salas - Escuela Inglaterra</title>
    <link rel="stylesheet" href="assets/css/agenda.css">
</head>
<body>
    <div class="container">
        <h1>Sistema de Reservas</h1>
        
        <div class="salas-grid">
            <?php while($sala = mysqli_fetch_assoc($resultado_salas)): ?>
                <div class="sala-card" onclick="mostrarFormulario(<?php echo $sala['id_sala']; ?>, '<?php echo $sala['nombre']; ?>')">
                    <h2><?php echo htmlspecialchars($sala['nombre']); ?></h2>
                    <p><?php echo htmlspecialchars($sala['descripcion']); ?></p>
                    <p>Capacidad: <?php echo $sala['capacidad']; ?> personas</p>
                </div>
            <?php endwhile; ?>
        </div>

        <div id="formulario-reserva" style="display: none;">
            <h2>Realizar Reserva</h2>
            <form id="form-reserva" action="php/procesar_reserva.php" method="POST">
                <input type="hidden" id="id_sala" name="id_sala">
                
                <div class="form-group">
                    <label>Fecha:</label>
                    <input type="date" name="fecha" required min="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="form-group">
                    <label>Periodo y Sección:</label>
                    <select name="periodo_seccion" required>
                        <option value="">Seleccione horario</option>
                        <option value="1A">1° Periodo - Sección A (8:00-8:45)</option>
                        <option value="1B">1° Periodo - Sección B (8:45-9:30)</option>
                        <option value="2A">2° Periodo - Sección A (9:55-10:40)</option>
                        <option value="2B">2° Periodo - Sección B (10:40-11:25)</option>
                        <option value="3A">3° Periodo - Sección A (11:40-12:25)</option>
                        <option value="3B">3° Periodo - Sección B (12:25-13:10)</option>
                        <option value="4A">4° Periodo - Sección A (13:40-14:25)</option>
                        <option value="4B">4° Periodo - Sección B (14:25-15:10)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Curso:</label>
                    <input type="text" name="curso" required placeholder="Ej: 4° Básico A">
                </div>

                <div class="form-group">
                    <label>Asignatura:</label>
                    <input type="text" name="asignatura" required>
                </div>

                <div class="form-group">
                    <label>Objetivo de la clase:</label>
                    <textarea name="objetivo" required rows="4"></textarea>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-primary">Confirmar Reserva</button>
                    <button type="button" class="btn-secondary" onclick="cancelarReserva()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function mostrarFormulario(salaId, nombreSala) {
        document.getElementById('id_sala').value = salaId;
        document.getElementById('formulario-reserva').style.display = 'block';
        document.getElementById('formulario-reserva').scrollIntoView({ behavior: 'smooth' });
    }

    function cancelarReserva() {
        document.getElementById('formulario-reserva').style.display = 'none';
        document.getElementById('form-reserva').reset();
    }
    </script>
</body>
</html>