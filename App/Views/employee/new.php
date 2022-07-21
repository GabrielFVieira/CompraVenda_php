<div class="modal fade" id="modalNewEmployee" tabindex="-1" role="dialog" aria-labelledby="newEmployeeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newEmployeeModalLabel">Funcionário</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="employeeForm">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="name">Nome:</label>
                        <input type="text" class="form-control" id="name" name="name" maxlength="50">
                    </div>
                    <div class="form-group">
                        <label for="cpf">CPF:</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" maxlength="14">
                    </div>
                    <div class="form-group">
                        <label for="role">Papel:</label>
                        <select class="form-control" name="role" id="role">
                            <option disabled selected value>- Selecione uma opção -</option>
                            <?php
                            use App\Controllers\EmployeeController;
                            use App\models\Role;

                            foreach (Role::getRoles() as $role) {
                            ?>
                            <option value="<?= $role['id'] ?>"><?= $role['name'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div id="passwordDiv" class="form-group">
                        <label for="password">Senha padrão:</label>
                        <input type="text" class="form-control" id="password"
                            value="<?= EmployeeController::DefaultPassword ?>" readonly>
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
    $("#id").on('change', function() {
        if ($(this).val()) {
            $('#passwordDiv').css("display", "none");
        } else {
            $('#passwordDiv').css("display", "");
        }
    });

    const validator = $('#employeeForm').validate({
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
            role: {
                required: true,
            }
        }
    });

    $('#cpf').mask('000.000.000-00');
});
</script>