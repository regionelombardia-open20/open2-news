<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news\views\news-categorie
 * @category   CategoryName
 */

use lispa\amos\news\AmosNews;

/**
 * @var yii\web\View $this
 * @var lispa\amos\news\models\NewsCategorie $model
 */

$this->title = AmosNews::t('amosnews', 'Crea categoria', [
    'modelClass' => 'News Categorie',
]);
$this->params['breadcrumbs'][] = ['label' => AmosNews::t('amosnews', 'Notizie'), 'url' => '/news'];
$this->params['breadcrumbs'][] = ['label' => AmosNews::t('amosnews', 'Categorie notizie'), 'url' => ['index']];
$this->params['breadcrumbs'][] = AmosNews::t('amosnews', 'Crea');
?>

<div class="news-categorie-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
