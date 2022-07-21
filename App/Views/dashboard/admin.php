<?php
if (isset($_SESSION['id']) && isset($_SESSION['nomeUsuario'])) : ?>

<div class="d-flex">
    <?php require_once 'App/Views/sidebar/index.php' ?>
    <div class="container-fluid p-4 mr-5">
        <div class="row m-0 w-100 h-100 gx-5">
            <div class="col-7 px-5 d-flex flex-column">
                <div class="w-100 d-flex px-5"
                    style="height: 25%; min-height:200px; border-radius: 14px; background-color: #F5F5F7;">
                    <div class="h-100 py-5 d-flex flex-column justify-content-center text-left">
                        <h1 class="font-weight-bold">Olá <?= $_SESSION['nomeUsuario'] ?>!</h1>
                        <h4>Tenha um bom dia de trabalho!</h4>
                    </div>
                    <div class="h-100 mx-auto pl-1 d-flex align-items-end">
                        <img src="<?= URL_IMG ?>char.svg" style="max-width:100%; max-height:100%;" />
                    </div>
                </div>
                <div class="container">
                    <div class="row" style="height:150px;">
                        <div class="col-6 py-4">
                            <button id="btnNewEmployee" class="btn btn-block text-white h-100"
                                style="border-radius: 14px; background-color: #000;">
                                <h2 class="font-weight-bold">Nova Compra</h2>
                                </abutton>
                        </div>
                        <div class="col-3 py-4">
                            <a href="<?= BASE_URL ?>/employees"
                                class="btn btn-block text-white d-flex justify-content-center align-items-center h-100"
                                role="button" aria-pressed="true" style="border-radius: 14px; background-color: #000;">
                                <p class="m-0 font-weight-bold">Funcionários</p>
                            </a>
                        </div>
                        <div class="col-3 py-4">
                            <a href="<?= BASE_URL ?>/products"
                                class="btn btn-block text-white d-flex justify-content-center align-items-center h-100"
                                role="button" aria-pressed="true" style="border-radius: 14px; background-color: #000;">
                                <p class="m-0 font-weight-bold">Produtos</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="w-100 text-left flex-grow-1"
                    style="height: 190px; border-radius: 14px; background-color: #F5F5F7;">
                </div>
            </div>
            <div class="col-5 pl-5 d-flex flex-column">
                <div style="height: 100%; border-radius: 14px; background-color: #F5F5F7;">
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= URL_JS ?>jquery-3.6.0.min.js"></script>
<script src="<?= URL_JS ?>defaultScripts.js"></script>
<?php include 'App/Views/employee/new.php' ?>

<script>
$(document).ready(function() {
    const setupFieldValues = (data) => {
        $("#id").val(data.id);
        $("#name").val(data.nome);
        $("#cpf").val(data.cpf);
        $("#password").val(data.senha);
        $("#role").val(data.papel);
    }

    const emptyFields = () => {
        $("#id").val("");
        $("#name").val("");
        $("#cpf").val("");
        $("#password").val("");
        $("#role").val("");
    }

    options = {
        resource: "Funcionário",
        path: "<?= BASE_URL . '/employees' ?>",
        formId: "#employeeForm",
        btnNewId: "#btnNewEmployee",
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