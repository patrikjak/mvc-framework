<?php
/* @var View $this
 * @var ContactFrom $model
 */

use app\core\form\Form;
use app\core\form\TextareaField;
use app\core\View;
use app\models\ContactFrom;

$this->title = 'Contact - PHP MVC framework'
?>
<h1>Contact me</h1>

<?php $form = Form::begin('', 'POST') ?>
<?= $form->field($model, 'subject') ?>
<?= $form->field($model, 'email') ?>
<?= new TextareaField($model, 'body') ?>
<button type="submit" class="btn btn-primary">Submit</button>
<?php Form::end(); ?>