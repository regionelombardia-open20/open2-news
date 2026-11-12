<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\news\views\news-categorie
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

use open20\amos\core\views\AmosGridView;
use open20\amos\news\AmosNews;
use open20\amos\news\models\NewsCategorie;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\news\models\search\NewsCategorieSearch $searchModel
 */

//cta: tutte le notizie
$this->title ='Categoria notizie';
$this->params['breadcrumbs'][] = ['label' => AmosNews::t('amosnews', 'Categorie notizie') , 'url' => '/news'];
//$this->params['breadcrumbs'][] = $this->title;

?>
<div class="news-categorie-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php echo AmosGridView::widget([
        'dataProvider' => $dataProvider,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
        //'filterModel' => $model,
        'columns' => [
            [
                'label' => $model->getAttributeLabel('categoryIcon'),
                'format' => 'html',
                'value' => function ($model) {
                    /** @var NewsCategorie $model */
                    $url = $model->getCategoryIconUrl();
                    $contentImage = Html::img($url, ['class' => 'gridview-image', 'alt' => $model->getAttributeLabel('categoryIcon')]);
                    return $contentImage;
                }
            ],
            'titolo',
            'sottotitolo',
            'descrizione_breve',
            'descrizione:html',
            [
                'class' => 'open20\amos\core\views\grid\ActionColumn'
            ]
        ]
    ]); ?>
</div>
