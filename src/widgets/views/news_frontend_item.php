<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-news
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

use open20\amos\core\helpers\Html;
use open20\amos\news\AmosNews;
?>
<?php /** @var $model \open20\amos\news\models\base\News */?>

    <div class="col-xs-12">
        <div class="col-xs-12 news-image">
            <?php
            $url = '/img/img_default.jpg';
            if (!is_null($model->newsImage)) {
                $url = $model->newsImage->getWebUrl('square_medium', false, true);
            }
            $contentImage = Html::img($url, [
                'class' => 'img-responsive',
                'alt' => 'image'
            ]);
            ?>
            <?= $contentImage ?>
        </div>
        <div class="col-xs-12 news-title">
            <h1><?= $model->titolo?></h1>
        </div>
        <div class="col-xs-12 news-subtitle">
            <h2><?= $model->sottotitolo?></h2>
        </div>
        <div class="col-xs-12 news-abstract">
            <p><?= $model->descrizione_breve?></p>
        </div>
        <div class="col-xs-12 news-description">
            <p><?= $model->descrizione?></p>
        </div>
        <?php if(\Yii::$app->getModule('sitemanagement') ){
            $url = \amos\sitemanagement\widgets\SMContainerWidget::getUrlContentModel($model)?>
            <div class="col-xs-12 news-read-all">
                <p><?= Html::a(AmosNews::t('amosnews', 'Leggi tutto'), $url) ?></p>
            </div>
        <?php } else { ?>
            <div class="col-xs-12 news-read-all">
                <p><?= Html::a(AmosNews::t('amosnews', 'Leggi tutto'), $model->getFullViewUrl()) ?></p>
            </div>
        <?php } ?>
    </div>
