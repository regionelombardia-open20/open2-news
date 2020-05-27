<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\views\news
 * @category   CategoryName
 */
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\core\views\DataProviderView;
use open20\amos\news\AmosNews;
use open20\amos\news\models\News;
use open20\amos\news\widgets\NewsCarouselWidget;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\news\models\search\NewsSearch $searchModel
 * @var \open20\amos\dashboard\models\AmosUserDashboards $currentDashboard
 * @var string $currentView
 */
$actionColumnDefault = '{view}{update}{delete}';
$actionColumnToValidate = '{validate}{reject}';
$actionColumn = $actionColumnDefault;
if (Yii::$app->controller->action->id == 'to-validate-news') {
  $actionColumn = $actionColumnToValidate . $actionColumnDefault;
}
$hidePubblicationDate = Yii::$app->controller->newsModule->hidePubblicationDate;
$hideDataRimozioneView = Yii::$app->controller->newsModule->hideDataRimozioneView;

$queryParamCurrentView = Yii::$app->request->getQueryParam('currentView');
?>
<div class="news-index">
<?= $this->render('_search', ['model' => $model, 'queryParamCurrentView' => $queryParamCurrentView]); ?>
<?= $this->render('_order', ['model' => $model, 'queryParamCurrentView' => $queryParamCurrentView]); ?>

    <?= NewsCarouselWidget::widget(); ?>

    <?=
    DataProviderView::widget([
      'dataProvider' => $dataProvider,
      'currentView' => $currentView,
      'gridView' => [
        //'filterModel' => $model,
        'columns' => [
          //'sottotitolo',
          //'descrizione_breve',
          //'descrizione:ntext',
//            'metakey:ntext', 
//            'metadesc:ntext', 
//                'primo_piano:statosino', 
//            'hits', 
//            'abilita_pubblicazione:statosino',

          'immagine' => [
            'label' => AmosNews::t('amosnews', 'Immagine'),
            'format' => 'html',
            'value' => function ($model) {
              /** @var News $model */
              $url = '/img/img_default.jpg';
              if (!is_null($model->newsImage)) {
                $url = $model->newsImage->getUrl('table_small', false, true);
              }
              $contentImage = Html::img($url, ['class' => 'gridview-image', 'alt' => AmosNews::t('amosnews', 'Immagine della notizia')]);
              
              return Html::a($contentImage, $model->getFullViewUrl());
            }
          ],
          'titolo',
          'created_by' => [
            'attribute' => 'createdUserProfile',
            'label' => AmosNews::t('amosnews', 'Pubblicato Da'),
            'value' => function ($model) {
              return Html::a($model->createdUserProfile->nomeCognome, ['/admin/user-profile/view', 'id' => $model->createdUserProfile->id], [
                  'title' => AmosNews::t('amosnews', 'Apri il profilo di {nome_profilo}', ['nome_profilo' => $model->createdUserProfile->nomeCognome])
              ]);
            },
            'format' => 'html'
          ],
          'data_pubblicazione' => [
            'label' => $hidePubblicationDate ? AmosNews::t('amosnews', 'Pubblicato il') : AmosNews::t('amosnews', 'Pubblica dal'),
            'attribute' => 'data_pubblicazione',
            'value' => function ($model) {
              /** @var News $model */
              return (is_null($model->data_pubblicazione)) ? AmosNews::t('amosnews', 'Subito') : Yii::$app->formatter->asDate($model->data_pubblicazione);
            }
          ],
          'data_rimozione' => [
            'visible' => (!$hidePubblicationDate && !$hideDataRimozioneView),
            'attribute' => 'data_rimozione',
            'value' => function ($model) {
              /** @var News $model */
              return (is_null($model->data_rimozione)) ? AmosNews::t('amosnews', 'Mai') : Yii::$app->formatter->asDate($model->data_rimozione);
            }
          ],
          'status' => [
            'attribute' => 'status',
            'value' => function ($model) {
              /** @var News $model */
              return $model->hasWorkflowStatus() ? AmosNews::t('amosnews', $model->getWorkflowStatus()->getLabel()) : '--';
            }
          ],
          'news_categorie_id' => [
            'attribute' => 'newsCategorie.titolo',
            'label' => AmosNews::t('amosnews', 'Categoria')
          ],
//            ['attribute'=>'data_rimozione','format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],
          [
            'class' => 'open20\amos\core\views\grid\ActionColumn',
            'template' => $actionColumn,
            'buttons' => [
              'validate' => function ($url, $model) {
                /** @var News $model */
                $btn = '';
                if (Yii::$app->getUser()->can('NewsValidate', ['model' => $model])) {
                  $btn = ModalUtility::addConfirmRejectWithModal([
                      'modalId' => 'validate-news-modal-id',
                      'modalDescriptionText' => AmosNews::t('amosnews', '#VALIDATE_NEWS_MODAL_TEXT'),
                      'btnText' => AmosIcons::show('check-circle', ['class' => '']),
                      'btnLink' => Yii::$app->urlManager->createUrl(['/news/news/validate-news', 'id' => $model['id']]),
                      'btnOptions' => ['title' => AmosNews::t('amosnews', 'Publish'), 'class' => 'btn btn-tools-secondary']
                  ]);
                }
                
                return $btn;
              },
              'reject' => function ($url, $model) {
                /** @var News $model */
                $btn = '';
                if (Yii::$app->getUser()->can('NewsValidate', ['model' => $model])) {
                  $btn = ModalUtility::addConfirmRejectWithModal([
                      'modalId' => 'reject-news-modal-id',
                      'modalDescriptionText' => AmosNews::t('amosnews', '#REJECT_NEWS_MODAL_TEXT'),
                      'btnText' => AmosIcons::show('minus-circle', ['class' => '']),
                      'btnLink' => Yii::$app->urlManager->createUrl(['/news/news/reject-news', 'id' => $model['id']]),
                      'btnOptions' => ['title' => AmosNews::t('amosnews', 'Reject'), 'class' => 'btn btn-tools-secondary']
                  ]);
                }
                
                return $btn;
              },
              'update' => function ($url, $model) {
                /** @var News $model */
                $btn = '';
                if (Yii::$app->user->can('NEWS_UPDATE', ['model' => $model])) {
                  $action = '/news/news/update?id=' . $model->id;
                  $options = ModalUtility::getBackToEditPopup($model,
                      'NewsValidate', $action, ['class' => 'btn btn-tools-secondary', 'title' => Yii::t('amoscore', 'Modifica'), 'data-pjax' => '0']);
                  $btn = Html::a(AmosIcons::show('edit'), $action, $options);
                }
                
                return $btn;
              }
            ]
          ],
        ],
        'enableExport' => true
      ],
      'listView' => [
        'itemView' => '_item',
        'masonry' => true,
        'masonrySelector' => '.grid',
        'masonryOptions' => [
          'itemSelector' => '.grid-item',
          'columnWidth' => '.grid-sizer',
          'percentPosition' => 'true',
          'gutter' => 10
        ],
        'showItemToolbar' => false,
      ]
    ]);
    ?>
</div>
