<?php

namespace backend\modules\products;

class product extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\products\controllers';

    public function init()
    {
        parent::init();
		
        // custom initialization code goes here
       /* 
        $this->components['view'] = [
			'class' => '\yii\web\View',
			'theme' => 'xxx',
		];
		* */
    }
}
