<form action="<?= BASE_URL ?>/users/guardar" method="post">
    <label for="correo">Correo:</label>
    <input type="email" id="correo" name="correo" required>
    <label for="clave">Clave:</label>
    <input type="password" id="clave" name="clave" required>
    <button type="submit">Guardar</button>
</form>