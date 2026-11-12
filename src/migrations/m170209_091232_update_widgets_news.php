<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\news
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

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m170209_091232_update_widgets_news
 */
class m170209_091232_update_widgets_news extends AmosMigrationWidgets
{
    const MODULE_NAME = 'news';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = array_merge(
            $this->initIconWidgetsConf(),
            $this->initGraphicWidgetsConf()
        );
    }

    /**
     * Init the icon widgets configurations
     * @return array
     */
    private function initIconWidgetsConf()
    {
        return [
            [
                'classname' => open20\amos\news\widgets\icons\WidgetIconNewsDashboard::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 1,
                'update' => true
            ],
            [
                'classname' => \open20\amos\news\widgets\icons\WidgetIconNews::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\news\widgets\icons\WidgetIconNewsDashboard::className(),
                'default_order' => 10,
                'update' => true
            ],
            [
                'classname' => open20\amos\news\widgets\icons\WidgetIconNewsCreatedBy::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\news\widgets\icons\WidgetIconNewsDashboard::className(),
                'default_order' => 30,
                'update' => true
            ],
            [
                'classname' => \open20\amos\news\widgets\icons\WidgetIconAllNews::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\news\widgets\icons\WidgetIconNewsDashboard::className(),
                'default_order' => 40,
                'update' => true
            ],
            [
                'classname' => open20\amos\news\widgets\icons\WidgetIconNewsDaValidare::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\news\widgets\icons\WidgetIconNewsDashboard::className(),
                'default_order' => 50,
                'update' => true
            ],
            [
                'classname' => open20\amos\news\widgets\icons\WidgetIconNewsCategorie::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\news\widgets\icons\WidgetIconNewsDashboard::className(),
                'default_order' => 60,
                'update' => true
            ]
        ];
    }

    /**
     * Init the graphic widgets configurations
     * @return array
     */
    private function initGraphicWidgetsConf()
    {
        return [
            [
                'classname' => \open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\news\widgets\icons\WidgetIconNewsDashboard::className(),
                'default_order' => 1,
                'update' => true
            ]
        ];
    }
}
