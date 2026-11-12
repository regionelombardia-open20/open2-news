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

use open20\amos\notificationmanager\AmosNotify;
use open20\amos\news\AmosNews;


$colors =  \open20\amos\notificationmanager\utility\NotifyUtility::getColorNetwork($color);
$notifyModule = AmosNotify::instance();

$moduleNews = AmosNews::instance();
$showSignature =$moduleNews->showSignature;
?>
<?php foreach ($arrayModels as $model) { ?>
    <?php
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
                                <?php if($showSignature): ?>
                                    <td width="400" style="text-align:left;">
                                        <table width="100%">
                                            <tr>
                                                    <?= \open20\amos\notificationmanager\widgets\ItemAndCardWidgetEmailSummaryWidget::widget([
                                                        'model' => $model,
                                                    ]); ?>
                                            </tr>
                                        </table>
                                    </td>
                                <?php endif; ?>
                                <td align="right" width="85" valign="bottom" style="text-align: center; padding-left: 10px;">
                                <a href="<?=  Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl())?>"
                                    style="background:<?= ($notifyModule->mailThemeColor['bgPrimary']) ? $notifyModule->mailThemeColor['bgPrimary'] : '#297A38' ?>;
                                    border:3px solid <?= ($notifyModule->mailThemeColor['bgPrimary']) ? $notifyModule->mailThemeColor['bgPrimary'] : '#297A38' ?>;
                                    color:<?= ($notifyModule->mailThemeColor['textContrastBgPrimary']) ? $notifyModule->mailThemeColor['textContrastBgPrimary'] : '#ffffff' ?>;
                                    font-family:sans-serif; font-size: 11px; line-height: 22px; text-align: center; text-decoration: none; display: block;
                                    font-weight: bold; text-transform: uppercase; height: 20px;" class="button-a">
                                        <!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->Leggi<!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->
                                    </a></td>
                            </tr>

                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

<?php } ?>