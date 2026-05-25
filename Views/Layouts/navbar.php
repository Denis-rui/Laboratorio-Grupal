<nav>
    <ul>
        <li><a href="<?= BASE_URL ?>">Inicio</a></li>
        <?php foreach (($menusNavbar ?? []) as $menu): ?>
            <?php
            $menuUrl = trim($menu['url'] ?? '');
            if ($menuUrl === '') {
                continue;
            }
            $href = BASE_URL . ltrim($menuUrl, '/');
            ?>
            <li>
                <a href="<?= $href ?>"><?= htmlspecialchars($menu['titulo'] ?? 'Menu', ENT_QUOTES, 'UTF-8') ?></a>
            </li>
        <?php endforeach; ?>
        <?php if (isset($_SESSION['user_data'])): ?>
            <li><a href="<?= BASE_URL ?>Login/logout">Cerrar sesion</a></li>
        <?php endif; ?>
    </ul>
</nav>