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
use lispa\amos\news\models\News;
use lispa\amos\news\models\search\NewsSearch;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconNewsDaValidare
 * @package lispa\amos\news\widgets\icons
 */
class WidgetIconNewsDaValidare extends WidgetIcon
{
    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        $options = parent::getOptions();

        //aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
        return ArrayHelper::merge($options, ["children" => []]);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosNews::tHtml('amosnews', 'Notizie da validare'));
        $this->setDescription(AmosNews::t('amosnews', 'Visualizza le news da validare da parte dell\'utente'));

        $this->setIcon('feed');

        $this->setUrl(['/news/news/to-validate-news']);

        $this->setCode('NEWS_VALIDATE');
        $this->setModuleName('news');
        $this->setNamespace(__CLASS__);

        $search = new NewsSearch();
        $notifier = Yii::$app->getModule('notify');
        $count = 0;
        if($notifier)
        {
            $count = $notifier->countNotRead(Yii::$app->getUser()->id, News::class,
                $search->buildQuery([], 'to-validate'));
        }
        $this->setBulletCount($count);

        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-primary'
        ]));
    }
}
