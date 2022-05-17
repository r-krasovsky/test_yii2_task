<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use app\assets\NewsAsset;

/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $oForm yii\widgets\ActiveForm */

NewsAsset::register($this);

?>

<div class="news-form">
    <?php $oForm = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id' => 'news_form']]); ?>
    <div class="row">
        <div class="mx-auto image img-fluid img-thumbnail ">
            <?=Html::img('@web/' . $model->image, ['alt' => 'Изображение новости', 'class' => '', 'id' => 'img'])?>
        </div>
    </div>
    <?=$oForm->field($model, 'image')->fileInput()?>
    
    <?=$oForm->field($model, 'title')->textInput(['maxlength' => TRUE])?>
    
    <?=$oForm->field($model, 'content')->textarea(['rows' => 6])?>
    <div class="form-group">
        <?=Html::submitButton('Сохранить', ['class' => 'btn btn-success'])?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
