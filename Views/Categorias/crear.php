<h2>Nueva Categoria</h2>

<form action="<?= BASE_URL ?>categorias/guardar" method="post">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required>

    <label for="descripcion">Descripcion:</label>
    <textarea id="descripcion" name="descripcion" rows="4"></textarea>

    <button type="submit">Guardar</button>
</form>

<a href="<?= BASE_URL ?>categorias/listar">Volver al listado</a>
