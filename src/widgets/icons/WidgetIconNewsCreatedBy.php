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
use lispa\amos\news\AmosNews;
use lispa\amos\news\models\search\NewsSearch;
use lispa\amos\news\models\News;
use lispa\amos\core\widget\WidgetAbstract;
use lispa\amos\core\icons\AmosIcons;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconNewsCreatedBy
 * @package lispa\amos\news\widgets\icons
 */
class WidgetIconNewsCreatedBy extends WidgetIcon
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
        $this->setLabel(AmosNews::tHtml('amosnews', 'Notizie create da me'));
        $this->setDescription(AmosNews::t('amosnews', 'Visualizza le notizie create da me'));

        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->setIconFramework(AmosIcons::IC);
            $this->setIcon('news');
            $paramsClassSpan = [];
        } else {
            $this->setIcon('feed');
        }

        $this->setUrl(['/news/news/own-news']);
        $this->setCode('NEWS_CREATEDBY');
        $this->setModuleName('news');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );

        $this->setBulletCount(
            $this->makeBulletCounter(null)
        );
    }

    /**
     * Make the number to set in the bullet count.
     * 
     * @param type $user_id
     * @return type
     */
    public function makeBulletCounter($user_id = null)
    {
        $modelSearch = new NewsSearch();
        $dataProvider = $modelSearch->searchOwnNews([]);

        return $dataProvider->query
            ->andWhere([News::tableName() . '.status' => News::NEWS_WORKFLOW_STATUS_BOZZA])
            ->asArray()
            ->count();
    }

    /**
     * Aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
     * 
     * @inheritdoc
     */
    public function getOptions()
    {
        return ArrayHelper::merge(
            parent::getOptions(),
            ['children' => []]
        );
    }

}
