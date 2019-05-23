<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news
 * @category   CategoryName
 */

namespace lispa\amos\news;

use lispa\amos\core\interfaces\CmsModuleInterface;
use lispa\amos\core\interfaces\SearchModuleInterface;
use lispa\amos\core\module\AmosModule;
use lispa\amos\core\module\ModuleInterface;
use lispa\amos\news\widgets\graphics\WidgetGraphicsUltimeNews;
use lispa\amos\news\widgets\icons\WidgetIconAllNews;
use lispa\amos\news\widgets\icons\WidgetIconNews;
use lispa\amos\news\widgets\icons\WidgetIconNewsCategorie;
use lispa\amos\news\widgets\icons\WidgetIconNewsCreatedBy;
use lispa\amos\news\widgets\icons\WidgetIconNewsDashboard;
use lispa\amos\news\widgets\icons\WidgetIconNewsDaValidare;
use yii\helpers\ArrayHelper;

/**
 * Class AmosNews
 * @package lispa\amos\news
 */
class AmosNews extends AmosModule implements ModuleInterface, SearchModuleInterface, CmsModuleInterface {

  const
    MAX_LAST_NEWS_ON_DASHBOARD = 3;

  public static $CONFIG_FOLDER = 'config';

  /**
   * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
   * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
   * will be taken. If this is false, layout will be disabled within this module.
   */
  public $layout = 'main';

  /**
   * @var string $name
   */
  public $name = 'Notizie';

  /**
   * If this attribute is true the validation of the publication date is active
   * @var boolean $validatePublicationDate
   */
  public $validatePublicationDate = true;

  /**
   * @var bool|false $filterCategoriesByRole - if true, enables category role check via table news_category_roles_mm
   */
  public $filterCategoriesByRole = false;
        
  /**
     * @var array
     */
    public $whiteListRolesCategories = ['ADMIN', 'BASIC_USER'];

  /**
   * @var bool|false $hidePubblicationDate
   */
  public $hidePubblicationDate = false;

  /**
   * Hide the Option wheel in the graphic widget
   * @var bool|false $hideWidgetGraphicsActions
   */
  public $hideWidgetGraphicsActions = false;

  /**
   * @var array $newsRequiredFields - mandatory fields in News form
   */
  public $newsRequiredFields = [
    'news_categorie_id',
    'titolo',
    'status',
    'descrizione',
  ];

  /**
   * The ID of the default category pre-selected for the new News
   * @var integer
   */
  public $defaultCategory;

  /**
   * The default value for enable comments
   * @var integer
   */
  public $defaultEnableComments = 1;

  /**
   * @var bool $hideDataRimozioneView
   */
  public $hideDataRimozioneView = false;

  /**
   * @var array $defaultListViews This set the default order for the views in lists
   */
  public $defaultListViews = ['list', 'grid'];

  /**
   * This set the auto update of the publication date on the save if the news is published
   * @var boolean $autoUpdatePublicationDate
   */
  public $autoUpdatePublicationDate = false;

  /**
   *
   * @var type 
   */
  public $defaultWidgetIndexUrl = '/news/news/own-interest-news';


    /**
     * @var bool
     */
    public $enableCategoriesForCommunity = false;

    /**
     * @var bool
     */
    public $showAllCategoriesForCommunity = true;


  /**
   * @inheritdoc
   */
  public static function getModuleName() {
    return "news";
  }

  /**
   * @inheritdoc
   */
  public static function getModelSearchClassName() {
    return models\search\NewsSearch::className();
  }

  /**
   * @inheritdoc
   */
  public static function getModelClassName() {
    return models\News::className();
  }

  /**
   * @inheritdoc
   */
  public static function getModuleIconName() {
    return 'feed';
  }

  /**
   * @inheritdoc
   */
  public function init() {
    parent::init();

    \Yii::setAlias('@lispa/amos/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers');

    //Configuration: merge default module configurations loaded from config.php with module configurations set by the application
    $config = require(__DIR__ . DIRECTORY_SEPARATOR . self::$CONFIG_FOLDER . DIRECTORY_SEPARATOR . 'config.php');
    \Yii::configure($this, ArrayHelper::merge($config, $this));
  }

  /**
   * @inheritdoc
   */
  public function getWidgetIcons() {
    return [
      WidgetIconNews::className(),
      WidgetIconNewsCategorie::className(),
      WidgetIconNewsCreatedBy::className(),
      WidgetIconNewsDaValidare::className(),
      WidgetIconNewsDashboard::className(),
      WidgetIconAllNews::className(),
    ];
  }

  /**
   * @inheritdoc
   */
  public function getWidgetGraphics() {
    return [
      WidgetGraphicsUltimeNews::className(),
    ];
  }

  /**
   * Get default model classes
   */
  protected function getDefaultModels() {
    return [
      'News' => __NAMESPACE__ . '\\' . 'models\News',
      'NewsCategorie' => __NAMESPACE__ . '\\' . 'models\NewsCategorie',
      'NewsSearch' => __NAMESPACE__ . '\\' . 'models\search\NewsSearch',
    ];
  }

  /**
   * This method return the session key that must be used to add in session
   * the url from the user have started the content creation.
   * @return string
   */
  public static function beginCreateNewSessionKey() {
    return 'beginCreateNewUrl_' . self::getModuleName();
  }



}
