<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news
 * @category   CategoryName
 */

use lispa\amos\news\AmosNews;
use lispa\amos\news\models\News;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var lispa\amos\news\models\search\NewsSearch $model
 * @var yii\widgets\ActiveForm $form
 */

$moduleTag = Yii::$app->getModule('tag');

$enableAutoOpenSearchPanel = isset(\Yii::$app->params['enableAutoOpenSearchPanel']) 
    ? \Yii::$app->params['enableAutoOpenSearchPanel'] 
    : false;
?>

<div class="news-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-xs-12"><h2><?= AmosNews::t('amosnews', 'Cerca per') ?>:</h2></div>

    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'id' => 'news_form_' . $model->id,
            'class' => 'form',
            'enctype' => 'multipart/form-data',
        ]
    ]);

    echo Html::hiddenInput("enableSearch", $enableAutoOpenSearchPanel);
    echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView'));


    ?>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'titolo') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'sottotitolo') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'descrizione') ?>
    </div>

    <div class="clearfix"></div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'data_pubblicazione')->widget(DateControl::className(), [
            'type' => DateControl::FORMAT_DATE
        ]) ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?php
        $creator = '';
        $userProfileCreator = \lispa\amos\admin\models\UserProfile::find()->andWhere(['user_id' => $model->created_by])->one();
        if(!empty($userProfileCreator)) {
            $creator = $userProfileCreator->getNomeCognome();
        }
        echo $form->field($model, 'created_by')->widget(Select2::className(), [
                'data' => (!empty($model->created_by) ? [$model->created_by => $creator] : []),
                'options' => ['placeholder' => AmosNews::t('amosnews', 'Cerca ...')],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'ajax' => [
                        'url' => \yii\helpers\Url::to(['/admin/user-profile-ajax/ajax-user-list']),
                        'dataType' => 'json',
                        'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                    ],
                ],
            ]
        );
        ?>
    </div>

    <!--div class="col-sm-6 col-lg-4">
        < ?= $form->field($model, 'data_rimozione')->widget(DateControl::className(), [
            'type' => DateControl::FORMAT_DATE
        ]) ?>
    </div-->
    <?php if (isset($moduleTag) && in_array(News::className(), $moduleTag->modelsEnabled) && $moduleTag->behaviors): ?>
    <div class="col-xs-12">
        <?php
        $params = \Yii::$app->request->getQueryParams();
        /*echo \lispa\amos\tag\widgets\TagWidget::widget([
            'model' => $model,
            'attribute' => 'tagValues',
            'form' => $form,
            'isSearch' => true,
            'form_values' => isset($params[$model->formName()]['tagValues']) ? $params[$model->formName()]['tagValues'] : []
        ]);*/
        ?>
    </div>
    <?php endif; ?>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(AmosNews::t('amosnews', 'Annulla'), [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosNews::t('amosnews', 'Cerca'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <!--a><p class="text-center">Ricerca avanzata<br>
        < ?=AmosIcons::show('caret-down-circle');?>
    </p></a-->

    <?php ActiveForm::end(); ?>

</div>
