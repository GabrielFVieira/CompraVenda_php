<?php
if (isset($_SESSION['id']) && isset($_SESSION['nomeUsuario'])) : ?>

    <div id="pageBody">
        <?php require_once 'App/Views/sidebar/index.php' ?>
        <div id="pageBody_content" class="px-4">
            <div class="container-fluid h-100">
                <div class="row h-100">
                    <div class="col-lg-7 px-4 py-4 d-flex flex-column">
                        <?php include 'App/Views/dashboard/header.php' ?>
                        <div class="container-fluid my-4" style="min-height: 80px">
                            <div class="row h-100">
                                <div class="col-6 pl-0">
                                    <button id="btnNewPurchase" class="btn btn-block text-white h-100" style="border-radius: 14px; background-color: #000;">
                                        <h2 class="m-0 font-weight-bold">Nova Compra</h2>
                                        </abutton>
                                </div>
                                <div class="col-3">
                                    <a href="<?= BASE_URL ?>/categories" class="btn btn-block text-white d-flex justify-content-center align-items-center h-100" role="button" aria-pressed="true" style="border-radius: 14px; background-color: #000;">
                                        <h4 class="m-0">Categorias</h4>
                                    </a>
                                </div>
                                <div class="col-3 pr-0">
                                    <a href="<?= BASE_URL ?>/products" class="btn btn-block text-white d-flex justify-content-center align-items-center h-100" role="button" aria-pressed="true" style="border-radius: 14px; background-color: #000;">
                                        <h4 class="m-0">Produtos</h4>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card flex-grow-1" style="border-radius: 14px; border-width:0; background-color: #F5F5F7;">
                            <div class="card-body">
                                <h3 class="text-center">Suas últimas compras</h3>
                                <div class="table-responsive">
                                    <table id="purchasesTable" class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">Produto</th>
                                                <th scope="col">Data</th>
                                                <th scope="col">Quantidade</th>
                                                <th scope="col">Preço</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 px-4 py-4 d-flex flex-column">
                        <div class="p-4" style="height: 100%; border-radius: 14px; background-color: #F5F5F7;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="<?= URL_JS ?>jquery-3.6.0.min.js"></script>
    <script src="<?= URL_JS ?>dataTables/datatables.min.js"></script>
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
                btnNewId: "#btnNewPurchase",
                btnEditId: "#btnEdit",
                btnDeleteId: "#btnDelete",
                modelId: "#modalNewPurchase",
                setupFieldValues: setupFieldValues,
                emptyFields: emptyFields,
                redirectTo: "<?= BASE_URL . '/purchases' ?>"
            }

            setupDocument(options);

            $.ajax({
                type: 'GET',
                url: '<?= BASE_URL . '/my/purchases' ?>',
                success: function(data) {
                    parsedData = JSON.parse(data);

                    const formatter = new Intl.NumberFormat('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    });

                    const table = $("#purchasesTable tbody");
                    $.each(parsedData, function(idx, elem) {
                        table.append(
                            "<tr><td>" + elem.nomeProduto +
                            "</td><td>" + elem.dataCompra +
                            "</td><td>" + elem.quantidadeCompra +
                            "</td><td>" + formatter.format(parseFloat(elem.valorCompra)) +
                            "</td></tr>");
                    });

                    $('#purchasesTable').DataTable({
                        responsive: true,
                        info: false,
                        paging: false,
                        searching: false,
                        ordering: false
                    });
                    $('.dataTables_length').addClass('bs-select');
                }
            });
        });
    </script>

<?php endif; ?>