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
 * Class m201217_153729_fix_news_tables
 */
class m201217_153729_fix_news_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->removeForeignKey('news', 'fk-edited-by-agid-organizational-unit-id');
        $this->removeForeignKey('news_agid_person_mm', 'fk-news-id');
        $this->removeForeignKey('news_agid_person_mm', 'fk-agid-person-id');
        $this->removeForeignKey('news_related_news_mm', 'fk-news-related-news-mm-news-id');
        $this->removeForeignKey('news_related_news_mm', 'fk-news-related-news-mm-related-news-id');
        $this->removeForeignKey('news_related_documenti_mm', 'fk-news-related-documenti-mm-news-id');
        $this->removeForeignKey('news_related_documenti_mm', 'fk-news-related-documenti-mm-related-documenti-id');
        $this->removeForeignKey('news_related_agid_service_mm', 'fk-news-related-agid-service-mm-news-id');
        $this->removeForeignKey('news_related_agid_service_mm', 'fk-news-related-agid-service-mm-related-agid-service-id');
        $this->removeForeignKey('news', 'fk-news-content-type-id');
        $this->removeForeignKey('news', 'fk-news-news-documento-id');
        $this->removeForeignKey('news', 'fk-news-image-site-management-slider-id');
        $this->removeForeignKey('news', 'fk-news-video-site-management-slider-id');
        return true;
    }
    
    /**
     * @param string $tableName
     * @param string $foreignKeyName
     */
    protected function removeForeignKey($tableName, $foreignKeyName)
    {
        $tableSchema = $this->db->schema->getTableSchema($tableName, true);
        $foreignKeys = $tableSchema->foreignKeys;
        if (isset($foreignKeys[$foreignKeyName])) {
            $this->dropForeignKey($foreignKeyName, $tableName);
        }
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m201217_153729_fix_news_tables cannot be reverted.\n";
        return false;
    }
}
