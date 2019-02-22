<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news\widgets\graphics
 * @category   CategoryName
 */

namespace lispa\amos\news\widgets\graphics;

use lispa\amos\core\widget\WidgetGraphic;
use lispa\amos\news\AmosNews;
use lispa\amos\news\models\search\NewsSearch;
use lispa\amos\notificationmanager\base\NotifyWidgetDoNothing;

class WidgetGraphicsUltimeNews extends WidgetGraphic
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setCode('ULTIME_NEWS_GRAPHIC');
        $this->setLabel(AmosNews::tHtml('amosnews', 'Ultime news'));
        $this->setDescription(AmosNews::t('amosnews', 'Elenca le ultime news'));
    }

    public function getHtml()
    {
        $search = new NewsSearch();
        $search->setNotifier(new NotifyWidgetDoNothing());
        $listaNews = $search->ultimeNews($_GET, 3);

        $viewToRender = 'ultime_news';
        $moduleLayout = \Yii::$app->getModule('layout');

        if(is_null($moduleLayout)){
            $viewToRender = 'ultime_news_old';
        }
        return $this->render( $viewToRender, [
            'listaNews' => $listaNews,
            'widget' => $this,
            'toRefreshSectionId' => 'widgetGraphicLatestNews'
        ]);
    }
}