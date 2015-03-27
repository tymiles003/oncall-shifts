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
    '#77AADD', '#77CCCC', '#88CCAA', '#DDDD77'
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
