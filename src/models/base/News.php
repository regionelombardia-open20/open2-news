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

use lispa\amos\core\record\ContentModel;
use lispa\amos\news\AmosNews;
use yii\helpers\ArrayHelper;

/**
 * Class News
 *
 * This is the base-model class for table "news".
 *
 * @property integer $id
 * @property string $slug
 * @property string $titolo
 * @property string $sottotitolo
 * @property string $descrizione_breve
 * @property string $descrizione
 * @property string $metakey
 * @property string $metadesc
 * @property integer $primo_piano
 * @property integer $immagine
 * @property integer $hits
 * @property integer $abilita_pubblicazione
 * @property integer $in_evidenza
 * @property string $data_pubblicazione
 * @property string $data_rimozione
 * @property integer $news_categorie_id
 * @property string $status
 * @property integer $comments_enabled
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $version
 *
 * @property \lispa\amos\news\models\NewsCategorie $newsCategorie
 * @property \lispa\amos\upload\models\FilemanagerMediafile $immagineNews
 *
 * @package lispa\amos\news\models\base
 */
abstract class News extends ContentModel
{
    /**
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     */
    public function rules()
    {

        return ArrayHelper::merge(parent::rules(), [
            [['descrizione', 'metakey', 'metadesc'], 'string'],
            [['primo_piano', 'immagine', 'hits', 'abilita_pubblicazione', 'in_evidenza', 'news_categorie_id', 'created_by', 'updated_by', 'deleted_by', 'version', 'comments_enabled'], 'integer'],
            [['slug', 'data_pubblicazione', 'data_rimozione', 'created_at', 'updated_at', 'deleted_at', 'status', 'comments_enabled'], 'safe'],
            [['titolo', 'sottotitolo'], 'string', 'max' => 100],
            [['descrizione_breve'], 'string', 'max' => 250],
            [['descrizione_breve'], 'required'],
        ]);

    }

    /**
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(),
            [
                'id' => AmosNews::t('amosnews', 'Id'),
                'titolo' => AmosNews::t('amosnews', '#title_field'),
                'sottotitolo' => AmosNews::t('amosnews', '#subtitle_field'),
                'descrizione_breve' => AmosNews::t('amosnews', '#abstract_field'),
                'descrizione' => AmosNews::t('amosnews', '#description_field'),
                'metakey' => AmosNews::t('amosnews', 'Meta key'),
                'metadesc' => AmosNews::t('amosnews', 'Meta descrizione'),
                'primo_piano' => AmosNews::t('amosnews', 'Pubblica sul sito'),
                'in_evidenza' => AmosNews::t('amosnews', 'In evidenza'),
                'hits' => AmosNews::t('amosnews', 'Visualizzazioni'),
                'abilita_pubblicazione' => AmosNews::t('amosnews', 'Abilita pubblicazione'),
                'data_pubblicazione' => AmosNews::t('amosnews', '#start_publication_date'),
                'data_rimozione' => AmosNews::t('amosnews', '#end_publication_date'),
                'news_categorie_id' => AmosNews::t('amosnews', 'Categoria'),
                'status' => AmosNews::t('amosnews', 'Stato'),
                'comments_enabled' => AmosNews::t('amosnews', '#comments_enabled'),
                'created_at' => AmosNews::t('amosnews', 'Creato il'),
                'updated_at' => AmosNews::t('amosnews', 'Aggiornato il'),
                'deleted_at' => AmosNews::t('amosnews', 'Cancellato il'),
                'created_by' => AmosNews::t('amosnews', 'Creato da'),
                'updated_by' => AmosNews::t('amosnews', 'Aggiornato da'),
                'deleted_by' => AmosNews::t('amosnews', 'Cancellato da'),
                'version' => AmosNews::t('amosnews', 'Versione numero'),
            ]
        );
    }

    /**
     * @return mixed
     */
    public function attributeHints()
    {
        return ArrayHelper::merge(parent::attributeHints(),
            [
                'id' => AmosNews::t('amosnews', ''),
                'titolo' => AmosNews::t('amosnews', '#title_field_hint'),
                'sottotitolo' => AmosNews::t('amosnews', '#subtitle_field_hint'),
                'descrizione_breve' => AmosNews::t('amosnews', '#abstract_field_hint'),
                'descrizione' => AmosNews::t('amosnews', ''),
                'metakey' => AmosNews::t('amosnews', ''),
                'metadesc' => AmosNews::t('amosnews', ''),
                'primo_piano' => AmosNews::t('amosnews', ''),
                'in_evidenza' => AmosNews::t('amosnews', ''),
                'hits' => AmosNews::t('amosnews', ''),
                'abilita_pubblicazione' => AmosNews::t('amosnews', ''),
                'data_pubblicazione' => AmosNews::t('amosnews', '#start_publication_date_hint'),
                'data_rimozione' => AmosNews::t('amosnews', '#end_publication_date_hint'),
                'news_categorie_id' => AmosNews::t('amosnews', ''),
                'status' => AmosNews::t('amosnews', ''),
                'comments_enabled' => AmosNews::t('amosnews', ''),
                'created_at' => AmosNews::t('amosnews', ''),
                'updated_at' => AmosNews::t('amosnews', ''),
                'deleted_at' => AmosNews::t('amosnews', ''),
                'created_by' => AmosNews::t('amosnews', ''),
                'updated_by' => AmosNews::t('amosnews', ''),
                'deleted_by' => AmosNews::t('amosnews', ''),
                'version' => AmosNews::t('amosnews', ''),
            ]
        );
    }


    /**
     * Validation of $attribute if the attribute publication date of the module is true
     * @param string $attribute
     * @param array $params
     */
    public function checkDate($attribute, $params)
    {
        $isValid = TRUE;
        if ($this->isNewRecord && \Yii::$app->getModule('news')->validatePublicationDate == true) {
            if ($this->$attribute < date('Y-m-d')) {
                $isValid = FALSE;
            }
        }
        if (!$isValid) {
            $this->addError($attribute, $this->getAttributeLabel($attribute) . AmosNews::t('amosnews', ' non può essere inferiore alla data odierna'));
        }
    }

    /**
     * This is the relation between the news and the related category.
     * Return an ActiveQuery related to NewsCategorie model.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewsCategorie()
    {
        return $this->hasOne(\lispa\amos\news\models\NewsCategorie::className(), ['id' => 'news_categorie_id']);
    }


    /**
     * This is the relation between the news and the single related picture.
     * Return an ActiveQuery related to FilemanagerMediafile model.
     *
     * @return \yii\db\ActiveQuery
     * @deprecated since version 1.5
     */
    public function getImmagineNews()
    {
        return $this->hasOne(\lispa\amos\upload\models\FilemanagerMediafile::className(), ['id' => 'immagine']);
    }
}
