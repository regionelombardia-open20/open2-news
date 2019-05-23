<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\migrations
 * @category   CategoryName
 */

use lispa\amos\documenti\models\Documenti;
use yii\db\Migration;

/**
 * Class m171206_092631_add_documenti_fields_1
 */
class m190329_153431_add_field_news_category_comunity_mm extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        
        $table = $this->db->schema->getTableSchema(\lispa\amos\news\models\NewsCategoryCommunityMm::tableName());
        if (!isset($table->columns['visible_to_cm'])) {
            $this->addColumn(\lispa\amos\news\models\NewsCategoryCommunityMm::tableName(), 'visible_to_cm', $this->integer(1)->null()->defaultValue(null)->after('community_id'));
            $this->addColumn(\lispa\amos\news\models\NewsCategoryCommunityMm::tableName(), 'visible_to_participant', $this->integer(1)->null()->defaultValue(1)->after('community_id'));
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(\lispa\amos\news\models\NewsCategoryCommunityMm::tableName(), 'visible_to_cm');
        $this->dropColumn(\lispa\amos\news\models\NewsCategoryCommunityMm::tableName(), 'visible_to_participant');

    }
}
