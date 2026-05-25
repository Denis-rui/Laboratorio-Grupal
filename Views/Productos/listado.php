<?php $data = $data ?? []; ?>

<h2>Listado de Productos</h2>
<a href="<?= BASE_URL ?>productos/crear">Nuevo Producto</a>
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
                    <a href="<?= BASE_URL ?>productos/eliminar/<?php echo $producto['id']; ?>" onclick="return confirm('¿Desactivar producto?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
