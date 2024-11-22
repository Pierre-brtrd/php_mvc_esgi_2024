<header class="sticky-top bg-primary">
    <nav class="navbar navbar-expand-sm navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">MVC App</a>
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavId">
                <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                    <?php if (isset($_SESSION['user']) && in_array('ROLE_ADMIN', $_SESSION['user']['roles'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $_GET['q'] === '' ? 'active' : '' ?>" href="/" aria-current="page">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo str_starts_with($_GET['q'], 'admin/users') ? 'active' : '' ?>" href="/admin/users">Gestion des users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo str_starts_with($_GET['q'], 'admin/posts') ? 'active' : '' ?>" href="/admin/posts">Gestion des posts</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="/" aria-current="page">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Articles</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item">
                        <a href="/logout" class="btn btn-danger">Déconnexion</a>
                    </li>
                    <?php else :?>
                        <li class="nav-item">
                        <a href="/login" class="btn btn-secondary">Connexion</a>
                    </li>
                    <?php endif;?>
                </ul>
            </div>
        </div>
    </nav>
</header>
