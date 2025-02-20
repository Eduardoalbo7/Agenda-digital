<?php
$conexion = mysqli_connect("localhost", "root", "", "login_register_db");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Establecer charset
if (!mysqli_set_charset($conexion, "utf8mb4")) {
    die("Error cargando el conjunto de caracteres utf8mb4: " . mysqli_error($conexion));
}

// Verificar que la base de datos existe
if (!mysqli_select_db($conexion, "login_register_db")) {
    die("Error seleccionando la base de datos: " . mysqli_error($conexion));
}

// Verificar que las tablas existen
$tablas_requeridas = ['usuarios', 'salas', 'reservas'];
foreach ($tablas_requeridas as $tabla) {
    $query = "SHOW TABLES LIKE '$tabla'";
    $resultado = mysqli_query($conexion, $query);
    if (mysqli_num_rows($resultado) == 0) {
        die("Error: La tabla '$tabla' no existe");
    }
}
?>