<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news\views\news
 * @category   CategoryName
 */

use lispa\amos\core\forms\ContextMenuWidget;
use lispa\amos\core\forms\ItemAndCardHeaderWidget;
use lispa\amos\core\forms\PublishedByWidget;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\views\toolbars\StatsToolbar;
use lispa\amos\news\AmosNews;
use lispa\amos\notificationmanager\forms\NewsWidget;

/**
 * @var \lispa\amos\news\models\News $model
 */
?>
<div class="listview-container news-item grid-item nop">
    <div class="col-xs-12 nop icon-header">
        <div class="col-xs-12 nop top-header">
            <?= Html::tag('span', AmosNews::t('amosnews', '#news_item_published') . ' ' . Html::tag('strong', \Yii::$app->getFormatter()->asDate($model->getPublicatedFrom(), 'long'), ['class' => 'date'])) ?>
            <?= ContextMenuWidget::widget([
                'model' => $model,
                'actionModify' => "/news/news/update?id=" . $model->id,
                'actionDelete' => "/news/news/delete?id=" . $model->id,
                'labelDeleteConfirm' => AmosNews::t('amosnews', 'Sei sicuro di voler cancellare questa notizia?'),
                'modelValidatePermission' => 'NewsValidate'
            ]) ?>
            <?= NewsWidget::widget(['model' => $model]); ?>
        </div>
        <div class="news-image">
            <?php
            $url = '/img/img_default.jpg';
            if (!is_null($model->newsImage)) {
                $url = $model->newsImage->getUrl('square_medium', false, true);
            }
            $contentImage = Html::img($url, [
                'class' => 'img-responsive',
                'alt' => AmosNews::t('amosnews', 'Immagine della notizia')
            ]);
            ?>
            <?= Html::a($contentImage, $model->getFullViewUrl()) ?>
        </div>
        <div class="col-xs-12">
            <?= ItemAndCardHeaderWidget::widget([
                    'model' => $model,
                    'publicationDateNotPresent' => true,
                    'showPrevalentPartnershipAndTargets' => true,
                ]
            ) ?>
        </div>
    </div>
    <div class="col-xs-12 nop icon-body">
        <div class="col-xs-3 nop counter-column">
            <?php
            $visible = isset($statsToolbar) ? $statsToolbar : false;
            if ($visible) {
                echo StatsToolbar::widget([
                    'model' => $model,
                    'layoutType' => StatsToolbar::LAYOUT_VERTICAL,
                    'disableLink' => true,
                ]);
            }
            ?>
        </div>
        <div class="col-xs-9 nop text-column">
            <?= Html::a(Html::tag('h3', $model->titolo), $model->getFullViewUrl(), ['class' => 'title']) ?>
            <?= Html::tag('p', $model->descrizione_breve . Html::a(AmosNews::t('amosnews', 'Leggi tutto'), $model->getFullViewUrl(), ['class' => 'read-all']), ['class' => 'text']) ?>
        </div>
    </div>

</div>
<div class="clearfix"></div>
