<?php
$data = $data ?? [];
$categorias = $data['categorias'] ?? [];
?>

<h2>Nuevo Producto</h2>

<form action="<?= BASE_URL ?>productos/guardar" method="post">
    <label for="categoria_id">Categoria:</label>
    <select id="categoria_id" name="categoria_id" required>
        <option value="">Seleccione una categoria</option>
        <?php foreach ($categorias as $categoria) : ?>
            <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nombre']; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required>

    <label for="precio">Precio:</label>
    <input type="number" id="precio" name="precio" step="0.01" min="0" required>

    <label for="stock">Stock:</label>
    <input type="number" id="stock" name="stock" min="0" required>

    <button type="submit">Guardar</button>
</form>

<a href="<?= BASE_URL ?>productos/listar">Volver al listado</a>
