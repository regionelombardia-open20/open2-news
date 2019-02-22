<?php

use lispa\amos\core\migration\AmosMigrationPermissions;
use lispa\amos\news\models\News;
use yii\rbac\Permission;

class m170616_131710_news_remove_update_permission_NewsValidateOnDomain extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'NEWS_UPDATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['NewsValidateOnDomain']
                ]
            ],
        ];
    }
}