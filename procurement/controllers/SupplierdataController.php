<?php

namespace procurement\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;

use Office365\PHP\Client\Runtime\Auth\NetworkCredentialContext;
use Office365\PHP\Client\SharePoint\ClientContext;
use Office365\PHP\Client\Runtime\Auth\AuthenticationContext;
use Office365\PHP\Client\Runtime\Utilities\RequestOptions;
use Office365\PHP\Client\SharePoint\ListCreationInformation;
use Office365\PHP\Client\SharePoint\SPList;
use Office365\PHP\Client\SharePoint\Web;

use procurement\models\SupplierData;
use procurement\models\User;
use procurement\models\SupplyUser;
use procurement\models\BusinessDirectors;
use procurement\models\UploadFiles;

/**
 * SupplierdataController implements the CRUD actions for Supplierdata model.
 */
class SupplierdataController extends Controller
{
    //public $layout = 'login';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            "access" => [
                "class" => AccessControl::className(),
                "rules" => [
                    [
                        "actions" => [
                            "documents",
                            "submit",
                            "view-document",
                            "profile",
                            "personnel",
                            "categories",
                            "supervisors",
                            "update",
                            "updateprofile",
                            "delete-personnel",
                            "user-profile",
                            "update-user-profile",
                            "bank-branches",
                            "branches",
                            "getbankbranches",
                        ],
                        "allow" => true,
                        "roles" => ["@"],
                    ],
                    [
                        "actions" => ["index"],
                        "allow" => true,
                        "roles" => ["?"],
                    ],
                ],
            ],
            "verbs" => [
                "class" => VerbFilter::className(),
                "actions" => [
                    "logout" => ["post"],
                ],
            ],
        ];
    }
    /**
     * Lists all Supplierdata models.
     * @return mixed
     */
    public function actionIndex()
    {
    }

    public function actionUserProfile()
    {
        $this->layout = "main-wide";
        $identity = Yii::$app->user->identity;
        return $this->render("user_profile", ["model" => $identity]);
    }

    /**
     * Displays a single Supplierdata model.
     * @param string $id
     * @return mixed
     */
    public function actionProfile($id = "")
    {
        $identity = Yii::$app->user->identity;
        if (empty($identity->ApplicantId)) {
            $id = "None";
        } else {
            $id = $identity->ApplicantId;
        }
        $filter = ["No" => $id];
        $id = "";
        $service = Yii::$app->params["ServiceName"]["SupplierDataList"];
        $Data = Yii::$app->navhelper->getData($service, $filter);
        $myData = empty($Data) ? [] : $Data[0];
        $profile = new SupplierData();

        foreach ($myData as $name => $value) {
            $profile->$name = $value;
        }
        //echo $profile->Banker; exit;
        //populate lookup values with desctiptions
        if ($profile->Branch) {
            $profile->Branch = $this->getBankBranch(
                $profile->Branch,
                $profile->Banker
            );
        }
        if ($profile->Banker) {
            $profile->Banker = $this->getBank($profile->Banker);
        }
        if ($profile->SupplierType) {
            $profile->SupplierType = $this->getAGPOCategory(
                $profile->SupplierType
            );
        }
        return $this->render("view_profile", ["model" => $profile]);
    }

    /**
     * Updates an existing Supplierdata model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        return $this->render("_form", ["model" => $model]);
    }

    public function actionUpdateUserProfile()
    {
        $this->layout = "main-wide";
        $Profile = User::findOne(Yii::$app->user->identity->id);
        if ($Profile->load(Yii::$app->request->post())) {
            //ensure no changes in company name and KRA PIN
            $Profile->CompanyName = Yii::$app->user->identity->CompanyName;
            $Profile->KRA_PIN = Yii::$app->user->identity->KRA_PIN;
            $Profile->save();
            return $this->redirect(["user-profile"]);
        }
        return $this->render("_user_profile_form", ["model" => $Profile]);
    }

    public function actionUpdateprofile()
    {
        $this->layout = "main-wide";
        $profile = $this->getModel();

        if (
            $profile->load(Yii::$app->request->post()) &&
            $profile->validate()
        ) {
            //pass the variables to the service and post
            $service = Yii::$app->params["ServiceName"]["SupplierDataList"];

            $key = $profile->Key;
            $SupplierData = Yii::$app->request->post("SupplierData");

            $SupplierData["MaxBusinessValue"] = str_replace(
                ",",
                "",
                $SupplierData["MaxBusinessValue"]
            );
            if (!$key) {
                unset($SupplierData["No"]);
                $result = Yii::$app->navhelper->postData(
                    $service,
                    $SupplierData
                );
            } else {
                $result = Yii::$app->navhelper->updateData(
                    $service,
                    $SupplierData
                );
            }
            //print_r($result); exit;
            if (!is_object($result)) {
                Yii::$app->session->setFlash(
                    "error",
                    "Your details could not be saved. Please try again"
                );
            } else {
                $user = Yii::$app->user->identity;
                $user->ApplicantId = $result->No;
                $user->save();
                Yii::$app->session->setFlash(
                    "success",
                    "Your Business particulars have been updated"
                );
                return $this->redirect(["personnel"]);
            }
        }
        $Forms_Of_Business = $this->getFormsOFBusiness();
        $banks = $this->getBanks();
        $postcodes = $this->getPostCodes();
        $branch = [];
        if ($profile->Branch) {
            $branch = [
                $profile->Branch => $this->getBankBranch(
                    $profile->Branch,
                    $profile->Banker
                ),
            ];
        }
        $agpo = $this->getAGPOCategories();
        //print_r($profile); exit;
        return $this->render("_form", [
            "model" => $profile,
            "agpo" => $agpo,
            "banks" => $banks,
            "branch" => $branch,
            "postcodes" => $postcodes,
        ]);
    }

    public function actionPersonnel($ref = "")
    {
        $this->layout = "main-wide";
        $SupplierID = Yii::$app->user->identity->ApplicantId;
        $model = new BusinessDirectors();
        if ($ref) {
            $filter = ["No" => $SupplierID, "Line_No" => $ref];
            $service =
                Yii::$app->params["ServiceName"]["BusinessDirectorsList"];
            $Data = Yii::$app->navhelper->getData($service, $filter);
            $myData = empty($Data) ? [] : $Data[0];
            foreach ($myData as $name => $value) {
                $model->$name = $value;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $Params = Yii::$app->request->post("BusinessDirectors");
            //print_r( $Params); exit;
            $Key = $Params["Key"];
            $Service =
                Yii::$app->params["ServiceName"]["BusinessDirectorsCard"];
            $Params["No"] = $SupplierID;
            $MaxShares = Yii::$app->request->post("totalShares");
            $SharesSent = $Params["Shares"];
            //echo $SharesSent.' <<<>>>'.$MaxShares; exit;
            if ($SharesSent > $MaxShares) {
                if ($Params["Relationship"] == 1) {
                    //is a proprietor
                    Yii::$app->session->setFlash(
                        "error",
                        "You cannot add more than one proprietor in a sole propritorship form of business. Consider changing your form of business"
                    );
                } else {
                    Yii::$app->session->setFlash(
                        "error",
                        "Maximum number of shares should not exceed 100%"
                    );
                }
            } else {
                if ($Key) {
                    $Result = Yii::$app->navhelper->updateData(
                        $Service,
                        $Params
                    );
                } else {
                    $Result = Yii::$app->navhelper->postData($Service, $Params);
                }
                if (is_object($Result)) {
                    Yii::$app->session->setFlash(
                        "success",
                        "Profile updated successfully."
                    );
                    return $this->redirect(["personnel"]);
                } else {
                    Yii::$app->session->setFlash(
                        "error",
                        "Error Saving Information : " . $Result
                    );
                }
            }
        }

        $service2 = Yii::$app->params["ServiceName"]["SupplierDataList"];
        $Supplier = Yii::$app->navhelper->getData($service2, [
            "No" => $SupplierID,
        ]);
        $businesstype = @$Supplier[0]->Form_Of_Business;
        $OptionsArray = [];
        if ($businesstype == "Sole_Proprietorship") {
            $OptionsArray = ["1" => "Proprietor"];
        }
        if ($businesstype == "Partnership") {
            $OptionsArray = ["2" => "Partner"];
        }
        if ($businesstype == "Company") {
            $OptionsArray = ["3" => "Director", "4" => "Shareholder"];
        }

        $DataArray = [];
        $totalShares = 0;
        $filter = ["No" => $SupplierID];
        $service = Yii::$app->params["ServiceName"]["BusinessDirectorsList"];
        $personels = Yii::$app->navhelper->getData($service, $filter);
        $countries = $this->getCountries();
        $max = 100;
        $relArray = [
            "Not Indicated",
            "Proprietor",
            "Partner",
            "Director",
            "Shareholder",
        ];
        foreach ($personels as $v) {
            $totalShares += (float) @$v->Shares;
            $DataArray[] = [
                "Key" => @$v->Key,
                "ID_Passport" => @$v->ID_Passport,
                "DirectorName" => @$v->DirectorName,
                "Nationality" => $this->getCountry(@$v->Nationality),
                "Citizenship" => @$v->Citizenship,
                "Relationship" => @$relArray[@$v->Relationship],
                "Shares" => @$v->Shares,
                "KRA_PIN" => @$v->KRA_PIN,
                "Line_No" => @$v->Line_No,
                "TotalShares" => $totalShares,
            ];
        }
        //echo $totalShares; exit;
        $max = $max - $totalShares + (int) @$model->Shares;
        if ($businesstype == "Sole_Proprietorship") {
            $model->Shares = 100;
        }
        $dataProvider = new ArrayDataProvider([
            "allModels" => $DataArray,
            "pagination" => [
                "pageSize" => 10,
            ],
            "sort" => [
                "attributes" => ["No", "Title"],
            ],
        ]);
        return $this->render("_personnel", [
            "model" => $model,
            "max" => $max,
            "businesstype" => $businesstype,
            "dataProvider" => $dataProvider,
            "OptionsArray" => $OptionsArray,
            "totalShares" => $max,
            "countries" => $countries,
        ]);
    }

    public function actionDeletePersonnel($key)
    {
        $service = Yii::$app->params["ServiceName"]["BusinessDirectorsList"];
        $result = Yii::$app->navhelper->deleteData($service, $key);
        if (is_object($result)) {
            Yii::$app->session->setFlash("Error", " DELETED.");
        } else {
            Yii::$app->session->setFlash(
                "error",
                "Error Saving Information :" . $result
            );
        }
        return $this->redirect(["personnel"]);
    }

    public function actionCategories()
    {
        $this->layout = "main-wide";
        $SupplierID = Yii::$app->user->identity->ApplicantId;
        $Service =
            Yii::$app->params["ServiceName"]["PrequalificationApplication"];
        $OpenCategories = Yii::$app->navhelper->getData($Service, [
            "Supplier_ID" => $SupplierID,
            "Status" => "Open",
        ]);

        if (Yii::$app->request->post()) {
            //delete all open categories selected
            foreach ($OpenCategories as $OpenCat) {
                Yii::$app->navhelper->deleteData($Service, $OpenCat->Key);
            }
            $SelectedCategories = Yii::$app->request->post("categories");
            //create an application for each
            foreach ($SelectedCategories as $SelectedCategory) {
                $AppEntry = [];
                $AppEntry["Supplier_ID"] = $SupplierID;
                $AppEntry["Category"] = $SelectedCategory;
                $result = Yii::$app->navhelper->postData($Service, $AppEntry);
            }
            Yii::$app->session->setFlash(
                "success",
                "Your choice of categories havee been saved"
            );
            return $this->redirect(["categories"]);
        }
        //create an array of selected items
        $Selected = [];
        foreach ($OpenCategories as $OpenCats) {
            $Selected[] = $OpenCats->Category;
        }
        //fetch all supplier categories
        $Service = Yii::$app->params["ServiceName"]["SupplierCategories"];
        $Categories = Yii::$app->navhelper->getData($Service, ["Blocked" => 0]);
        $CategoryOptions = [];
        foreach ($Categories as $Category) {
            if (@$Category->Category_Code && @$Category->Description) {
                $CategoryOptions[$Category->Category_Code] =
                    $Category->Description;
            }
        }
        return $this->render("categories", [
            "CategoryOptions" => $CategoryOptions,
            "Selected" => $Selected,
        ]);
    }

    public function actionDocuments()
    {
        $this->layout = "main-wide";
        $SupplierID = Yii::$app->user->identity->ApplicantId;
        $Service =
            Yii::$app->params["ServiceName"]["PrequalificationApplication"];
        $SupDocsService = Yii::$app->params["ServiceName"]["SupplierDocuments"];
        if (Yii::$app->request->post()) {
            $Params = Yii::$app->request->post();
            foreach ($Params as $DocumentTypeID => $DocumentParams) {
                if (@$DocumentParams["useexisting"]) {
                    //no uploading
                    $DocumentID = $DocumentParams["useexisting"];
                } else {
                    //upload the file
                    $FilesModel = new UploadFiles();
                    $FilesModel->uploadFile = UploadedFile::getInstanceByName(
                        $DocumentTypeID . "[file]"
                    );
                    $DocumentMetadata = [
                        "RFX" => "APPLICATION",
                        "TenderNumber" => "Applying for Prequalification",
                        "SupplierNumber" => strtoupper(
                            Yii::$app->user->identity->{'CompanyName'}
                        ),
                        "DocumentType" => @$DocumentParams["name"],
                        //'DocumentNumber' => @$DocumentParams['number'],
                        "DocumentNumber" => "100",
                        "ExpiryDate" => date(
                            "Y-m-d",
                            strtotime(@$DocumentParams["expiry"])
                        ),
                    ];
                    //  var_dump($DocumentMetadata);exit;
                    $UploadedFileName = $FilesModel->uploadfile(
                        $DocumentMetadata
                    );
                    //delete all existing files of this type
                    $ExistingFilter = [
                        "Supplier" => Yii::$app->user->identity->ApplicantId,
                        "DocumentType" => $DocumentTypeID,
                        "Deleted" => 0,
                    ];
                    $ExistingDocuments = Yii::$app->navhelper->getData(
                        $SupDocsService,
                        $ExistingFilter
                    );
                    foreach ($ExistingDocuments as $ExistingDocument) {
                        // print_r($ExistingDocument); exit;
                        Yii::$app->navhelper->updateData($SupDocsService, [
                            "Key" => $ExistingDocument->Key,
                            "Deleted" => 1,
                        ]);
                    }
                    //post to supplier Docuemnts table
                    if ($UploadedFileName) {
                        $DocsService =
                            Yii::$app->params["ServiceName"][
                                "SupplierDocuments"
                            ];
                        $DocRecord = [
                            "Supplier" => $SupplierID,
                            "DocumentType" => @$DocumentTypeID,
                            "DucumentURL" =>
                                Yii::$app->params["sharepointUrl"] .
                                "/" .
                                Yii::$app->params["SupplierDocumentsURL"] .
                                "/" .
                                $UploadedFileName,
                            "Deleted" => 0,
                            "DateCreated" => date("Y-m-d"),
                            "Document_Number" => @$DocumentParams["number"],
                            // 'DocumentNumber' => '100',
                            "Expiry_Date" => date(
                                "Y-m-d",
                                strtotime(@$DocumentParams["expiry"])
                            ),
                        ];
                        //  var_dump($DocRecord);exit;
                        $SupplierDocEntry = Yii::$app->navhelper->PostData(
                            $DocsService,
                            $DocRecord
                        );

                        // $SupplierDocEntry = $SupplierDocEntry[0];

                        $DocumentID = @$SupplierDocEntry->No;
                        //  var_dump($DocumentID);exit;
                    }
                }
                //apply the document to categories
                $DocCategories = $DocumentParams["categories"];
                $DocCategories = explode("###", $DocCategories);
                //var_dump($DocumentID);exit;
                foreach ($DocCategories as $DocCategory) {
                    $CategoryDocsSVC =
                        Yii::$app->params["ServiceName"]["CategoryDocuments"];

                    $Data = [
                        "Supplier" => $SupplierID,
                        "Category" => $DocCategory,
                        "Document_Type" => $DocumentTypeID,
                        "Document_ID" => $DocumentID,
                    ];

                    Yii::$app->navhelper->postData($CategoryDocsSVC, $Data);
                }
            }
            return $this->redirect(["submit"]);
        }
        //get selected categories
        $OpenCategories = Yii::$app->navhelper->getData($Service, [
            "Supplier_ID" => $SupplierID,
            "Status" => "Open",
        ]);
        //get docuemnts for each category
        $CategoriesFilter = "";
        foreach ($OpenCategories as $OpenCat) {
            if ($CategoriesFilter) {
                $CategoriesFilter .= "|";
            }
            $CategoriesFilter .= $OpenCat->Category;
        }
        $DocsService = Yii::$app->params["ServiceName"]["PreqDocuments"];
        $RequiredDocuments = Yii::$app->navhelper->getData($DocsService, [
            "Category" => $CategoriesFilter,
        ]);
        //TODO::get documents required for business type and add to the list of documents
        $DocumentsList = [];
        $DocumentTypesFilter = "";
        $DocumentiCategories = [];
        foreach ($RequiredDocuments as $RequiredDocument) {
            if ($DocumentTypesFilter) {
                $DocumentTypesFilter .= "|";
            }
            $DocumentTypesFilter .= $RequiredDocument->Code;
            if (
                !array_key_exists($RequiredDocument->Code, $DocumentiCategories)
            ) {
                $DocumentiCategories[$RequiredDocument->Code] = [];
            }
            $DocumentiCategories[$RequiredDocument->Code][] =
                $RequiredDocument->Category;
        }
        //get the details of all the required documets
        $DocumentTypesSvc = Yii::$app->params["ServiceName"]["DocumentTypes"];
        $DocumentTypes = Yii::$app->navhelper->getData($DocumentTypesSvc, [
            "ID" => $DocumentTypesFilter,
        ]);

        foreach ($DocumentTypes as $DocumentType) {
            $DocumentsList[$DocumentType->ID] = [
                "name" => $DocumentType->Document_Type,
                "expires" => @$DocumentType->Document_Expires,
                "number" => @$DocumentType->Requires_Document_Number,
            ];
        }

        $ExistingFilter = [
            "Supplier" => Yii::$app->user->identity->ApplicantId,
            "DocumentType" => $DocumentTypesFilter,
            "Deleted" => 0,
        ];
        $ExistingDocuments = Yii::$app->navhelper->getData(
            $SupDocsService,
            $ExistingFilter
        );
        $ExistingArray = [];
        foreach ($ExistingDocuments as $ExistingDocument) {
            $ExistingArray[$ExistingDocument->DocumentType] =
                $ExistingDocument->No;
        }
        return $this->render("documents", [
            "DocumentiCategories" => $DocumentiCategories,
            "OpenCategories" => $OpenCategories,
            "ExistingDocuments" => $ExistingArray,
            "DocumentsList" => $DocumentsList,
        ]);
    }

    public function actionSubmit()
    {
        $this->layout = "main-wide";
        $SupplierID = Yii::$app->user->identity->ApplicantId;
        $SupplierSVC = Yii::$app->params["ServiceName"]["SupplierDataList"];
        $Supplier = Yii::$app->navhelper->getData($SupplierSVC, [
            "No" => $SupplierID,
        ]);
        //applied categories
        $Service =
            Yii::$app->params["ServiceName"]["PrequalificationApplication"];
        $OpenCategories = Yii::$app->navhelper->getData($Service, [
            "Supplier_ID" => $SupplierID,
            "Status" => "Open",
        ]);
        // echo '<pre>';
        // print_r($OpenCategories); exit;
        $CategoriesFilter = "";
        foreach ($OpenCategories as $OpenCategory) {
            if ($CategoriesFilter) {
                $CategoriesFilter .= "|";
            }
            $CategoriesFilter .= $OpenCategory->Category;
        }
        //personnel
        $PersonnelSVC =
            Yii::$app->params["ServiceName"]["BusinessDirectorsList"];
        $Personnel = Yii::$app->navhelper->getData($PersonnelSVC, [
            "No" => $SupplierID,
        ]);
        //attached Documents
        $CategoryDocsSVC =
            Yii::$app->params["ServiceName"]["CategoryDocuments"];
        $Data = [
            "Supplier" => $SupplierID,
            "Category" => $CategoriesFilter,
        ];
        $CategoryDocuments = Yii::$app->navhelper->getData(
            $CategoryDocsSVC,
            $Data
        );
        // var_dump($CategoryDocuments);exit;
        $DocumentsList = [];
        foreach ($CategoryDocuments as $CategoryDocument) {
            if (!@$CategoryDocument->Document_Name) {
                continue;
            }
            if (!@$DocumentsList[$CategoryDocument->Document_Name]) {
                $DocumentsList[$CategoryDocument->Document_Name] = "";
            }
            $DocumentsList[
                $CategoryDocument->Document_Name
            ] = @$CategoryDocument->Document_ID;
        }
        if (Yii::$app->request->post()) {
            //submit the application and redirect
            $Service =
                Yii::$app->params["ServiceName"]["SubmitPreqApplication"];
            $result = $result = Yii::$app->navhelper->prequalification(
                $Service,
                $SupplierID
            );

            // Yii::$app
            // ->mailer
            // ->compose(

            //     ['html' => 'confirm-receipt-html'],
            //     ['Supplier' => @$Supplier[0],'Supplier', 'OpenCategories' => $OpenCategories, 'Personnel' => $Personnel, 'DocumentsList' => $DocumentsList]
            // )
            // ->setFrom([Yii::$app->params['supportEmail'] => 'FRC Supplier Portal'])
            // ->setTo(Yii::$app->user->identity->Email)
            // ->setCC('evans.kiprotich@attain-es.com')
            // ->setSubject('APPLICATION FOR REGISTRATTION')
            // ->send();
            // //send email
            // //update as applied for prequalification
            // $user = Yii::$app->user->identity;
            // $user->AppliedForReg = 1;
            // $user->save();

            return $this->redirect(["home/dashboard"]);
        }

        return $this->render("confirm", [
            "Supplier" => @$Supplier[0],
            "Supplier",
            "OpenCategories" => @$OpenCategories,
            "Personnel" => $Personnel,
            "DocumentsList" => $DocumentsList,
        ]);
        //return $this->render('confirm');
    }

    public function actionViewDocument($ref)
    {
        // var_dump($ref);exit;
        $SupplierID = Yii::$app->user->identity->ApplicantId;
        $DocsService = Yii::$app->params["ServiceName"]["SupplierDocuments"];
        $SupplierDoc = Yii::$app->navhelper->getData($DocsService, [
            "No" => $ref,
            "Supplier" => $SupplierID,
        ]);
        // var_dump($SupplierDoc);exit;
        $SupplierDoc = $SupplierDoc[0];
        //var_dump($SupplierDoc[0]);exit;

        $FileURL = @$SupplierDoc->DucumentURL;
        //  var_dump($FileURL);exit;
        if ($FileURL) {
            $Url = Yii::$app->params["sharepointUrl"];
            $Library = Yii::$app->params["SupplierDocumentsURL"];
            $FileName = str_ireplace($Url . "/" . $Library, "", $FileURL);
            $FileName = Yii::getAlias("@webroot/attachments" . $FileName);
            return Yii::$app->response->sendFile(
                $FileName,
                @$SupplierDoc->DucumentURL . ".pdf",
                ["inline" => true]
            );
            // download from sharepoint and sendfile
            $Url = Yii::$app->params["sharepointUrl"];
            $username = Yii::$app->params["sharepointUsername"];
            $password = Yii::$app->params["sharepointPassword"];
            //'rba123!!';
            try {
                $authCtx = new NetworkCredentialContext($username, $password);
                $authCtx->AuthType = CURLAUTH_NTLM; //NTML Auth schema
                $ctx = new ClientContext($Url, $authCtx);
                $fileName = $ref . time() . ".pdf";
                $filePath = Yii::getAlias("@webroot/attachments/" . $fileName);
                $sourceFileUrl = str_replace($Url, "", $FileURL);
                $fp = fopen($filePath, "w+");
                $url =
                    $ctx->getServiceRootUrl() .
                    "web/getfilebyserverrelativeurl('$sourceFileUrl')/\$value";
                $options = new RequestOptions($url);
                $options->StreamHandle = $fp;
                $ctx->executeQueryDirect($options);
                return \Yii::$app->response->sendFile($filePath, $fileName, [
                    "inline" => true,
                ]);
            } catch (Exception $e) {
                print "Authentication failed: " . $e->getMessage() . "\n";
            }
        }
    }

    private function downloadFile(
        \Office365\PHP\Client\SharePoint\ClientContext $ctx,
        $fileUrl,
        $targetFilePath
    ) {
        try {
            $fileContent = \Office365\PHP\Client\SharePoint\File::openBinary(
                $ctx,
                $fileUrl
            );
            file_put_contents($targetFilePath, $fileContent);
            print "File {$fileUrl} has been downloaded successfully\r\n";
        } catch (Exception $e) {
            print "File download failed:\r\n";
        }
    }

    private function ensureList(
        \Office365\PHP\Client\SharePoint\ClientContext $ctx,
        $listTitle
    ) {
        $list = null;
        $lists = $ctx->getWeb()->getLists();
        $ctx->load($lists);
        $ctx->executeQuery();
        foreach ($lists->getData() as $curList) {
            if ($listTitle == $curList->Title) {
                print "List {$curList->Title} has been found\r\n";
                return $curList;
            }
        }
        return createList($ctx, $listTitle);
    }

    private static function getCountries()
    {
        $service = Yii::$app->params["ServiceName"]["CountryRegions"];
        $ls = Yii::$app->navhelper->getData($service);
        foreach ($ls as $k => $v) {
            $data[] = ["Code" => $v->Code, "Description" => $v->Name];
        }
        return $data;
    }

    private static function getCountry($id)
    {
        $service = Yii::$app->params["ServiceName"]["CountryRegions"];
        $ls = Yii::$app->navhelper->getData($service, ["Code" => $id]);
        if (is_array($ls)) {
            return isset($ls[0]->Code) ? $ls[0]->Code : "";
        } else {
            return "";
        }
    }

    private function getModel()
    {
        $identity = Yii::$app->user->identity;

        $id = "None";
        if ($identity->ApplicantId) {
            $id = $identity->ApplicantId;
        }
        $filter = ["No" => $id];
        $service = Yii::$app->params["ServiceName"]["SupplierDataList"];
        $Data = Yii::$app->navhelper->getData($service, $filter);

        $myData = empty($Data) ? [] : $Data[0];

        $profile = new SupplierData();
        foreach ($myData as $name => $value) {
            if ($name == "MaxBusinessValue") {
                if (!$value) {
                    $value = 0;
                }
                $profile->$name = Yii::$app->formatter->asDecimal($value);
            } else {
                $profile->$name = $value;
            }
        }
        if (!$identity->ApplicantId) {
            $profile->Email = $identity->Email;
            $profile->Telephone = $identity->Cell;
            $profile->KRA_PIN = $identity->KRA_PIN;
            $profile->Name = $identity->CompanyName;
        }
        return $profile;
    }

    /**
     * Finds the Supplierdata model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Supplierdata the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeletedocument($id, $docid)
    {
        $MyDocuments = SupplierDocuments::find()
            ->where(["Supplier" => $id, "No_" => $docid])
            ->one();
        $MyDocuments->Deleted = 1;
        $MyDocuments->save();

        return $this->redirect(["documents", "id" => $id]);
    }

    protected function findModel($id)
    {
        if (($model = SupplierData::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(
                "The requested page does not exist."
            );
        }
    }

    protected function getFormsOFBusiness()
    {
        $service = Yii::$app->params["ServiceName"]["FormsOfBusiness"];
        $NavData = Yii::$app->navhelper->getData($service); //Prequalifications
        return $NavData;
    }

    private function getBanks()
    {
        $service = Yii::$app->params["ServiceName"]["BankNames"];
        $ls = Yii::$app->navhelper->getData($service);
        foreach ($ls as $k => $v) {
            $data[] = [
                "Bank_Code" => @$v->Bank_Code,
                "Bank_Name" => @$v->Bank_Name,
            ];
        }
        return $data;
    }

    private function getBank($id)
    {
        $service = Yii::$app->params["ServiceName"]["BankNames"];
        $ls = Yii::$app->navhelper->getData($service, ["Bank_Code" => $id]);
        if (is_array($ls)) {
            return @$ls[0]->Bank_Name;
        } else {
            return "";
        }
    }

    public function actionBankBranches($id)
    {
        $service = Yii::$app->params["ServiceName"]["BankBranches"];
        $result = Yii::$app->navhelper->getData($service, ["Bank_Code" => $id]);
        return $result;
    }

    private function getBankBranches($bankcode)
    {
        $filter = ["Bank_Code" => $bankcode];
        $service = Yii::$app->params["ServiceName"]["BankBranches"];
        $result = Yii::$app->navhelper->getData($service, $filter);
        //print_r($result); exit;
        $branches = [];
        foreach ($result as $r) {
            $branches[] = ["id" => $r->Branch_Code, "name" => $r->Branch_Name];
        }
        return $branches;
    }

    public function actionBranches()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST["depdrop_parents"])) {
            $parents = $_POST["depdrop_parents"];
            if ($parents != null) {
                $bank_code = $parents[0];
                $out = self::getBankBranches($bank_code);

                return ["output" => $out, "selected" => ""];
            }
        }

        return ["output" => "", "selected" => ""];
    }

    private function getBankBranch($id, $bank)
    {
        $service = Yii::$app->params["ServiceName"]["BankBranches"];
        $ls = Yii::$app->navhelper->getData($service, [
            "Branch_Code" => $id,
            "Bank_Code" => $bank,
        ]);
        if (is_array($ls)) {
            return @$ls[0]->Branch_Name;
        } else {
            return "";
        }
    }

    private function getPostCodes()
    {
        $service = Yii::$app->params["ServiceName"]["PostCodes"];
        $ls = Yii::$app->navhelper->getData($service);
        foreach ($ls as $k => $v) {
            $data[] = [
                "Code" => @$v->Code,
                "City" => @$v->Code . "-" . @$v->City,
            ];
        }
        return $data;
    }

    private function getPostCode($id)
    {
        $service = Yii::$app->params["ServiceName"]["PostCodes"];
        $ls = Yii::$app->navhelper->getData($service, ["Code" => $id]);
        if (is_array($ls)) {
            return @$ls[0]->City;
        } else {
            return "";
        }
    }

    private function getAGPOCategories()
    {
        $service = Yii::$app->params["ServiceName"]["AGPOCategories"];
        $ls = Yii::$app->navhelper->getData($service);
        foreach ($ls as $k => $v) {
            $data[] = ["Code" => @$v->Code, "Name" => @$v->Name];
        }
        return $data;
    }

    private function getAGPOCategory($id)
    {
        $service = Yii::$app->params["ServiceName"]["AGPOCategories"];
        $ls = Yii::$app->navhelper->getData($service, ["Code" => $id]);
        if (is_array($ls)) {
            return @$ls[0]->Name;
        } else {
            return "";
        }
    }
}
