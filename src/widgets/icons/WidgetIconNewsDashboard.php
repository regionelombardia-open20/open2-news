<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

namespace open20\amos\news\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;

use open20\amos\dashboard\models\AmosUserDashboards;

use open20\amos\news\widgets\icons\WidgetIconAllNews;
use open20\amos\news\AmosNews;

use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

class WidgetIconNewsDashboard extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
            'color-primary'
        ];

        $this->setLabel(AmosNews::tHtml('amosnews', 'Notizie'));
        $this->setDescription(AmosNews::t('amosnews', 'Modulo news'));

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('news');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('feed');
        }

        $this->setUrl(['/news']);
        $this->setCode('NEWS_MODULE');
        $this->setModuleName('news-dashboard');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );

        $this->setBulletCount(
            $this->makeBulletCounter(
                Yii::$app->user->getId()
            )
        );
    }

    /**
     * 
     * @param type $userId
     * @return type
     */
    public function makeBulletCounter($userId = null, $className = null, $externalQuery = null)
    {
        return $this->getBulletCountChildWidgets($userId);
    }

    /**
     * 
     * @param type $userId
     * @return int - the sum of bulletCount internal widget
     */
    private function getBulletCountChildWidgets($userId = null)
    {
        $count = 0;

        try {
            /** @var AmosUserDashboards $userModuleDashboard */
            $userModuleDashboard = AmosUserDashboards::findOne([
                'user_id' => $userId,
                'module' => AmosNews::getModuleName()
            ]);

            if (is_null($userModuleDashboard)) {
                return 0;
            }

            $widgetAllnews = \Yii::createObject(WidgetIconAllNews::className());
            $widgetCreatedBy = \Yii::createObject(WidgetIconNewsCreatedBy::className());

            $count = $widgetAllnews->getBulletCount() + $widgetCreatedBy->getBulletCount();
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }

        return $count;
    }

    /**
     * Aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
     * 
     * @return type
     */
    public function getOptions()
    {
        return ArrayHelper::merge(
            parent::getOptions(),
            ['children' => $this->getWidgetsIcon()]
        );
    }

    /**
     * TEMPORANEA
     * 
     * @return type
     */
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
