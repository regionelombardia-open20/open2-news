<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news\models\base
 * @category   CategoryName
 */

namespace lispa\amos\news\models\base;

use lispa\amos\core\record\Record;
use lispa\amos\news\AmosNews;
use yii\helpers\ArrayHelper;

/**
 * Class NewsCategorie
 *
 * This is the base-model class for table "news_categorie".
 *
 * @property integer $id
 * @property string $titolo
 * @property string $sottotitolo
 * @property string $descrizione_breve
 * @property string $descrizione
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $version
 *
 * @property \lispa\amos\news\models\News $news
 * @property \lispa\amos\news\models\NewsCategoryRolesMm[] $newsCategoryRolesMms
 *
 * @package lispa\amos\news\models\base
 */
class NewsCategorie extends Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news_categorie';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['titolo'], 'required'],
            [['descrizione'], 'string'],
            [['created_by', 'updated_by', 'deleted_by', 'version'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['titolo', 'sottotitolo', 'descrizione_breve'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => AmosNews::t('amosnews', 'Id'),
            'titolo' => AmosNews::t('amosnews', 'Titolo'),
            'sottotitolo' => AmosNews::t('amosnews', 'Sottotitolo'),
            'descrizione_breve' => AmosNews::t('amosnews', 'Descrizione breve'),
            'descrizione' => AmosNews::t('amosnews', 'Descrizione'),
            'created_at' => AmosNews::t('amosnews', 'Creato il'),
            'updated_at' => AmosNews::t('amosnews', 'Aggiornato il'),
            'deleted_at' => AmosNews::t('amosnews', 'Cancellato il'),
            'created_by' => AmosNews::t('amosnews', 'Creato da'),
            'updated_by' => AmosNews::t('amosnews', 'Aggiornato da'),
            'deleted_by' => AmosNews::t('amosnews', 'Cancellato da'),
            'version' => AmosNews::t('amosnews', 'Versione numero')
        ]);
    }

    /**
     * Relation between category and single news.
     * Returns an ActiveQuery related to model News.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(\lispa\amos\news\models\News::className(), ['news_categorie_id' => 'id']);
    }

    /**
     * Relation between category and category-roles mm table.
     * Returns an ActiveQuery related to model NewsCategoryRolesMm.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewsCategoryRolesMms()
    {
        return $this->hasMany(\lispa\amos\news\models\NewsCategoryRolesMm::className(), ['news_category_id' => 'id']);
    }
}
