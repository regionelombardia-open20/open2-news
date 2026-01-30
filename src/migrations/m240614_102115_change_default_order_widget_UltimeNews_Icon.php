<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m181012_162615_add_user_profile_area_field_1
 */
class m240614_102115_change_default_order_widget_UltimeNews_Icon extends Migration
{



    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('amos_widgets', ['default_order' => 2], ['classname' => 'open20\amos\news\widgets\icons\WidgetIconNewsDashboard','module' => 'community']);


    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }
}
