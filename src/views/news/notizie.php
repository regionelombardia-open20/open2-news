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

use open20\amos\core\views\DataProviderView;
use open20\amos\news\AmosNews;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\news\models\search\NewsSearch $searchModel
 */
$this->title = AmosNews::t('amosnews', 'Notizie');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <?php
    echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            //'filterModel' => $model,
            'columns' => [
                'titolo',
                'sottotitolo',
                'descrizione_breve',
                //'descrizione:ntext',
//            'metakey:ntext', 
//            'metadesc:ntext', 
//                'primo_piano:statosino', 
//            'hits', 
//            'abilita_pubblicazione:statosino', 
                'news_categorie_id' => [
                    'attribute' => 'newsCategorie.titolo',
                    'label' => 'Categoria'
                ],
                ['attribute' => 'data_pubblicazione', 'format' => ['date', (isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],
                /*'condominio' => [
                    'label' => 'Pubblicata per',
                    'value' => function ($model) {
                        return $model->getNetworkPubblicazione();
                    }
                ],*/
//            ['attribute'=>'data_rimozione','format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']], 

                /*            'news_stati_id' => [
                  'attribute' => 'newsStati.nome',
                  'label' => 'Stato'
                  ], */
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                ],
            ],
        ],
        'listView' => [
            'itemView' => '_itemUtenti'
        ],
    ]);
    ?>

</div>
