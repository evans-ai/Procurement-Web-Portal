<?php
namespace procurement\models;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

use Office365\PHP\Client\Runtime\Auth\NetworkCredentialContext;
use Office365\PHP\Client\SharePoint\ClientContext;
use Office365\PHP\Client\Runtime\Auth\AuthenticationContext;
use Office365\PHP\Client\Runtime\Utilities\RequestOptions;

use Office365\PHP\Client\SharePoint\ListCreationInformation;
use Office365\PHP\Client\SharePoint\SPList;
use Office365\PHP\Client\SharePoint\Web;

class UploadFiles extends Model
{
    /**
     * @var UploadedFile
     */
    public $uploadFile;

	//public $imageFile;

    public function rules()
    {
        return [
            ['uploadFile', 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf' ],		
        ];
    }

    public function uploadfile($DocumentMetadata){
        if ($this->validate()) {
            //$this->uploadFile->baseName
            $NewBaseName = Yii::$app->getSecurity()->generateRandomString(13).time();
            $destination = Yii::getAlias('@webroot/attachments/'. $NewBaseName . '.' . $this->uploadFile->extension);
            if($this->uploadFile->saveAs($destination)){
                //upload to SharePoint
              //  $TargetLibrary = Yii::$app->params['SupplierDocumentsURL'];
              //  $SharePointURL = $this->Shrpnt_attach($destination, $TargetLibrary, $DocumentMetadata);
                return  $NewBaseName . '.' . $this->uploadFile->extension;
            }            
        } 
        return  false;
    }
   
    private function Shrpnt_attach($filepath, $targetLibraryTitle, $DocumentMetadata){
        $Url = Yii::$app->params['sharepointUrl'];
        $username = Yii::$app->params['sharepointUsername'];
        $password = Yii::$app->params['sharepointPassword'];
        //'rba123!!';
        try {
            $authCtx = new NetworkCredentialContext($username, $password);
            $authCtx->AuthType = CURLAUTH_NTLM; //NTML Auth schema
            $ctx = new ClientContext($Url, $authCtx);
            $site = $ctx->getSite();
            $ctx->load($site); //load site settings            
            $ctx->executeQuery();
            $list = $this->ensureList($ctx->getWeb(),$targetLibraryTitle, \Office365\PHP\Client\SharePoint\ListTemplateType::DocumentLibrary);
            $this->uploadToSP($filepath, $list, $DocumentMetadata);
        }
        catch (Exception $e) {
            print 'Authentication failed: ' .  $e->getMessage(). "\n";
        }
    }
   

    private static function ensureList(Web $web, $listTitle, $type, $clearItems = true) {
        $ctx = $web->getContext();
        $lists = $web->getLists()->filter("Title eq '$listTitle'")->top(1);
        $ctx->load($lists);
        $ctx->executeQuery();
        if ($lists->getCount() == 1) {
            $existingList = $lists->getData()[0];
            if ($clearItems) {
                //self::deleteListItems($existingList);
            }
            return $existingList;
        }
        return ListExtensions::createList($web, $listTitle, $type);
    }
    
    /**
     * @param \Office365\PHP\Client\SharePoint\SPList $list
     */
    private static function deleteList(\Office365\PHP\Client\SharePoint\SPList $list){
        $ctx = $list->getContext();
        $list->deleteObject();
        $ctx->executeQuery();
    }

    private static function uploadToSP($localFilePath, \Office365\PHP\Client\SharePoint\SPList $targetList, $DocumentMetadata) {
        $ctx = $targetList->getContext();        
        $fileCreationInformation = new \Office365\PHP\Client\SharePoint\FileCreationInformation();
        $fileCreationInformation->Content = file_get_contents($localFilePath);
        $fileCreationInformation->Url = basename($localFilePath);
        //print_r($fileCreationInformation); exit;
        $uploadFile = $targetList->getRootFolder()->getFiles()->add($fileCreationInformation);
        $ctx->executeQuery();

        $uploadFile->getListItemAllFields()->setProperty('Title', $DocumentMetadata['SupplierNumber'].' - '.$DocumentMetadata['DocumentType']);
        $uploadFile->getListItemAllFields()->setProperty('Supplier_x0020_Name', $DocumentMetadata['SupplierNumber']);//Document_x0020_Number
        $uploadFile->getListItemAllFields()->setProperty('RFX', $DocumentMetadata['RFX']);//
        $uploadFile->getListItemAllFields()->setProperty('Document_x0020_Type', $DocumentMetadata['DocumentType']);
        $uploadFile->getListItemAllFields()->setProperty('Document_x0020_No_x002e_', $DocumentMetadata['DocumentNumber']);
        $uploadFile->getListItemAllFields()->setProperty('Expiry_x0020_Date', $DocumentMetadata['ExpiryDate']);
        $uploadFile->getListItemAllFields()->setProperty('Reference_x0020_Number', $DocumentMetadata['TenderNumber']);
        $uploadFile->getListItemAllFields()->update();
        $ctx->executeQuery();
    }


}
