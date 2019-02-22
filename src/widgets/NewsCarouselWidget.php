<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news\widgets
 * @category   CategoryName
 */

namespace lispa\amos\news\widgets;

use lispa\amos\core\forms\AmosCarouselWidget;
use lispa\amos\news\models\News;
use yii\db\ActiveQuery;

/**
 * Class NewsCarouselWidget
 * @package lispa\amos\news\widgets
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
            $query = News::find();
            $query->distinct();
            $query->andWhere(['id' => $newsHighlightsIds]);
            $query->andWhere(['status' => News::NEWS_WORKFLOW_STATUS_VALIDATO]);
            $query->andWhere(['or',
                ['data_rimozione' => null],
                ['>=', 'data_rimozione', date('Y-m-d')]
            ]);
            $newsHighlights = $query->all();
        }

        return $newsHighlights;
    }
}
