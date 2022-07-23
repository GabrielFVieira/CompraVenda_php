<nav class="navbar navbar-light bg-light">
    <div class="container">
        <ul class="nav navbar-nav navbar-right">
            <li>
                <div class="btn-nav"><a class="btn btn-primary btn-small navbar-btn"
                        href="<?= BASE_URL . '/login' ?>">ENTRAR</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
<div class="d-flex">
    <div class="vh-100 p-4 d-flex flex-column">
        <div class="table-wrapper-scroll-y my-custom-scrollbar">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Descrição</th>
                        <th scope="col">Preço</th>
                        <th scope="col">Quantidade Disponível</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($data['products'])) {
                        foreach ($data['products'] as $product) {
                    ?>
                    <tr>
                        <td><?= $product->getNome() ?></td>
                        <td><?= $product->getDescricao() ?></td>
                        <td>R$<?= $product->getPrecoVenda() ?></td>
                        <td><?= $product->getQuantidadeDisponivel() ?></td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>