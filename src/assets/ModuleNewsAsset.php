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

class ModuleNewsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/lispa/amos-news/src/assets/web';

    public $css = [
        'less/news.less',
    ];
    public $js = [
        'js/news.js'
    ];
    public $depends = [
    ];

    public function init()
    {
        $moduleL = \Yii::$app->getModule('layout');
        if(!empty($moduleL)){
            $this->depends [] = 'lispa\amos\layout\assets\BaseAsset';
        }else{
            $this->depends [] = 'lispa\amos\core\views\assets\AmosCoreAsset';
        }
        parent::init();
    }
}