<?php $data = $data ?? []; ?>

<h2>Listado de Categorias</h2>
<a href="<?= BASE_URL ?>categorias/crear">Nueva Categoria</a>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripcion</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $categoria) : ?>
            <tr>
                <td><?php echo $categoria['id']; ?></td>
                <td><?php echo $categoria['nombre']; ?></td>
                <td><?php echo $categoria['descripcion']; ?></td>
                <td>
                    <a href="<?= BASE_URL ?>categorias/ver/<?php echo $categoria['id']; ?>">Ver</a>
                    <a href="<?= BASE_URL ?>categorias/editar/<?php echo $categoria['id']; ?>">Editar</a>
                    <a href="<?= BASE_URL ?>categorias/eliminar/<?php echo $categoria['id']; ?>" data-confirm="¿Eliminar categoria?">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
