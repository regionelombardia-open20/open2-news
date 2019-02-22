<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news\utility
 * @category   CategoryName
 */

namespace lispa\amos\news\utility;


use lispa\amos\news\models\NewsCategorie;
use lispa\amos\news\models\NewsCategoryRolesMm;
use yii\base\Object;
use yii\db\ActiveQuery;

class NewsUtility extends Object
{

    public static function getNewsCategories()
    {
        /** @var ActiveQuery $query */
        $query = NewsCategorie::find();
        if(\Yii::$app->getModule('news')->filterCategoriesByRole){
            //check enabled role for category active - user can publish under a category if there's at least one match betwwn category and user roles
            $query->joinWith('newsCategoryRolesMms')->innerJoin('auth_assignment', 'item_name='. NewsCategoryRolesMm::tableName().'.role and user_id ='. \Yii::$app->user->id);
        }
        return $query;
    }
}