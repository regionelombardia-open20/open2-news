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
use yii\helpers\ArrayHelper;

class WidgetIconNewsCategorie extends WidgetIcon
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

        $this->setLabel(AmosNews::tHtml('amosnews', 'Categorie notizie'));
        $this->setDescription(AmosNews::t('amosnews', 'Visualizza le categorie delle news'));

        $this->setIcon('feed');
        //$this->setIconFramework();

        $this->setUrl(['/news/news-categorie/index']);

        $this->setCode('NEWS_CATEGORIE');
        $this->setModuleName('news');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-primary'
        ]));
    }
}