<a href="<?= BASE_URL ?>clientes/crear">Crear Cliente</a>
<a href="<?= BASE_URL ?>clientes/editar">Editar Cliente</a>
<a href="<?= BASE_URL ?>clientes/eliminar">Eliminar Cliente</a>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>DNI</th>
            <th>Teléfono</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $clientes) : ?>
            <tr>
                <td><?php echo $clientes['id'] ?></td>
                <td><?php echo $clientes['nombre'] ?></td>
                <td><?php echo $clientes['dni'] ?></td>
                <td><?php echo $clientes['telefono'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>