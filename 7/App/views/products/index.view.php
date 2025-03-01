<?php
namespace App\views\products;

loadPartial('header');
loadPartial('navigation');
?>

<?php loadPartial('errors', [
            'errors' => $errors ?? []
        ]) ?>

<?php
loadPartial('footer');