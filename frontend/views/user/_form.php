<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<div class="well">
    <?= $form->field($model, 'email')->input('email') ?>
    <?= $form->field($model, 'phone_mobile')->widget(MaskedInput::className(),
        [
             'name' => 'phone_mobile',
             'mask' => '+99 (999) 999-99-99'
        ])
    ?>

</div>
    <hr>

    <h2>Change password</h2>
<div class="well">
    <?= $form->field($model, 'password_new')->passwordInput(['value' => '']) ?>
    <?= $form->field($model, 'password_confirm')->passwordInput(['value' => '']) ?>
</div>
    <hr>
    <h2>Upload new avatar</h2>

<div class="well">
    <?= $form->field($model, 'avatar_file')->fileInput() ?>
</div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-lg btn-primary', 'name' => 'save-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
