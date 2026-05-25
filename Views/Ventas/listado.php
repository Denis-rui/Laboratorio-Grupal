<a href="<?= BASE_URL ?>/ventas/crear">Crear Venta</a>
<?php $data = $data ?? []; ?>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Fecha</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $venta) : ?>
            <tr>
                <td><?php echo $venta['id']; ?></td>
                <td><?php echo $venta['cliente']; ?></td>
                <td><?php echo $venta['producto']; ?></td>
                <td><?php echo $venta['cantidad']; ?></td>
                <td><?php echo number_format((float)$venta['precio_unitario'], 2); ?></td>
                <td><?php echo $venta['fecha']; ?></td>
                <td><?php echo number_format((float)$venta['total'], 2); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
