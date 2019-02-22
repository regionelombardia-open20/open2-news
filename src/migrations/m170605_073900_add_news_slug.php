<?php

use yii\db\Migration;

class m170605_073900_add_news_slug extends Migration
{

    public function safeUp()
    {

        $this->addColumn(\lispa\amos\news\models\News::tableName(), 'slug',
            $this->text()
                ->null()
                ->after('id')
        );

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn(\lispa\amos\news\models\News::tableName(), 'slug');

        return true;
    }
}
