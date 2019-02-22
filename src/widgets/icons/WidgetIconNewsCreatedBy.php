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
use yii\helpers\ArrayHelper;
use Yii;
use \lispa\amos\news\models\News;

/**
 * Class WidgetIconNewsCreatedBy
 * @package lispa\amos\news\widgets\icons
 */
class WidgetIconNewsCreatedBy extends WidgetIcon
{
    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        $options = parent::getOptions();

        // Aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
        return ArrayHelper::merge($options, ["children" => []]);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setLabel(AmosNews::tHtml('amosnews', 'Notizie create da me'));
        $this->setDescription(AmosNews::t('amosnews', 'Visualizza le notizie create da me'));
        $this->setIcon('feed');
        //$this->setIconFramework();
        $this->setUrl(['/news/news/own-news']);
        $this->setBulletCount($this->makeBulletCount());
        
        $this->setCode('NEWS_CREATEDBY');
        $this->setModuleName('news');
        $this->setNamespace(__CLASS__);
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-primary'
        ]));
    }

    /**
     * Make the number to set in the bullet count.
     */
    public function makeBulletCount()
    {
        $modelSearch = new NewsSearch();
        $dataProvider = $modelSearch->searchOwnNews([]);
        $count = $dataProvider->query->andWhere([News::tableName() . '.status' => News::NEWS_WORKFLOW_STATUS_BOZZA])->count();
        return $count;
    }
    
}
