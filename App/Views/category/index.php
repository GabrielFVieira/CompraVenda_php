<?php
if (isset($_SESSION['id']) && isset($_SESSION['nomeUsuario'])) : ?>
<div class="d-flex">
    <?php require_once 'App/Views/sidebar/index.php' ?>
    <div class="vh-100 p-4 d-flex flex-column">
        <?php
            if ($_SESSION['papelUsuario'] == "Comprador") {
            ?>
        <button id="btnNew" class="btn text-white" style="border-radius: 14px; background-color: #000;">
            Nova categoria
        </button>
        <?php
            }
            ?>
        <div class="table-wrapper-scroll-y my-custom-scrollbar">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if (isset($data['categories'])) {
                            foreach ($data['categories'] as $category) {
                        ?>
                    <tr>
                        <td><?= $category->getNome() ?></td>
                        <td>
                            <?php
                                        if ($_SESSION['papelUsuario'] == "Comprador") {
                                        ?>
                            <button type="button" id="btnEdit" data-id="<?= $category->getId() ?>"
                                class="btn btn-outline-primary">Editar</button>
                            <button type="button" id="btnDelete" data-id="<?= $category->getId() ?>"
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

<div class="modal fade" id="modalNewCategory" tabindex="-1" role="dialog" aria-labelledby="modalNewCategoryLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNewCategoryLabel">Categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= BASE_URL . '/categories' ?>" id="categoryForm" method="POST">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="name">Nome:</label>
                        <input type="text" class="form-control" id="name" name="name" maxlength="50">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btSalvarInclusao" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="<?= URL_JS ?>jquery-3.6.0.min.js"></script>
<script src="<?= URL_JS ?>popper.min.js"></script>
<script src="<?= URL_JS ?>jquery.mask.js"></script>
<script src="<?= URL_JS ?>jquery.validate.min.js"></script>
<script src="<?= URL_JS ?>additional-methods.min.js"></script>
<script src="<?= URL_JS ?>localization/messages_pt_BR.js"></script>
<script>
const handleError = (data) => {
    response = data.responseJSON;

    message = "";
    if (response && response.errors) {
        response.errors.forEach(err => {
            message += err + "<br>";
        });
    } else {
        message = "Erro Inesperado";
    }

    Swal.fire({
        title: "Erro",
        html: message,
        icon: "error",
    });

    $("#id").val("");
    $("#name").val("");
    $("#modalNewCategory").modal('hide');
}

$(document).ready(function() {
    const validator = $('#categoryForm').validate({
        errorPlacement: function(label, element) {
            label.addClass('error-msg text-danger');
            label.insertAfter(element);
        },
        wrapper: 'span',
        rules: {
            name: {
                required: true,
                maxlength: 50
            },
        }
    });

    $("#categoryForm").submit(function(e) {
        e.preventDefault();

        var form = $(this);
        var actionUrl = form.attr('action');
        var id = $("#id").val();

        $.ajax({
            type: id ? "PUT" : "POST",
            url: id ? (actionUrl + "/" + id) : actionUrl,
            data: form.serialize(),
            dataType: "JSON",
            success: function(data) {
                Swal.fire({
                    title: "Sucesso",
                    text: "Categoria " + (id ? "atualizada" : "cadastrada") +
                        " com sucesso",
                    icon: "success",
                }).then(() => {
                    location.reload();
                });
            },
            error: function(data) {
                handleError(data)
            }
        });
    });

    $('#btnNew').on('click', function() {
        $("#id").val("");
        $("#name").val("");
        $("#modalNewCategory").modal('show');
    })

    $(document).on("click", "#btnEdit", function() {
        var id = $(this).attr("data-id");

        $.ajax({
            url: "<?= BASE_URL . '/categories' ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $("#id").val(id);
                $("#name").val(data.nome)
                $("#modalNewCategory").modal('show');
            },
            error: function(data) {
                handleError(data)
            }
        });
    })

    $(document).on("click", "#btnDelete", function() {
        var id = $(this).attr("data-id");

        Swal.fire({
            title: 'Confirma a exclusão da categoria?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Confirma Exclusão'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= BASE_URL . '/categories/' ?>" + id,
                    type: "DELETE",
                    dataType: "JSON",
                    success: function(data) {
                        Swal.fire({
                            title: "Sucesso",
                            text: "Categoria excluida com sucesso",
                            icon: "success",
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(data) {
                        handleError(data)
                    }
                });
            }
        })
    })
});
</script>
<?php endif; ?>