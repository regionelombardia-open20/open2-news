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
use yii\rbac\Permission;

/**
 * Class m180727_124144_add_news_read_rule
 */
class m180727_124144_add_news_read_rule extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'NewsRead',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to read a News ',
                'ruleName' => \lispa\amos\core\rules\ReadContentRule::className(),
                'parent' => ['AMMINISTRATORE_NEWS', 'CREATORE_NEWS', 'VALIDATORE_NEWS', 'LETTORE_NEWS', 'FACILITATORE_NEWS']
            ],
            [
                'name' => 'NEWS_READ',
                'type' => Permission::TYPE_PERMISSION,
                'update' => true,
                'newValues' => [
                    'removeParents' =>  ['AMMINISTRATORE_NEWS', 'CREATORE_NEWS', 'VALIDATORE_NEWS', 'LETTORE_NEWS', 'FACILITATORE_NEWS'],
                    'addParents' => ['NewsRead']
                ]
            ],
        ];
    }
}
