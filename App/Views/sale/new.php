<div class="modal fade" id="modalNewSale" tabindex="-1" role="dialog" aria-labelledby="newSaleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newSaleModalLabel">Compra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="saleForm">
                    <input type="hidden" id="id" name="id">
                    <div class="form-group">
                        <label for="product">Produto:</label>
                        <select class="form-control" name="product" id="product">
                            <option disabled selected value>- Selecione uma opção -</option>
                            <?php
                            if (isset($data['products'])) {
                                foreach ($data['products'] as $product) {
                            ?>
                                    <option value="<?= $product->getId() ?>" price="<?= $product->getPrecoVenda() ?>">
                                        <?= $product->getNome() ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="customer">Cliente:</label>
                        <select class="form-control" name="customer" id="customer">
                            <option disabled selected value>- Selecione uma opção -</option>
                            <?php
                            if (isset($data['customers'])) {
                                foreach ($data['customers'] as $customers) {
                            ?>
                                    <option value="<?= $customers->getId() ?>"><?= $customers->getNome() ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amount">Quantidade:</label>
                        <input type="number" class="form-control" id="amount" name="amount" min="1">
                    </div>
                    <div class="form-group">
                        <label for="value">Valor:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">R$</div>
                            </div>
                            <input type="number" class="form-control" name="value" id="value" readonly />
                        </div>
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
        $('#product').on('change', function() {
            const value = $(this).find(":selected").attr('price');
            $('#value').val(value);
        });

        const validator = $('#saleForm').validate({
            errorPlacement: function(label, element) {
                label.addClass('error-msg text-danger');

                if (element[0].id === 'value') {
                    label.insertAfter(element[0].parentElement);
                } else {
                    label.insertAfter(element);
                }
            },
            wrapper: 'span',
            rules: {
                product: {
                    required: true,
                },
                customer: {
                    required: true,
                },
                amount: {
                    required: true,
                    min: 1
                }
            }
        });
    });
</script>