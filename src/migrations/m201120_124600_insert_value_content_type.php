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

use yii\db\Migration;

/**
 * Class m201120_124600_insert_value_content_type
 */
class m201120_124600_insert_value_content_type extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // remove old value
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        \Yii::$app->db->createCommand()->truncateTable('news_content_type')->execute();
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');
        
        $this->insert('news_content_type', [
            'name' => 'News'
        ]);
        
        $this->insert('news_content_type', [
            'name' => 'Avvisi'
        ]);
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m201120_124600_insert_value_content_type cannot be reverted.\n";
        return false;
    }
}
