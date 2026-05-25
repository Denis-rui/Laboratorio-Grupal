<form action="<?= BASE_URL ?>Clientes/guardar" method="post">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required>
    <label for="dni">DNI:</label>
    <input type="text" id="dni" name="dni" required>
    <label for="telefono">Teléfono:</label>
    <input type="text" id="telefono" name="telefono" required>
    <button type="submit">Guardar</button>
</form>