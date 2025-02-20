<?php
session_start();
include 'conexion_be.php';

$correo = mysqli_real_escape_string($conexion, $_POST['correo']);
$contrasena = $_POST['contrasena'];

$validar_login = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo='$correo'");

if(mysqli_num_rows($validar_login) > 0){
    $fila = mysqli_fetch_assoc($validar_login);
    if(password_verify($contrasena, $fila['contrasena'])){
        $_SESSION['usuario'] = $correo;
        $_SESSION['id'] = $fila['id'];
        $_SESSION['nombre_completo'] = $fila['nombre_completo'];
        header("location: ../agenda.php");
        exit;
    } else {
        echo '
        <script>
            alert("Contraseña incorrecta");
            window.location = "../index.php";
        </script>';
    }
} else {
    echo '
    <script>
        alert("Usuario no encontrado");
        window.location = "../index.php";
    </script>';
}
mysqli_close($conexion);
?>