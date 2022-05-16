<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\LinkPager;
use app\assets\NewsAsset;

/* @var $this yii\web\View */
/* @var $aNews \app\models\Country[]|array|\yii\db\ActiveRecord[] */

NewsAsset::register($this);

$this->title = 'Новости';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index mb-3">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if(!Yii::$app->user->isGuest):?>
    <p>
        <?= Html::a('Добавить новость', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif;?>
    <div class="mx-auto news_container">
        <?php foreach ($aNews as $item):?>
            <div class="border rounded border-light bg-light ml-2 mt-4 text-center news_card">
                <?= Html::a('<div class="mx-auto image">'
                            . Html::img('@web/'. $item->image, ['alt' => 'Картинка новости', 'class' => 'img-fluid img-thumbnail'])
                            . '</div>'
                            . Html::encode($item->title), ['news/view/?id=' . $item->id])?>
            </div>
        <?php endforeach;?>
    </div>
  
</div>

<?=LinkPager::widget(['pagination'=>$oPagination])?>