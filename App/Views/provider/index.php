<?php
if (isset($_SESSION['id']) && isset($_SESSION['nomeUsuario'])) : ?>
<div class="d-flex">
    <?php require_once 'App/Views/sidebar/index.php' ?>
    <div class="vh-100 p-4 d-flex flex-column">
        <?php
            if ($_SESSION['papelUsuario'] == "Comprador") {
            ?>
        <button id="btnNew" class="btn text-white" style="border-radius: 14px; background-color: #000;">
            Novo fornecedor
        </button>
        <?php
            }
            ?>
        <div class="table-wrapper-scroll-y my-custom-scrollbar">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Razão Social / CNPJ</th>
                        <th scope="col">Endereço</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Email</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if (isset($data['providers'])) {
                            foreach ($data['providers'] as $provider) {
                        ?>
                    <tr>
                        <td>
                            <div style="display: flex; flex-direction: column;">
                                <p style="margin: 0; font-weight: bold;"><?= $provider->getRazaoSocial() ?></p>
                                <p style="margin: 0" class="text-muted"><?= $provider->getCNPJ() ?></p>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; flex-direction: column;">
                                <p style="margin: 0">
                                    <?= $provider->getEndereco() ?> - <?= $provider->getBairro() ?>
                                </p>
                                <p style="margin: 0" class="text-muted">
                                    <?= $provider->getCidade() ?> - <?= $provider->getUF() ?>,
                                    <?= $provider->getCEP() ?>
                                </p>
                            </div>
                        </td>
                        <td><?= $provider->getTelefone() ?></td>
                        <td><?= $provider->getEmail() ?></td>
                        <td>
                            <?php
                                        if ($_SESSION['papelUsuario'] == "Comprador") {
                                        ?>
                            <button type="button" id="btnEdit" data-id="<?= $provider->getId() ?>"
                                class="btn btn-outline-primary">Editar</button>
                            <button type="button" id="btnDelete" data-id="<?= $provider->getId() ?>"
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
<?php include 'App/Views/provider/new.php' ?>
<script>
$(document).ready(function() {
    const setupFieldValues = (data) => {
        $("#id").val(data.id);
        $("#corporateName").val(data.razaoSocial);
        $("#cnpj").val(data.cnpj);
        $("#address").val(data.bairro);
        $("#district").val(data.bairro);
        $("#city").val(data.cidade);
        $("#uf").val(data.uf);
        $("#cep").val(data.cep);
        $("#phone").val(data.telefone);
        $("#email").val(data.email);
    }

    const emptyFields = () => {
        $("#id").val("");
        $("#corporateName").val("");
        $("#cnpj").val("");
        $("#address").val("");
        $("#district").val("");
        $("#city").val("");
        $("#uf").val("");
        $("#cep").val("");
        $("#phone").val("");
        $("#email").val("");
    }

    options = {
        resource: "Fornecedor",
        path: "<?= BASE_URL . '/providers' ?>",
        formId: "#providerForm",
        btnNewId: "#btnNew",
        btnEditId: "#btnEdit",
        btnDeleteId: "#btnDelete",
        modelId: "#modalNewProvider",
        setupFieldValues: setupFieldValues,
        emptyFields: emptyFields
    }

    setupDocument(options);
});
</script>

<?php endif; ?>