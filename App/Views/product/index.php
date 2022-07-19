<?php
if (isset($_SESSION['id']) && isset($_SESSION['nomeUsuario'])) : ?>
<div class="d-flex">
    <?php require_once 'App/Views/sidebar/index.php' ?>
    <div class="vh-100 p-4 d-flex flex-column">
        <?php
            if ($_SESSION['papelUsuario'] == "Comprador") {
            ?>
        <button id="btnNew" class="btn text-white" style="border-radius: 14px; background-color: #000;">
            Novo produto
        </button>
        <?php
            }
            ?>
        <div class="table-wrapper-scroll-y my-custom-scrollbar">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Descrição</th>
                        <th scope="col">Preço Compra</th>
                        <th scope="col">Preço Venda</th>
                        <th scope="col">Quantidade Disponível</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Liberado</th>
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
                        <td>R$<?= $product->getPrecoCompra() ?></td>
                        <td>R$<?= $product->getPrecoVenda() ?></td>
                        <td><?= $product->getQuantidadeDisponivel() ?></td>
                        <td><?= $product->getNomeCategoria() ?></td>
                        <td><?= $product->getLiberadoVenda() ?></td>
                        <td>
                            <?php
                                        if ($_SESSION['papelUsuario'] == "Comprador") {
                                        ?>
                            <button type="button" id="btnEdit" data-id="<?= $product->getId() ?>"
                                class="btn btn-outline-primary">Editar</button>
                            <button type="button" id="btnDelete" data-id="<?= $product->getId() ?>"
                                class="btn btn-outline-danger">Remover</button>
                            <?php
                                        }
                                        ?>
                        </td>
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

<script src="<?= URL_JS ?>jquery-3.6.0.min.js"></script>
<script src="<?= URL_JS ?>defaultScripts.js"></script>
<?php include 'App/Views/product/new.php' ?>

<script>
$(document).ready(function() {
    const setupFieldValues = (data) => {
        $("#id").val(data.id);
        $("#name").val(data.nome);
        $("#description").val(data.descricao);
        $("#category").val(data.idCategoria);

        const active = data.liberadoVenda == "S" ? true : false
        $("#active").prop("checked", active);
        $("#amount").val(data.quantidadeDisponivel);
        $("#sellValue").val(data.precoVenda);
    }

    const emptyFields = () => {
        $("#id").val("");
        $("#name").val("");
        $("#description").val("");
        $("#category").val("");
        $("#active").prop("checked", false);

        $("#product").val('');
        $("#provider").val('');
        $("#amount").val('');
        $("#sellValue").val('');
    }

    options = {
        resource: "Produto",
        path: "<?= BASE_URL . '/products' ?>",
        formId: "#productForm",
        btnNewId: "#btnNew",
        btnEditId: "#btnEdit",
        btnDeleteId: "#btnDelete",
        modelId: "#modalNewProduct",
        setupFieldValues: setupFieldValues,
        emptyFields: emptyFields
    }

    setupDocument(options);
});
</script>

<?php endif; ?>