<a href="<?= BASE_URL ?>/users/crear">Crear Usuario</a>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Correo</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $usuario) : ?>
            <tr>
                <td><?php echo $usuario['id'] ?></td>
                <td><?php echo $usuario['correo'] ?></td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>