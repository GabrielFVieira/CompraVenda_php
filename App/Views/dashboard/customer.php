<nav class="sticky-top navbar navbar-dark" style="background-color: #F5F5F7;">
    <a routerLink="/" class="navbar-brand d-flex flex-row align-items-center" href="#">
        <img src="<?= URL_IMG ?>logo.svg" height="40px" class="mr-2" />
        <p class="m-0 font-weight-bold" style="color: #000;">CompraVenda</p>
    </a>
    <div class="d-flex flex-row align-items-center">
        É funcionário? Clique em
        <a class="btn btn-primary btn-small navbar-btn ml-2" style="border-radius: 14px; background-color: #000;"
            href="<?= BASE_URL . '/login' ?>">ENTRAR</a>
    </div>
</nav>
<div id="landingPageContent" class="mt-2 p-4">
    <div class="container-fluid mb-4 p-4 d-flex flex-column align-items-center justify-content-center text-white text-center"
        style="min-height: 100px; border-radius: 14px; background-color: #000
;">
        <h1 class="font-weight-bold mb-4">Bem vindo ao app CompraVenda!</h1>
        <h4>
            Veja abaixo os produtos disponíveis para venda no momento<br>
            Caso tenha interesse em algo basta entrar em contato com um dos nossos vendedores
        </h4>
    </div>
    <div class="card-deck">
        <?php
        if (isset($data['products'])) {
            foreach ($data['products'] as $product) {
        ?>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?= $product->getNome() ?></h5>
                <p class="card-text"><?= $product->getDescricao() ?></p>
            </div>
            <div class="card-footer d-flex flex-row align-items-center justify-content-between">
                <small class="text-muted mr-2"><?= $product->getQuantidadeDisponivel() ?> unidades disponíveis</small>
                <small class="font-weight-bold">R$ <?= $product->getPrecoVendaFormatado() ?></small>
            </div>
        </div>
        <?php
            }
        }
        ?>
    </div>
</div>