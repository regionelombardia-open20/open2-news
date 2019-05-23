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

use lispa\amos\core\widget\WidgetAbstract;
use lispa\amos\core\widget\WidgetGraphic;
use lispa\amos\news\AmosNews;
use lispa\amos\news\models\search\NewsSearch;
use lispa\amos\notificationmanager\base\NotifyWidgetDoNothing;

class WidgetGraphicsUltimeNews extends WidgetGraphic {

  /**
   * @inheritdoc
   */
  public function init() {
    parent::init();

    $this->setCode('ULTIME_NEWS_GRAPHIC');
    $this->setLabel(AmosNews::tHtml('amosnews', 'Ultime news'));
    $this->setDescription(AmosNews::t('amosnews', 'Elenca le ultime news'));
  }

  /**
   * 
   * @return type@inheritdoc
   */
  public function getHtml() {
    $search = new NewsSearch();    
    $search->setNotifier(new NotifyWidgetDoNothing());

    $viewPath = '@vendor/lispa/amos-news/src/widgets/graphics/views/';   
    $viewToRender = $viewPath . 'ultime_news';

    $newsLimit = AmosNews::MAX_LAST_NEWS_ON_DASHBOARD;
    if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
      $newsLimit = 12;
    }
    
    $listaNews = $search->ultimeNews($_GET, $newsLimit);
 
    $moduleLayout = \Yii::$app->getModule('layout');
    if (is_null($moduleLayout)) {
      $viewToRender .= '_old';
    }
    
    return $this->render(
      $viewToRender,
      [
        'listaNews' => $listaNews,
        'widget' => $this,
        'toRefreshSectionId' => 'widgetGraphicLatestNews'
      ]
    );
  }

}