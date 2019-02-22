<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news
 * @category   CategoryName
 */

namespace lispa\amos\news\controllers;

use lispa\amos\core\controllers\CrudController;
use lispa\amos\core\helpers\BreadcrumbHelper;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\cwh\query\CwhActiveQuery;
use lispa\amos\dashboard\controllers\TabDashboardControllerTrait;
use lispa\amos\news\AmosNews;
use lispa\amos\news\assets\ModuleNewsAsset;
use lispa\amos\news\models\News;
use lispa\amos\news\models\search\NewsSearch;
use raoul2000\workflow\base\WorkflowException;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
use ReflectionClass;

/**
 * Class NewsController
 *
 * NewsController implements the CRUD actions for News model.
 *
 * @package lispa\amos\news\controllers
 */
class NewsController extends CrudController {

    /**
     * Trait used for initialize the news dashboard
     */
    use TabDashboardControllerTrait;

    /**
     * @var string $layout
     */
    public $layout = 'list';

    /**
     * @var AmosNews|null $newsModule
     */
    public $newsModule = null;

    /**
     * @inheritdoc
     */
    public function behaviors() {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
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
        ]);
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function init() {
        $this->initDashboardTrait();

        $this->setModelObj(AmosNews::instance()->createModel('News'));
        $this->setModelSearch(AmosNews::instance()->createModel('NewsSearch'));

        ModuleNewsAsset::register(Yii::$app->view);

        $this->newsModule = Yii::$app->getModule(AmosNews::getModuleName());

        $this->viewList = [
            'name' => 'list',
            'label' => AmosIcons::show('view-list') . Html::tag('p', AmosNews::t('amosnews', 'Card')),
            'url' => '?currentView=list'
        ];

        $this->viewGrid = [
            'name' => 'grid',
            'label' => AmosIcons::show('view-list-alt') . Html::tag('p', AmosNews::t('amosnews', 'Tabella')),
            'url' => '?currentView=grid'
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
    public function actions() {
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
    public function actionIndex($layout = NULL) {
        return $this->redirect(['/news/news/all-news']);

        Url::remember();

        $this->setDataProvider($this->getModelSearch()->searchAll(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(AmosNews::t('amosnews', 'Tutte le notizie'));
        $this->setListViewsParams();

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render('index', [
                    'dataProvider' => $this->getDataProvider(),
                    'model' => $this->getModelSearch(),
                    'currentView' => $this->getCurrentView(),
                    'availableViews' => $this->getAvailableViews(),
                    'url' => ($this->url) ? $this->url : NULL,
                    'parametro' => ($this->parametro) ? $this->parametro : NULL
        ]);
    }

    /**
     * Used for set page title and breadcrumbs.
     * @param string $newsPageTitle News page title (ie. Created by news, ...)
     */
    private function setTitleAndBreadcrumbs($newsPageTitle) {
        $this->setNetworkDashboardBreadcrumb();
        Yii::$app->session->set('previousTitle', $newsPageTitle);
        Yii::$app->session->set('previousUrl', Url::previous());
        Yii::$app->view->title = $newsPageTitle;
        Yii::$app->view->params['breadcrumbs'][] = ['label' => $newsPageTitle];
    }

    public function setNetworkDashboardBreadcrumb() {
        /** @var \lispa\amos\cwh\AmosCwh $moduleCwh */
        $moduleCwh = Yii::$app->getModule('cwh');
        $scope = NULL;
        if (!empty($moduleCwh)) {
            $scope = $moduleCwh->getCwhScope();
        }
        if (!empty($scope)) {
            if (isset($scope['community'])) {
                $communityId = $scope['community'];
                $community = \lispa\amos\community\models\Community::findOne($communityId);
                $dashboardCommunityTitle = AmosNews::t('amosnews', "Dashboard") . ' ' . $community->name;
                $dasbboardCommunityUrl = Yii::$app->urlManager->createUrl(['community/join', 'id' => $communityId]);
                Yii::$app->view->params['breadcrumbs'][] = ['label' => $dashboardCommunityTitle, 'url' => $dasbboardCommunityUrl];
            }
        }
    }

    /**
     * Set a view param used in \lispa\amos\core\forms\CreateNewButtonWidget
     */
    private function setCreateNewBtnLabel() {
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => AmosNews::t('amosnews', 'Add new news'),
            'urlCreateNew' => [(array_key_exists("noWizardNewLayout", Yii::$app->params) ? '/news/news/create' : '/news/news-wizard/introduction')]
        ];
    }

    /**
     * This method is useful to set all common params for all list views.
     */
    protected function setListViewsParams() {
        $this->setCreateNewBtnLabel();
        Yii::$app->session->set(AmosNews::beginCreateNewSessionKey(), Url::previous());
    }

    /**
     * Action for search all validated news.
     *
     * @return string
     */
    public function actionNotizie() {
        Url::remember();

        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        $this->setListViewsParams();
        return $this->render('notizie', [
                    'dataProvider' => $this->getDataProvider(),
                    'currentView' => $this->getAvailableView('list'),
        ]);
    }

    /**
     * Displays a single News model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id) {
        /** @var News $model */
        $model = $this->findModel($id);
        if (isset(Yii::$app->params['isPoi']) && Yii::$app->params['isPoi'] == true) {
            if ($id == 2579) {
                $cwhActiveQuery = new CwhActiveQuery(News::className());
                $queryUsers = $cwhActiveQuery->getRecipients($model->regola_pubblicazione, $model->tagValues, $model->destinatari);
                $users = ArrayHelper::map($queryUsers->all(), 'id', 'id');
                if (!in_array(Yii::$app->user->id, $users)) {
                    return $this->redirect('\dashboard');
                }
            }
        }
        $this->setUpLayout('main');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'idNews' => $id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * @param int $id News id.
     * @return \yii\web\Response
     */
    public function actionValidateNews($id) {
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

    public function setDaValidareStatus($model, $redirect) {
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
    public function actionRejectNews($id) {
        $newsModel = $this->newsModule->model('News');
        $news = $newsModel::findOne($id);
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
    public function actionCreate() {
        $this->setUpLayout('form');
        $module = \Yii::$app->getModule(AmosNews::getModuleName());
        
        if ($module->hidePubblicationDate) {
            $model =  $module->createModel('News', ['scenario' => News::SCENARIO_CREATE_HIDE_PUBBLICATION_DATE]);
        } else {
            $model = $module->createModel('News', ['scenario' => News::SCENARIO_CREATE]);
        }
        $this->model = $model;

        $this->registerConfirmJs();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $validateOnSave = true;
                if ($model->status == News::NEWS_WORKFLOW_STATUS_DAVALIDARE) {
                    $model->status = News::NEWS_WORKFLOW_STATUS_BOZZA;
                    $model->save();
                    $model->status = News::NEWS_WORKFLOW_STATUS_DAVALIDARE;
                    $validateOnSave = false;
                }

                if ($model->status == News::NEWS_WORKFLOW_STATUS_VALIDATO) {
                    $model->status = News::NEWS_WORKFLOW_STATUS_BOZZA;
                    $model->save();
                    $model->status = News::NEWS_WORKFLOW_STATUS_VALIDATO;
                    $validateOnSave = false;
                }
                if ($model->save($validateOnSave)) {
                    Yii::$app->getSession()->addFlash('success', AmosNews::t('amosnews', 'Notizia salvata con successo.'));

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
                    Yii::$app->getSession()->addFlash('danger', AmosNews::t('amosnews', 'Si &egrave; verificato un errore durante il salvataggio'));
                    return $this->render('create', [
                                'model' => $model,
                    ]);
                }
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosNews::t('amosnews', "Modifiche non salvate. Verifica l'inserimento dei campi"));
            }
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Il metodo registra, all'evento di READY, il javascript di conferma su ogni elemento su cui è necessario.
     */
    private function registerConfirmJs() {
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
    private function createConfirmJsString($elementIds) {
        $confirmJsString = "";
        foreach ($elementIds as $elementId) {
            $confirmJsString .= $this->javascriptConfirm($elementId);
        }
        return $confirmJsString;
    }

    /**
     * Il metodo crea il listener sull'evento di click per un qualche elemento del DOM. L'evento mostra un messaggio e chiede conferma.
     *
     * @param $buttonId
     * @return string
     */
    private function javascriptConfirm($elementId) {
        return "
            $('#" . $elementId . "').click(function (e) {
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
    public function actionUpdate($id, $backToEditStatus = false) {
        $this->setUpLayout('form');

        /** @var News $model */
        $model = $this->findModel($id);
        $this->registerConfirmJs();

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

//                if(strpos($model->status, 'VALIDATO') !== FALSE){
//                    if($model->data_pubblicazione == ''){
//                        $model->data_pubblicazione = date('Y-m-d');
//                    } else {
//                        if(strtotime($model->data_pubblicazione) < strtotime(date('Y-m-d'))) {
//                            $model->data_pubblicazione = date('Y-m-d');
//                        }
//                    }
//                }
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
                            Yii::$app->getSession()->addFlash('success', AmosNews::t('amosnews', 'Notizia aggiornata con successo.'));
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
                        Yii::$app->getSession()->addFlash('danger', AmosNews::t('amosnews', 'Si &egrave; verificato un errore durante il salvataggio'));
                        return $this->render('update', [
                                    'model' => $model,
                        ]);
                    }
                } else {
                    Yii::$app->getSession()->addFlash('danger', AmosNews::t('amosnews', "Modifiche non salvate. Verifica l'inserimento dei campi"));
                }
            }
        } else {
            if ($backToEditStatus && ($model->status != $model->getDraftStatus() && !$redirectToUpdatePage)) {
                $model->status = $model->getDraftStatus();
                if ($model->validate()) {
                    $ok = $model->save();
                    if (!$ok) {
                        Yii::$app->getSession()->addFlash('danger', AmosNews::t('amosnews', 'Si &egrave; verificato un errore durante il salvataggio'));
                    }
                } else {
                    Yii::$app->getSession()->addFlash('danger', AmosNews::t('amosnews', "Modifiche non salvate. Verifica l'inserimento dei campi"));
                }
            }
        }
        if ($redirectToUpdatePage) {
            return $this->render('update', [
                        'model' => $model,
            ]);
        } else {
            return $this->redirect('/news/news/own-news');
        }
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id) {
        /** @var News $model */
        $model = $this->findModel($id);
        $model->delete();

        if (!$model->hasErrors()) {
            Yii::$app->getSession()->addFlash('success', AmosNews::t('amosnews', 'Notizia cancellata correttamente.'));
        } else {
            Yii::$app->getSession()->addFlash('danger', AmosNews::t('amosnews', 'Non sei autorizzato a cancellare la notizia.'));
        }
        return $this->redirect(Yii::$app->session->get(AmosNews::beginCreateNewSessionKey()));
    }

    /**
     * Action to search only for their news
     *
     * @return string
     */
    public function actionOwnNews($currentView = null) {
        Url::remember();

        $this->setDataProvider($this->getModelSearch()->searchOwnNews(Yii::$app->request->getQueryParams()));

        $this->setTitleAndBreadcrumbs(AmosNews::t('amosnews', 'Notizie create da me'));
        $this->setListViewsParams();
        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosNews::t('amosnews', '{iconaTabella}' . Html::tag('p', AmosNews::t('amosnews', 'Tabella')), [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ]
        ]);
        $this->setCurrentView($this->getAvailableView('grid'));

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render('index', [
                    'dataProvider' => $this->getDataProvider(),
                    'model' => $this->getModelSearch(),
                    'currentView' => $this->getCurrentView(),
                    'availableViews' => $this->getAvailableViews(),
                    'url' => ($this->url) ? $this->url : NULL,
                    'parametro' => ($this->parametro) ? $this->parametro : NULL
        ]);
    }

    /**
     * Action to search to validate news.
     *
     * @return string
     */
    public function actionToValidateNews() {
        Url::remember();

        $this->setDataProvider($this->getModelSearch()->searchToValidateNews(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(AmosNews::t('amosnews', 'Notizie da validare'));
        $this->setListViewsParams();

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosNews::t('amosnews', '{iconaTabella}' . Html::tag('p', AmosNews::t('amosnews', 'Tabella')), [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ]
        ]);
        $this->setCurrentView($this->getAvailableView('grid'));

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render('index', [
                    'dataProvider' => $this->getDataProvider(),
                    'model' => $this->getModelSearch(),
                    'currentView' => $this->getCurrentView(),
                    'availableViews' => $this->getAvailableViews(),
                    'url' => ($this->url) ? $this->url : NULL,
                    'parametro' => ($this->parametro) ? $this->parametro : NULL
        ]);
    }

    /**
     * Action for search all news.
     *
     * @return string
     */
    public function actionAllNews($currentView = null) {
        Url::remember();

        if (empty($currentView)) {
            $currentView = reset($this->newsModule->defaultListViews);
        }
        $this->setDataProvider($this->getModelSearch()->searchAll(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(AmosNews::t('amosnews', 'Tutte le notizie'));
        $this->setListViewsParams();
        $this->setCurrentView($this->getAvailableView($currentView));

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render('index', [
                    'dataProvider' => $this->getDataProvider(),
                    'model' => $this->getModelSearch(),
                    'currentView' => $this->getCurrentView(),
                    'availableViews' => $this->getAvailableViews(),
                    'url' => ($this->url) ? $this->url : NULL,
                    'parametro' => ($this->parametro) ? $this->parametro : NULL
        ]);
    }

    /**
     * @param null $currentView
     * @return string
     */
    public function actionAdminAllNews($currentView = null) {
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

        return $this->render('index', [
                    'dataProvider' => $this->getDataProvider(),
                    'model' => $this->getModelSearch(),
                    'currentView' => $this->getCurrentView(),
                    'availableViews' => $this->getAvailableViews(),
                    'url' => ($this->url) ? $this->url : NULL,
                    'parametro' => ($this->parametro) ? $this->parametro : NULL
        ]);
    }

    /**
     * Action for search all news.
     *
     * @return string
     */
    public function actionOwnInterestNews($currentView = null) {
        Url::remember();

        if (empty($currentView)) {
            $currentView = reset($this->newsModule->defaultListViews);
        }
        $this->setDataProvider($this->getModelSearch()->searchOwnInterest(Yii::$app->request->getQueryParams()));

        $this->setTitleAndBreadcrumbs(AmosNews::t('amosnews', 'Notizie di mio interesse'));
        $this->setListViewsParams();
        $this->setCurrentView($this->getAvailableView($currentView));

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render('index', [
                    'dataProvider' => $this->getDataProvider(),
                    'model' => $this->getModelSearch(),
                    'currentView' => $this->getCurrentView(),
                    'availableViews' => $this->getAvailableViews(),
                    'url' => ($this->url) ? $this->url : NULL,
                    'parametro' => ($this->parametro) ? $this->parametro : NULL
        ]);
    }

}
