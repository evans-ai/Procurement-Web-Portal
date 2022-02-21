<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>
<div class="verify-email">
    <p>Dear <?= Html::encode($Supplier->Name) ?>,</p>

    <p>Your application for registration as a supplier under the following categories has been received:</p>
    <ol>
        <?php foreach($OpenCategories as $OpenCat){ ?>
        <li><?=$OpenCat->Category_Name ?></li>
        <?php } ?>
    </ol>
    <p>Below is a summary of the information that you have provided:</p>
    <table width="500px" border="1" cellpadding="5" cellspacing="5" style="border-collapse: collapse">
            <tr>
                <th style="text-align: left;" width="20%">Supplier Name</th>
                <td><?=$Supplier->Name ?></td>
            </tr>
            <tr>
                <th style="text-align: left;">Address</th>
                <td><?=$Supplier->Address ?><br><?=$Supplier->PostalAddress?></td>
            </tr>
            <tr>
                <th style="text-align: left;">Contacts</th>
                <td>Tel: <?=$Supplier->Telephone ?>, Email: <?=$Supplier->Email?></td>
            </tr>
            <tr>
                <th style="text-align: left;">Registration No.</th>
                <td><?=$Supplier->LicenseNo ?></td>
            </tr>  
            <tr>
                <th style="text-align: left;">PIN No.</th>
                <td><?=$Supplier->KRA_PIN ?></td>
            </tr>  
            <tr>
                <th style="text-align: left;">Supplier Type</th>
                <td><?=$Supplier->SupplierType ?></td>
            </tr>          
            <tr>
                <th style="text-align: left;">Ownership/Management Team</th>
                <td>
                    <ul>
                        <?php foreach($Personnel as $Person){ ?>
                        <li><?=$Person->DirectorName ?></li>
                        <?php } ?>
                    </ul>                
                </td>
            </tr>       
            <tr>
                <th style="text-align: left;">Documents Attached</th>
                <td>
                    <ul>
                        <?php foreach($DocumentsList as $Attached => $ID){ ?>
                            <li><?=$Attached ?></li>
                        <?php } ?>
                    </ul>
                </td>
            </tr>            
    </table>
    <p>Our team will review the information provided and the documents attached and communicate the outcome to you.</p>
    <p>
        Kind regards<br>
        <b>Procurement Team</b><br>
        Financial Reporting Center(FRC<br>
        Rahimtulla Tower, 13th Floor,<br>
        Upper Hill Road, Opp UK High Commission.<br>
        P.O. Box 57733 - 00200<br>
        Nairobi, Kenya
    </p>
</div>
