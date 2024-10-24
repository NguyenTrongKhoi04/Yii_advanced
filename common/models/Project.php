<?php

namespace common\models;

use Yii;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string $name
 * @property string $tech_tass
 * @property string $description
 * @property string|null $start_date
 * @property string|null $end_date
 *
 * @property ProjectImage[] $images
 * @property Testimonial[] $testimonials
 */
class Project extends \yii\db\ActiveRecord
{
     /**
     * @var UploadedFile
     */
    public $imageFiles; // store uploaded images

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'tech_tass', 'description'], 'required'],
            [['tech_tass', 'description'], 'string'],
            [['start_date', 'end_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['imageFiles'], 'file', 'skipOnEmpty'=>false, 'extensions' => 'jpg,png,jpeg', 'maxSize' => 1024 * 1024 * 5, 'maxFiles'=>10],// 5MB
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'tech_tass' => Yii::t('app', 'Tech Tass'),
            'description' => Yii::t('app', 'Description'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
        ];
    }

    /**
     * Gets query for [[ProjectImages]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(ProjectImage::class, ['project_id' => 'id']);
    }

    /**
     * Gets query for [[Testimonials]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getTestimonials()
    {
        return $this->hasMany(Testimonial::class, ['project_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ProjectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProjectQuery(get_called_class());
    }

    public function saveImages(){
        Yii::$app->db->transaction(function(){
            /**
            * @var db yii\db\Connection
            */            
            foreach($this->imageFiles as $imageFile){
                $file = new File();
                $file->name = uniqid(true). '.'. $imageFile->extension;
                $file->path_url = Yii::$app->params['uploads']['projects'];
                $file->base_url = Yii::$app->urlManager->createAbsoluteUrl($file->path_url);
                $file->mime_type = mime_content_type($imageFile->tempName);
                $file->save();
                
                $projectImage = new ProjectImage();
                $projectImage->project_id = $this->id;
                $projectImage->file_id = $file->id;
                $projectImage->save();

                $thumbnail = Image::thumbnail($imageFile->tempName, null, 1080);
                $didSave = $thumbnail->save($file->path_url.'/'.$file->name);

                if(!$imageFile->saveAs($file->path_url. $file->name)) {
                    $db->transaction->rollBack();
                }
            }
        });
    }

    public function hasImages(){
        return count($this->images) > 0;
    }

    public function imageAbsoluteUrls(){
        $urls = [];
        foreach ($this->images as $image) {
            $urls[] = $image->file->absoluteUrl();
        };
        return $urls;
    }

    public function imageConfigs(){
        $configs = [];
        foreach ($this->images as $image) {
            $configs[] = [
                'key' => $image->id,
            ];
        }
        return $configs;
    }

    public function loadUploadedImageFiles()
    {
        $this->imageFiles = UploadedFile::getInstances($this, 'imageFiles');
    }

}

