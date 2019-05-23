<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news\widgets\graphics\views
 * @category   CategoryName
 */

use lispa\amos\core\forms\WidgetGraphicsActions;
use lispa\amos\news\AmosNews;
use lispa\amos\news\models\News;
use lispa\amos\news\widgets\graphics\WidgetGraphicsUltimeNews;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;
use lispa\amos\news\assets\ModuleNewsAsset;
use lispa\amos\core\icons\AmosIcons;
use kv4nt\owlcarousel\OwlCarouselWidget;

ModuleNewsAsset::register($this);

/**
 * @var View $this
 * @var ActiveDataProvider $listaNews
 * @var WidgetGraphicsUltimeNews $widget
 * @var string $toRefreshSectionId
 */

$moduleNews = \Yii::$app->getModule(AmosNews::getModuleName());

?>
<div class="box-widget-header">
    <?php
    if (isset($moduleNews) && !$moduleNews->hideWidgetGraphicsActions) {
        echo WidgetGraphicsActions::widget([
            'widget' => $widget,
            'tClassName' => AmosNews::className(),
            'actionRoute' => '/news/news/create',
            'toRefreshSectionId' => $toRefreshSectionId
        ]);
    } ?>

    <div class="box-widget-wrapper">
        <h2 class="box-widget-title">
            <?= AmosIcons::show('news', ['class' => 'am-2'], AmosIcons::IC)?>
            <?= AmosNews::tHtml('amosnews', 'Ultime notizie') ?>
        </h2>
    </div>


    <?php
    if (count($listaNews->getModels()) == 0):
        $textReadAll = AmosNews::t('amosnews', '#addNews');
        $linkReadAll = ['/news/news/create'];
    else:
        $textReadAll = AmosNews::t('amosnews', '#showAll') . AmosIcons::show('chevron-right');
        $linkReadAll = ['/news/news/all-news'];
    endif;
    ?>
    <div class="read-all"><?= Html::a($textReadAll, $linkReadAll, ['class' => '']); ?></div>
</div>

<div class="box-widget latest-news">
    <section>
        <?php Pjax::begin(['id' => $toRefreshSectionId]); ?>
        <?php if (count($listaNews->getModels()) == 0): ?>
            <div class="list-items list-empty"><h3><?= AmosNews::t('amosnews', 'Nessuna notizia') ?> </h3></div>
        <?php else:  
            $configuration = [
                'containerOptions' => [
                    'id'=> 'newsOwlCarousel'
                ],
                'pluginOptions'    => [
                    'autoplay'          => false,
                    'items'             => 1,
                    'loop'              => true,
                    'nav'               => true,
                    'dots'              => true
                ]
            ];

            OwlCarouselWidget::begin($configuration); ?>

            <?php
            /** @var News $news */
            $news = $listaNews->getModels();
            $lenghtNews = count($news);
            $moduleOf3 = $lenghtNews < 3 ? $lenghtNews + 1 : 3*floor($lenghtNews / 3); 

            for($i = 1; $i < $moduleOf3; $i+=3) :
            ?>
                <div class="wrap-slide-carousel-box">
                    <?php
                    for($a = 0; $a < 3; $a++) :
                        if(isset($news[($i+$a-1)])) :
                            $newsSingola = $news[($i+$a-1)];
                    ?>      <div class="wrap-item-carousel-box" data-index="<?=($i+$a-1)?>">
                            <?php
                                $url = '/img/img_default.jpg';
                                if (!is_null($newsSingola->newsImage)) {
                                    $url = $newsSingola->newsImage->getUrl('square_medium',false,true);
                                }
                        
                                echo Html::img($url, ['class' => 'img-responsive', 'alt' => AmosNews::t('amosnews', 'Immagine della notizia')]); 
                            ?>  <div class="abstract">
                                    <div class="box-widget-info-top">
                                        <div class="listbox-label"><?=$newsSingola->category->titolo;?></div>
                                        <?php if (isset($moduleNews) && !$moduleNews->hidePubblicationDate): ?>
                                            <p><?= Yii::$app->getFormatter()->asDate($newsSingola->data_pubblicazione); ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <?=Html::a('<h2 class="box-widget-subtitle">'. $newsSingola->titolo .'</h2>',
                                        ['../news/news/view', 'id' => $newsSingola->id]); ?>

                                    <p class="box-widget-text">
                                        <?php
                                        if (strlen($newsSingola->descrizione_breve) > 200) {
                                            $stringCut = substr($newsSingola->descrizione_breve, 0, 200);
                                            echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                        } else {
                                            echo $newsSingola->descrizione_breve;
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div>
                        <?php
                        endif;
                    endfor;
                    ?>
                </div>
            <?php endfor;
            
            OwlCarouselWidget::end();
            


            $configuration['containerOptions']['id'] = 'newsOwlCarouselTouch';
            $configuration['pluginOptions']['nav'] = false;
            $configuration['pluginOptions']['items'] = 2;
            $configuration['pluginOptions']['dotsEach'] = 1;
            $configuration['pluginOptions']['margin'] = 10;
            OwlCarouselWidget::begin($configuration);

            for($i = 0; $i < $lenghtNews; $i++) : ?>
                <div class="wrap-slide-carousel-box touch">
        <?php   $newsSingola = $news[$i]; ?>
                    <div class="wrap-item-carousel-box" data-index="<?=($i)?>">
                        <?php
                        $url = '/img/img_default.jpg';
                        if (!is_null($newsSingola->newsImage)) {
                            $url = $newsSingola->newsImage->getUrl('square_medium',false,true);
                        }
                        ?>
                        <?= Html::img($url, ['class' => 'img-responsive', 'alt' => AmosNews::t('amosnews', 'Immagine della notizia')]); ?>

                        <div class="abstract">
                            <div class="box-widget-info-top">
                                <div class="listbox-label"><?=$newsSingola->category->titolo;?></div>
                                <?php if (isset($moduleNews) && !$moduleNews->hidePubblicationDate): ?>
                                    <p><?= Yii::$app->getFormatter()->asDate($newsSingola->data_pubblicazione); ?></p>
                                <?php endif; ?>
                            </div>

                            <?=Html::a('<h2 class="box-widget-subtitle">'. $newsSingola->titolo .'</h2>',
                                ['../news/news/view', 'id' => $newsSingola->id]); ?>

                            <p class="box-widget-text">
                                <?php
                                if (strlen($newsSingola->descrizione_breve) > 200) {
                                    $stringCut = substr($newsSingola->descrizione_breve, 0, 200);
                                    echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                } else {
                                    echo $newsSingola->descrizione_breve;
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
        <?php endfor; ?>
        <?php OwlCarouselWidget::end(); ?>

        <?php endif; ?>
        <?php Pjax::end(); ?>
    </section>
</div>