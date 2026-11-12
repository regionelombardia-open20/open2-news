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
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\news\models\search\NewsSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="news-order element-to-toggle" data-toggle-element="form-order">
    <div class="col-xs-12">
        <p class="h3"><?= AmosNews::t('amosnews', 'Ordina per') ?>:</p>
    </div>

    <?php $form = ActiveForm::begin([
        'action' => isset($originAction) ? [$originAction] : Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
//    echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView'));
    echo Html::hiddenInput("currentView", $queryParamCurrentView);
    ?>
    
    <div class="col-sm-6 col-lg-4">
        <?php 
            $orderAttributes = $model->getOrderAttributesLabels();
            
            $modulo = AmosNews::getModuleName();           
            $moduloObj = Yii::$app->getModule($modulo); 
            
            if($moduloObj->hasProperty('enableCustomOrderFields') && $moduloObj->enableCustomOrderFields){
            
                $defaultOrderField = $moduloObj->params['orderParams'][$modulo]['default_field'];
                $defaultOrderFieldLabel = isset($orderAttributes[$defaultOrderField]) ? $orderAttributes[$defaultOrderField] : Yii::t('amoscore','default-mancante');
                /*
                 * prendo tutti i campi del modulo a prescindere dal visibile perche
                 * inizialmente se nel db non è presenti nessuna riga mostro i campi di default.     
                 * 
                 */
                $aviableFields = \open20\amos\core\models\base\CustomOrderFields::find()->select(['colonna','visibile'])->where(['modulo'=>$modulo])->asArray()->all();

                if(!empty($aviableFields)){
                    /*
                    * se presenti i campi a db che sono sia visibili a 1 che a 0 
                    * considero solo quelli effettivamente a 1 per farli visualizzare
                    */
                    foreach($aviableFields as $row=>$col){
                        if($col['visibile'] == 0)
                            unset($aviableFields[$row]);
                    }                
                    $aviableFields = yii\helpers\ArrayHelper::map($aviableFields, 'colonna', 'visibile');             
                    $orderAttributes = array_intersect_key($orderAttributes,$aviableFields);

                    /**
                     * Se vengono tolti tutti viene preso quello di default dai params
                     */                
                    if(empty($orderAttributes)){
                        $orderAttributes = [$defaultOrderField => $defaultOrderFieldLabel];
                    }
                }
            }
            
        ?>
        <?= $form->field($model, 'orderAttribute')->dropDownList($orderAttributes) ?>
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
            <?= Html::a(AmosNews::t('amosnews', 'Annulla'), [isset($originAction) ? $originAction : Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                ['class'=>'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosNews::t('amosnews', 'Ordina'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>

</div>