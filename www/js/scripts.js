// po načtení stránky
$(function () {
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

	$("input[type=select]").change(function() {
		$(this).submit();
	});

	$("input[name=send]").closest('tr').hide();
});
