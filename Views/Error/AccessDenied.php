<?php $data = $data ?? []; ?>

<section>
    <h1>Acceso denegado</h1>
    <p><?= htmlspecialchars($data['message'] ?? 'No tienes permiso para acceder a esta sección', ENT_QUOTES, 'UTF-8') ?></p>

    <button type="button" onclick="history.back()">Regresar</button>
</section>