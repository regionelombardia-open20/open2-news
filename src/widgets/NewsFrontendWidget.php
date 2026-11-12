<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-news
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

/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 15/11/2018
 * Time: 16:06
 */

namespace open20\amos\news\widgets;

use open20\amos\news\models\News;
use yii\base\Widget;
use yii\data\ActiveDataProvider;

class NewsFrontendWidget extends Widget
{
    const TYPE_HIGHLIGHTS = 'highlights';
    const TYPE_FRONTEND   = 'frontend';
    const TYPE_ALL        = 'all';

    public $tags = [];
    public $andWhereInIds = [];
    public $category;
    public $type                    = NewsFrontendWidget::TYPE_ALL;
    public $statuses                = [];
    public $validated_at_least_once = false;
    public $queryOrderBy;
    public $view_path               = '@vendor/open20/amos-news/src/widgets/views/news_frontend_item';
    public $paginationPageSize = 20;

    /**
     *
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        $query = News::find();

        if ($this->type == NewsFrontendWidget::TYPE_FRONTEND) {
            $query->andWhere(['primo_piano' => 1]);
        } else if ($this->type == NewsFrontendWidget::TYPE_HIGHLIGHTS) {
            $query->andWhere(['in_evidenza' => 1]);
        }

        if ($this->validated_at_least_once) {
            $query->innerJoin('amos_workflow_transitions_log', 'owner_primary_key = news.id')
                ->andWhere(['end_status' => News::NEWS_WORKFLOW_STATUS_VALIDATO]);
        } else {
            if (!empty($this->statuses)) {
                $query->andWhere(['status' => $this->statuses]);
            }
        }

        if (!empty($this->tags) && count($this->tags) > 0) {
            $newsClassname = News::className();
            $newsClassname = addslashes($newsClassname);
            $query->leftJoin('entitys_tags_mm', "entitys_tags_mm.record_id=news.id AND entitys_tags_mm.classname='$newsClassname'")
            ->andFilterWhere(['entitys_tags_mm.tag_id' => $this->tags]);
        }

        if(!empty($this->andWhereInIds)){
            $query->andFilterWhere(['news.id' => $this->andWhereInIds]);
        }

        if (!empty($this->category)) {
            $query->andWhere(['news_categorie_id' => $this->category]);
        }

        if (!empty($this->queryOrderBy)) {
            $query->orderBy(new \yii\db\Expression("{$this->queryOrderBy}"));
        }

        /** @var  $dataProvider */
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $dataProvider->pagination->pageSize = $this->paginationPageSize;

        return $this->render('news_frontend_index', ['dataProvider' => $dataProvider, 'view_item' => $this->view_path]);
    }
}