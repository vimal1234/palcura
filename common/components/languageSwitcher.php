<?php
/*
author :: Pitt Phunsanit
website :: http://plusmagi.com
change language by get language=EN, language=TH,...
or select on this widget
*/
 
namespace common\components;
 
use Yii;
use yii\base\Component;
use yii\base\Widget;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Url;
use yii\web\Cookie;
 
class languageSwitcher extends Widget
{
    /* Enter your language here */
    public $languages = [
        'en' => 'English',
        'en-US' => 'English-US',
        'fr' => 'French',
    ];
 
    public function init()
    {
        if(php_sapi_name() === 'cli')
        {
            return true;
        }
 
        parent::init();
 
        $cookies = Yii::$app->response->cookies;
        // remove a cookie
		$cookies->remove('language');
        //echo '<pre>'; print_r($cookies); die;
        $languageNew = Yii::$app->request->get('language');
        if($languageNew)
        {
            if(isset($this->languages[$languageNew]))
            {
                Yii::$app->language = $languageNew;
                $cookies->add(new \yii\web\Cookie([
                    'name' => 'language',
                    'value' => $languageNew
                ]));
            }
        }
        elseif($cookies->has('language'))
        {
            Yii::$app->language = $cookies->getValue('language');
        }
 
    }
 
    public function run(){
        $languages = $this->languages;
        $current = $languages[Yii::$app->language];
        unset($languages[Yii::$app->language]);
 
        $items = [];
        foreach($languages as $code => $language)
        {
            $temp = [];
            $temp['label'] = $language;
            $temp['url'] = Url::current(['language' => $code]);
            array_push($items, $temp);
        }
 
        echo ButtonDropdown::widget([
            'label' => $current,
            'dropdown' => [
                'items' => $items,
            ],
        ]);
    }
 
}
