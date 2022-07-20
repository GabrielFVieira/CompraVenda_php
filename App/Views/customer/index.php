<?php
if (isset($_SESSION['id']) && isset($_SESSION['nomeUsuario'])) : ?>
<div class="d-flex">
    <?php require_once 'App/Views/sidebar/index.php' ?>
    <div class="vh-100 p-4 d-flex flex-column">
        <?php
            if ($_SESSION['papelUsuario'] == "Vendedor") {
            ?>
        <button id="btnNew" class="btn text-white" style="border-radius: 14px; background-color: #000;">
            Novo cliente
        </button>
        <?php
            }
            ?>
        <div class="table-wrapper-scroll-y my-custom-scrollbar">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Nome / CPF</th>
                        <th scope="col">Endereço</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Email</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if (isset($data['customers'])) {
                            foreach ($data['customers'] as $customer) {
                        ?>
                    <tr>
                        <td>
                            <div style="display: flex; flex-direction: column;">
                                <p style="margin: 0; font-weight: bold;"><?= $customer->getNome() ?></p>
                                <p style="margin: 0" class="text-muted"><?= $customer->getCPF() ?></p>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; flex-direction: column;">
                                <p style="margin: 0">
                                    <?= $customer->getEndereco() ?> - <?= $customer->getBairro() ?>
                                </p>
                                <p style="margin: 0" class="text-muted">
                                    <?= $customer->getCidade() ?> - <?= $customer->getUF() ?>,
                                    <?= $customer->getCEP() ?>
                                </p>
                            </div>
                        </td>
                        <td><?= $customer->getTelefone() ?></td>
                        <td><?= $customer->getEmail() ?></td>
                        <td>
                            <?php
                                        if ($_SESSION['papelUsuario'] == "Vendedor") {
                                        ?>
                            <button type="button" id="btnEdit" data-id="<?= $customer->getId() ?>"
                                class="btn btn-outline-primary">Editar</button>
                            <button type="button" id="btnDelete" data-id="<?= $customer->getId() ?>"
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
<?php include 'App/Views/customer/new.php' ?>
<script>
$(document).ready(function() {
    const setupFieldValues = (data) => {
        $("#id").val(data.id);
        $("#name").val(data.nome);
        $("#cpf").val(data.cpf);
        $("#address").val(data.endereco);
        $("#district").val(data.bairro);
        $("#city").val(data.cidade);
        $("#uf").val(data.uf);
        $("#cep").val(data.cep);
        $("#phone").val(data.telefone);
        $("#email").val(data.email);
    }

    const emptyFields = () => {
        $("#id").val("");
        $("#name").val("");
        $("#cpf").val("");
        $("#address").val("");
        $("#district").val("");
        $("#city").val("");
        $("#uf").val("");
        $("#cep").val("");
        $("#phone").val("");
        $("#email").val("");
    }

    options = {
        resource: "Cliente",
        path: "<?= BASE_URL . '/customers' ?>",
        formId: "#customerForm",
        btnNewId: "#btnNew",
        btnEditId: "#btnEdit",
        btnDeleteId: "#btnDelete",
        modelId: "#modalNewCustomer",
        setupFieldValues: setupFieldValues,
        emptyFields: emptyFields
    }

    setupDocument(options);
});
</script>

<?php endif; ?>