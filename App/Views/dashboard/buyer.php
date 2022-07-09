<?php
// listando os artigos
if (isset($_SESSION['id']) && isset($_SESSION['nomeUsuario'])) : ?>

<div class="d-flex">
    <?php require_once 'App/Views/sidebar/index.php' ?>
    <div class="container-fluid p-4 mr-5">
        <div class="row m-0 w-100 h-100">
            <div class="col-7 px-5 d-flex flex-column">
                <div class="w-100 text-left p-5" style="height: 190px; border-radius: 14px; background-color: #F5F5F7;">
                    <h1 class="font-weight-bold">Olá <?= $_SESSION['nomeUsuario'] ?>!</h1>
                    <h4>Tenha um bom dia de trabalho!</h4>
                </div>
                <div class="row" style="height: 130px;">
                    <div class="col-6 py-4">
                        <a href="<?= BASE_URL ?>/sales/new"
                            class="btn btn-block text-white d-flex justify-content-center align-items-center h-100"
                            role="button" aria-pressed="true" style="border-radius: 14px; background-color: #000;">
                            <h2 class="font-weight-bold">Nova Compra</h2>
                        </a>
                    </div>
                    <div class="col-3 py-4">
                        <a href="<?= BASE_URL ?>/categories"
                            class="btn btn-block text-white d-flex justify-content-center align-items-center h-100"
                            role="button" aria-pressed="true" style="border-radius: 14px; background-color: #000;">
                            <p class="m-0 font-weight-bold">Categorias</p>
                        </a>
                    </div>
                    <div class="col-3 py-4">
                        <a href="<?= BASE_URL ?>/providers"
                            class="btn btn-block text-white d-flex justify-content-center align-items-center h-100"
                            role="button" aria-pressed="true" style="border-radius: 14px; background-color: #000;">
                            <p class="m-0 font-weight-bold">Fornecedores</p>
                        </a>
                    </div>
                </div>
                <div class="w-100 text-left flex-grow-1"
                    style="height: 190px; border-radius: 14px; background-color: #F5F5F7;">
                </div>
            </div>
            <div class="col-5 bg-info">
                2
            </div>
        </div>
    </div>
</div>
<?php endif; ?>