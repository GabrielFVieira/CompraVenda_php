<?php

use App\models\Role;

if (isset($_SESSION['id']) && isset($_SESSION['nomeUsuario'])) : ?>
    <div id="pageBody">
        <?php require_once 'App/Views/sidebar/index.php' ?>
        <div id="pageBody_content" class="px-4">
            <div class="w-95 h-100 my-4 p-4" style="border-radius: 14px; background-color: #F5F5F7;">
                <h3 class="text-center mb-4">Listagem de Vendas</h3>
                <?php
                if ($_SESSION['papelUsuario'] == Role::toString(Role::Vendedor)) {
                ?>
                    <div style="width: 100%; display: flex; justify-content: right;">
                        <button id="btnNew" class="btn text-white mb-3" style="border-radius: 14px; height: 50px; background-color: #000;">
                            Nova Venda
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
                                <th scope="col">Cliente</th>
                                <th scope="col">Vendedor</th>
                                <th scope="col">Data Venda</th>
                                <th scope="col">Quantidade</th>
                                <th scope="col">Valor Un.</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($data['sales'])) {
                                foreach ($data['sales'] as $sale) {
                                    $date = date_create($sale->getDate());
                            ?>
                                    <tr>
                                        <td><?= $sale->getProductName() ?></td>
                                        <td><?= $sale->getClientName() ?></td>
                                        <td><?= $sale->getEmployeeName() ?></td>
                                        <td><?= date_format($date, 'd/m/Y') ?></td>
                                        <td><?= $sale->getAmount() ?></td>
                                        <td>R$<?= $sale->getFormattedValue() ?></td>
                                        <td class="text-right">
                                            <?php
                                            if (
                                                $_SESSION['papelUsuario'] == Role::toString(Role::Vendedor) &&
                                                $sale->getEmployeeId() == $_SESSION['id']
                                            ) {
                                            ?>
                                                <button type="button" id="btnEdit" data-id="<?= $sale->getId() ?>" class="btn btn-outline-primary">Editar</button>
                                                <button type="button" id="btnDelete" data-id="<?= $sale->getId() ?>" class="btn btn-outline-danger">Remover</button>
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
        <?php include 'App/Views/sale/new.php' ?>

        <script>
            $(document).ready(function() {
                const setupFieldValues = (data) => {
                    $("#id").val(data.id);
                    $("#product").val(data.productId);
                    $("#customer").val(data.clientId);
                    $("#amount").val(data.amount);
                    $("#value").val(data.value);
                }

                const emptyFields = () => {
                    $("#id").val("");
                    $("#product").val("");
                    $("#customer").val("");
                    $("#amount").val("");
                    $("#value").val("");
                }

                options = {
                    resource: "Venda",
                    path: "<?= BASE_URL . '/sales' ?>",
                    formId: "#saleForm",
                    btnNewId: "#btnNew",
                    btnEditId: "#btnEdit",
                    btnDeleteId: "#btnDelete",
                    modelId: "#modalNewSale",
                    setupFieldValues: setupFieldValues,
                    emptyFields: emptyFields
                }

                setupDocument(options);
            });
        </script>
    <?php endif; ?>