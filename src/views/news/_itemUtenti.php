<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\news
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

<div class="listview-container">

    <div class="bk-listViewElement">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h2><?= $model->titolo ?></h2>

                <h3><?= $model->sottotitolo ?></h3>
                <p><br><u><?= AmosNews::t('amosnews', 'Abstract') ?></u>: <i><?= $model->descrizione_breve ?></i></p>
                <p><?= $model->descrizione ?></p>

                <!--p><b>Condominio:</b> < ?= $model->networkPubblicazione ?></p-->
                <p><b><?= AmosNews::t('amosnews', 'Categoria') ?>:</b> <?= $model->newsCategorie->titolo ?></p>
                <div class="bk-elementActions">
                    <?= $buttons ?>
                </div>
                <div class="clear"></div>
            </div>

            <div class="col-lg-12 col-md-12">
                <br>
                <h3><b><?= AmosNews::t('amosnews', 'Allegati') ?></b></h3>
                <?php
                $allegati = $model->getNewsAllegatis();
                if ($allegati->count() == 0) {
                    ?>
                    <p><h3><?= AmosNews::t('amosnews', 'Nessun allegato presente') ?></h3></p>
                    <?php
                } else {
                    ?>
                    <ul>
                        <?php
                        foreach ($allegati->all() as $Allegati) {
                            ?>
                            <li>
                                <a href="/news/news/download?idfile=<?= $Allegati['filemanager_mediafile_id'] ?>">
                                    <h3 title="Download file">
                                        <i><?= $Allegati['titolo'] ?></i>
                                    </h3>
                                </a>
                                <i><?= $Allegati['descrizione'] ?></i>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                    <?php
                }
                ?>

            </div>
        </div>

        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
