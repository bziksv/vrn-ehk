var arBtnOff = ["1", "7", "14", "15", "17", "23"];
var inp_select = $("input[name='prop_select']").val();
var arMainSetOff = ["23"];
var mainSetOn = false;
var change = false;
var button_on = false;
var num = 0;
var blocks = $("#tr_PROPERTY_"+inp_select).find("select").find("option").length - 1;
var tabs = blocks + 4;



function eachOption()
{
	var	arrOpt = $("#tr_PROPERTY_"+inp_select).find("select").find("option");

	arrOpt.each(
		function(index)
		{

			if($(this).is(':selected'))
			{
				if(index>0)
				{
					change = true;
					button_on = true;
					mainSetOn = true;

					num = index+2;
					
					if(arBtnOff.indexOf(index.toString()) != -1)
						button_on = false;

					if(arMainSetOff.indexOf(index.toString()) != -1)
						mainSetOn = false;
					
				}
			}

		}
	);


	$(".adm-detail-tab").each(
		function(index)
		{

			if(index <= tabs)
			{
				if(index != 0)
					$(this).hide();


				if(mainSetOn)
				{
					if(index <=2 && change)
						$(this).show();
				}

				if(index == num && change)
					$(this).show();

				

				if(index == (blocks + 3) && button_on)
					$(this).show();

			}

		}
	);

	button_on = false;
	change = false;

}

$(document).ready(function(){
	eachOption();
	$("#tr_PROPERTY_"+inp_select).find("select").change(function() {
		eachOption();
	});

});