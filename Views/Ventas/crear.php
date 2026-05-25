<?php
$data = $data ?? [];
$clientes = $data['clientes'] ?? [];
$productos = $data['productos'] ?? [];
?>

<form action="<?= BASE_URL ?>/ventas/guardar" method="post">
    <label for="cliente_id">Cliente:</label>
    <select id="cliente_id" name="cliente_id" required>
        <option value="">Seleccione un cliente</option>
        <?php foreach ($clientes as $cliente) : ?>
            <option value="<?php echo $cliente['id']; ?>"><?php echo $cliente['nombre']; ?></option>
        <?php endforeach; ?>
    </select>

    <label for="producto_id">Producto:</label>
    <select id="producto_id" name="producto_id" required>
        <option value="">Seleccione un producto</option>
        <?php foreach ($productos as $producto) : ?>
            <option value="<?php echo $producto['id']; ?>">
                <?php echo $producto['nombre']; ?> - S/ <?php echo number_format((float)$producto['precio'], 2); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="cantidad">Cantidad:</label>
    <input type="number" min="1" id="cantidad" name="cantidad" required>

    <p>El total se calcula automaticamente segun el producto y cantidad.</p>

    <button type="submit">Guardar</button>
</form>
