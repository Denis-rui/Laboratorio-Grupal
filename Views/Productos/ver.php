<?php $data = $data ?? []; ?>

<h2>Detalle de Producto</h2>

<p><strong>ID:</strong> <?php echo $data['id'] ?? ''; ?></p>
<p><strong>Nombre:</strong> <?php echo $data['nombre'] ?? ''; ?></p>
<p><strong>Categoria:</strong> <?php echo $data['categoria'] ?? ''; ?></p>
<p><strong>Precio:</strong> <?php echo isset($data['precio']) ? number_format((float)$data['precio'], 2) : ''; ?></p>
<p><strong>Stock:</strong> <?php echo $data['stock'] ?? ''; ?></p>
<p><strong>Estado:</strong> <?php echo isset($data['estado']) && (int)$data['estado'] === 1 ? 'Activo' : 'Inactivo'; ?></p>

<a href="<?= BASE_URL ?>productos/listar">Volver al listado</a>
