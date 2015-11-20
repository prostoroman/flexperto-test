<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Edit my profile';
$this->params['breadcrumbs'][] = ['label' => 'My profile', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
