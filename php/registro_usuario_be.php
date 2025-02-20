<?php
include 'conexion_be.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_completo = mysqli_real_escape_string($conexion, $_POST['nombre_completo']);
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);

    // Verificar si el correo ya existe
    $verificar_correo = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo = '$correo'");
    if(mysqli_num_rows($verificar_correo) > 0) {
        echo '
        <script>
            alert("Este correo ya está registrado");
            window.location = "../index.php";
        </script>
        ';
        exit();
    }

    // Insertar nuevo usuario
    $query = "INSERT INTO usuarios(nombre_completo, correo, usuario, contrasena) 
              VALUES('$nombre_completo', '$correo', '$usuario', '$contrasena')";
    
    $ejecutar = mysqli_query($conexion, $query);

    if($ejecutar) {
        echo '
        <script>
            alert("Usuario registrado exitosamente");
            window.location = "../index.php";
        </script>
        ';
    } else {
        echo '
        <script>
            alert("Error al registrar usuario: ' . mysqli_error($conexion) . '");
            window.location = "../index.php";
        </script>
        ';
    }
}
?>