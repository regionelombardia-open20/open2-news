<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news
 * @category   CategoryName
 */

namespace lispa\amos\news\widgets\icons;

use lispa\amos\core\widget\WidgetIcon;
use lispa\amos\dashboard\models\AmosUserDashboards;
use lispa\amos\news\widgets\icons\WidgetIconAllNews;
use lispa\amos\news\AmosNews;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use Yii;


class WidgetIconNewsDashboard extends WidgetIcon
{
    public function init()
    {
        parent::init();

        $this->setLabel(AmosNews::tHtml('amosnews', 'Notizie'));
        $this->setDescription(AmosNews::t('amosnews', 'Modulo news'));

        $this->setIcon('feed');
        //$this->setIconFramework();

        $this->setUrl(['/news']);

        $this->setCode('NEWS_MODULE');
        $this->setModuleName('news-dashboard');
        $this->setNamespace(__CLASS__);
        $this->setBulletCount($this->getBulletCountChildWidgets());
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-primary'
        ]));

    }

    /**
     *
     * @return int - the sum of bulletCount internal widget
     *
     */
    private function getBulletCountChildWidgets()
    {
        $count = 0;

        try {
            /** @var AmosUserDashboards $userModuleDashboard */
            $userModuleDashboard = AmosUserDashboards::findOne([
                'user_id' => \Yii::$app->user->id,
                'module' => AmosNews::getModuleName()
            ]);
            if (is_null($userModuleDashboard)) return 0;

            $widgetAllnews = \Yii::createObject(WidgetIconAllNews::className());
            $widgetCreatedBy = \Yii::createObject(WidgetIconNewsCreatedBy::className());

            $count = $widgetAllnews->getBulletCount()+ $widgetCreatedBy->getBulletCount();
        }catch(Exception $ex){
            Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }

        return $count;
    }

    public function getOptions()
    {
        $options = parent::getOptions();

        //aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
        return ArrayHelper::merge($options, ["children" => $this->getWidgetsIcon()]);
    }

    /* TEMPORANEA */
    public function getWidgetsIcon()
    {
        $widgets = [];

        $WidgetIconNewsCategorie = new WidgetIconNewsCategorie();
        if ($WidgetIconNewsCategorie->isVisible()) {
            $widgets[] = $WidgetIconNewsCategorie->getOptions();
        }

        $WidgetIconNewsCreatedBy = new WidgetIconNewsCreatedBy();
        if ($WidgetIconNewsCreatedBy->isVisible()) {
            $widgets[] = $WidgetIconNewsCreatedBy->getOptions();
        }

        return $widgets;
    }
}