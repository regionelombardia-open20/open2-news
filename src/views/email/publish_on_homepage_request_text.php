<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\news\views\email
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

use open20\amos\news\AmosNews;

?>

<?= AmosNews::t('amosnews', '#hp_dear_user', [
    'whocan_name' => $whocan_name,
    'community' => $community
]); ?>

<?= AmosNews::t('amosnews', '#hp_row_1', [
    'user_request' => $user_request,
]) ?>

<div>
    <?= AmosNews::t('amosnews', '#hp_row_2_label') ?>
    <span style="font-size: 1.1rem;line-height: 1.25;font-weight: 600;">
        <?= AmosNews::t('amosnews', '#hp_row_2', [
            'news_title' => $news_title
        ]) ?>
    </span>
</div>


<?= AmosNews::t('amosnews', '#hp_row_3',[
    'news_categoria' => $news_categoria
]) ?>

<?= AmosNews::t('amosnews', '#hp_row_4', [
    'news_descr' => strip_tags($news_descr)
]) ?>

<?= AmosNews::t('amosnews', '#hp_row_5', [
    'tags' => $tags
]) ?>

<?= AmosNews::t('amosnews', '#hp_row_6') ?><a href ="<?= $news_url ?>"><?= $news_url ?></a>