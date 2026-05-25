<form action="<?= BASE_URL ?>Clientes/actualizar/<?= $data['id'] ?>" method="post">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" value="<?= $data['nombre'] ?>" required>

    <label for="dni">DNI:</label>
    <input type="text" id="dni" name="dni" value="<?= $data['dni'] ?>" required>

    <label for="telefono">Teléfono:</label>
    <input type="text" id="telefono" name="telefono" value="<?= $data['telefono'] ?>" required>

    <button type="submit">Actualizar</button>
</form>