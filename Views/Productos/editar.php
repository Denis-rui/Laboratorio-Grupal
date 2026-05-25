<?php
$data = $data ?? [];
$producto = $data['producto'] ?? [];
$categorias = $data['categorias'] ?? [];
?>

<h2>Editar Producto</h2>

<form action="<?= BASE_URL ?>productos/actualizar" method="post">
    <input type="hidden" name="id" value="<?php echo $producto['id'] ?? ''; ?>">

    <label for="categoria_id">Categoria:</label>
    <select id="categoria_id" name="categoria_id" required>
        <option value="">Seleccione una categoria</option>
        <?php foreach ($categorias as $categoria) : ?>
            <option value="<?php echo $categoria['id']; ?>" <?php echo ((int)$categoria['id'] === (int)($producto['categoria_id'] ?? 0)) ? 'selected' : ''; ?>>
                <?php echo $categoria['nombre']; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo $producto['nombre'] ?? ''; ?>" required>

    <label for="precio">Precio:</label>
    <input type="number" id="precio" name="precio" step="0.01" min="0" value="<?php echo $producto['precio'] ?? ''; ?>" required>

    <label for="stock">Stock:</label>
    <input type="number" id="stock" name="stock" min="0" value="<?php echo $producto['stock'] ?? ''; ?>" required>

    <button type="submit">Actualizar</button>
</form>

<a href="<?= BASE_URL ?>productos/listar">Volver al listado</a>
