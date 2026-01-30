<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
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
