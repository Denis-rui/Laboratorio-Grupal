<?php $data = $data ?? []; ?>

<h2>Editar Categoria</h2>

<form action="<?= BASE_URL ?>categorias/actualizar" method="post">
    <input type="hidden" name="id" value="<?php echo $data['id'] ?? ''; ?>">

    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo $data['nombre'] ?? ''; ?>" required>

    <label for="descripcion">Descripcion:</label>
    <textarea id="descripcion" name="descripcion" rows="4"><?php echo $data['descripcion'] ?? ''; ?></textarea>

    <button type="submit">Actualizar</button>
</form>

<a href="<?= BASE_URL ?>categorias/listar">Volver al listado</a>
