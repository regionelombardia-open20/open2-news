<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-news
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

namespace open20\amos\news\models\base;

use Yii;

/**
* This is the base-model class for table "news_categorie_mm".
*
    * @property integer $id
    * @property integer $news_id
    * @property integer $news_categorie_id
    * @property string $created_at
    * @property string $updated_at
    * @property string $deleted_at
    * @property integer $created_by
    * @property integer $updated_by
    * @property integer $deleted_by
    *
            * @property \open20\amos\news\models\NewsCategorie $newsCategorie
            * @property \open20\amos\news\models\News $news
    */
 class  NewsCategorieMm extends \open20\amos\core\record\Record
{
    public $isSearch = false;

/**
* @inheritdoc
*/
public static function tableName()
{
return 'news_categorie_mm';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['news_id', 'news_categorie_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['news_categorie_id'], 'exist', 'skipOnError' => true, 'targetClass' => NewsCategorie::className(), 'targetAttribute' => ['news_categorie_id' => 'id']],
            [['news_id'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['news_id' => 'id']],
];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => Yii::t('amosnews', 'ID'),
    'news_id' => Yii::t('amosnews', 'News'),
    'news_categorie_id' => Yii::t('amosnews', 'Category'),
    'created_at' => Yii::t('amosnews', 'Created at'),
    'updated_at' => Yii::t('amosnews', 'Updated at'),
    'deleted_at' => Yii::t('amosnews', 'Deleted at'),
    'created_by' => Yii::t('amosnews', 'Created by'),
    'updated_by' => Yii::t('amosnews', 'Updated by'),
    'deleted_by' => Yii::t('amosnews', 'Deleted by'),
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getNewsCategorie()
    {
    return $this->hasOne(\open20\amos\news\models\NewsCategorie::className(), ['id' => 'news_categorie_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getNews()
    {
    return $this->hasOne(\open20\amos\news\models\News::className(), ['id' => 'news_id']);
    }
}
