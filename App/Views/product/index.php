<?php

use App\models\Role;

if (isset($_SESSION['id']) && isset($_SESSION['nomeUsuario'])) : ?>
    <div id="pageBody">
        <?php require_once 'App/Views/sidebar/index.php' ?>
        <div id="pageBody_content" class="px-4">
            <div class="w-95 h-100 my-4 p-4" style="border-radius: 14px; background-color: #F5F5F7;">
                <h3 class="text-center mb-4">Listagem de Produtos</h3>
                <?php
                if ($_SESSION['papelUsuario'] == Role::toString(Role::Comprador)) {
                ?>
                    <div style="width: 100%; display: flex; justify-content: right;">
                        <button id="btnNew" class="btn text-white mb-3" style="border-radius: 14px; height: 50px; background-color: #000;">
                            Novo produto
                        </button>
                    </div>
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
                                        <td>R$<?= $product->getPrecoCompraFormatado() ?></td>
                                        <td>R$<?= $product->getPrecoVendaFormatado() ?></td>
                                        <td><?= $product->getQuantidadeDisponivel() ?></td>
                                        <td><?= $product->getNomeCategoria() ?></td>
                                        <td><?= $product->getLiberadoVenda() ?></td>
                                        <td class="text-right">
                                            <?php
                                            if ($_SESSION['papelUsuario'] == Role::toString(Role::Comprador)) {
                                            ?>
                                                <button type="button" id="btnEdit" data-id="<?= $product->getId() ?>" class="btn btn-outline-primary">Editar</button>
                                                <button type="button" id="btnDelete" data-id="<?= $product->getId() ?>" class="btn btn-outline-danger">Remover</button>
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