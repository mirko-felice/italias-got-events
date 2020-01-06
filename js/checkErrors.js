$(document).ready(function(){
    $("main > form > input[type='submit']").click((e) => {
        if ($("#password").val() !== $("#check_password").val()){
            e.preventDefault();
            alert("Attenzione! Le password NON corrispondono.");
        } else {
            $("main > form > input[type='submit']").submit();
        }
    });

    $("#tickets").change(() => {
        if($("#tickets option:selected").val() != -1){
            $("#check_not_available").prop('disabled', true);
        } else {
            $("#check_not_available").prop('disabled', false);
        }
    });

    $("#check_more_days").change(() => {
        if(!$("#check_more_days").is(":checked")){
            $("label[for='end_date']").fadeOut();
        } else {
            $("label[for='end_date']").fadeIn();
        }
    });
    
    $("#check_more_days").change(() => {
        if(!$("#check_more_days").is(":checked")){
            $("label[for='end_date']").fadeOut();
        } else {
            $("label[for='end_date']").fadeIn();
        }
    });
});