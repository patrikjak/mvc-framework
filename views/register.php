<?php

use app\core\form\Form;

?>
<h1>Register</h1>

<?php $form = Form::begin('', 'POST'); ?>
    <?php echo $form->field($model, 'name'); ?>
    <?php echo $form->field($model, 'email'); ?>
    <?php echo $form->field($model, 'password')->passwordField(); ?>
    <?php echo $form->field($model, 'passwordConfirm')->passwordField(); ?>
    <button type="submit" class="btn btn-primary">Submit</button>
<?= Form::end(); ?>