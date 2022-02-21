/*=========================================================================================
    File Name: wizard-steps.js
    Description: wizard steps page specific js
    ----------------------------------------------------------------------------------------
    Item Name: Modern Admin - Clean Bootstrap 4 Dashboard HTML Template
   Version: 3.0
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

// Wizard tabs with numbers setup
$(".number-tab-steps").steps({
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: "fade",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: 'Submit'
    },
    onFinished:  function (event, currentIndex) {
        alert("Applicant Profile Saved.");
    },
    onStepChanging: async function (event, currentIndex) {
        await doAjax(currentIndex).then((rest) => {
           
            if(rest.ApplicantId && typeof rest.ApplicantId !== 'undefined' ){
                 console.log('Applicant Id  ...'+rest.ApplicantId);
                
                 document.getElementById("qualification-applicant_no").value = rest.ApplicantId;

                  document.getElementById("attachment-applicant_no").value = rest.ApplicantId;
                 document.getElementById("experience-applicant_no").value = rest.ApplicantId;
                 document.getElementById("referee-no").value = rest.ApplicantId;
                 document.getElementById("comment-applicant_no").value = rest.ApplicantId;
            }
            //appField.value = rest.ApplicantId;
           
            if(rest.No && typeof rest.No !== 'undefined' ){
                 console.log('Applicant Id  ...'+rest.No);
                //attachment-applicant_no
                document.getElementById("attachment-applicant_no").value = rest.No;
            }
            if(rest.Applicant_No && typeof Applicant_No !== 'undefined'){
                 console.log('Applicant Id  ...'+ rest.Applicant_No);
                //experience-applicant_no referee-no comment-applicant_no
                 document.getElementById("experience-applicant_no").value = rest.Applicant_No;
                 document.getElementById("referee-no").value = rest.Applicant_No;
                 document.getElementById("comment-applicant_no").value = rest.Applicant_No;
            }
            alert(JSON.stringify(rest));
            return true;
        }, (error) => {
            return false;
        }) ;               
    }
});

async function doAjax(currentIndex) {
    var formData = new FormData($('#dataForm')[0]);
    //console.log(formData);
    formData.append('currentIndex', currentIndex);

    
    var url = document.getElementById('dataForm').action;

    console.log('processor.....'+url);

    const result = await $.ajax({
        url: url, //"http://procurement.rbadev.com/supplierdata/create",
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        dataType: "json"
    });
//console.log(result.ApplicantId);
    
    return result;
}


async function submitForm(currentIndex) {
    var form = document.getElementById('form');
    // var form = document.querySelector('form');
    var formData = new FormData(form); 
    formData.append('currentIndex', currentIndex);
    var xhr = new XMLHttpRequest();
    var url = 'http://procurement.rbadev.com/supplierdata/create'

    xhr.open('POST', url, true);   
    // Send the Data.
    await xhr.send(formData); 
    // Set up a handler for when the request finishes.
    xhr.onload = function () 
    {
        if (xhr.status === 200) {
            // var obj =  JSON.parse(xhr.responseText);

            // console.log(xhr.responseText); 
            console.log('successfull');
            return true;         
        } else 
        {
            console.log('Failed to Save record');
            return true;
        }
    };  
    
}


// Wizard tabs with icons setup
$(".icons-tab-steps").steps({
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: "fade",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: 'Submit'
    },
    onFinished: function (event, currentIndex) {
        alert("Form submitted.");
    }
});

// Vertical tabs form wizard setup
$(".vertical-tab-steps").steps({
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: "fade",
    stepsOrientation: "vertical",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: 'Submit'
    },
    onFinished: function (event, currentIndex) {
        alert("Form submitted1.");
    }
});

// Validate steps wizard

// Show form
var form = $(".steps-validation").show();

$(".steps-validation").steps({
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: "fade",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: 'Submit'
    },
    onStepChanging: function (event, currentIndex, newIndex) {
        // Allways allow previous action even if the current form is not valid!
        if (currentIndex > newIndex) {
            return true;
        }
        // Forbid next action on "Warning" step if the user is too young
        if (newIndex === 3 && Number($("#age-2").val()) < 18) {
            return false;
        }
        // Needed in some cases if the user went back (clean up)
        if (currentIndex < newIndex) {
            // To remove error styles
            form.find(".body:eq(" + newIndex + ") label.error").remove();
            form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
        }
        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
    },
    onFinishing: function (event, currentIndex) {
        form.validate().settings.ignore = ":disabled";
        return form.valid();
    },
    onFinished: function (event, currentIndex) {
        alert("Submitted!");
    }
});

// Initialize validation
$(".steps-validation").validate({
    ignore: 'input[type=hidden]', // ignore hidden fields
    errorClass: 'danger',
    successClass: 'success',
    highlight: function (element, errorClass) {
        $(element).removeClass(errorClass);
    },
    unhighlight: function (element, errorClass) {
        $(element).removeClass(errorClass);
    },
    errorPlacement: function (error, element) {
        error.insertAfter(element);
    },
    rules: {
        email: {
            email: true
        }
    }
});


// Initialize plugins
// ------------------------------

// Date & Time Range
$('.datetime').daterangepicker({
    timePicker: true,
    timePickerIncrement: 30,
    locale: {
        format: 'MM/DD/YYYY h:mm A'
    }
});