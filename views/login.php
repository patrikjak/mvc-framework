<?php

use app\core\form\Form;
use app\models\User;

/* @var $model User */

?>
<h1>LOGIN</h1>

<?php $form = Form::begin('', 'POST'); ?>
    <?= $form->field($model, 'email'); ?>
    <?= $form->field($model, 'password')->passwordField(); ?>
    <button type="submit" class="btn btn-primary">Submit</button>
<?= Form::end(); ?>