<?php
use open20\amos\notificationmanager\AmosNotify;
$notifyModule = AmosNotify::instance();

$url = '/img/img_default.jpg';
if (!is_null($model->newsImage)) {
    $url = $model->newsImage->getWebUrl('square_large', false, true);
}
$url =  Yii::$app->urlManager->createAbsoluteUrl($url);
?>
<tr>
    <td colspan="2" style="padding-bottom:10px;">
        <table width="100%">
            <tr>

                <td  valigh="top" style="font-size:18px; font-weight:bold; font-family: sans-serif; text-align:left; vertical-align:top;">
                    <p style="margin:0 0 5px 0">
                        <?= \yii\helpers\Html::a($model->getTitle(), Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl()), ['style' => 'color: #000; text-decoration:none;']) ?>
                    </p>
                    <p style="font-size:13px; color:#7d7d7d; padding:10px 0; font-family: sans-serif; font-weight:normal; margin:0; text-align: left;"><?= $model->getDescription(true) ?></p>
                </td>
                <td width="35%" align="right" valign="top" style="padding-left:10px; text-align:right;">
                    <img src="<?= $url ?>" width="165" border="0" style="max-width:100%">

                </td>

            </tr>
            <tr>
                <td colspan="2" style="padding:0 0 10px 0; border-bottom:1px solid #D8D8D8;">
                    <table width="100%">
                        <tr>
                            <td width="400" style="text-align:left;">
                                <table width="100%">
                                    <tr>
                                        <?= \open20\amos\notificationmanager\widgets\ItemAndCardWidgetEmailSummaryWidget::widget([
                                            'model' => $model,
                                        ]); ?>
                                    </tr>
                                </table>
                            </td>
                            <td align="right" width="85" valign="bottom" style="text-align: center; padding-left: 10px;">
                                <a href="<?=  Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl())?>"
                                   style="background:___network_color1___;
                                       border:3px solid ___network_color1___;
                                       color: #ffffff;
                                       font-family:sans-serif; font-size: 11px; line-height: 22px; text-align: center; text-decoration: none; display: block;
                                       font-weight: bold; text-transform: uppercase; height: 20px;" class="button-a">
                                    <!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->
                                    <?= \open20\amos\news\AmosNews::t('amosnews', 'Leggi')?>
                                    <!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->
                                </a></td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>