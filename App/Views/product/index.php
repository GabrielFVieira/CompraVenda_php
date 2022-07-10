<?php
if (isset($_SESSION['id']) && isset($_SESSION['nomeUsuario'])) : ?>

<?php
    if (isset($data['errors'])) { ?>
<div class="w-100">
    <div class="mt-5 alert alert-danger alert-dismissible fade show text-center" role="alert"
        style="position: absolute; top: 0; left: 50%; transform: translateX(-50%);">
        <?php

                foreach ($data['errors'] as $error) {
                    echo $error . "<br>";
                }

                ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
<?php
    }
    ?>

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
                        <td><?= $product['nome_produto'] ?></td>
                        <td><?= $product['descricao'] ?></td>
                        <td>R$<?= $product['preco_compra'] ?></td>
                        <td>R$<?= $product['preco_venda'] ?></td>
                        <td><?= $product['quantidade_disponível'] ?></td>
                        <td><?= $product['nome_categoria'] ?></td>
                        <td><?= $product['liberado_venda'] ?></td>
                        <td>
                            <?php
                                        if ($_SESSION['papelUsuario'] == "Comprador") {
                                        ?>
                            <button type="button" id="btnEdit" data-id="<?= $product['id'] ?>"
                                class="btn btn-outline-primary">Editar</button>
                            <button type="button" id="btnDelete" data-id="<?= $product['id'] ?>"
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

<?php require_once 'App/Views/product/new.php' ?>

<script>
$(document).ready(function() {
    $('#btnNew').on('click', function() {
        $("#name").val("");
        $("#description").val("");
        $("#active").prop("checked", false);
        $("#category").val("");

        $('#productForm').attr('action', '<?= BASE_URL . '/products' ?>')
        $("#modalNewProduct").modal('show');
    })

    $(document).on("click", "#btnEdit", function() {
        var id = $(this).attr("data-id");

        $.ajax({
            url: "<?= BASE_URL . '/products/' ?>" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {

                console.log(data)

                $("#name").val(data.nome_produto);
                $("#description").val(data.descricao);
                $("#category").val(data.id_categoria);

                const active = data.liberado_venda == "S" ? true : false
                $("#active").prop("checked", active);

                $("#description").val(data.descricao);
                $("#sellValue").val(data.preco_venda);

                $('#productForm').attr('action', '<?= BASE_URL . '/products' ?>/' + id)
                $("#modalNewProduct").modal('show');
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
            title: 'Confirma a exclusão do produto?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Confirma Exclusão'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= BASE_URL . '/products/' ?>" + id,
                    type: "DELETE",
                    dataType: "JSON",
                    success: function(data) {
                        Swal.fire({
                            title: "Sucesso",
                            text: "Produto excluido com sucesso",
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