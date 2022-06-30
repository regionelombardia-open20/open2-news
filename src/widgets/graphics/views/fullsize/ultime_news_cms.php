<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\widgets\graphics\views
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\news\AmosNews;
use open20\amos\news\assets\ModuleNewsAsset;
use open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews;
use yii\data\ActiveDataProvider;
use yii\web\View;
use open20\amos\core\utilities\CurrentUser;



ModuleNewsAsset::register($this);

/**
 * @var View $this
 * @var ActiveDataProvider $listaNews
 * @var WidgetGraphicsUltimeNews $widget
 * @var string $toRefreshSectionId
 */
$moduleNews  = \Yii::$app->getModule(AmosNews::getModuleName());
$listaModels = $listaNews->getModels();
$userModule = CurrentUser::getUserProfile();
?>

<?php

$modelLabel = 'news';

$titleSection = AmosNews::t('amosnews', 'Notizie');
$urlLinkAll = AmosNews::t('amosnews', '/news/news/all-news');
$labelLinkAll = AmosNews::t('amosnews', 'Tutte le notizie');
$titleLinkAll = AmosNews::t('amosnews', 'Visualizza la lista delle notizie');

$labelCreate = AmosNews::t('amosnews', 'Nuova');
$titleCreate = AmosNews::t('amosnews', 'Crea una nuova notizia');
$labelManage = AmosNews::t('amosnews', 'Gestisci');
$titleManage = AmosNews::t('amosnews', 'Gestisci le notizie');
$urlCreate = AmosNews::t('amosnews', '/news/news/create');

$manageLinks = [];
$controller = \open20\amos\news\controllers\NewsController::class;
if (method_exists($controller, 'getManageLinks')) {
    $manageLinks = $controller::getManageLinks();
}


$moduleCwh = \Yii::$app->getModule('cwh');
if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
    $scope = $moduleCwh->getCwhScope();
    $isSetScope = (!empty($scope)) ? true : false;
}

?>

<div class="widget-graphic-cms-bi-less card-<?= $modelLabel ?> <?= $modelLabel ?>-index container">
    <div class="page-header">
        <?= $this->render(
            "@vendor/open20/amos-layout/src/views/layouts/fullsize/parts/bi-less-plugin-header",
            [
                'isGuest' => \Yii::$app->user->isGuest,
                'isSetScope' => $isSetScope,
                'modelLabel' => 'news',
                'titleSection' => $titleSection,
                'subTitleSection' => $subTitleSection,
                'urlLinkAll' => $urlLinkAll,
                'labelLinkAll' => $labelLinkAll,
                'titleLinkAll' => $titleLinkAll,
                'labelCreate' => $labelCreate,
                'titleCreate' => $titleCreate,
                'labelManage' => $labelManage,
                'titleManage' => $titleManage,
                'urlCreate' => $urlCreate,
                'manageLinks' => $manageLinks,
            ]
        );
        ?>
    </div>

    <?php if ($listaModels) { ?>
        <div class="list-view">
            <div>
                <div class="" role="listbox" data-role="list-view">
                    <?php foreach ($listaModels as $singolaNews) : ?>
                        <div>
                            <?= $this->render("@vendor/open20/amos-news/src/views/news/_item", ['model' => $singolaNews]); ?>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>

    <?php } ?>
</div>