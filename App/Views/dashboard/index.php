<h1>Dashboard</h1>
<?php
// listando os artigos
if (isset($_SESSION['id']) && isset($_SESSION['nomeUsuario'])) : ?>


    <div class="row">
        <div class="card mt-3 border-0">
            <div class="card-body px-2">
                <i class="fas fa-user"></i> <strong>Nome</strong>
                <p class="text-muted"><?= htmlentities(utf8_encode($_SESSION['nomeUsuario'])) ?></p>
                <i class="fas fa-at"></i><strong> Email</strong>
                <p class="text-muted"><?= htmlentities(utf8_encode($_SESSION['emailUsuario'])) ?></p>

            </div>
        </div>
        <div class="card mt-3 border-0">
            <div class="card-body px-2">
            <a href="#" class="btn btn-outline-success">Artigos</a>
            </div>
        </div>
        <div class="card mt-3 border-0">
            <div class="card-body px-2">
            <a href="<?= url('painelusuario') ?>" class="btn btn-outline-primary">Usuarios</a>
            </div>
        </div>

    </div>

<?php endif; ?>