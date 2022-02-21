<p>Dear <?=$User->{'CompanyName'}?>,</p>
<p>The Financial Reporting Center(FRC would like to acknowledge receipt of your response to RFX No. <?=$Tender->Document_No?> below:</p>
<table border="1" cellpadding="5" style="border-collapse: collapse; width: 700px; border: 1px solid #E3EBF3;">
    <tr>
        <th  style="background-color: #F5F5F5;" width="20%">Reference No.</th>
        <td><?=$Tender->Document_No ?></td>
    </tr>
    <tr>
        <th  style="background-color: #F5F5F5;" width="20%">Title</th>
        <td><?=$Tender->Title ?></td>
    </tr>
    <tr>
        <th  style="background-color: #F5F5F5;" width="20%">Closing Date</th>
        <td><?=date('d/m/Y h:i a', strtotime($Tender->Closing_Date_Time)) ?></td>
    </tr>
    <tr>
        <th style="background-color: #F5F5F5;" width="20%">Response Date</th>
        <td><?=date('d/m/Y') ?></td>
    </tr>
</table>
<p>Your respose was received as follows:</p>
<h5>Mandatory/Technical Responses</h5> 
<table border="1" cellpadding="5" style="border-collapse: collapse; width: 700px; border: 1px solid #E3EBF3;">
    <tr style="background-color: #F5F5F5;">
        <th>#</th>
        <th>Requirement</th>
        <th>Response</th>
        <th>Document No.</th>
    </tr>
    <?php 
        $count = 1;
        foreach($Response->Response_Lines->Response_Lines as $Line){ 
            if($Line->Evaluation_Guide) continue;
    ?>                                
        <tr>
            <td><?=$count++ ?></td>
            <td><?=@$Line->Description ?></td>
            <td><?=@$Line->Response ?></td>
            <td><?=@$Line->Document_No ? @$Line->Document_No : '_' ?></td>
        </tr>
    <?php } ?>
</table>
<?php if(@$Response->Financial_Response_Lines->Financial_Response_Lines){ ?>
<h5>Financials (Prices Inclusive of Taxes)</h5>  
<table border="1" cellpadding="5" style="border-collapse: collapse; width: 700px; border: 1px solid #E3EBF3;">
    <tr style="background-color: #F5F5F5;">
        <th>#</th>
        <th>Description</th>
        <th>Unit of Measure</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Total Amount</th>
    </tr>
    <?php 
        $count = 1; $Amount = 0;
        foreach($Response->Financial_Response_Lines->Financial_Response_Lines as $Line){ 
            $Amount += @$Line->Amount;
    ?>                                
        <tr>
            <td><?=$count++ ?></td>
            <td><?=@$Line->Description ?></td>
            <td><?=@$Line->Unit_of_Measure ?></td>
            <td><?=@$Line->Quantity ?></td>
            <td><?=@$Line->Unit_Price ?></td>
            <th style="font-weight: 600; "><?=number_format(@$Line->Amount, 2) ?></th>             
        </tr>
    <?php } ?>
    <tr style="background-color: #F5F5F5;">
        <th style="font-weight: 600; text-align: center;" colspan="5">Total</th>
        <th style="font-weight: 600;"><?=number_format($Amount, 2) ?></th>
    </tr>
</table>
<?php } ?>
<p>We wish you all the best in the next steps.</p>
<p>Kind Regards</p>
<p><strong>Procurement Department<br>Financial Reporting Center(FRC</strong><br />
Rahimtullah Tower, 13th Floor, Upperhill Road<br />
P.O.Box 57733-00200, Nairobi, Kenya<br />
Tel: +254 20 2809000;<br />
Fax:2710330.<br />
E-Mail: procurement@rba.go.ke<br />
Website: www.rba.go.ke</p>