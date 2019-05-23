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

/**
 * @var yii\web\View $this
 * @var lispa\amos\news\models\News $model
 */

/** @var \lispa\amos\news\controllers\NewsController $controller */
$controller = Yii::$app->controller;
$controller->setNetworkDashboardBreadcrumb();
$this->title = $model->titolo;
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
//$this->params['breadcrumbs'][] = ['label' => $model->titolo, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = AmosNews::t('amosnews', 'Aggiorna');

?>

<div class="news-update">
<?= $this->render(
  '_form',
  [
    'model' => $model,
    'moduleCwh' => $moduleCwh,
    'scope' => $scope
  ])
?>
</div>