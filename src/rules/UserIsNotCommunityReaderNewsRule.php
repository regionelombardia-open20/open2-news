<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\rules
 * @category   CategoryName
 */

namespace open20\amos\news\rules;

use open20\amos\core\rules\UserCreatorContentOnDomain;

class UserIsNotCommunityReaderNewsRule extends UserCreatorContentOnDomain
{
    public $name = 'userIsNotCommunityReaderNewsRule';

}