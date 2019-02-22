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
use lispa\amos\dashboard\models\AmosWidgets;
use lispa\amos\news\AmosNews;
use lispa\amos\news\models\search\NewsSearch;
use yii\helpers\ArrayHelper;
use Yii;
use \lispa\amos\news\models\News;

/**
 * Class WidgetIconNews
 * @package lispa\amos\news\widgets\icons
 */
class WidgetIconNews extends WidgetIcon
{
    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        $options = parent::getOptions();

        // Aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
        return ArrayHelper::merge($options, ["children" => $this->getWidgetsIcon()]);
    }

    public function getWidgetsIcon()
    {
        return AmosWidgets::find()
            ->andWhere([
                'child_of' => self::className()
            ])->all();
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosNews::tHtml('amosnews', 'Notizie di mio interesse'));
        $this->setDescription(AmosNews::t('amosnews', 'Visualizza le notizie di mio interesse'));

        $this->setIcon('feed');
        //$this->setIconFramework();

        $this->setUrl(['/news/news/own-interest-news']);

        $this->setCode('NEWS');
        $this->setModuleName('news');
        $this->setNamespace(__CLASS__);

        $search = new NewsSearch();
        $notifier = Yii::$app->getModule('notify');
        $count = 0;
        if($notifier)
        {
            $count = $notifier->countNotRead(Yii::$app->getUser()->id, News::className(),
                $search->buildQuery([], 'own-interest'));
        }
        $this->setBulletCount($count);

        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-primary'
        ]));
    }
}
