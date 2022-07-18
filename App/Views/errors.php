<?php
if (isset($data['errors'])) { ?>
    <div class="w-100">
        <div class="mt-5 alert alert-danger alert-dismissible fade show text-center" role="alert" style="position: absolute; top: 0; left: 50%; transform: translateX(-50%);">
            <?php

            foreach ($data['errors'] as $error) {
                echo $error . "<br>";
            }

            ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
<?php
}
?>