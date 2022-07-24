<?php

use App\models\Role;

if (isset($_SESSION['id']) && isset($_SESSION['nomeUsuario'])) : ?>

    <div id="pageBody">
        <?php require_once 'App/Views/sidebar/index.php' ?>
        <div id="pageBody_content" class="px-4">
            <div class="w-95 h-100 my-4 p-4" style="border-radius: 14px; background-color: #F5F5F7;">
                <h3 class="text-center mb-4">Listagem de Compras</h3>
                <?php
                if ($_SESSION['papelUsuario'] == Role::toString(Role::Comprador)) {
                ?>
                    <div style="width: 100%; display: flex; justify-content: right;">
                        <button id="btnNew" class="btn text-white mb-3" style="border-radius: 14px; height: 50px; background-color: #000;">
                            Nova Compra
                        </button>
                    </div>
                <?php
                }
                ?>
                <div class="table-wrapper-scroll-y my-custom-scrollbar">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Produto</th>
                                <th scope="col">Fornecedor</th>
                                <th scope="col">Comprador</th>
                                <th scope="col">Data Compra</th>
                                <th scope="col">Quantidade</th>
                                <th scope="col">Valor Un.</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($data['purchases'])) {
                                foreach ($data['purchases'] as $purchase) {
                                    $date = date_create($purchase->getData());
                            ?>
                                    <tr>
                                        <td><?= $purchase->getNomeProduto() ?></td>
                                        <td><?= $purchase->getNomeFornecedor() ?></td>
                                        <td><?= $purchase->getNomeFuncionario() ?></td>
                                        <td><?= date_format($date, 'd/m/Y') ?></td>
                                        <td><?= $purchase->getQuantidade() ?></td>
                                        <td>R$<?= $purchase->getValorFormatado() ?></td>
                                        <td class="text-right">
                                            <?php
                                            if (
                                                $_SESSION['papelUsuario'] == Role::toString(Role::Comprador) &&
                                                $purchase->getIdFuncionario() == $_SESSION['id']
                                            ) {
                                            ?>
                                                <button type="button" id="btnEdit" data-id="<?= $purchase->getId() ?>" class="btn btn-outline-primary">Editar</button>
                                                <button type="button" id="btnDelete" data-id="<?= $purchase->getId() ?>" class="btn btn-outline-danger">Remover</button>
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
        <?php include 'App/Views/purchase/new.php' ?>

        <script>
            $(document).ready(function() {
                const setupFieldValues = (data) => {
                    $("#id").val(data.id);
                    $("#product").val(data.idProduto);
                    $("#provider").val(data.idFornecedor);
                    $("#amount").val(data.quantidadeCompra);
                    $("#value").val(data.valorCompra);
                }

                const emptyFields = () => {
                    $("#id").val("");
                    $("#product").val("");
                    $("#provider").val("");
                    $("#amount").val("");
                    $("#value").val("");
                }

                options = {
                    resource: "Compra",
                    path: "<?= BASE_URL . '/purchases' ?>",
                    formId: "#purchaseForm",
                    btnNewId: "#btnNew",
                    btnEditId: "#btnEdit",
                    btnDeleteId: "#btnDelete",
                    modelId: "#modalNewPurchase",
                    setupFieldValues: setupFieldValues,
                    emptyFields: emptyFields
                }

                setupDocument(options);
            });
        </script>
    <?php endif; ?>