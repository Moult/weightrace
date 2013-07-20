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

$("input[name='stake']").focus( function() {
    if ( $(this).val()=="The loser will have to ...") {
        $(this).val("");
        $(this).css("color", "#444");
    }
});

$("input[name='stake']").blur( function() {
    if ( $(this).val()=="") {
        $(this).css("color", "#bbb");
        $(this).val("The loser will have to ...");
    }
});

$("input[name='update_weight']").focus( function() {
    if ( $(this).val()=="Weight in kg...") {
        $(this).val("");
        $(this).css("color", "#444");
    }
});

$("input[name='update_weight']").blur( function() {
    if ( $(this).val()=="") {
        $(this).css("color", "#bbb");
        $(this).val("Weight in kg...");
    }
});

$("textarea[name='update_food']").focus( function() {
    if ( $(this).val()=="Today, I ate ... (optional)") {
        $(this).val("");
        $(this).css("color", "#444");
    }
});

$("textarea[name='update_food']").blur( function() {
    if ( $(this).val()=="") {
        $(this).css("color", "#bbb");
        $(this).val("Today, I ate ... (optional)");
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

$('#bookmark').click(function() {
    if (window.sidebar && window.sidebar.addPanel) { // Mozilla Firefox Bookmark
        window.sidebar.addPanel(document.title,window.location.href,'');
    } else if(window.external && window.external.AddFavorite) { // IE Favorite
        window.external.AddFavorite(location.href,document.title);
    } else if(window.opera && window.print) { // Opera Hotlist
        alert('Press ' + (navigator.userAgent.toLowerCase().indexOf('mac') != - 1 ? 'Command/Cmd' : 'CTRL') + ' + D to bookmark this page.');
    } else { // webkit - safari/chrome
        alert('Press ' + (navigator.userAgent.toLowerCase().indexOf('mac') != - 1 ? 'Command/Cmd' : 'CTRL') + ' + D to bookmark this page.');
    }
});
