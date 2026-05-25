<a href="<?= BASE_URL ?>Clientes/crear">Crear Cliente</a>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>DNI</th>
            <th>Teléfono</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $cliente) : ?>
            <tr>
                <td><?= $cliente['id'] ?></td>
                <td><?= $cliente['nombre'] ?></td>
                <td><?= $cliente['dni'] ?></td>
                <td><?= $cliente['telefono'] ?></td>
                <td>
                    <a href="<?= BASE_URL ?>Clientes/editar/<?= $cliente['id'] ?>">Editar</a>

                    <?php if (in_array('eliminar', $_SESSION['permisos'])) : ?>
                        <a href="<?= BASE_URL ?>Clientes/eliminar/<?= $cliente['id'] ?>">Eliminar</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>