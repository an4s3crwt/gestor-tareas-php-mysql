<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="estilos/login.css">
</head>
<body>

    <div class="left-section">
        <button class="back-button">←</button>
        <form action="login.php" method="post" class="login-form">
            <h2>LOG IN</h2>

            <div class="input-group">
                <label for="username" class="visually-hidden">Username</label>
                <input type="text" name="username" placeholder="Username" required>
                <span class="icon">*</span>
            </div>

            <div class="input-group">
                <label for="password" class="visually-hidden">Contraseña</label>
                <input type="password" name="password" placeholder="Contraseña" required>
                <span class="icon">👁</span>
            </div>


            <button type="submit" class="sign-in-button">Inicar Sesión</button>

        </form>
    </div>

    <div class="right-section">
        <div class="loading-animation">
            <span class="loading-icon">⟳</span>
        </div>
    </div>
</body>
</html>
