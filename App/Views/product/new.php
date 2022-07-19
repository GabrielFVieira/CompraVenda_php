<div class="modal fade" id="modalNewProduct" tabindex="-1" role="dialog" aria-labelledby="newProductModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newProductModalLabel">Produto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="name">Nome:</label>
                        <input type="text" class="form-control" id="name" name="name" maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="description">Descrição:</label>
                        <input type="textArea" class="form-control" id="description" name="description" maxlength="255">
                    </div>
                    <div class="form-group">
                        <label for="category">Categoria:</label>
                        <select class="form-control" name="category" id="category">
                            <option disabled selected value>- Selecione uma opção -</option>
                            <?php
                            if (isset($data['categories'])) {
                                foreach ($data['categories'] as $category) {
                            ?>
                            <option value="<?= $category->getId() ?>"><?= $category->getNome() ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sellValue">Preço Venda:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">R$</div>
                            </div>
                            <input type="text" class="form-control" name="sellValue" id="sellValue"
                                placeholder="000,00" />
                        </div>
                    </div>
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="active" id="active" value="false">
                        <label class="form-check-label" for="active">
                            Liberar para venda
                        </label>
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
    const validator = $('#productForm').validate({
        errorPlacement: function(label, element) {
            label.addClass('error-msg text-danger');

            if (element[0].id === 'sellValue') {
                label.insertAfter(element[0].parentElement);
            } else {
                label.insertAfter(element);
            }
        },
        wrapper: 'span',
        rules: {
            name: {
                required: true,
                maxlength: 100,
            },
            description: {
                required: true,
                maxlength: 255,
            },
            category: {
                required: true,
            },
            sellValue: {
                required: true,
            },
        }
    });

    $('#sellValue').mask('0000000000.00', {
        reverse: true
    });
});
</script>