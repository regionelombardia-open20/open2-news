<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\projectmanagement\rules\workflow
 * @category   CategoryName
 */

namespace lispa\amos\news\rules\workflow;

use lispa\amos\core\rules\ToValidateWorkflowContentRule;

class NewsToValidateWorkflowRule extends ToValidateWorkflowContentRule
{

    public $name = 'newsToValidateWorkflow';
    public $validateRuleName = 'NewsValidate';

}