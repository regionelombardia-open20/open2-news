<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news
 * @category   CategoryName
 */

use lispa\amos\attachments\components\AttachmentsTableWithPreview;
use lispa\amos\core\forms\ContextMenuWidget;
use lispa\amos\core\forms\ItemAndCardHeaderWidget;
use lispa\amos\core\forms\PublishedByWidget;
use lispa\amos\core\forms\ShowUserTagsWidget;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\core\views\toolbars\StatsToolbar;
use lispa\amos\news\AmosNews;
use lispa\amos\core\forms\CreatedUpdatedWidget;
use lispa\amos\attachments\components\AttachmentsList;
use lispa\amos\core\forms\InteractionMenuWidget;
use lispa\amos\news\assets\ModuleNewsAsset;
use \lispa\amos\news\models\News;
ModuleNewsAsset::register($this);

/**
 * @var yii\web\View $this
 * @var lispa\amos\news\models\News $model
 */

$this->title = $model->titolo;


/** @var \lispa\amos\news\controllers\NewsController $controller */
$url = '/img/img_default.jpg';
if (!is_null($model->newsImage)) {
    $url = $model->newsImage->getWebUrl('square_large', false, true);
}

?>

<div class="news-view col-xs-12 nop">
    <div class="clearfix"></div>
    <div class="col-xs-12">
        <div class="header col-xs-12 nop">
            <img class="img-responsive" src="<?= $url ?>" alt="<?= $model->titolo ?>">
            <div class="title col-xs-12">
                <h2 class="title-text"><?= $model->titolo ?></h2>
                <h3 class="subtitle-text"><?= $model->sottotitolo ?></h3>
            </div>
        </div>
        <div class="text-content col-xs-12 nop">
            <?= $model->descrizione; ?>
        </div>
    </div>
    <div class="col-xs-12 text-center">
        <hr>
        <?= Html::a(AmosNews::t('amosnews', '#enter_into_platform'), ['/news/news/view', 'id' => $model->id], [
            'class' => 'btn btn-navigation-primary'
        ])?>
    </div>
</div>

