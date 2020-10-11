<?php


namespace app\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;


class AppController extends Controller
{

    protected function setMeta($title = null, $keywords = null, $description = null)
    {
        $this->view->title = $title;
        $this->view->registerMetaTag(['name' => 'keywords', 'content' => "$keywords"]);
        $this->view->registerMetaTag(['name' => 'description', 'content' => "$description"]);
    }
    //  public function changeCurrency($currencyCode)
    // {            
    //     $currency = Currency::find()->where(['code' => $currencyCode])
    //     $session = Yii::$app->session;
    //     $session->open();
    //     $currency->code = $session['currency.code'];        
    //     return $this->render('main', compact('currency', 'currencyCode'));
    // }

}