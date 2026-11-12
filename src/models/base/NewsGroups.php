<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\news\models\base
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

use open20\amos\core\record\Record;
use open20\amos\news\AmosNews;
use yii\helpers\ArrayHelper;

/**
 * Class NewsGroups
 *
 * This is the base-model class for table "news_groups".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @package open20\amos\news\models\base
 */
class NewsGroups extends Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news_groups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => AmosNews::t('amosnews', 'Id'),
            'description' => AmosNews::t('amosnews', 'Descrizione'),
            'name' => AmosNews::t('amosnews', 'Nome'),
            'created_at' => AmosNews::t('amosnews', 'Creato il'),
            'updated_at' => AmosNews::t('amosnews', 'Aggiornato il'),
            'deleted_at' => AmosNews::t('amosnews', 'Cancellato il'),
            'created_by' => AmosNews::t('amosnews', 'Creato da'),
            'updated_by' => AmosNews::t('amosnews', 'Aggiornato da'),
            'deleted_by' => AmosNews::t('amosnews', 'Cancellato da')
        ]);
    }

    /**
     * Relation between group and single news.
     * Returns an ActiveQuery related to model News.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(\open20\amos\news\models\News::class, ['news_groups_id' => 'id']);
    }
    
}
