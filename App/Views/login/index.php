<?php
if (isset($data['mensagens'])) { ?>
<div class="col-6">
    <div class="alert alert-danger" role="alert">
        <?php

            foreach ($data['mensagens'] as $mensagem) {
                echo $mensagem . "<br>";
            }

            ?>
    </div>
</div>
<?php
}
?>
<section class="vh-100" style="background-color: #000;">
    <div class="container d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card" style="border-radius: 1rem;">
                <div class="card-body p-5">

                    <div class="mb-1 d-flex justify-content-center" style="width: 100%; height: 6rem;">
                        <img src="<?= URL_IMG ?>shop.svg" />
                    </div>

                    <h5 class="mb-2 text-center">CompraVenda</h5>

                    <hr class="my-4">

                    <h3 class="mb-5 text-center">Login</h3>

                    <div class="form-group">
                        <label for="cpf">CPF</label>
                        <input id="cpf" type="text" class="cpf form-control" placeholder="Digite o seu CPF" />
                    </div>

                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input id="password" type="password" class="form-control" placeholder="Digite sua senha" />
                    </div>

                    <button class="btn text-white btn-lg btn-block mt-4"
                        style="background-color: #000;border-radius: 8px;" type="submit">Entrar</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?= URL_JS ?>jquery-3.4.1.min.js"></script>
<script src="<?= URL_JS ?>popper.min.js"></script>
<script src="<?= URL_JS ?>jquery.mask.js"></script>
<script src="<?= URL_JS ?>jquery.validate.min.js"></script>
<script src="<?= URL_JS ?>additional-methods.min.js"></script>
<script src="<?= URL_JS ?>localization/messages_pt_BR.js"></script>

<script>
$(document).ready(function() {
    $('.cpf').mask('000.000.000-00', {
        reverse: true
    });
});
</script>