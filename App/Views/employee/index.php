<?php

use App\models\Role;

if (isset($_SESSION['id']) && isset($_SESSION['nomeUsuario'])) : ?>
<div class="d-flex">
    <?php require_once 'App/Views/sidebar/index.php' ?>
    <div class="vh-100 p-4 d-flex flex-column">
        <?php
            if ($_SESSION['papelUsuario'] == Role::toString(Role::Administrador)) {
            ?>
        <button id="btnNew" class="btn text-white" style="border-radius: 14px; background-color: #000;">
            Novo funcionário
        </button>
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
                        <td>
                            <?php
                                        if ($_SESSION['papelUsuario'] == Role::toString(Role::Administrador)) {
                                        ?>
                            <button type="button" id="btnEdit" data-id="<?= $employee->getId() ?>"
                                class="btn btn-outline-primary">Editar</button>
                            <button type="button" id="btnDelete" data-id="<?= $employee->getId() ?>"
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