<?php

use lispa\amos\core\helpers\Html;
use lispa\amos\news\AmosNews;
?>

<?php
/** @var $model \lispa\amos\news\models\base\News
 * @var $view_item string
 */?>
<?php
echo \lispa\amos\core\views\AmosGridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'label' => '',
            'value' => function($model) use ($view_item){
                return $this->render($view_item, ['model' => $model]);
            },
            'format' => 'raw'
        ]
    ]
])
?>

