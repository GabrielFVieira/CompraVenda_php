<div class="modal fade" id="modalNewCustomer" tabindex="-1" role="dialog" aria-labelledby="newCustomerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newCustomerModalLabel">Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="customerForm">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="name">Nome:</label>
                        <input type="text" class="form-control" id="name" name="name" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="cpf">CPF:</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" maxlength="18">
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
    const validator = $('#customerForm').validate({
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
            cpf: {
                required: true,
                cpfBR: true,
                maxlength: 14
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
                maxlength: 8
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

    $('#cpf').mask('000.000.000-00');
    $('#cep').mask('00000000');
    $('#phone').mask('(00)0#');
});
</script>