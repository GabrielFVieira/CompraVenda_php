<section class="vh-100" style="background-color: #000;">
    <div class="container d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card" style="border-radius: 1rem;">
                <form id="formLogin" class="login-form">
                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>" />
                    <div class="card-body p-5">
                        <a href="<?= BASE_URL ?>" style="color:#000; position:absolute; left:3rem; top:3rem;">
                            <i class="fa fa-arrow-left fa-2x" aria-hidden="true"></i>
                        </a>
                        <div class="mb-1 d-flex justify-content-center" style="width: 100%; height: 5rem;">
                            <img src="<?= URL_IMG ?>logo.svg" />
                        </div>

                        <h5 class="mb-2 text-center">CompraVenda</h5>

                        <hr class="my-4">

                        <h3 class="mb-5 text-center">Login</h3>

                        <div class="form-group mb-4">
                            <label for="cpf">CPF</label>
                            <input id="cpf" name="cpf" type="text" class="cpf form-control" placeholder="Digite o seu CPF" />
                        </div>

                        <div class="form-group mb-4">
                            <label for="password">Senha</label>
                            <input id="password" name="password" type="password" class="form-control" placeholder="Digite sua senha" />
                        </div>

                        <button class="btn text-white btn-lg btn-block mt-5" style="background-color: #000;border-radius: 8px;" type="submit">Entrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="w-100">
    <div class="mt-5 alert alert-info alert-dismissible fade show text-center" role="alert" style="position: absolute; top: 0; left: 50%; transform: translateX(-50%);">
        Usuários previamente disponíveis:<br>
        Comprador: 167.740.300-41<br>
        Vendedor: 081.599.500-80<br>
        Administrador: 249.252.810-38<br>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>

<script src="<?= URL_JS ?>jquery-3.6.0.min.js"></script>
<script src="<?= URL_JS ?>popper.min.js"></script>
<script src="<?= URL_JS ?>jquery.mask.js"></script>
<script src="<?= URL_JS ?>jquery.validate.min.js"></script>
<script src="<?= URL_JS ?>additional-methods.min.js"></script>
<script src="<?= URL_JS ?>localization/messages_pt_BR.js"></script>
<script src="<?= URL_JS ?>defaultScripts.js"></script>

<script>
    $(document).ready(function() {
        const validator = $('#formLogin').validate({
            errorPlacement: function(label, element) {
                label.addClass('error-msg text-danger');
                label.insertAfter(element);
            },
            wrapper: 'span',
            rules: {
                cpf: {
                    required: true,
                    cpfBR: true,
                },
                password: {
                    required: true,
                    minlength: 3,
                },
            },
        });

        $('#formLogin').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);

            var validator = form.validate();
            if (validator.form()) {
                $.ajax({
                    type: 'POST',
                    url: '<?= BASE_URL . '/login' ?>',
                    data: form.serialize(),
                    dataType: 'JSON',
                    success: function(data) {
                        location.href = '<?= BASE_URL . '/' ?>';
                    },
                    error: function(data) {
                        handleError(data);
                    },
                });
            }
        });

        $('.cpf').mask('000.000.000-00', {
            reverse: true
        });
    });
</script>