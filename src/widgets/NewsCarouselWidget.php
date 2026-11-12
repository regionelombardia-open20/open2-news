<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\news\widgets
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

namespace open20\amos\news\widgets;

use open20\amos\core\forms\AmosCarouselWidget;
use open20\amos\news\models\News;
use yii\db\ActiveQuery;

/**
 * Class NewsCarouselWidget
 * @package open20\amos\news\widgets
 */
class NewsCarouselWidget extends AmosCarouselWidget
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setItems($this->getNewsItems());

        parent::init();
    }

    /**
     * @return array
     */
    protected function getNewsItems()
    {
        $newsHighlights = [];
        $highlightsModule = \Yii::$app->getModule('highlights');

        if (!is_null($highlightsModule)) {
            /** @var \amos\highlights\Module $highlightsModule */
            $newsHighlightsIds = $highlightsModule->getHighlightedContents(News::className());
            /** @var ActiveQuery $query */
            $query = News::find()
                ->distinct()
                ->andWhere(['id' => $newsHighlightsIds])
                ->andWhere(['status' => News::NEWS_WORKFLOW_STATUS_VALIDATO])
                ->andWhere(['or',
                    ['data_rimozione' => null],
                    ['>=', 'data_rimozione', date('Y-m-d')]
                ]);
            
            $newsHighlights = $query->all();
        }

        return $newsHighlights;
    }
}