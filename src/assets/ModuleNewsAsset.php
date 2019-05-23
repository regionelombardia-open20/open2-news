<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news
 * @category   CategoryName
 */

namespace lispa\amos\news\assets;

use yii\web\AssetBundle;
use lispa\amos\core\widget\WidgetAbstract;

class ModuleNewsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/lispa/amos-news/src/assets/web';

    public $css = [
        'less/news.less',
    ];
    public $js = [
        'js/news-module.js',
        'js/news.js'
    ];
    public $depends = [
    ];

    public function init()
    {
        $moduleL = \Yii::$app->getModule('layout');

        if(!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS){
            $this->css = ['less/news_fullsize.less'];
        }

        if(!empty($moduleL)){
            $this->depends [] = 'lispa\amos\layout\assets\BaseAsset';
        }else{
            $this->depends [] = 'lispa\amos\core\views\assets\AmosCoreAsset';
        }
        parent::init();
    }
}