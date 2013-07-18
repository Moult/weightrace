$("input[name='email']").focus( function() {
    if ( $(this).val()=="Type your email...") {
        $(this).val("");
    }
});

$("input[name='email']").blur( function() {
    if ( $(this).val()=="") {
        $(this).val("Type your email...");
    }
});

$("input[name='competition_name']").focus( function() {
    if ( $(this).val()=="Flabicide 2013") {
        $(this).val("");
        $(this).css("color", "#444");
    }
});

$("input[name='competition_name']").blur( function() {
    if ( $(this).val()=="") {
        $(this).css("color", "#bbb");
        $(this).val("Flabicide 2013");
    }
});

var racers = [ "racer1", "racer2", "racer3", "racer4" ];
var fields = [ "name", "weight", "height", "goal_weight", "email" ];
var fieldValues = [ "Name...", "Weight in kg...", "Height in cm...", "Goal weight in kg...", "Email..." ];

$.each(racers, function(i, racer) {
    $.each(fields, function(fieldIndex, fieldName) {
        $("input[name='" + racer + "_" + fieldName + "']").focus( function() {
            if ( $(this).val()==fieldValues[fieldIndex]) {
                $(this).val("");
            }
        });

        $("input[name='" + racer + "_" + fieldName + "']").blur( function() {
            if ( $(this).val()=="") {
                $(this).val(fieldValues[fieldIndex]);
            }
        });
    });
});

$(".datepicker").datepicker({
    minDate: 0,
    dateFormat: 'dd/mm/yy'
});
