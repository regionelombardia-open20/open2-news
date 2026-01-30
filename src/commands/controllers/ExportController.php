<?php

namespace open20\amos\news\commands\controllers;

use open20\amos\news\AmosNews;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use open20\amos\news\models\News;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use open20\amos\taskmanager\models\TaskManagerJob;


/**
 * Export controller
 */
class ExportController extends \yii\console\Controller
{
    /**
     * @var string $filename
     */
    public $filename = 'news_export.xlsx';

    /**
     * @var array $data
     */
    public $data = [];

    /**
     * @var string[] $passedOptions
     */
    public $passedOptions = [
        'filename',
        'data'
    ];

    /**
     * @param $actionID
     * @return array|string[]
     */
    public function options($actionID)
    {
        return ArrayHelper::merge(parent::options($actionID), $this->passedOptions);
    }

    /**
     * @return void
     */
    public function init()
    {
        $this->module = AmosNews::instance();
        parent::init();
    }

    /**
     * @return void
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     * @throws \yii\base\InvalidConfigException
     * @throws Exception
     */
    public function actionExportNews()
    {
        $filename = $this->filename;

        // Base news query
        $rawQuery = htmlspecialchars_decode($this->data['query']);
        $rawQuery .= ' ORDER BY news.created_at DESC';
        $newsQuery = \Yii::$app->db->createCommand($rawQuery);

        $newsModels = $newsQuery->queryAll();

        // Excel header columns
        $xlsData = [];
        $xlsData[] = [
            'Titolo',
            'Stato',
            'Creatore',
            'Ultima modifica',
            'Data ultima modifica',
            'Inizio pubblicazione',
            'Fine pubblicazione',
            'Categoria'
        ];

        // Populate rows
        foreach ($newsModels as $news) {
            $model = News::findOne($news['id']);
            if ($model->hasWorkflowStatus()) {
                $statusLabel = $model->getWorkflowStatus()->getLabel();
            } else {
                $statusLabel = '--';
            }
            $createdBy = $model->createdUserProfile->nomeCognome;
            $updatedBy = $model->updatedUserProfile->nomeCognome;
            if (is_null($model->data_pubblicazione)) {
                $dataPubblicazione = AmosNews::t('amosnews', 'Immediata');
            } else {
                $dataPubblicazione = Yii::$app->formatter->asDatetime($model->data_pubblicazione, 'php:d/m/Y H:i');
            }
            if (is_null($model->data_rimozione) || $model->data_rimozione == '9999-12-31 00:00:00') {
                $dataRimozione = AmosNews::t('amosnews', 'Mai');
            } else {
                $dataRimozione = Yii::$app->formatter->asDatetime($model->data_rimozione, 'php:d/m/Y H:i');
            }

            $xlsData[] = [
                $model->titolo,
                $statusLabel,
                $createdBy,
                $updatedBy,
                Yii::$app->formatter->asDatetime($model->updated_at, 'php:d/m/Y H:i'),
                $dataPubblicazione,
                $dataRimozione,
                $model->newsCategorie->titolo
            ];
        }

        // Initialize PhpSpreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Cells styles
        foreach (range('A', 'H') as $column) {
            $column .= '1';
            $sheet->getStyle($column)->getFont()->setBold(true);
            $sheet->getStyle($column)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM);
        }

        // Add data to the sheet
        $sheet->fromArray($xlsData, NULL, 'A1');

        // Save file
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);

        Console::stdout("News exported successfully.\n");
    }

}