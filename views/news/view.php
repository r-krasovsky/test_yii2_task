<?php

use yii\web\YiiAsset;
use app\assets\NewsAsset;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
NewsAsset::register($this);
?>
<div class="news-view">
    
    <h1><?=Html::encode($this->title)?></h1>
        <div class=" float-right">
            <p>
                <?php if(!Yii::$app->user->isGuest):?>
                    <?=Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])?>
                    <?=Html::a('Удалить', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data'  => [
                                    'confirm' => 'Вы уверены, что хотите удалить эту новость?',
                                    'method'  => 'post',
                            ],
                    ])?>
                <?php endif;?>
            </p>
        </div>
    <div class="mx-auto image img-fluid img-thumbnail" style="">
        <?= Html::img('@web/'. $model->image, ['alt' => 'Картинка новости', 'class' => ''])?>
    </div>
    <p><?=preg_replace('/\r\n/', '<br>', Html::encode($model->content))?></p>
</div>