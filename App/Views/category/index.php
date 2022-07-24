<?php

use App\models\Role;

if (isset($_SESSION['id']) && isset($_SESSION['nomeUsuario'])) : ?>
    <div id="pageBody">
        <?php require_once 'App/Views/sidebar/index.php' ?>
        <div id="pageBody_content" class="px-4">
            <div class="w-95 h-100 my-4 p-4" style="border-radius: 14px; background-color: #F5F5F7;">
                <h3 class="text-center mb-4">Listagem de Categorias</h3>
                <?php
                if ($_SESSION['papelUsuario'] == Role::toString(Role::Comprador)) {
                ?>
                    <div style="width: 100%; display: flex; justify-content: right;">
                        <button id="btnNew" class="btn text-white mb-3" style="border-radius: 14px; height: 50px; background-color: #000;">
                            Nova Categoria
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
                                        <td class="text-right">
                                            <?php
                                            if ($_SESSION['papelUsuario'] == Role::toString(Role::Comprador)) {
                                            ?>
                                                <button type="button" id="btnEdit" data-id="<?= $category->getId() ?>" class="btn btn-outline-primary">Editar</button>
                                                <button type="button" id="btnDelete" data-id="<?= $category->getId() ?>" class="btn btn-outline-danger">Remover</button>
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

        <div class="modal fade" id="modalNewCategory" tabindex="-1" role="dialog" aria-labelledby="modalNewCategoryLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalNewCategoryLabel">Categoria</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="categoryForm">
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
        <script src="<?= URL_JS ?>defaultScripts.js"></script>
        <script>
            $(document).ready(function() {
                const setupFieldValues = (data) => {
                    $("#id").val(data.id);
                    $("#name").val(data.nome);
                }

                const emptyFields = () => {
                    $("#id").val("");
                    $("#name").val("");
                }

                options = {
                    resource: "Categoria",
                    path: "<?= BASE_URL . '/categories' ?>",
                    formId: "#categoryForm",
                    btnNewId: "#btnNew",
                    btnEditId: "#btnEdit",
                    btnDeleteId: "#btnDelete",
                    modelId: "#modalNewCategory",
                    setupFieldValues: setupFieldValues,
                    emptyFields: emptyFields
                }

                $("#categoryForm").validate({
                    errorPlacement: function(label, element) {
                        label.addClass('error-msg text-danger');
                        label.insertAfter(element);
                    },
                    wrapper: 'span',
                    rules: {
                        name: {
                            required: true,
                            maxlength: 50,
                        },
                    },
                });

                setupDocument(options);
            });
        </script>
    <?php endif; ?>