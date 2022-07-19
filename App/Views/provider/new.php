<div class="modal fade" id="modalNewProvider" tabindex="-1" role="dialog" aria-labelledby="newProviderModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newProviderModalLabel">Fornecedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="providerForm">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="corporateName">Razao Social:</label>
                        <input type="text" class="form-control" id="corporateName" name="corporateName" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="cnpj">CNPJ:</label>
                        <input type="text" class="form-control" id="cnpj" name="cnpj" maxlength="18">
                    </div>
                    <div class="form-group">
                        <label for="address">Endereço:</label>
                        <input type="text" class="form-control" id="address" name="address" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="district">Bairro:</label>
                        <input type="text" class="form-control" id="district" name="district" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="city">Cidade:</label>
                        <input type="text" class="form-control" id="city" name="city" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="uf">UF:</label>
                        <input type="text" class="form-control" id="uf" name="uf" maxlength="2"
                            style="text-transform: uppercase;">
                    </div>
                    <div class="form-group">
                        <label for="cep">CEP:</label>
                        <input type="text" class="form-control" id="cep" name="cep" maxlength="9">
                    </div>
                    <div class="form-group">
                        <label for="phone">Telefone:</label>
                        <input type="text" class="form-control" id="phone" name="phone" maxlength="20">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" maxlength="50">
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

<script src="<?= URL_JS ?>popper.min.js"></script>
<script src="<?= URL_JS ?>jquery.mask.js"></script>
<script src="<?= URL_JS ?>jquery.validate.min.js"></script>
<script src="<?= URL_JS ?>additional-methods.min.js"></script>
<script src="<?= URL_JS ?>localization/messages_pt_BR.js"></script>

<script>
$(document).ready(function() {
    const validator = $('#providerForm').validate({
        errorPlacement: function(label, element) {
            label.addClass('error-msg text-danger');
            label.insertAfter(element);
        },
        wrapper: 'span',
        rules: {
            corporateName: {
                required: true,
                maxlength: 100,
            },
            cnpj: {
                required: true,
                cnpjBR: true,
                maxlength: 18
            },
            address: {
                required: true,
                maxlength: 50
            },
            district: {
                required: true,
                maxlength: 50
            },
            city: {
                required: true,
                maxlength: 50
            },
            uf: {
                required: true,
                maxlength: 2
            },
            cep: {
                required: true,
                postalcodeBR: true,
                maxlength: 9
            },
            phone: {
                required: true,
                maxlength: 20
            },
            email: {
                required: true,
                email: true,
                maxlength: 50
            }
        }
    });

    $('#cnpj').mask('00.000.000/0000-00');
    $('#cep').mask('00000-000');
    $('#phone').mask('0#');
});
</script>