<?php
if (isset($_SESSION['id']) && isset($_SESSION['nomeUsuario'])) : ?>

<div class="d-flex">
    <?php require_once 'App/Views/sidebar/index.php' ?>
    <div class="vh-100 p-4 d-flex flex-column">
        <?php
            if ($_SESSION['papelUsuario'] == "Comprador") {
            ?>
        <button id="btnNew" class="btn text-white" style="border-radius: 14px; background-color: #000;">
            Nova Compra
        </button>
        <?php
            }
            ?>
        <div class="table-wrapper-scroll-y my-custom-scrollbar">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Produto</th>
                        <th scope="col">Fornecedor</th>
                        <th scope="col">Vendedor</th>
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
                                $date = date_create($purchase['data_compra']);
                        ?>
                    <tr>
                        <td><?= $purchase['nome_produto'] ?></td>
                        <td><?= $purchase['fornecedor'] ?></td>
                        <td><?= $purchase['nome_funcionario'] ?></td>
                        <td><?= date_format($date, 'd/m/Y') ?></td>
                        <td><?= $purchase['quantidade_compra'] ?></td>
                        <td>R$<?= $purchase['valor_compra'] ?></td>
                        <td>
                            <?php
                                        if ($purchase['id_funcionario'] == $_SESSION['id']) {
                                        ?>
                            <button type="button" id="btnEdit" data-id="<?= $purchase['id'] ?>"
                                class="btn btn-outline-primary">Editar</button>
                            <button type="button" id="btnDelete" data-id="<?= $purchase['id'] ?>"
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

<?php require_once 'App/Views/purchase/new.php' ?>

<script>
$(document).ready(function() {
    $('#btnNew').on('click', function() {
        $("#product").val("");
        $("#provider").val("");
        $("#amount").val("");
        $("#value").val("");

        $('#purchaseForm').attr('action', '<?= BASE_URL . '/purchases' ?>')
        $("#modalNewPurchase").modal('show');
    })

    $(document).on("click", "#btnEdit", function() {
        var id = $(this).attr("data-id");

        $.ajax({
            url: "<?= BASE_URL . '/purchases/' ?>" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $("#product").val(data.id_produto);
                $("#provider").val(data.id_fornecedor);
                $("#amount").val(data.quantidade_compra);
                $("#value").val(data.valor_compra);

                $('#purchaseForm').attr('action', '<?= BASE_URL . '/purchases' ?>/' + id)
                $("#modalNewPurchase").modal('show');
            },
            error: function(data) {
                response = data.responseJSON;

                Swal.fire({
                    title: "Erro",
                    text: response.error ? response.error : "Erro Inesperado",
                    icon: "error",
                });

                $("#product").val('');
                $("#provider").val('');
                $("#amount").val('');
                $("#value").val('');

                $("#modalNewPurchase").modal('hide');
            }
        });
    })

    $(document).on("click", "#btnDelete", function() {
        var id = $(this).attr("data-id");

        Swal.fire({
            title: 'Confirma a exclusão da compra?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Confirma Exclusão'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= BASE_URL . '/purchases/' ?>" + id,
                    type: "DELETE",
                    dataType: "JSON",
                    success: function(data) {
                        Swal.fire({
                            title: "Sucesso",
                            text: "Compra excluida com sucesso",
                            icon: "success",
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(data) {
                        Swal.fire({
                            title: "Erro",
                            text: "Erro Inesperado",
                            icon: "error",
                        });
                    }
                });
            }
        })
    })
});
</script>

<?php endif; ?>