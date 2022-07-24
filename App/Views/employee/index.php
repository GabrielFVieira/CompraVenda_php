<?php

use App\models\Role;

if (isset($_SESSION['id']) && isset($_SESSION['nomeUsuario'])) : ?>
    <div id="layoutSidenav">
        <?php require_once 'App/Views/sidebar/index.php' ?>
        <div id="layoutSidenav_content" class="px-4">
            <div class="w-95 h-100 my-4 p-4" style="border-radius: 14px; background-color: #F5F5F7;">
                <h3 class="text-center mb-4">Listagem de Fornecedores</h3>
                <?php
                if ($_SESSION['papelUsuario'] == Role::toString(Role::Administrador)) {
                ?>
                    <div style="width: 100%; display: flex; justify-content: right;">
                        <button id="btnNew" class="btn text-white mb-3" style="border-radius: 14px; height: 50px; background-color: #000;">
                            Novo Funcionário
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
                                <th scope="col">CPF</th>
                                <th scope="col">Papel</th>
                                <th scope="col">Senha ativada?</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($data['employees'])) {
                                foreach ($data['employees'] as $employee) {
                            ?>
                                    <tr>
                                        <td><?= $employee->getNome() ?></td>
                                        <td><?= $employee->getCPF() ?></td>
                                        <td><?= $employee->getPapelString() ?></td>
                                        <td><?= $employee->isSenhaDefault() ? 'Não' : 'Sim' ?></td>
                                        <td class="text-right">
                                            <?php
                                            if ($_SESSION['papelUsuario'] == Role::toString(Role::Administrador)) {
                                            ?>
                                                <button type="button" id="btnEdit" data-id="<?= $employee->getId() ?>" class="btn btn-outline-primary">Editar</button>
                                                <button type="button" id="btnDelete" data-id="<?= $employee->getId() ?>" class="btn btn-outline-danger">Remover</button>
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
        <?php include 'App/Views/employee/new.php' ?>
        <script>
            $(document).ready(function() {
                const setupFieldValues = (data) => {
                    $("#id").val(data.id).change();
                    $("#name").val(data.nome);
                    $("#cpf").val(data.cpf);
                    $("#role").val(data.papel);
                }

                const emptyFields = () => {
                    $("#id").val("").change();
                    $("#name").val("");
                    $("#cpf").val("");
                    $("#role").val("");
                }

                options = {
                    resource: "Funcionário",
                    path: "<?= BASE_URL . '/employees' ?>",
                    formId: "#employeeForm",
                    btnNewId: "#btnNew",
                    btnEditId: "#btnEdit",
                    btnDeleteId: "#btnDelete",
                    modelId: "#modalNewEmployee",
                    setupFieldValues: setupFieldValues,
                    emptyFields: emptyFields
                }

                setupDocument(options);
            });
        </script>

    <?php endif; ?>