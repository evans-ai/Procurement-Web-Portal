<?php
use yii\helpers\Html;
use yii\grid\GridView;
$this->title = 'RBA Procurement Portal - How To';
$this->params['breadcrumbs'][] = $this->title;
$baseUrl = Yii::$app->request->baseUrl;
?>
<section id="basic-form-layouts">
    <div class="row match-height">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-uppercase"><?=$this->title;?></h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <h2><strong>Navigation (Side Menu)</strong></h2>
                        <p><strong>Business Particulars</strong></p>
                        <p>Capture your company information by clicking on this link</p>
                        <p><em>* Remember to capture the director&rsquo;s information to complete your business details</em></p>
                        <p><strong>My Favorites</strong></p>
                        <p>Gives a list of tenders that you will mark for ease of reference</p>
                        <p><strong>My Applications</strong></p>
                        <p>A list of tenders that the organization has participated in via the portal</p>
                        <p><strong>RFX</strong></p>
                        <p>Menu containing available RBA tender opportunities that a supplier can participate in. It contains two menus ie</p>
                        <ul>
                            <li>Open RFX - List of tenders that are still available for participation</li>
                            <li>Expired RFX - List of tenders that were available but have since closed</li>
                        </ul>
                        <h2><strong>How to apply</strong></h2>
                        <ul>
                            <li>Step 1: Go to RFX menu</li>
                            <li>Step 2: Select the open RFX menu</li>
                            <li>Step 3: Peruse through the list of open RFX</li>
                            <li>Step 4: Click on view for the desired RFX</li>
                            <li>Step 5: Download the tender document and read it carefully</li>
                            <li>Step 6: Once you have what is required,</li>
                            <li>Step 7: Complete the downloaded tender form, scan and save</li>
                            <li>&nbsp;Step 8: Click on APPLY to apply</li>
                            <li>Step 9: Complete the online form and attach the document scanned in step 8 above</li>
                            <li>Step 10: Click on Submit to submit your response</li>
                        </ul>
                        <p>You will receive an email acknowledgement</p>
                        <p>*If you choose to respond with a no quote, on step 8: click <strong>SUBMIT A NO QUOTE RESPONSE&nbsp;</strong></p>
                        <p>Incase of any challenge get in touch with us on <a href="mailto:procurement @rba.go.ke"><strong>procurement @rba.go.ke</strong></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>                
               
<!-- Form wizard with vertical tabs section end -->

