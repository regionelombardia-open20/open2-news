<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news\migrations
 * @category   CategoryName
 */

use lispa\amos\core\migration\AmosMigrationWorkflow;
use lispa\amos\news\models\News;

/**
 * Class m170428_164712_change_news_workflow
 */
class m180604_161112_add_transition_news_workflow extends AmosMigrationWorkflow
{
    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => News::NEWS_WORKFLOW,
                'start_status_id' => 'BOZZA',
                'end_status_id' => 'VALIDATO'
            ]
        ];
    }
}
