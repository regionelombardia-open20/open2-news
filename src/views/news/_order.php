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
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var lispa\amos\news\models\search\NewsSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="news-order element-to-toggle" data-toggle-element="form-order">
    <div class="col-xs-12">
        <h2><?= AmosNews::t('amosnews', 'Ordina per') ?>:</h2>
    </div>

    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
//    echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView'));
    echo Html::hiddenInput("currentView", $queryParamCurrentView);
    ?>
    
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'orderAttribute')->dropDownList($model->getOrderAttributesLabels()) ?>
    </div>
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'orderType')->dropDownList(
            [
                SORT_ASC => AmosNews::t('amosnews', 'Crescente'),
                SORT_DESC => AmosNews::t('amosnews', 'Decrescente'),
            ]
        )
        ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(AmosNews::t('amosnews', 'Annulla'), [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                ['class'=>'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosNews::t('amosnews', 'Ordina'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>

</div>