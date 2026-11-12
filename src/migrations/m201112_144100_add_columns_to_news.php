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
 * Class m201112_144100_add_columns_to_news
 */
class m201112_144100_add_columns_to_news extends Migration {

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $table = $this->db->schema->getTableSchema(\open20\amos\news\models\News::tableName());
        if (!isset($table->columns['edited_by_agid_organizational_unit_id'])) {

            $this->addColumn('news', 'edited_by_agid_organizational_unit_id', $this->integer()->null()->defaultValue(null));
        }

        // Add columns
        if (!isset($table->columns['date_news'])) {
            $this->addColumn('news', 'date_news', $this->date()->null()->defaultValue(null)->comment('Date News'));
        }
        if (!isset($table->columns['news_expiration_date'])) {
            $this->addColumn('news', 'news_expiration_date', $this->date()->null()->defaultValue(null)->comment('News Expiration Date'));
        }
        if (!isset($table->columns['body_news'])) {
            $this->addColumn('news', 'body_news', $this->text()->null()->defaultValue(null)->comment('Body News'));
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        // dropColumn
        $this->dropColumn('news', 'edited_by_agid_organizational_unit_id');

        // dropColumn
        $this->dropColumn('news', 'date_news');
        $this->dropColumn('news', 'news_expiration_date');
        $this->dropColumn('news', 'body_news');
    }
}
