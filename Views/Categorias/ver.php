<?php $data = $data ?? []; ?>

<h2>Detalle de Categoria</h2>

<p><strong>ID:</strong> <?php echo $data['id'] ?? ''; ?></p>
<p><strong>Nombre:</strong> <?php echo $data['nombre'] ?? ''; ?></p>
<p><strong>Descripcion:</strong> <?php echo $data['descripcion'] ?? ''; ?></p>

<a href="<?= BASE_URL ?>categorias/listar">Volver al listado</a>
