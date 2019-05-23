<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news
 * @category   CategoryName
 */

use lispa\amos\attachments\components\AttachmentsTableWithPreview;
use lispa\amos\core\forms\ContextMenuWidget;
use lispa\amos\core\forms\ItemAndCardHeaderWidget;
use lispa\amos\core\forms\PublishedByWidget;
use lispa\amos\core\forms\ShowUserTagsWidget;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\core\views\toolbars\StatsToolbar;
use lispa\amos\news\AmosNews;
use lispa\amos\core\forms\CreatedUpdatedWidget;
use lispa\amos\attachments\components\AttachmentsList;
use lispa\amos\core\forms\InteractionMenuWidget;
use lispa\amos\news\assets\ModuleNewsAsset;
use \lispa\amos\news\models\News;
ModuleNewsAsset::register($this);

/**
 * @var yii\web\View $this
 * @var lispa\amos\news\models\News $model
 */

$this->title = $model->titolo;

$ruolo = Yii::$app->authManager->getRolesByUser(Yii::$app->getUser()->getId());
if (isset($ruolo['ADMIN'])) {
    $url = ['index'];
}

/** @var \lispa\amos\news\controllers\NewsController $controller */
$controller = Yii::$app->controller;
$controller->setNetworkDashboardBreadcrumb();
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

$hidePubblicationDate = Yii::$app->controller->newsModule->hidePubblicationDate;

$url = '/img/img_default.jpg';
if (!is_null($model->newsImage)) {
    $url = $model->newsImage->getUrl('square_large', false, true);
}

if($model->status != News::NEWS_WORKFLOW_STATUS_VALIDATO) {
    echo \lispa\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget::widget([
        'model' => $model,
        'workflowId' => News::NEWS_WORKFLOW,
        'classDivMessage' => 'message',
        'viewWidgetOnNewRecord' => true
    ]);
}

?>

<div class="news-view">
    <div class="row">
        <div class="col-md-8 col-xs-12">
            <div class="col-xs-12 header-widget nop">
                <?= ItemAndCardHeaderWidget::widget([
                        'model' => $model,
                        'publicationDateField' => 'data_pubblicazione',
                        'showPrevalentPartnershipAndTargets' => true,
                    ]
                ) ?>
                <?= ContextMenuWidget::widget([
                    'model' => $model,
                    'actionModify' => "/news/news/update?id=" . $model->id,
                    'actionDelete' => "/news/news/delete?id=" . $model->id,
                    'labelDeleteConfirm' => AmosNews::t('amosnews', 'Sei sicuro di voler cancellare questa notizia?'),
                    'modelValidatePermission' => 'NewsValidate'
                ]) ?>
                <?= CreatedUpdatedWidget::widget(['model' => $model, 'isTooltip' => true]) ?>
                <?php
                $reportModule = \Yii::$app->getModule('report');
                if (isset($reportModule) && in_array($model->className(), $reportModule->modelsEnabled)) {
                    echo \lispa\amos\report\widgets\ReportFlagWidget::widget([
                        'model' => $model,
                    ]);
                }
                ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-xs-12">
            <div class="header col-xs-12 nop">
                <img class="img-responsive" src="<?= $url ?>" alt="<?= $model->titolo ?>">
                <div class="title col-xs-12">
                    <h2 class="title-text"><?= $model->titolo ?></h2>
                    <h3 class="subtitle-text"><?= $model->sottotitolo ?></h3>
                </div>
            </div>
            <div class="text-content col-xs-12 nop">
                <?= $model->descrizione; ?>
            </div>
            <div class="widget-body-content col-xs-12 nop">
                <?php
                $reportModule = \Yii::$app->getModule('report');
                if (isset($reportModule) && in_array($model->className(), $reportModule->modelsEnabled)) {
                    echo \lispa\amos\report\widgets\ReportDropdownWidget::widget([
                        'model' => $model,
                    ]);
                }
                ?>

                <?php $baseUrl = (!empty(\Yii::$app->params['platform']['backendUrl']) ? \Yii::$app->params['platform']['backendUrl'] : '') ?>
                <?= \lispa\amos\core\forms\editors\socialShareWidget\SocialShareWidget::widget([
                    'mode' => \lispa\amos\core\forms\editors\socialShareWidget\SocialShareWidget::MODE_DROPDOWN,
                    'configuratorId'  => 'socialShare',
                    'model' => $model,
                    'url'           => \yii\helpers\Url::to($baseUrl . '/news/news/view?id='.$model->id, true),
                    'title'         => $model->title,
                    'description'   => $model->descrizione_breve,
                    'imageUrl'      => !empty($model->getNewsImage()) ? $model->getNewsImage()->getWebUrl('square_small') : '',
                ]);
                ?>
            </div>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="col-xs-12 attachment-section-sidebar nop" id="section-attachments">
                <?= Html::tag('h2', AmosIcons::show('paperclip',[],'dash') . AmosNews::t('amosnews', '#attachments_title')) ?>
                <div class="col-xs-12">
                    <?= AttachmentsList::widget([
                        'model' => $model,
                        'attribute' => 'attachments',
                        'viewDeleteBtn' => false,
                        'viewDownloadBtn' => true,
                        'viewFilesCounter' => true,
                    ]) ?>
                </div>
            </div>
            <div class="tags-section-sidebar col-xs-12 nop" id="section-tags">
                <?= Html::tag('h2', AmosIcons::show('tag', [], 'dash') . AmosNews::t('amosnews', '#tags_title')) ?>
                <div class="col-xs-12">
                    <?= \lispa\amos\core\forms\ListTagsWidget::widget([
                        'userProfile' => $model->id,
                        'className' => $model->className(),
                        'viewFilesCounter' => true,
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= Html::a(AmosNews::t('amosnews', '#go_back'),  (\Yii::$app->request->referrer ?: \Yii::$app->session->get('previousUrl')), [
    'class' => 'btn btn-secondary pull-left m-b-10'
])?>
