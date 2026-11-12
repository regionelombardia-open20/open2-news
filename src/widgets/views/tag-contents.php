<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\news\widgets\graphics\views
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

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\ListView;
use open20\amos\news\AmosNews;
use open20\amos\news\assets\ModuleNewsAsset;
use open20\amos\news\controllers\NewsController;
use open20\amos\news\models\search\NewsSearch;
use open20\amos\tag\models\Tag;
use yii\data\ActiveDataProvider;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var array $tagContentsViewParams
 * @var string $models_classname_id
 * @var Tag $tag
 * @var bool $isActionIndex
 * @var NewsSearch $modelSearch
 * @var NewsController $controller
 */

ModuleNewsAsset::register($this);


// Search
$searchEnabled = $controller->module->params['searchParams'][$controller->id]['enable'];
$showSearch = $searchEnabled && !$isActionIndex;

// Sorting
$sortingEnabled = $controller->module->params['orderParams'][$controller->id]['enable'];
$showSorting = $sortingEnabled && !$isActionIndex;

$showChangeView = $showSearch || $showSorting;

$additionalContainerClass = '';
if ($isActionIndex && $tagContentsViewParams['showBackground']) {
    $additionalContainerClass .= ' background-active';
}

?>

<div class="tag-contents-news<?= $additionalContainerClass ?>">

    <?php if ($isActionIndex) {
        // Header
        echo $this->render('@vendor/open20/amos-tag/src/views/tag-contents/parts/_header', [
            'tagContentsViewParams' => $tagContentsViewParams,
            'models_classname_id' => $models_classname_id,
            'tag' => $tag
        ]);
    } ?>

    <?php if ($showChangeView) { ?>

        <div class="container-change-view">
            <div class="btn-tools-container flexbox">
                <div class="tools-right">
                    <?php if ($showSearch) {
                        // Filter search button
                        echo $this->render('@vendor/open20/amos-tag/src/views/tag-contents/parts/_filter-btn', [
                            'modelSearch' => $modelSearch,
                        ]);
                    } ?>

                    <?php if ($showSorting) {
                        // Sorting button
                        echo Html::tag('div', AmosIcons::show('unfold-more', [
                            'class' => 'btn btn-secondary show-hide-element',
                            'data-toggle-element' => 'form-order',
                            'title' => Yii::t('amoslayout', 'Cambia ordinamento'),
                            //'data-toggle' => 'tooltip'
                        ]), [
                            'class' => 'btn-group',
                            'tabindex' => 0
                        ]);
                    } ?>
                </div>
            </div>
        </div>

    <?php } ?>

    <div class="card-news news-index container">

        <?php if ($showSearch) {
            // Search form
            echo $this->render('@vendor/open20/amos-news/src/views/news/_search', [
                'model' => $modelSearch,
                'originAction' => Yii::$app->urlManager->createUrl(['/tag/tag-contents/detail',
                    'tag_id' => $tag->id,
                    'models_classname_id' => $models_classname_id
                ]),
                'isTagContentsSearch' => true,
            ]);
        } ?>

        <?php if ($showSorting) {
            // Sorting form
            echo $this->render('@vendor/open20/amos-news/src/views/news/_order', [
                'model' => $modelSearch,
                'originAction' => Yii::$app->urlManager->createUrl(['/tag/tag-contents/detail',
                    'tag_id' => $tag->id,
                    'models_classname_id' => $models_classname_id
                ]),
            ]);
        } ?>

        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '@vendor/open20/amos-news/src/views/news/_item',
        ]) ?>
    </div>

</div>
