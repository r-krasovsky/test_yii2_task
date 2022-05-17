<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\imagine\Image;

/**
 * This is the model class for table "news".
 *
 * @property int    $id
 * @property string $title
 * @property string $content
 * @property Image   $image
 */
class News extends ActiveRecord {
    
    public const SCENARIO_CREATE = 'create';
    public const SCENARIO_UPDATE = 'update';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'news';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['title', 'content', 'image'], 'required', 'message' => 'Заполните поле.', 'on' => self::SCENARIO_CREATE],
            [['title', 'content'], 'required', 'message' => 'Заполните поле.', 'on' => self::SCENARIO_UPDATE],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['image'], 'image', 'skipOnEmpty' => ($this->id !== NULL), 'extensions' => 'png, jpg', "minSize" => 600 * 600],
        
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id'      => 'ID',
            'title'   => 'Заголовок',
            'content' => 'Новость',
            'image'   => 'Изображение новости',
        ];
    }
    
    public function uploadImage($rImage) {
        $sImageName = 'news_image_' . $this->id . '.' . $rImage->extension;
        if (Image::resize($rImage->tempName, 600, 600)
            ->save(Yii::getAlias('@webroot/news/' . $sImageName), ['quality' => 80])) {
            $this->image = 'news/' . $sImageName;
        }
        return TRUE;
    }
    
    
}
