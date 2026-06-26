<?php
$data = $data ?? [];
$categorias = [];

foreach ($data as $producto) {
    if (!isset($producto['categoria_id'], $producto['categoria'])) {
        continue;
    }

    $categorias[(int) $producto['categoria_id']] = $producto['categoria'];
}
?>

<h2>Listado de Productos</h2>
<a href="<?= BASE_URL ?>productos/crear">Nuevo Producto</a>

<section class="productos-filtros" aria-labelledby="titulo-filtros-productos">
    <div class="productos-filtros__header">
        <h3 id="titulo-filtros-productos">Filtros</h3>
        <span id="estado-filtros-productos" class="productos-filtros__estado">Seleccione una categoría</span>
    </div>

    <div class="productos-filtros__categoria">
        <label for="filtro_categoria_id">Categoría</label>
        <select id="filtro_categoria_id" data-productos-categoria>
            <option value="">Todas las categorías</option>
            <?php foreach ($categorias as $categoriaId => $categoriaNombre) : ?>
                <option value="<?php echo $categoriaId; ?>"><?php echo htmlspecialchars($categoriaNombre, ENT_QUOTES, 'UTF-8'); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div
        id="contenedor-filtros-productos"
        class="productos-filtros__dinamicos"
        data-productos-filtros
        aria-live="polite"></div>
</section>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Categoria</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $producto) : ?>
            <tr>
                <td><?php echo $producto['id']; ?></td>
                <td><?php echo $producto['nombre']; ?></td>
                <td><?php echo $producto['categoria']; ?></td>
                <td><?php echo number_format((float)$producto['precio'], 2); ?></td>
                <td><?php echo $producto['stock']; ?></td>
                <td><?php echo ((int)$producto['estado'] === 1) ? 'Activo' : 'Inactivo'; ?></td>
                <td>
                    <a href="<?= BASE_URL ?>productos/ver/<?php echo $producto['id']; ?>">Ver</a>
                    <a href="<?= BASE_URL ?>productos/editar/<?php echo $producto['id']; ?>">Editar</a>
                    <a href="<?= BASE_URL ?>productos/eliminar/<?php echo $producto['id']; ?>" data-confirm="¿Desactivar producto?">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>