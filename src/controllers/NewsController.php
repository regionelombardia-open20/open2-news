<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

namespace open20\amos\news\controllers;

use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\BreadcrumbHelper;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\cwh\query\CwhActiveQuery;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\news\AmosNews;
use open20\amos\news\assets\ModuleNewsAsset;
use open20\amos\news\models\News;
use open20\amos\news\models\search\NewsSearch;
use raoul2000\workflow\base\WorkflowException;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
use ReflectionClass;
use open20\amos\core\widget\WidgetAbstract;

/**
 * Class NewsController
 *
 * NewsController implements the CRUD actions for News model.
 *
 * @package open20\amos\news\controllers
 */
class NewsController extends CrudController
{

    /**
     * Trait used for initialize the news dashboard
     */
    use TabDashboardControllerTrait;
    public
        $layout     = 'list', // @var string $layout
        $newsModule = null, // @var AmosNews|null $newsModule
        $moduleCwh,
        $scope

    ;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initDashboardTrait();

        $this->setModelObj(AmosNews::instance()->createModel('News'));
        $this->setModelSearch(AmosNews::instance()->createModel('NewsSearch'));

        ModuleNewsAsset::register(Yii::$app->view);

        $this->newsModule = Yii::$app->getModule(AmosNews::getModuleName());
        $this->moduleCwh  = Yii::$app->getModule('cwh');

        $this->scope = null;
        if (!empty($this->moduleCwh)) {
            $this->scope = $this->moduleCwh->getCwhScope();
        }

        $this->viewList = [
            'name' => 'list',
            'label' => AmosIcons::show('view-list').Html::tag('p', AmosNews::t('amosnews', 'Card')),
            'url' => '?currentView=list',
        ];

        $this->viewGrid = [
            'name' => 'grid',
            'label' => AmosIcons::show('view-list-alt').Html::tag('p', AmosNews::t('amosnews', 'Tabella')),
            'url' => '?currentView=grid',
        ];

        $defaultViews = [
            'list' => $this->viewList,
            'grid' => $this->viewGrid,
        ];

        $availableViews = [];
        foreach ($this->newsModule->defaultListViews as $view) {
            if (isset($defaultViews[$view])) {
                $availableViews[$view] = $defaultViews[$view];
            }
        }

        $this->setAvailableViews($availableViews);

        parent::init();

        $this->setUpLayout();
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Lists all the validated news.
     * @return string
     */
    public function actionIndex($layout = null)
    {
        // ?!??!??!?!?!
        return $this->redirect(['/news/news/all-news']);

        Url::remember();

        $this->setDataProvider($this->getModelSearch()->searchAll(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(AmosNews::t('amosnews', 'Tutte le notizie'));
        $this->setListViewsParams();

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render(
                'index',
                [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'parametro' => ($this->parametro) ? $this->parametro : null
                ]
        );
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(
                parent::behaviors(),
                [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'own-news',
                                'to-validate-news',
                                'all-news',
                                'admin-all-news',
                                'own-interest-news'
                            ],
                            'roles' => ['AMMINISTRATORE_NEWS']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'validate-news',
                                'reject-news',
                            ],
                            'roles' => ['AMMINISTRATORE_NEWS', 'FACILITATORE_NEWS', 'FACILITATOR']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'own-news',
                                'all-news',
                                'own-interest-news'
                            ],
                            'roles' => ['LETTORE_NEWS']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'to-validate-news',
                                'all-news',
                                'validate-news',
                                'own-interest-news'
                            ],
                            'roles' => ['VALIDATORE_NEWS']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'own-news',
                                'to-validate-news',
                                'all-news',
                                'own-interest-news'
                            ],
                            'roles' => ['FACILITATORE_NEWS']
                        ],
                        [
                            'allow' => true,
                            'actions' => [
                                'to-validate-news',
                                'validate-news',
                                'reject-news',
                            ],
                            'roles' => ['NewsValidateOnDomain']
                        ],
                    ]
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post', 'get']
                    ]
                ]
                ]
        );

        return $behaviors;
    }

    /**
     * Used for set page title and breadcrumbs.
     * @param string $newsPageTitle News page title (ie. Created by news, ...)
     */
    private function setTitleAndBreadcrumbs($newsPageTitle)
    {
        $this->setNetworkDashboardBreadcrumb();
        Yii::$app->session->set('previousTitle', $newsPageTitle);
        Yii::$app->session->set('previousUrl', Url::previous());
        Yii::$app->view->title                   = $newsPageTitle;
        Yii::$app->view->params['breadcrumbs'][] = ['label' => $newsPageTitle];
    }

    /**
     * 
     */
    public function setNetworkDashboardBreadcrumb()
    {
        if (!empty($this->scope)) {
            if (isset($this->scope['community'])) {
                $communityId                             = $this->scope['community'];
                $community                               = \open20\amos\community\models\Community::findOne($communityId);
                $dashboardCommunityTitle                 = AmosNews::t('amosnews', "Dashboard").' '.$community->name;
                $dasbboardCommunityUrl                   = Yii::$app->urlManager->createUrl(['community/join', 'id' => $communityId]);
                Yii::$app->view->params['breadcrumbs'][] = ['label' => $dashboardCommunityTitle, 'url' => $dasbboardCommunityUrl];
            }
        }
    }

    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    private function setCreateNewBtnLabel()
    {
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => AmosNews::t('amosnews', 'Add new news'),
            'urlCreateNew' => [(array_key_exists("noWizardNewLayout", Yii::$app->params) ? '/news/news/create' : '/news/news-wizard/introduction')]
        ];
    }

    /**
     * This method is useful to set all common params for all list views.
     */
    protected function setListViewsParams()
    {
        $this->setCreateNewBtnLabel();
        Yii::$app->session->set(AmosNews::beginCreateNewSessionKey(), Url::previous());
    }

    /**
     * Action for search all validated news.
     *
     * @return string
     */
    public function actionNotizie()
    {
        Url::remember();

        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        $this->setListViewsParams();

        return $this->render(
                'notizie',
                [
                'dataProvider' => $this->getDataProvider(),
                'currentView' => $this->getAvailableView('list'),
                ]
        );
    }

    /**
     * Displays a single News model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        /** @var News $model */
        $model = $this->findModel($id);
        if (isset(Yii::$app->params['isPoi']) && Yii::$app->params['isPoi'] == true) {
            if ($id == 2579) {
                $cwhActiveQuery = new CwhActiveQuery(News::className());
                $queryUsers     = $cwhActiveQuery->getRecipients($model->regola_pubblicazione, $model->tagValues,
                    $model->destinatari);
                $users          = ArrayHelper::map($queryUsers->all(), 'id', 'id');
                if (!in_array(Yii::$app->user->id, $users)) {
                    return $this->redirect('\dashboard');
                }
            }
        }

        $this->setUpLayout('main');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'idNews' => $id]);
        }

        return $this->render(
                'view',
                [
                'model' => $model,
                'moduleCwh' => $this->moduleCwh,
                'scope' => $this->scope
                ]
        );
    }

    /**
     * @param int $id News id.
     * @return \yii\web\Response
     */
    public function actionValidateNews($id)
    {
        $news = News::findOne($id);
        try {
            $news->sendToStatus(News::NEWS_WORKFLOW_STATUS_VALIDATO);
//            if($news->data_pubblicazione == ''){
//                $news->data_pubblicazione = date('Y-m-d');
//            } else {
//                if(strtotime($news->data_pubblicazione) < strtotime(date('Y-m-d'))) {
//                    $news->data_pubblicazione = date('Y-m-d');
//                }
//            }
            $ok = $news->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success', AmosNews::t('amosnews', 'News validated!'));
            } else {
                Yii::$app->session->addFlash('danger', AmosNews::t('amosnews', '#ERROR_WHILE_VALIDATING_NEWS'));
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
            return $this->redirect(Url::previous());
        }

        return $this->redirect(Url::previous());
    }

    /**
     * 
     * @param type $model
     * @param type $redirect
     * @return type
     */
    public function setDaValidareStatus($model, $redirect)
    {
        $news = $model;
        try {
            $news->sendToStatus(News::NEWS_WORKFLOW_STATUS_DAVALIDARE);
            $ok = $news->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success', AmosNews::t('amosnews', 'News validated!'));
            } else {
                Yii::$app->session->addFlash('danger', AmosNews::t('amosnews', '#ERROR_WHILE_VALIDATING_NEWS'));
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
            return $this->redirect(Url::previous());
        }

        return $redirect;
    }

    /**
     * @param int $id News id.
     * @return \yii\web\Response
     */
    public function actionRejectNews($id)
    {
        $newsModel = $this->newsModule->model('News');
        $news      = $newsModel::findOne($id);
        try {
            $news->sendToStatus(News::NEWS_WORKFLOW_STATUS_BOZZA);
            $ok = $news->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success', AmosNews::t('amosnews', 'News rejected!'));
            } else {
                Yii::$app->session->addFlash('danger', AmosNews::t('amosnews', '#ERROR_WHILE_REJECTING_NEWS'));
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
            return $this->redirect(Url::previous());
        }

        return $this->redirect(Url::previous());
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        $this->registerConfirmJs();

        Yii::$app->view->params['textHelp']['filename'] = 'create_news_dashboard_description';

        $module = \Yii::$app->getModule(AmosNews::getModuleName());
        if ($module->hidePubblicationDate) {
            $scenario = News::SCENARIO_CREATE_HIDE_PUBBLICATION_DATE;
        } else {
            $scenario = News::SCENARIO_CREATE;
        }

        $model = $module->createModel('News', ['scenario' => $scenario]);

        $this->model = $model;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $validateOnSave = true;
                if ($model->status == News::NEWS_WORKFLOW_STATUS_DAVALIDARE) {
                    $model->status  = News::NEWS_WORKFLOW_STATUS_BOZZA;
                    $model->save();
                    $model->status  = News::NEWS_WORKFLOW_STATUS_DAVALIDARE;
                    $validateOnSave = false;
                }

                if ($model->status == News::NEWS_WORKFLOW_STATUS_VALIDATO) {
                    $model->status  = News::NEWS_WORKFLOW_STATUS_BOZZA;
                    $model->save();
                    $model->status  = News::NEWS_WORKFLOW_STATUS_VALIDATO;
                    $validateOnSave = false;
                }

                if ($model->save($validateOnSave)) {
                    Yii::$app->getSession()->addFlash('success',
                        AmosNews::t('amosnews', 'Notizia salvata con successo.'));

                    $redirectToUpdatePage = false;

                    if (Yii::$app->getUser()->can('NEWS_UPDATE', ['model' => $model])) {
                        $redirectToUpdatePage = true;
                    }

                    if (Yii::$app->getUser()->can('NewsValidate', ['model' => $model])) {
                        $redirectToUpdatePage = true;
                    }

                    if ($redirectToUpdatePage) {
                        return $this->redirect(['/news/news/update', 'id' => $model->id]);
                    } else {
                        return $this->redirect('/news/news/own-news');
                    }
                } else {
                    Yii::$app->getSession()->addFlash('danger',
                        AmosNews::t('amosnews', 'Si &egrave; verificato un errore durante il salvataggio'));

                    return $this->render(
                            'create',
                            [
                            'model' => $model,
                            'moduleCwh' => $this->moduleCwh,
                            'scope' => $this->scope
                            ]
                    );
                }
            } else {
                Yii::$app->getSession()->addFlash('danger',
                    AmosNews::t('amosnews', "Modifiche non salvate. Verifica l'inserimento dei campi"));
            }
        }

        return $this->render(
                'create',
                [
                'model' => $model,
                'moduleCwh' => $this->moduleCwh,
                'scope' => $this->scope
                ]
        );
    }

    /**
     * Il metodo registra, all'evento di READY, il javascript di conferma su ogni elemento su cui è necessario.
     */
    private function registerConfirmJs()
    {
        $btnIds = [
            'new-news-attachment'
        ];

        $confirmJs = $this->createConfirmJsString($btnIds);
        Yii::$app->view->registerJs($confirmJs, View::POS_READY);
    }

    /**
     * Il metodo crea la stringa javascript pronta da registrare con tutti i listener sull'evento di click che chiedono la conferma
     * concatenando tutti i javascript per ciascun id presente nell'array di stringhe passato come parametro.
     *
     * @param array $elementIds
     * @return string
     */
    private function createConfirmJsString($elementIds)
    {
        $confirmJsString = '';
        foreach ($elementIds as $elementId) {
            $confirmJsString .= $this->javascriptConfirm($elementId);
        }

        return $confirmJsString;
    }

    /**
     * TBD manca la traduzione!!!!

     * Il metodo crea il listener sull'evento di click per un qualche elemento del DOM.
     * L'evento mostra un messaggio e chiede conferma.
     *
     * @param $buttonId
     * @return string
     */
    private function javascriptConfirm($elementId)
    {
        return "
      $('#".$elementId."').click(function (e) {
        return confirm('Attenzione! Si sta per lasciare la pagina. Salvare tutti i dati, altrimenti andranno persi.');
      });
    ";
    }

    /**
     * Updates an existing News model.
     *
     * @param integer $id
     * @param bool|false $backToEditStatus Save the model with status Editing in progress before form rendering
     *
     * @return mixed
     */
    public function actionUpdate($id, $backToEditStatus = false)
    {
        $this->setUpLayout('form');

        /** @var News $model */
        $model = $this->findModel($id);
        $this->registerConfirmJs();

        Yii::$app->view->params['textHelp']['filename'] = 'create_news_dashboard_description';
        $redirectToUpdatePage = false;
        if (Yii::$app->getUser()->can('NEWS_UPDATE', ['model' => $model])) {
            $redirectToUpdatePage = true;
        }

        if (Yii::$app->getUser()->can('NewsValidate', ['model' => $model])) {
            $redirectToUpdatePage = true;
        }

        if (Yii::$app->request->post()) {
            $previousStatus = $model->status;
            if ($model->load(Yii::$app->request->post())) {

                if ($model->validate()) {
                    if ($model->save()) {
                        $redirectToUpdatePage = false;
                        if (Yii::$app->getUser()->can('NEWS_UPDATE', ['model' => $model])) {
                            $redirectToUpdatePage = true;
                        }

                        if (Yii::$app->getUser()->can('NewsValidate', ['model' => $model])) {
                            $redirectToUpdatePage = true;
                        }

                        if ($redirectToUpdatePage) {
                            Yii::$app->getSession()->addFlash('success',
                                AmosNews::t('amosnews', 'Notizia aggiornata con successo.'));
                            if (strpos($model->status, 'VALIDATO')) {
                                //return $this->redirect(BreadcrumbHelper::lastCrumbUrl());
                                return $this->redirect(['/news/news/update', 'id' => $model->id]);
                            } elseif (strpos($model->status, 'BOZZA') && strpos($previousStatus, 'DAVALIDARE')) {
                                return $this->redirect(BreadcrumbHelper::lastCrumbUrl());
                            } else {
                                return $this->redirect(['/news/news/update', 'id' => $model->id]);
                            }
                        } else {
                            return $this->redirect('/news/news/own-news');
                        }
                    } else {
                        Yii::$app->getSession()->addFlash('danger',
                            AmosNews::t('amosnews', 'Si &egrave; verificato un errore durante il salvataggio'));
                        return $this->render(
                                'update',
                                [
                                'model' => $model,
                                'moduleCwh' => $this->moduleCwh,
                                'scope' => $this->scope
                        ]);
                    }
                } else {
                    Yii::$app->getSession()->addFlash('danger',
                        AmosNews::t('amosnews', "Modifiche non salvate. Verifica l'inserimento dei campi"));
                }
            }
        } else {
            if ($backToEditStatus && ($model->status != $model->getDraftStatus() && !$redirectToUpdatePage)) {
                $model->status = $model->getDraftStatus();
                if ($model->validate()) {
                    $ok = $model->save();
                    if (!$ok) {
                        Yii::$app->getSession()->addFlash('danger',
                            AmosNews::t('amosnews', 'Si &egrave; verificato un errore durante il salvataggio'));
                    }
                } else {
                    Yii::$app->getSession()->addFlash('danger',
                        AmosNews::t('amosnews', "Modifiche non salvate. Verifica l'inserimento dei campi"));
                }
            }
        }

        if ($redirectToUpdatePage) {
            return $this->render(
                    'update',
                    [
                    'model' => $model,
                    'moduleCwh' => $this->moduleCwh,
                    'scope' => $this->scope
                    ]
            );
        }

        return $this->redirect('/news/news/own-news');
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        /** @var News $model */
        $model = $this->findModel($id);
        $model->delete();

        if (!$model->hasErrors()) {
            Yii::$app->getSession()->addFlash('success', AmosNews::t('amosnews', 'Notizia cancellata correttamente.'));
        } else {
            Yii::$app->getSession()->addFlash('danger',
                AmosNews::t('amosnews', 'Non sei autorizzato a cancellare la notizia.'));
        }

        return $this->redirect(Yii::$app->session->get(AmosNews::beginCreateNewSessionKey()));
    }

    /**
     * Action to search only for their news
     *
     * @return string
     */
    public function actionOwnNews($currentView = null)
    {
        Url::remember();

        Yii::$app->view->params['textHelp']['filename'] = 'news_dashboard_description';
        $this->setDataProvider(
            $this->getModelSearch()
                ->searchOwnNews(Yii::$app->request->getQueryParams())
        );

        $this->setTitleAndBreadcrumbs(AmosNews::t('amosnews', 'Notizie create da me'));
        $this->setListViewsParams();
        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosNews::t('amosnews', '{iconaTabella}'.Html::tag('p', AmosNews::t('amosnews', 'Tabella')),
                    [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ]
        ]);

        $this->setCurrentView($this->getAvailableView('grid'));

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render(
                'index',
                [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : NULL,
                'parametro' => ($this->parametro) ? $this->parametro : NULL
                ]
        );
    }

    /**
     * Action to search to validate news.
     *
     * @return string
     */
    public function actionToValidateNews()
    {
        Url::remember();

        Yii::$app->view->params['textHelp']['filename'] = 'news_dashboard_description';
        $this->setDataProvider($this->getModelSearch()->searchToValidateNews(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(AmosNews::t('amosnews', 'Notizie da validare'));
        $this->setListViewsParams();

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosNews::t('amosnews', '{iconaTabella}'.Html::tag('p', AmosNews::t('amosnews', 'Tabella')),
                    [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ]
        ]);

        $this->setCurrentView($this->getAvailableView('grid'));
        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render(
                'index',
                [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'parametro' => ($this->parametro) ? $this->parametro : null
                ]
        );
    }

    /**
     * Action for search all news.
     *
     * @return string
     */
    public function actionAllNews($currentView = null)
    {
        Url::remember();

        if (empty($currentView)) {
            $currentView = reset($this->newsModule->defaultListViews);
        }

        Yii::$app->view->params['textHelp']['filename'] = 'news_dashboard_description';
        $this->setDataProvider($this->getModelSearch()->searchAll(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(AmosNews::t('amosnews', 'Tutte le notizie'));
        $this->setListViewsParams();
        $this->setCurrentView($this->getAvailableView($currentView));

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render(
                'index',
                [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : NULL,
                'parametro' => ($this->parametro) ? $this->parametro : NULL
                ]
        );
    }

    /**
     * @param null $currentView
     * @return string
     */
    public function actionAdminAllNews($currentView = null)
    {
        Url::remember();

        if (empty($currentView)) {
            $currentView = reset($this->newsModule->defaultListViews);
        }

        $this->setDataProvider($this->getModelSearch()->searchAdminAll(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(AmosNews::t('amosnews', 'Amministra notizie'));
        $this->setListViewsParams();
        $this->setCurrentView($this->getAvailableView($currentView));

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render(
                'index',
                [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'parametro' => ($this->parametro) ? $this->parametro : null
                ]
        );
    }

    /**
     * Action for search all news.
     *
     * @return string
     */
    public function actionOwnInterestNews($currentView = null)
    {
        Url::remember();

        Yii::$app->view->params['textHelp']['filename'] = 'news_dashboard_description';
        if (empty($currentView)) {
            $currentView = reset($this->newsModule->defaultListViews);
        }

        $this->setDataProvider($this->getModelSearch()->searchOwnInterest(Yii::$app->request->getQueryParams()));

        $this->setTitleAndBreadcrumbs(AmosNews::t('amosnews', 'Notizie di mio interesse'));
        $this->setListViewsParams();
        $this->setCurrentView($this->getAvailableView($currentView));

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render(
                'index',
                [
                'dataProvider' => $this->getDataProvider(),
                'model' => $this->getModelSearch(),
                'currentView' => $this->getCurrentView(),
                'availableViews' => $this->getAvailableViews(),
                'url' => ($this->url) ? $this->url : null,
                'parametro' => ($this->parametro) ? $this->parametro : null
                ]
        );
    }

    /**
     * @param $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionPublic($id)
    {
        $model        = $this->findModel($id);
        $this->layout = 'form';
        if ($this->isContentShared($id)) {
            return $this->render('public', ['model' => $model]);
        }
    }
}