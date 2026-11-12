<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\news\widgets\graphics
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

namespace open20\amos\news\widgets\graphics;

use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\widget\WidgetGraphic;
use open20\amos\news\AmosNews;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;

/**
 * Class WidgetGraphicsUltimeNews
 * @package open20\amos\news\widgets\graphics
 */
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
    
    /**
     * @@inheritdoc
     */
    public function getHtml()
    {
       $search = AmosNews::instance()->createModel('NewsSearch');
        $search->setNotifier(new NotifyWidgetDoNothing());
        
        $viewPath = '@vendor/open20/amos-news/src/widgets/graphics/views/';
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
