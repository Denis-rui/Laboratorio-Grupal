<a href="<?= BASE_URL ?>users/crear">Crear Usuario</a>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Clave</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $usuario) : ?>
            <tr>
                <td><?php echo $usuario['id'] ?></td>
                <td><?php echo $usuario['nombre'] ?></td>
                <td><?php echo $usuario['correo'] ?></td>
                <td><?php echo substr($usuario['clave'], 0, 25) . '...'; ?></td>
                <td><?php echo $usuario['estado'] ?></td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>