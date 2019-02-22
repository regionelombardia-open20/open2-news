<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news\migrations
 * @category   CategoryName
 */

use lispa\amos\core\migration\AmosMigrationPermissions;
use lispa\amos\news\models\News;
use yii\helpers\ArrayHelper;

/**
 * Class m171113_173622_news_remove_workflow_active_lettore
 */
class m171113_173622_news_remove_workflow_active_lettore extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => News::NEWS_WORKFLOW_STATUS_VALIDATO,
                'update' => true,
                'newValues' => [
                    'removeParents' => ['LETTORE_NEWS']
                ]
            ]
        ];
    }
}
