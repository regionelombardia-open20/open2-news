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

/**
 * Class m170914_135007_add_validatore_news_to_validator_role
 */
class m180522_163307_delete_community_manager_rule extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \lispa\amos\news\rules\DeleteCommunityManagerNewsRule::className(),
                'type' => \yii\rbac\Permission::TYPE_PERMISSION,
                'description' => 'Regola per cancellare una news se sei CM',
                'ruleName' => \lispa\amos\news\rules\DeleteCommunityManagerNewsRule::className(),
                'parent' => ['CREATORE_NEWS'],
                'children' => ['NEWS_DELETE']
            ]
        ];
    }
}
