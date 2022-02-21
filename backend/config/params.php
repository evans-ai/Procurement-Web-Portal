<?php
//http://KRB-SVR4.KRBHQS.GO.KE:8047/KRB/WS/Kenya%20Roads%20Board/Page/EmployeeCard


return [
    'sessionTimeoutSeconds'=>1800, // 3 minute 
    'adminEmail' => 'francnjamb@gmail.com',//IITA-HR@CGIAR.ORG
    //Test Creds
    'key'=>'//2sdf}Dp,324s#',
    'UploadsDirectory' => 'C:/inetpub/wwwroot/yii2/frontend/modules/appraisal/web/uploads/',
     //'server'=>'KRB-
    'server'=>'frc.attain-es.com',//'rba-nav.rbadev.com',//'iita-navdb.cgiarad.org',
    //'server'=>'iita-navapp.cgiarad.org',
    'WebServicePort'=>'7047',
    'ServerInstance'=>'BC130',//'DynamicsNAV110',
    //'ServerInstance'=>'DynamicsLive',
    'CompanyName'=>'FRC',//'RBA',//'IITA',
    'ldPrefix'=>'ATTAIN-ES',
    
    'ServiceName'=>
    array(
        'ClaimHeader'=>'claimHeader',
        'ClaimLine'=>'claimLines',
        'imprestHeader'=>'Imprest_Header',
        'imprestLine'=>'imprestLine',

        'leave'=>'Leave_application',//51511152(page)pub  leave list
        'leavecard'=>'LeaveCard',//Leave Application HR (51511153)
        'leavetypes'=>'leave_types',//51511154(page)pub
        'leaveAssignment'=>'Leave_Assignment',//51511156(page)

        'leaveRecallList'=>'Leave_Recall_List',//51511159(page)
        'leaveRecallCard'=>'Leave_Recall_Card',//51511160(page)
        'leavebalances'=>'Leavebalances',//51511078(page)
        'recallbuffer'=>'Recall_Buffer',//51511139(page)


        'storeqHeader'=>'StoreRequisitionHeader',
        'storeqLines'=>'StoreRequisitionLine',
        'purchasereqHeader'=>'Requisition_Header',
        'purchasereqLines'=>'Requisition_Line',

        'Qualifications'=>'Qualifications',//51511455 (page)
        'experience'=>'experience',//51511456(page)pub
        'referees'=>'referees',//51511457(page) pub
        'Attachments'=>'Attachments',//recruitment testimonials attached: 51511458(page) pub
        'appComments'=>'appComments',//51511459(page)
        'Job_Applicant_Card'=>'Job_Applicant_Card',//51511431(page)
        'HRUser'=>'HRUser',//51511804 (page)

        'Recruitment_Needs'=>'Recruitment_Needs',//51511292(page)
        'RecruitmentNeedsClosed'=>'RecruitmentNeedsClosed',//51511292(page)


        'Education_Levels'=>'Education_Levels',//51511836 --page
        'Academic_Certificates'=>'Academic_Certificates',//51511837 -- page
        'Academic_Grades_List'=>'Academic_Grades_List',//51511838 --page
        'CountryRegions'=>'CountryRegions',//10 --page
        'Ethnic'=>'Ethnic',//51511741 --page
        'Counties'=>'Counties',//51511777 --page
        'Subcountry'=>'Subcountry',//5151178 --page
        'Mandatorydocs'=>'Mandatorydocs',//51511699 --page


        'Recruitment_Request'=>'Recruitment_Request',//51511286(page)pub ==>Add start and end date
        'JobResponsibilities'=>'JobResponsibilities',//51511197(page) pub use 51511599 for categories
        'JobResponsibilityCategory'=>'JobResponsibilityCategory',
        'JobRequirements'=>'Job_Requirements',//51511195(page)  pub
        'Casuals'=>'CasualsRecruitmentNeeds',//test server -->51511259(page) not pub on live server the page id is 51511260
        'casualLines'=>'casualLines',//51511257(page) not live
        'casualsrequisition'=>'CasualsRequisition',//
        'JobAdverts'=>'JobAdverts',//51511296(page)not pub 51511296
        'trainingheader'=>'TrainingRequest',
        'trainingLines'=>'TrainingParticipants',
        'recruitmentpanel'=>'RecruitmentPanel',//page(51511437)
        'mandatorydocs'=>'MandatoryDocuments',//page(51511285)
        'recruitmentstages'=>'RecruitmentStages',//page (5151)
        'irscheck'=>'IRSReferenceCheck',//page(51511902)
        'nrscheck'=>'NRSReferenceCheck',//page(51511903)
        'companyjobs'=>'CompanyJobs',
        'shortlistinglines'=>'ShortListingLines',//51511995
        'ApplicantInterviewLines'=>'ApplicantInterviewLines',//51511436
        'stageshortlist'=>'StageShortlist',//51511705
        //'Interviewtypes'=>'InterviewTypes',//

        
        'approvals'=>'RequeststoApprove',//654 (PAGE)
        'approvalComments'=>'ApprovalComments',//660 (PAGE)

        'employeeList' => 'EmployeeList',//5201(page) 
        'profile'=>'EmployeeCard',//pub 5200(page)

        'codeunit'=>'TestCodeunit',//51511005(code unit)
        'codeunit_approvalmgt'=>'approvalmgt',//1535(code unit)
        'codeunit_factory' =>'CUnit_Factory',//51511010(code unit)

        'card'=>'card',
        'CustomerList'=>'CustomerList',
        'Countries'=>'Countries', //10 (page)pub
        'Applicant_Card'=>'Applicant_Card',//Applying for a job - 51511431(page)pub
        'Applicant_List'=>'Applicant_List',//51511432(page)pub
        'Applicantions'=>'Job_Applications',//job applications
        
        'postalcodes'=>'PostalCodes',//367(page)pub
        'Applicant_Qualifications'=>'Applicant_Qualification',//Qualifications applicant - 51511455(page)pub
        
        'departments'=>'Departments',//560(page)

//page 51511456 gives this error when I try to delete a record:  The requested operation is not supported.


        'loanRequest'=>'loanRequest',//51511709(page)  live -->loan request card
        'loanTypes'=>'loanTypeProduct',//51511018(page)  live
        'loanhistory'=>'loanRequestList',//51511712(page)  live


        'benefits'=>'benefitCard',//51511715(page) live //regards benefit header and lines
        'benefit_lines'=>'benefitLines',//51511209(page)//benefits lines--  live
        'employee_beneficiaries'=>'beneficiaries',//51511317(page)  live
        'employeeBenefits'=>'employmentBenefits',//51511208(page)  live --regards benefit types
        'benefithistory' =>'Benefits_Request', //page(51511716)

        'dimensionvalues'=>'dutystations', //560(page)live--->duplicate line 50
        'session'=>'Session_List',//9506 --> Nav login sessions

        'overtimelist'=>'Overtime_List',//51511116(page)
        'overtimecard'=>'Overtime_Header',//51511117(page)
        'overtimelines'=>'Overtime_Lines',//51511118(page)

        /***** Overtime Approvals **/
        'ot_approval_list'=>'OT_Approval_List',//51511141(page)
        'ot_approval_header'=>'OT_Approval_Header',//51511140(page)
        'ot_approval_lines'=>'OT_Approval_Lines',//51511142(page)

        'consultancypaymentlist'=>'Consultancypayment_List',//51511122(page)
        'consultancypaymentCard'=>'Consultancypayment_Card',//51511120(page)
        'consultancylines'=>'Consultancy_Lines',//51511121(page)

        'casualspaymentlist'=>'Casualspayment_List',//51511251(page)
        'casualpaymentCard'=>'Casualspayment_Card',//51511250(page)
        'casualpaymentLines'=>'Casual_Lines',//51511252(page)


        'payperiods'=>'Payperiods',//51511085(page)
        'HrDocuments'=>'HrDocuments',//51511085(page)

        //Employee mgt
        'em_update'=>'EM_Update_Header',//51511601
        'em_lines'=>'NOK_Update_Lines',//51511602




    ),
    'RequestType'=>[
        'Leave'=>0,
        'Recruitment'=>1,
        'Loan'=>2,
        'Benefit'=>3,
        'Leaverecall'=>4,
    ],
    //'leave_dir'=>'C:\\Users\\FNjambi\\SharePoint\\MS Dynamics - Documents\Leaves\\',
    'leave_dir'=>'C:\\Users\\Sngugi\\CGIAR\\MS Dynamics - Leaves\\',
    'dataUrl'=> 'http://localhost:7010/recruitment',
    'user.passwordResetTokenExpire' => 3600,
	'sharepointUrl'=>'http://192.168.200.145',
	'sharepointUsername'=>'rbss\administrator',
    'sharepointPassword'=>'Upgr@d320!9',
	'library'=>'Recruitment',
];

