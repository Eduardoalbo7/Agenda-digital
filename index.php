<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Agendamiento - Salas de Computación</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    <main>
        <h1 class="titulo">Portal de Agendamiento Salas de Computación 2025</h1>
        <div class="contenedor__todo">
            <div class="caja__trasera">
                <div class="caja__trasera-login">
                    <h3>¿Ya tienes una cuenta?</h3>
                    <p>Inicia sesión para agendar una sala</p>
                    <button id="btn__iniciar-sesion">Iniciar Sesión</button>
                </div>
                <div class="caja__trasera-register">
                    <h3>¿Aún no tienes una cuenta?</h3>
                    <p>Regístrate para poder realizar agendamientos</p>
                    <button id="btn__registrarse">Regístrarse</button>
                </div>
            </div>

            <div class="contenedor__login-register">
                <!-- Formulario de Login -->
                <form action="php/login_usuario_be.php" method="POST" class="formulario__login">
                    <h2>Iniciar Sesión</h2>
                    <input type="email" name="correo" placeholder="Correo Electrónico" required>
                    <input type="password" name="contrasena" placeholder="Contraseña" required>
                    <button type="submit">Entrar</button>
                </form>

                <!-- Formulario de Registro -->
                <form action="php/registro_usuario_be.php" method="POST" class="formulario__register">
                    <h2>Regístrarse</h2>
                    <input type="text" name="nombre_completo" placeholder="Nombre Completo" required>
                    <input type="email" name="correo" placeholder="Correo Electrónico" required>
                    <input type="text" name="usuario" placeholder="Usuario" required>
                    <input type="password" name="contrasena" placeholder="Contraseña" required 
                           minlength="6" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" 
                           title="La contraseña debe tener al menos 6 caracteres, una mayúscula, una minúscula y un número">
                    <button type="submit">Regístrarse</button>
                </form>
            </div>
        </div>
    </main>

    <script src="assets/js/script.js"></script>
</body>
</html>