<?php

use lispa\amos\core\migration\AmosMigrationPermissions;
use lispa\amos\news\widgets\icons\WidgetIconNewsDashboard;

class m170601_082601_add_news_basic_user_dashboard_widget extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {

        return [
            [
                'name' => WidgetIconNewsDashboard::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['BASIC_USER']
                ]
            ],

        ];
    }
}
