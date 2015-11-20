<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'My profile';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <p class="text-right">
        <?= Html::a('Edit my profile', ['update'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete my account', ['delete'], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete your account?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row">
        <div class="col-sm-3">
            <?php if ($model->avatar_url): ?>
                <img src="<?= $model->avatar_url ?>" class="img-rounded img-responsive">
                <p class="text-center">
                    <a class="btn btn-sm btn-danger" href="<?= Url::to(['user/remove-avatar']); ?>">Remove avatar</a>
                </p>
            <?php else: ?>
                <img src="img/default-avatar.gif" class="img-rounded img-responsive">
            <?php endif ?>
        </div>
        <div class="col-sm-9">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'username',
                    'email:email',
                    'phone_mobile',
                    'created_at:datetime',
                ],
            ]) ?>

        </div>
    </div>
</div>
