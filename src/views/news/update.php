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

/**
 * @var yii\web\View $this
 * @var open20\amos\news\models\News $model
 */

/** @var \open20\amos\news\controllers\NewsController $controller */
$controller = Yii::$app->controller;
$controller->setNetworkDashboardBreadcrumb();
$this->title = $model->titolo;
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = AmosNews::t('amosnews', 'Aggiorna');

if (!empty($_GET['redactional'])) {
    $this->params['forceBreadcrumbs'][] = ['label' => AmosNews::t('amosnews',"News"), 'url' => ['/news/news/redaction-all-news']];
    $this->params['forceBreadcrumbs'][] = ['label' => $this->title];
}


?>

<div class="news-update">
<?= $this->render('_form', [
    'model' => $model,
    'moduleCwh' => $moduleCwh,
    'scope' => $scope,

    /**
     * SiteManagementSlider
    */
    'slider_image' => $slider_image,
    'dataProviderSliderElemImage' => $dataProviderSliderElemImage,
    'slider_video' => $slider_video,
    'dataProviderSliderElemVideo' => $dataProviderSliderElemVideo,
    'siteManagementModule' => (isset($siteManagementModule) ? $siteManagementModule : null)
])
?>
</div>