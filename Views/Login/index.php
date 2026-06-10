<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Acceso al Sistema</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Bienvenido</h2>
            <p>Ingresa tus credenciales para continuar</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="error-message">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                Credenciales incorrectas
            </div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>Login/validar" method="POST">
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" name="correo" id="correo" placeholder="ejemplo@correo.com" required autocomplete="email">
            </div>
            
            <div class="form-group">
                <label for="clave">Contraseña</label>
                <input type="password" name="clave" id="clave" placeholder="••••••••" required autocomplete="current-password">
            </div>
            
            <button type="submit" class="btn-submit">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>
