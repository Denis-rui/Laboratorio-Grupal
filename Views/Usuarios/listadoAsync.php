<a href="<?= BASE_URL ?>usuarios/crear">Crear Usuario</a>
<table border="1" id="usuarios-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Clave</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody id="tblUsuario_body">

    </tbody>
</table>

<script id="listado-usuarios-script" src="<?= BASE_URL ?>public/js/listado_async.js" data-base-url="<?= BASE_URL ?>"></script>
