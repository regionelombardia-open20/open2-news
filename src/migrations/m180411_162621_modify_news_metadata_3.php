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

use cornernote\workflow\manager\models\Status;
use open20\amos\core\migration\libs\common\MigrationCommon;
use yii\db\Migration;

/**
 * Class m180411_162621_modify_news_metadata_3
 */
class m180411_162621_modify_news_metadata_3 extends Migration
{
    const WORKFLOW_NAME = 'NewsWorkflow';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->updateStatus('BOZZA', 'Bozza');
        $this->updateStatus('DAVALIDARE', 'In richiesta di pubblicazione');
        $this->updateStatus('VALIDATO', 'Pubblicata');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->updateStatus('BOZZA', 'Modifica in corso');
        $this->updateStatus('DAVALIDARE', 'Richiedi pubblicazione');
        $this->updateStatus('VALIDATO', 'Validato');
    }

    /**
     * @param string $status
     * @param string $label
     * @return bool
     */
    private function updateStatus($status, $label)
    {
        try {
            $this->update(Status::tableName(), ['label' => $label], [
                'workflow_id' => self::WORKFLOW_NAME,
                'id' => $status
            ]);
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage('Errore durante aggiornamento stato workflow: workflow = ' . self::WORKFLOW_NAME . '; status = ' . self::WORKFLOW_NAME . '/' . $status . '; label = ' . $label . ';');
            return false;
        }
        MigrationCommon::printConsoleMessage("Aggiornamento stato workflow eseguito correttamente: workflow = " . self::WORKFLOW_NAME . "; status = " . self::WORKFLOW_NAME . '/' . $status . '; label = ' . $label . ';');
        return true;
    }
}
