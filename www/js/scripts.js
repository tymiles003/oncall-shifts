// po načtení stránky
$(function () {
    recolor();
    // odeslání na formulářích
    $("form").submit(function () {
        $(this).ajaxSubmit();
        return false;
    });

    // odeslání pomocí tlačítek
    $("form :submit").click(function () {
        $(this).ajaxSubmit();
        return false;
    });

    $("select").change(function() {
        recolor();
        $(this).submit();
    });

    $("input[name=send]").closest('tr').hide();
});


var colors = [
    '#77AADD','#77CCCC','#88CCAA','#DDDD77','#DDAA77','#DD7788','#CC99BB',
    '#4477AA','#44AAAA','#44AA77','#AAAA44','#AA7744','#AA4455','#AA4488',
    '#114477','#117777','#117744','#777711','#774411','#771122','#771155'
];

function recolor() {
    var lastVal;
    $("select").each(function(){
        if ($(this).val()) {
            lastVal = $(this).val();
        }
        $(this).css("background-color", colors[lastVal]);
    });
}
