<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="estilos/login.css">
</head>
<body>
    <!-- Sección izquierda: Inicio de sesión -->
    <div class="left-section">
        <button class="back-button">← Volver</button>
        <div class="login-form">
            <h2>Inicio de Sesión</h2>
            <?php if (!empty($error)) : ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form id="formLogin" action="login.php" method="POST">
                <div class="input-group">
                    <input type="text" id="loginUser" name="username" placeholder="Nombre de usuario" required>
                </div>
                <div class="input-group">
                    <input type="password" id="loginPassword" name="password" placeholder="Contraseña" required>
                </div>
                <button type="submit" class="sign-in-button">Iniciar sesión</button>
            </form>
        </div>
    </div>

    <!-- Sección derecha: Registro -->
    <div class="right-section">
        <div class="login-form">
            <h2>Regístrate</h2>
            <form id="formRegister" action="login.php" method="POST">
                <input type="hidden" name="register" value="1">
                <div class="input-group">
                    <input type="text" id="registerUser" name="username" placeholder="Nombre de usuario" required>
                </div>
                <div class="input-group">
                    <input type="password" id="registerPassword" name="password" placeholder="Contraseña" required>
                </div>
                <button type="submit" class="sign-in-button">Registrarse</button>
            </form>
        </div>
    </div>

    <!-- Spinner -->
    <div class="loading-icon" id="spinner" style="display:none;">⟳</div>

    <!-- JavaScript -->
    <script>
        // Mostrar el spinner al enviar un formulario
        const spinner = document.getElementById('spinner');
        document.getElementById('formLogin').addEventListener('submit', () => {
            spinner.style.display = 'inline-block';
        });
        document.getElementById('formRegister').addEventListener('submit', () => {
            spinner.style.display = 'inline-block';
        });
    </script>
</body>
</html>
