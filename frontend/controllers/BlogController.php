<?php

namespace frontend\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class BlogController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'bookmark' => ['POST'],
                    'how-to-usey-gii' => ['POST'],
                ],
            ],
        ];
    }
    public function actionHowToUseyGii()
    {
        return $this->render('how-to-use-gii');
    }

    public function actionIAmStartingABlog()
    {
        return $this->render('i-am-starting-a-blog');
    }

    public function actionIndex()
    {
        
        return $this->render('index');
    }

}
