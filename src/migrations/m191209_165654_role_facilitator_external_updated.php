<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m181018_134854_add_admin_tag_tabs_permission
 */
class m191209_165654_role_facilitator_external_updated extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [

            [
                'name' => 'FACILITATORE_NEWS',
                'type' => Permission::TYPE_ROLE,
                'update' => true,
                'newValues' => [
                    'addParents' => ['FACILITATOR_EXTERNAL']
                ]
            ],
        ];
    }
}
