<?php
//http://KRB-SVR4.KRBHQS.GO.KE:8047/KRB/WS/Kenya%20Roads%20Board/Page/EmployeeCard

return [
    'sessionTimeoutSeconds'=>1800,
    'adminEmail' => 'noreply@attain-es.com',
    'supportEmail' => 'noreply@attain-es.com',
    //Test Creds
    'key'=>'//2sdf}Dp,324s#',
    'server'=>'frc.attain-es.com',//'tra-nav-main.tra.local',//'iita-navdb.cgiarad.org',
    
    'WebServicePort'=>'7047',
    'ServerInstance'=>'BC130',//'DynamicsNAV110',
    'CompanyName'=>'FRC',//'RBA',//'IITA',
    'ldPrefix'=>'ATTAIN-ES',//'rbadev',//'CGIARAD',//Change this while going live    
    'ServiceName'=>[
        'SupplierDataList'=>'SupplierDataList',
        'SupplierDataCard'=>'SupplierDataCard',
        'SupplierFavorites'=>'SupplierFavorites',
        'SupplierFavouritesCard'=>'SupplierFavouritesCard',
        'SupplierDocuments'=>'SupplierDocuments',

        'CountryRegions'=>'CountryRegions',
        'BankNames'=>'BankNames',
        'BankBranches'=>'BankBranches',
        'PostCodes'=>'PostCodes',
        'AGPOCategories'=>'AGPOCategories',
        'PrequalificationRequestList'=>'PrequalificationRequestList',
        //Business Directors
        'BusinessDirectorsList'=>'BusinessDirectorsList',//51511903
        'BusinessDirectorsCard'=>'BusinessDirectorsCard',//51511902
        'FormsOfBusiness'=>'FormsOfBusiness', //51513004 list
        //Business Experience
        'FiscalYearCategories'=>'FiscalYearCategories',
        'PrequalificationAnalysisCard'=>'PrequalificationAnalysisCard',//Response
        'PrequalificationVendorLines'=>'PrequalificationVendorLines',
        'ResponseCard'=>'ResponseCard',  ///51511596
        'ResponseList'=>'ResponseList', //Applied Tenders
        
        'RequiredAttachments'=>'RequiredAttachments',
        'ResponseLines'=>'ResponseLines',
        'ResponseAnswers'=>'ResponseAnswers',
        'ResponseTypes'=>'ResponseTypes',

        'ApprovedPrequalificationCategories'=>'ApprovedPrequalificationCategories',
        'PreqRequisitionLine'=>'PreqRequisitionLine',

        'PrequalificationRequestHeaderCard'=>'PrequalificationRequestHeaderCard',
        'PrequalificationRequestList'=>'PrequalificationRequestList',
        'PrequalificationRequestLines'=>'PrequalificationRequestLines',
        'PreQualifiedSuppliers'=>'PreQualifiedSuppliers',
        'SupplierSelection'=>'SupplierSelection',
        'ProcurementRequestLines'=>'ProcurementRequestLines',
        'QuotationEvaluationList'=>'QuotationEvaluationList',
        'FinancialResponseLines'=>'FinancialResponseLines',
        'QuotationsOnPortalCard' => 'QuotationsOnPortalCard',
        //Business Directors
        'BusinessExperience'=>'BusinessExperience',//51511898
        'BusinessExperienceCard'=>'BusinessExperienceCard',//51511899
        //Expression Of Interest
        'ExpresionOfInterest'=>'ExpresionOfInterest',//51511550 (Page)		
		'RequestforEOI'=>'RequestforEOI',//51511551 (Card)		
		//Released RFP
        'ReleasedRFPList'=>'ReleasedRFPList',//51511573 (Page)
		'RFPCard'=>'RFPCard',//51511538 Card
		//Quotation Pending Opening
        'QuotationPendingOpening'=>'QuotationPendingOpening',//51511521 (Page)
		'Quotation'=>'Quotation',//51511517 Card
        'QuotationsVisibleOnPortal'=>'QuotationsVisibleOnPortal',
		//Approved Tender
        'ApprovedTenderList'=>'ApprovedTenderList',//51511529 (Page)
		'ApprovedTenderCard'=>'ApprovedTenderCard',//51511530 Card
		//Direct Procurement
        'DirectProcurementList'=>'DirectProcurementList',//51511468 (Page)
		'DirectRFQ'=>'DirectRFQ',//51511473 Card
		//Tender Evaluation List
		'TenderEvaluationList'=>'TenderEvaluationList',
        'SupplyUser'=>'SupplyUser',//51511804 (page)
        'SupplyUserList'=>'SupplyUserList',
        //supplier categories list 51511481
        'SupplierCategories' => 'SupplierCategories',
        'PreqDocuments' => 'PreqDocuments',
        //prequalification application, and documents - 51511481, 51511996
        'PrequalificationApplication' => 'PrequalificationApplication',
        'CategoryDocuments' => 'CategoryDocuments',
        'DocumentTypes' => 'DocumentTypes',
        'SubmitPreqApplication' => 'SubmitPreqApplication'

    ],
    'baseUrl' => 'http://localhost:8045',
    'sharepointUrl' => 'http://dms.rba.go.ke',
    'sharepointUsername' => 'rbss\\administrator',
    'sharepointPassword' => 'Upgr@d320!9',
    'SupplierDocumentsURL' => 'Supplier Documents',
    'serviceUsername' => 'FRC\Administrator',
    'servicePassword' => 'FRC@202!#',
];

