<div class="nav" id="sidebar-wrapper">
    <div class="row sidenav">
        <?php foreach (config("Navigation")->items as $key => $menu) : if(!isset($menu['role']) || (isset($menu['role'], $userRole) && $menu['role'] === $userRole)): ?>
            <?php if (isset($menu['submenu'])) : ?>
                <a class="dropdown-btn <?= (isset($main) && $main == $key) ? 'active' : '' ?>" href="<?= $menu['href'] ?? 'javascript:void(0)' ?>"><?= $menu['icon'] ?? '' ?> <?= lang("Navigation.{$menu['label']}") ?>
                    <i class="nav-icon-right bi bi-caret-<?= (isset($main) && $main == $key) ? 'up' : 'down' ?>"></i>
                </a>

                <div class="dropdown-container" <?= (isset($main) && $main == $key) ? 'style="display: block;"' : '' ?>>
                    <div class="">
                        <?php foreach ($menu['submenu'] as $subkey => $submenu) : if(!isset($submenu['role']) || (isset($submenu['role'], $userRole) && $submenu['role'] === $userRole)): ?>
                            <a class="nav-menu-item <?= (isset($main) && $main == $key && isset($sub) && $sub == $subkey) ? 'active' : '' ?>" href="<?= $submenu['href'] ?? 'javascript:void(0)' ?>"><?= $submenu['icon'] ?? '' ?> <?= lang("Navigation.{$submenu['label']}") ?></a>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
            <?php else : ?>
                <a class="nav-menu-item <?= (isset($main) && $main == $key) ? 'active' : '' ?>" href="<?= $menu['href'] ?? 'javascript:void(0)' ?>"><?= $menu['icon'] ?? '' ?> <?= lang("Navigation.{$menu['label']}") ?></a>
            <?php endif; ?>
        <?php endif; endforeach; ?>
    </div>
</div>