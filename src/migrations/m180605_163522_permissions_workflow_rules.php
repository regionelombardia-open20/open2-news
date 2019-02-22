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
 * Class m180605_163522_permissions_workflow_rules
 */
class m180605_163522_permissions_workflow_rules extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \lispa\amos\news\rules\workflow\NewsToValidateWorkflowRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Check if you are an author',
                'ruleName' => \lispa\amos\news\rules\workflow\NewsToValidateWorkflowRule::className(),
                'parent' => ['CREATORE_NEWS', 'FACILITATORE_NEWS', 'NewsValidate', 'VALIDATORE_NEWS']
            ],
            [
                'name' => 'NewsWorkflow/DAVALIDARE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        \lispa\amos\news\rules\workflow\NewsToValidateWorkflowRule::className()
                    ],
                    'removeParents' => [
                        'CREATORE_NEWS', 'FACILITATORE_NEWS', 'NewsValidate', 'VALIDATORE_NEWS'
                    ]
                ],
            ],

        ];
    }
}
