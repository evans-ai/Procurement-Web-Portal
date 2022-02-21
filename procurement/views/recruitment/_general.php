<!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-header row">
        </div>
        <div class="content-wrapper">
            <div class="content-body">
                <!-- Revenue, Hit Rate & Deals -->
                <div class="row">
                    <div class="col-xl-12 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">General </h4>
                                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body pt-0">
                                    <form id="personal" action="./recruitment/general" method="post">
                                        <input type="hidden" name="_csrf-backend" value="<?=Yii::$app->request->getCsrfToken()?>" />
                                     <div class="row"><!--begin row-->

                                        <div class="col-md-10 mx-auto">
                                            <div class="form-group row">
                                                <div class="form-group col-sm-4">
                                                    <label for="code">First Name <span style="color:red">*</span></label>
                                                    <input type="text" form="personal" name="First_Name" value="" placeholder="First Name" class="form-control" required>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label for="code">Middle Name <span style="color:red">*</span></label>
                                                    <input type="text" form="personal" name="Middle_Name" value="" placeholder="Middle_Name" class="form-control input-normal" required>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label for="code">Last Name <span style="color:red">*</span></label>
                                                    <input form="personal" type="text" name="Last_Name" value="" placeholder="Last Name"  class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end row-->

                                    <!---row 2-->

                                     <div class="row">
                                            <div class="col-md-10 mx-auto">
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="code">ID /Passport Number <span style="color:red">*</span></label>
                                                        <input type="text" class="form-control" form="personal" name="ID_Number" value="" required>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label for="code">Gender</label>
                                                        <select form="personal" name="Gender" class="form-control">
                                                            <option>Select Your Gender</option>
                                                            <option value="Male">Male</option>
                                                            <option value="Female" >Female</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label for="code">Citizenship </label>
                                                        <select name="Citizenship" form="personal" id="citizenship"  class="form-control input-normal">
                                                            <option>Select your Citizenship</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                     </div>
                                     <hr />

                                            <h4 class="card-title">Personal </h4>



                                    <div class="row">
                                        <div class="col-md-10 mx-auto">
                                             <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <label for="code">Status <span style="color:red">*</span></label>
                                                        <select name="Status" form="personal" id="Status"  class="form-control input-normal">
                                                            <option>Select your Status</option>
                                                            <option value="Normal">Normal</option>
                                                            <option value="Resigned">Resigned</option>
                                                            <option value="Discharged">Discharged</option>
                                                            <option value="Retrenched">Retrenched</option>
                                                            <option value="Pension">Pension</option>
                                                            <option value="Disabled">Disabled</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6">
                                                         <label for="Disability_Details">Disability Details <span style="color:red">*</span></label>
                                                         <input type="text" name="Disability_Details" class="form-control">
                                                    </div>
                                            </div>



                                            <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <label for="code">Status <span style="color:red">*</span></label>
                                                        <select name="Marital_Status" form="personal" id="Marital_Status"  class="form-control input-normal">
                                                            <option>Marital Status</option>
                                                            <option value="_blank_">_blank_</option>
                                                            <option value="Single">Single</option>
                                                            <option value="Married">Married</option>
                                                            <option value="Separated">Separated</option>
                                                            <option value="Divorced">Divorced</option>
                                                            <option value="Widow_er">Widow_er</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6">
                                                         <label for="Date_Of_Birth">Date of Birth <span style="color:red">*</span></label>
                                                         <input type="date" name="Date_Of_Birth" class="form-control">
                                                    </div>
                                            </div>


                                             <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <label for="Ethnic_Origin">Ethnic Origin <span style="color:red">*</span></label>
                                                        <select name="Ethnic_Origin" form="personal" class="form-control input-normal">
                                                            <option>Ethnic Origin</option>
                                                            
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6">
                                                         <label for="Date_Of_Birth">Age <span style="color:red">*</span></label>
                                                         <input type="number" name="Age" class="form-control">
                                                    </div>
                                            </div>

                                            <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <label for="Disabled">Disabled <span style="color:red">*</span></label>
                                                        <select name="Disabled" form="personal" class="form-control input-normal">
                                                            <option>Disability Options</option>
                                                            <option value="_blank_">_blank_</option>
                                                            <option value="No">No</option>
                                                            <option value="Yes">Yes</option>
                                                        </select>
                                                    </div>
                                                   
                                            </div>



                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <input type="submit" form="personal" class="btn btn-success" value="Save and Continue">
                                     </div>
                                    </form>
                                    <!--end row 2-->
                                    
                               </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <!--/ Revenue, Hit Rate & Deals -->



            </div>
        </div>
    </div>
    <!-- END: Content-->


