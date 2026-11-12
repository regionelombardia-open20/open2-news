<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\news\migrations
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m201217_190033_fix_news_permissions
 */
class m201217_190033_fix_news_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'NEWS_PUBLISHER_FRONTEND',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSCONTENTTYPE_CREATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSCONTENTTYPE_READ',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSCONTENTTYPE_UPDATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSCONTENTTYPE_DELETE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDAGIDSERVICEMM_CREATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDAGIDSERVICEMM_READ',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDAGIDSERVICEMM_UPDATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDAGIDSERVICEMM_DELETE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDDOCUMENTIMM_CREATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDDOCUMENTIMM_READ',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDDOCUMENTIMM_UPDATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDDOCUMENTIMM_DELETE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDNEWSMM_CREATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDNEWSMM_READ',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDNEWSMM_UPDATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDNEWSMM_DELETE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSAGIDPERSONMM_CREATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSAGIDPERSONMM_READ',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSAGIDPERSONMM_UPDATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSAGIDPERSONMM_DELETE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ]
        ];
    }
}
