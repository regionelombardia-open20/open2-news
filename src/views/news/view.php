<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\news\utility\NewsUtility;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\news\AmosNews;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\attachments\components\AttachmentsList;
use open20\amos\news\assets\ModuleNewsAsset;
use \open20\amos\news\models\News;
use open20\amos\core\utilities\CurrentUser;


ModuleNewsAsset::register($this);

// ENABLE AGID FIELDS
$enableAgid = AmosNews::instance()->enableAgid;

/**
 * @var yii\web\View $this
 * @var open20\amos\news\models\News $model
 */

$this->title = $model->titolo;

$ruolo = Yii::$app->authManager->getRolesByUser(Yii::$app->getUser()->getId());
if (isset($ruolo['ADMIN'])) {
    $url = ['index'];
}

/** @var \open20\amos\news\controllers\NewsController $controller */
$controller = Yii::$app->controller;
$controller->setNetworkDashboardBreadcrumb();
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;


$hidePubblicationDate = Yii::$app->controller->newsModule->hidePubblicationDate;
$numberListTag = Yii::$app->controller->newsModule->numberListTag;

$url = '/img/img_default.jpg';
if (!is_null($model->newsImage)) {
    $url = $model->newsImage->getWebUrl('square_large', false, true);
}

if ($model->status != News::NEWS_WORKFLOW_STATUS_VALIDATO) {
    echo \open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget::widget([
        'model' => $model,
        'workflowId' => News::NEWS_WORKFLOW,
        'classDivMessage' => 'message',
        'viewWidgetOnNewRecord' => true
    ]);
}

$hideCategory   = false;
$newsCategories = NewsUtility::getAllNewsCategories();
if ($newsCategories->count() == 1) {
    $hideCategory = true;
} else {
    $category = $model->newsCategorie->titolo;
    $customCategoryClass = 'mb-1 px-1 ' . 'custom-category-bg-' . str_replace(' ', '-', strtolower($category));
    $colorBgCategory = $model->newsCategorie->color_background;
    $colorTextCategory = $model->newsCategorie->color_text;
}

?>

<div class="detail-news-hero-wrapper it-hero-wrapper it-dark it-overlay">
    <!-- - img-->
    <div class="img-responsive-wrapper">
        <div class="img-responsive">
            <div class="img-wrapper">
                <img src="<?= $url ?>" alt="<?= AmosNews::t('amosnews', 'Immagine della notizia') ?>">
            </div>
        </div>
    </div>
    <!-- - texts-->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="it-hero-text-wrapper bg-dark">
                    <span class="it-category  <?= $customCategoryClass ?>" <?php if ((!empty($colorBgCategory))) : ?> style="background-color: <?= $colorBgCategory ?> !important; padding: 0 4px; " <?php endif; ?>><strong <?php if ((!empty($colorTextCategory))) : ?> style="color: <?= $colorTextCategory ?>" <?php endif; ?>><?= $category ?></strong></span>

                    <p class="date"><?= Yii::$app->getFormatter()->asDate($model->data_pubblicazione) ?></p>
                    <h1 class="no_toc"><?= $model->titolo ?></h1>
                    <p class="d-none d-lg-block"><?= $model->sottotitolo ?></p>
                    <?php if (!empty(\open20\amos\core\utilities\CwhUtility::getTargetsString($model))) : ?>
                    <p><span class="mdi mdi-account-supervisor-circle mdi-24px"></span> <em><?=  \open20\amos\core\utilities\CwhUtility::getTargetsString($model) ?></em></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="news-view">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 header-widget">
                <?= ItemAndCardHeaderWidget::widget(
                    [
                        'model' => $model,
                        'publicationDateNotPresent' => true,
                        'showPrevalentPartnership' => true,
                        'enableLink' => !(CurrentUser::isPlatformGuest()),
                    ]
                ) ?>

                <div class="more-info-content">
                    <?php
                    $reportModule = \Yii::$app->getModule('report');
                    if (isset($reportModule) && in_array($model->className(), $reportModule->modelsEnabled)) {
                        echo \open20\amos\report\widgets\ReportDropdownWidget::widget([
                            'model' => $model,
                        ]);
                    }
                    ?>
                    <?php /*echo CreatedUpdatedWidget::widget(['model' => $model, 'isTooltip' => true])*/ ?>
                    <!-- < ?php
                    $reportModule = \Yii::$app->getModule('report');
                    if (isset($reportModule) && in_array($model->className(), $reportModule->modelsEnabled)) {
                        echo \open20\amos\report\widgets\ReportFlagWidget::widget([
                            'model' => $model,
                        ]);
                    }
                    ?> -->

                    <div class="m-l-10">
                        <?= ContextMenuWidget::widget([
                            'model' => $model,
                            'actionModify' => "/news/news/update?id=" . $model->id,
                            'actionDelete' => "/news/news/delete?id=" . $model->id,
                            'labelDeleteConfirm' => AmosNews::t('amosnews', 'Sei sicuro di voler cancellare questa notizia?'),
                            'modelValidatePermission' => 'NewsValidate'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="text-content">
                    <?= $model->descrizione; ?>
                </div>

                <?php
                $attachmentsWidget = '';
                $attachmentsWidget = AttachmentsList::widget([
                    'model' => $model,
                    'attribute' => 'attachments',
                    'viewDeleteBtn' => false,
                    'viewDownloadBtn' => true,
                    'viewFilesCounter' => true,
                ]);
                ?>
                <?php
                $tagsWidget = '';
                $tagsWidget = \open20\amos\core\forms\ListTagsWidget::widget([
                    'userProfile' => $model->id,
                    'className' => $model->className(),
                    'viewFilesCounter' => true,
                ]);
                ?>

                <?= $attachmentsWidget ?>

                <div class="clearfix"></div>

                <div class="tag-container">
                    <?= $tagsWidget ?>
                </div>

                <div class="clearfix"></div>
                <div class="footer-content">
                    <div class="social-share-wrapper">
                        <?php
                        $url = !empty(\Yii::$app->params['platform']['frontendUrl']) ? \Yii::$app->params['platform']['frontendUrl'] : "";
                        echo \open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget::widget([
                            'mode' => \open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget::MODE_NORMAL,
                            'configuratorId'  => 'socialShare',
                            'model' => $model,
                            'url'           => $url . $model->getFullViewUrl(),
                            'title'         => $model->title,
                            'description'   => $model->descrizione_breve,
                            'imageUrl'      => !empty($model->getNewsImage()) ? $model->getNewsImage()->getWebUrl('square_small') : '',
                            'isRedationalContent' => $model->primo_piano,
                        ]);
                        ?>
                    </div>
                    <div class="widget-body-content">


                        <?php

                        echo \open20\amos\core\forms\editors\likeWidget\LikeWidget::widget([
                            'model' => $model,
                        ]);
                        ?>
                    </div>
                </div>
                <?php if (\Yii::$app->getModule('correlations')) { ?>
                    <?=
                    open2\amos\correlations\widget\ListCorrelationsWidget::widget([
                        'model' => $model
                    ]);
                    ?>
                <?php } ?>
                <div class="clearfix"></div>
            </div>
            <!-- <div class="col-md-3 col-xs-12">

                < ?php

                $attachmentsWidget = '';
                $tagsWidget = '';

                $attachmentsWidget = AttachmentsList::widget([
                    'model' => $model,
                    'attribute' => 'attachments',
                    'viewDeleteBtn' => false,
                    'viewDownloadBtn' => true,
                    'viewFilesCounter' => true,
                ]);

                $tagsWidget = \open20\amos\core\forms\ListTagsWidget::widget([
                    'userProfile' => $model->id,
                    'className' => $model->className(),
                    'viewFilesCounter' => true,
                ]);

                ?>
                < ?=
                    $this->render(
                        '@vendor/open20/amos-layout/src/views/layouts/fullsize/parts/bi-view-detail-sidebar',
                        [
                            'attachments' => $attachmentsWidget,
                            'tags' => $tagsWidget,
                        ]
                    );
                ?>
            </div> -->

        </div>
    </div>


    <?php if (!is_null(\Yii::$app->getModule('sitemanagement')) && ($enableAgid)) : ?>
        <?php if(!empty($model->image_site_management_slider_id) ): ?>
            <?= \amos\sitemanagement\widgets\SMSliderWidget::widget(['sliderId' => $model->image_site_management_slider_id]); ?>
        <?php endif; ?>
        <?php if(!empty($model->video_site_management_slider_id)): ?>
            <?= \amos\sitemanagement\widgets\SMSliderWidget::widget(['sliderId' => $model->video_site_management_slider_id]); ?>
        <?php endif; ?>
    
    <?php endif; ?>

</div>