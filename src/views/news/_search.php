<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\news
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

use open20\amos\news\AmosNews;
use open20\amos\news\models\News;
use open20\amos\admin\AmosAdmin;
use kartik\select2\Select2;
use open20\amos\core\forms\editors\Select;
use kartik\datecontrol\DateControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var open20\amos\news\models\search\NewsSearch $model
 * @var yii\widgets\ActiveForm $form
 * @var bool $isTagContentsSearch
 */
$moduleTag = Yii::$app->getModule('tag');

/** @var AmosNews $newsModule */
$newsModule = AmosNews::instance();

// enable open search section
$enableAutoOpenSearchPanel = isset(\Yii::$app->params['enableAutoOpenSearchPanel']) ? \Yii::$app->params['enableAutoOpenSearchPanel'] : false;
?>

<div class="news-search element-to-toggle" data-toggle-element="form-search">

    <?php
    $form = ActiveForm::begin([
        'action' => isset($originAction) ? [$originAction] : Yii::$app->controller->action->id,
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

    <div class="col-sm-12 col-lg-4">
        <?= $form->field($model, 'descrizione') ?>
    </div>

    <div class="clearfix"></div>

    <div class="col-sm-4 col-lg-4">
        <?=
        $form->field($model, 'data_pubblicazione')->widget(DateControl::className(), [
            'type' => DateControl::FORMAT_DATE
        ])->hint('')
        ?>
    </div>

    <?php if (!\Yii::$app->user->isGuest) { ?>
        <div class="col-sm-4 col-lg-4">
            <?php
            $creator = '';
            $userProfileCreator = $model->createdUserProfile;
            if (!empty($userProfileCreator)) {
                $creator = $userProfileCreator->getNomeCognome();
            }
            echo $form->field($model, 'created_by')->widget(Select2::className(), [
                    'data' => (!empty($model->created_by) ? [$model->created_by => $creator] : []),
                    'options' => ['placeholder' => AmosNews::t('amosnews', 'Cerca ...')],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'ajax' => [
                            'url' => \yii\helpers\Url::to(['/' . AmosAdmin::getModuleName() . '/user-profile-ajax/ajax-user-list']),
                            'dataType' => 'json',
                            'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                        ],
                    ],
                ]
            );
            ?>
        </div>
    <?php } ?>

    <?php if (!$isTagContentsSearch) { ?>
        <div class="col-sm-4 col-lg-4">
            <?= $form->field($model, 'status')->widget(Select::className(), [
                'data' => $model->getAllWorkflowStatus(),
                'language' => substr(Yii::$app->language, 0, 2),
                'options' => [
                    'multiple' => false,
                    'placeholder' => AmosNews::t('amosnews', '#select_choose') . '...',
                    'value' => $model->status = \Yii::$app->request->get(end(explode("\\", $model::className())))['status']
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
    <?php } ?>

    <?php if ($newsModule->enableAgid) : ?>

        <div class="col-sm-6 col-lg-4">
            <?php
            $updateUser = '';
            $userProfileUpdate = $model->updatedUserProfile;
            if (!empty($userProfileUpdate)) {
                $updateUser = $userProfileUpdate->getNomeCognome();
            }
            echo $form->field($model, 'updated_by')->widget(Select2::className(), [
                    'data' => (!empty($model->updated_by) ? [$model->updated_by => $updateUser] : []),
                    'options' => ['placeholder' => AmosNews::t('amosnews', 'Cerca ...')],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'ajax' => [
                            'url' => \yii\helpers\Url::to(['/' . AmosAdmin::getModuleName() . '/user-profile-ajax/ajax-user-list']),
                            'dataType' => 'json',
                            'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                        ],
                    ],
                ]
            );
            ?>
        </div>


        <div class="col-sm-6 col-lg-4">
            <!-- Aggiornato il (range da -> a) -->
        </div>

    <?php endif; ?>
<!--    <div class="clearfix"></div>-->

    <div class="col-md-4">
        <?=
        $form->field($model, 'news_categorie_id')->widget(Select::className(), [
            'data' => ArrayHelper::map(\open20\amos\news\models\NewsCategorie::find()
                ->andWhere(['deleted_at' => NULL])->all(), 'id', 'titolo'),
            'language' => substr(Yii::$app->language, 0, 2),
            'options' => [
                'multiple' => false,
                'placeholder' => AmosNews::t('amosnews', '#select_choose') . '...',
                'value' => $model->news_categorie_id = \Yii::$app->request->get(end(explode("\\", $model::className())))['news_categorie_id']
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
    </div>

    <!--div class="col-sm-6 col-lg-4">
        < ?= $form->field($model, 'data_rimozione')->widget(DateControl::className(), [
            'type' => DateControl::FORMAT_DATE
        ]) ?>
    </div-->
    <?php if (isset($moduleTag) && in_array(News::className(), $moduleTag->modelsEnabled) && $newsModule->searchByTags && $moduleTag->behaviors): ?>
        <div class="col-xs-12">
            <?php
            $params = \Yii::$app->request->getQueryParams();
            echo \open20\amos\tag\widgets\TagWidget::widget([
                'model' => $model,
                'attribute' => 'tagValues',
                'form' => $form,
                'isSearch' => true,
                'form_values' => isset($params[$model->formName()]['tagValues']) ? $params[$model->formName()]['tagValues'] : []
            ]);
            ?>
        </div>
    <?php endif; ?>


    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(AmosNews::t('amosnews', 'Annulla'), [isset($originAction) ? $originAction : Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')], [
                'class' => 'btn btn-outline-primary'
            ]) ?>
            <?= Html::submitButton(AmosNews::tHtml('amosnews', 'Cerca'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <!--a><p class="text-center">Ricerca avanzata<br>
        < ?=AmosIcons::show('caret-down-circle');?>
    </p></a-->

    <?php ActiveForm::end(); ?>

</div>
