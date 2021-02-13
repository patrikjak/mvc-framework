<?php

use app\core\form\Form;
use app\models\User;

/* @var $model User */

?>
<h1>Register</h1>

<?php $form = Form::begin('', 'POST'); ?>
    <?= $form->field($model, 'name'); ?>
    <?= $form->field($model, 'email'); ?>
    <?= $form->field($model, 'password')->passwordField(); ?>
    <?= $form->field($model, 'passwordConfirm')->passwordField(); ?>
    <button type="submit" class="btn btn-primary">Submit</button>
<?= Form::end(); ?>