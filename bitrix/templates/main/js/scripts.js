jQuery(function($){

	BX.addCustomEvent('onAjaxSuccess', function(){
		$( "input[name=ORDER_PROP_15]" ).val($( "input[name=ORDER_PROP_1]" ).val());
		$( "input[name=ORDER_PROP_1]" ).blur(function() {
			var fio = $(this).val();
			$( "input[name=ORDER_PROP_15]" ).val(fio);
		});

		$( "input[name=ORDER_PROP_16]" ).inputmask("9999 999999");
	});

	function GetFormData(need_form,check)
	{
		var data="";
		var error=false;
		var error_text="";
		var flag_empty=false;
		var flag_email=false;
		var flag_phone=false;
		var flag_number=false;
		need_form.find("input[type=hidden]").each(function(){
			if($(this).attr("name")!="" && $(this).val()!="")
			{
				data+="&"+$(this).attr("name")+"="+$(this).val();
			}
		});
		need_form.find("select").each(function(){
			if($(this).attr("name")!="" && $(this).val()!="")
			{
				data+="&"+$(this).attr("name")+"="+$(this).val();
			}
		});
		need_form.find("input[type=text]").each(function(){
			if($(this).attr("name")!="" && $(this).val()!="")
			{
				data+="&"+$(this).attr("name")+"="+$(this).val();
			}
			if($(this).hasClass("email_check") && check && $(this).val()!="")
			{
				var email=$(this).val();
				var regexp = /^[0-9a-z\-\._+]+@(?:[0-9a-z\-]+\.)+[a-z]+$/i;
				if(!regexp.test(email))
				{
					error=true;
					if($(this).parents(".input_block").length>0)
					{
						$(this).parents(".input_block").addClass("err");
					}
					else
					{
						$(this).addClass("err");
					}
					if(flag_email==false)
					{
						flag_email=true;
						error_text+="E-mail введен неверно<br />";
					}
				}
			}
			if($(this).hasClass("phone_check") && check && $(this).val()!="")
			{
				var phone=$(this).val();
				var regexp = /^([0-9+\-\s]*)$/i;
				if(!regexp.test(phone) || phone=="")
				{
					error=true;
					if($(this).parents(".input_block").length>0)
					{
						$(this).parents(".input_block").addClass("err");
					}
					else
					{
						$(this).addClass("err");
					}
					if(flag_phone==false)
					{
						flag_phone=true;
						error_text+="Неверный формат телефона<br />";
					}
				}
			}
			if($(this).hasClass("number_check") && check && $(this).val()!="")
			{
				var number=$(this).val();
				var regexp = /^\d+$/i;
				if(!regexp.test(number) || number=="")
				{
					error=true;
					if($(this).parents(".input_block").length>0)
					{
						$(this).parents(".input_block").addClass("err");
					}
					else
					{
						$(this).addClass("err");
					}
					if(flag_number==false)
					{
						flag_number=true;
						error_text+="Проверьте правильность ввода числовых значений<br />";
					}
				}
			}
			if(($(this).hasClass("required") && $(this).val()=="" && check) || ($(this).val()=="Введите" && $(this).hasClass("required") && check))
			{
				error=true;
				if($(this).parents(".input_block").length>0)
				{
					$(this).parents(".input_block").addClass("err");
				}
				else
				{
					$(this).addClass("err");
				}
				if(flag_empty==false)
				{
					flag_empty=true;
					error_text+="Поля отмеченные звездочкой обязательны для заполнения<br />";
				}
			}
		});
		need_form.find("input[type=password]").each(function(){
			if($(this).attr("name")!="" && $(this).val()!="")
			{
				data+="&"+$(this).attr("name")+"="+$(this).val();
			}
			if(($(this).hasClass("required") && $(this).val()=="" && check) || ($(this).val()=="Введите" && $(this).hasClass("required") && check))
			{
				error=true;
				if($(this).parents(".input_block").length>0)
				{
					$(this).parents(".input_block").addClass("err");
				}
				else
				{
					$(this).addClass("err");
				}
				if(flag_empty==false)
				{
					flag_empty=true;
					error_text+="Поля отмеченные звездочкой обязательны для заполнения<br />";
				}
			}
		});
		need_form.find("textarea").each(function(){
			if($(this).attr("name")!="" && $(this).val()!="")
			{
				data+="&"+$(this).attr("name")+"="+$(this).val();
			}
			if($(this).hasClass("required") && $(this).val()=="" && check)
			{
				error=true;
				if($(this).parents(".input_block").length>0)
				{
					$(this).parents(".input_block").addClass("err");
				}
				else
				{
					$(this).addClass("err");
				}
				if(flag_empty==false)
				{
					flag_empty=true;
					error_text+="Поля отмеченные звездочкой обязательны для заполнения<br />";
				}
			}
		});
		need_form.find("input[type=checkbox]:checked").each(function(){
			if($(this).attr("name")!="" && $(this).val()!="")
			{
				data+="&"+$(this).attr("name")+"="+$(this).val();
			}
		});
		need_form.find("input[type=radio]:checked").each(function(){
			if($(this).attr("name")!="" && $(this).val()!="")
			{
				data+="&"+$(this).attr("name")+"="+$(this).val();
			}
		});
		if(error)
		{
			alertify.error(error_text);
			return "error";
		}
		else
		{
			return data;
		}
	}

	// перезагрузка формы
	$.fn.reloadForm=function(){
		
		var cur_form = $(this);
		
		$.ajax({
			type: "GET",
			url: "/ajax/form_reload.php",
			data:"form="+cur_form.attr("class"),
			success: function(msg){
				if(msg != "error")
				{
					if(cur_form.attr("rel"))
					{
						cur_form.attr("action",cur_form.attr("rel"));
					}
					
					cur_form.html(msg);
				}
			}
		});
		
		return cur_form;
	}

	// анимация загрузки
	function StartLoad(x,y)
	{
		$(".my_loader").show();
		$(".my_loader").css("left",x+16);
		$(".my_loader").css("top",y+16);
		$("body").bind("mousemove",function(e){
			$(".my_loader").css("left",e.pageX+16);
			$(".my_loader").css("top",e.pageY+16);
		});
	}
	function StopLoad(){
		$(".my_loader").hide();
		$(document).unbind("mousemove");
	}

	// функция для всплывающих окон
	$(document).on("click", ".open_popup", function(){
		var cur_link=$(this);
		var selector=".popup#"+$(this).data("id");
		//$(".darker").fadeIn("fast");
		if($(this).hasClass("change_item_id"))
		{
			$(selector).find(".change_id").val($(this).data("itemid"));
		}
		$(selector).fadeIn("fast");
		return false;
	});
	$(document).on("click", ".popup .close, .popup .close_me", function(){
		$(this).parents(".popup").stop().fadeOut("fast");
		//$(".darker").fadeOut("fast");
		return false;
	});

	// срабатывание submit
	$(document).on("click", ".submit a", function(){
		$(this).parents("form").submit();
		return false;
	});
	$(document).on("focus", "input", function(){
		if($(this).hasClass("err"))
		{
			$(this).removeClass("err");
		}
		if($(this).parents(".input_block").hasClass("err"))
		{
			$(this).parents(".input_block").removeClass("err");
		}
	});
	$(document).on("focus", "textarea", function(){
		if($(this).hasClass("err"))
		{
			$(this).removeClass("err");
		}
		if($(this).parents(".input_block").hasClass("err"))
		{
			$(this).parents(".input_block").removeClass("err");
		}
	});

	// submit форм
	$(document).on("click",".submit",function(){
		$(this).parents("form").submit();
		return false;
	});

	// проверка формы
	$(document).on("submit",".check_form",function(){
		if(GetFormData($(this),true)=="error")
		{
			return false;
		}
		else
		{
			return true;
		}
	});

	// прогрузка форм
	$("form[action='auto_load']").each(function(){
		$(this).reloadForm();
	});


	// анимация ошибки
	$.fn.ShowError=function(){
		var cur_popup=$(this);
		cur_popup.css("transform","perspective(300px) rotateY(0deg) translateX(0px)");
		cur_popup.css("transition-duration","0.1s");
		cur_popup.css("transition-property","all");
		cur_popup.css("transition-timing-function","linear");
		cur_popup.css("transform","perspective(300px) rotateY(-5deg) translateX(-30px)");
		setTimeout(function(){
			cur_popup.css("transition-duration","0.2s");
			cur_popup.css("transform","perspective(300px) rotateY(5deg) translateX(30px)");
		},100);
		setTimeout(function(){
			cur_popup.css("transform","perspective(300px) rotateY(-5deg) translateX(-30px)");
		},300);
		setTimeout(function(){
			cur_popup.css("transition-duration","0.1s");
			cur_popup.css("transform","perspective(300px) rotateY(0deg) translateX(0px)");
		},400);
		setTimeout(function(){
			cur_popup.css("transform","none");
			cur_popup.css("transition-duration","none");
			cur_popup.css("transition-property","none");
		},500);
		return cur_popup;
	}
	$.fn.ShowSuccess=function(){
		var cur_popup=$(this);
		cur_popup.css("transform","perspective(300px) rotateX(0deg) translateY(0px)");
		cur_popup.css("transition-duration","0.15s");
		cur_popup.css("transition-property","all");
		cur_popup.css("transition-timing-function","linear");
		cur_popup.css("transform","perspective(300px) rotateX(7deg) translateY(-50px)");
		setTimeout(function(){
			cur_popup.css("transition-duration","0.25s");
			cur_popup.css("transform","perspective(300px) rotateX(-10deg) translateY(70px)");
		},150);
		setTimeout(function(){
			cur_popup.css("transition-duration","0.15s");
			cur_popup.css("transform","perspective(300px) rotateX(0deg) translateY(0px)");
		},400);
		setTimeout(function(){
			cur_popup.css("transform","none");
			cur_popup.css("transition-duration","0s");
			cur_popup.css("transition-property","none");
		},550);
		return cur_popup;
	}

	// переключение вкладок
	$(document).on("click",".tabs_change li.change_tab a",function(){
		if(!$(this).parents("li").hasClass("active"))
		{
			var need_index=$(this).parents(".tabs_change").find(".change_tab a").index($(this));
			$(this).parents(".tabs_change").find(".tabs .tab.active").removeClass("active");
			$(this).parents(".tabs_change").find(".tabs .tab").eq(need_index).addClass("active");
			$(this).parents(".tabs_change").find(".change_tab.active").removeClass("active");
			$(this).parents("li").addClass("active");
			if($(this).parents(".catalog_spec_slider").length>0)
			{
				if($(".catalog_spec_slider .tabs .tab.active .carusel .inn li").length>5 && !$(this).parents(".tabs_change").find(".tabs .tab.active").hasClass("init"))
				{
					var max_height=0;
					$(".catalog_spec_slider .tabs .tab.active .carusel .inn li").each(function(){
						if($(this).height()>max_height)
						{
							max_height=$(this).height();
						}
					});
					$(".catalog_spec_slider .tabs .tab.active .carusel .inn li").css("height",max_height);
					var selector=".catalog_spec_slider .tabs .tab .arrows#arr_"+$(this).parents(".tabs_change").find(".tabs .tab.active").addClass("init").data("id");
					$(this).parents(".tabs_change").find(".tabs .tab.active").find(".carusel .inn").jCarouselLite({
						auto: 5000,
						vertical: false,
						circular: true,
						scroll: 1,
						visible: 5,
						btnNext: selector+" .right",
				        btnPrev: selector+" .left"
					});
				}
			}
		}
		return false;
	});
	
	$(document).on("click",".tabs_change .change_tab.item a",function () {
		
		// window.location.hash = $(this).attr("href");
		
		if(!$(this).parents(".item").hasClass("active"))
		{
			var need_index=$(this).parents(".tabs_change").find(".change_tab a").index($(this));
			$(this).parents(".tabs_change").find(".tabs .tab.active").removeClass("active");
			$(this).parents(".tabs_change").find(".tabs .tab").eq(need_index).addClass("active");
			$(this).parents(".tabs_change").find(".change_tab.active").removeClass("active");
			$(this).parents(".item").addClass("active");
		}
		return false;
	});

	// переключение вкладок по ссылкам
	$(document).on("click",".tabs_change .change_tab_a",function(){
		
		if(!$(this).hasClass("active"))
		{
			var need_index=$(this).parents(".tabs_change").find(".change_tab_a").index($(this));
			$(this).parents(".tabs_change").find(".tabs .tab.active").removeClass("active");
			$(this).parents(".tabs_change").find(".tabs .tab").eq(need_index).addClass("active");
			$(this).parents(".tabs_change").find(".change_tab_a.active").removeClass("active");
			$(this).addClass("active");
		}
		return false;
	});
	
	switch (window.location.hash.substring(1)) {
	  case "tab2":
		$(".tabs_change .change_tab.item a").eq(1).trigger("click");
		break;
	  case "tab3":
		$(".tabs_change .change_tab.item a").eq(2).trigger("click");
		break;
	  case "tab4":
		$(".tabs_change .change_tab.item a").eq(3).trigger("click");
	}
	
	// обратный звонок
	/*$(document).on("submit",".callback_form",function(e){
		var data=GetFormData($(this),true);
		var cur_form=$(this);
		var cur_popup=$(this).parents(".popup");
		if(data!="error")
		{
			cur_form.fadeTo("fast",0.3,function(){
				$.ajax({
					type: "POST",
					url: "/ajax/request_call.php",
					data:data,
					success: function(msg){
						if(msg!="error")
						{
							cur_form.fadeTo("fast",1,function(){
								cur_form.html(msg).parents(".popup").ShowSuccess().delay("3000").fadeOut("fast",function(){
									$(this).find("form").reloadForm();
								});
							});
						}
						else
						{
							cur_form.fadeTo("fast",1);
							cur_popup.ShowError();
							alertify.error("Произошла ошибка. Повторите запрос позже");
						}
					}
				});
			});
		}
		else
		{
			cur_popup.ShowError();
		}
		return false;
	});	*/


	// переключалка на главной
 	var time_delay=10000;
	function auto_scroll()
	{
		clearTimeout(hand_time);
		var need_count=$(".main_banner .banner").index($(".main_banner .banner.active"));
		need_count++;
		if(need_count>$(".main_banner .banner").length-1)
		{
			need_count=0;
		}
		$(".main_banner .banner.active").css("z-index","4").animate({"opacity":0}, "middle", function() {
			$(this).removeClass("active").removeAttr("style");
		});
		$(".main_banner .banner").eq(need_count).css("z-index","5").animate({"opacity":1}, "middle", function() {
			$(this).addClass("active");
		});
		$(".main_banner .man li.active").removeClass("active");
		$(".main_banner .man li").eq(need_count).addClass("active");
		hand_time=setTimeout(function(){auto_scroll();},time_delay);
		hand_time;
	}


	$(".main_banner .man li a").click(function()
	{
		clearTimeout(hand_time);
		if(!$(this).parents("li").hasClass("active"))
		{
			var need_count=$(".main_banner .man li a").index($(this));
			$(".main_banner .banner.active").css("z-index","4").animate({"opacity":0}, "middle", function() {
				$(this).removeClass("active").removeAttr("style");
			});
			$(".main_banner .banner").eq(need_count).css("z-index","5").animate({"opacity":1}, "middle", function() {
				$(this).addClass("active");
			});
			$(".main_banner .man li.active").removeClass("active");
			$(".main_banner .man li").eq(need_count).addClass("active");
		}
		hand_time=setTimeout(function(){auto_scroll();},time_delay);
		hand_time;
		return false;
	});
	if($(".main_banner .man li").length>1)
	{
		hand_time=setTimeout(function(){auto_scroll();},time_delay);
		hand_time;
	}

	// test form
	function NewGetFormData(need_form,check,show_errors)
	{
		var data="";
		var error=false;
		var error_text="";
		var flag_empty=false;
		var flag_email=false;
		var flag_phone=false;
		var flag_number=false;
		need_form.find("input[type=hidden]").each(function(){
			if($(this).attr("name")!="" && $(this).val()!="")
			{
				data+="&"+$(this).attr("name")+"="+$(this).val();
			}
		});
		need_form.find("select").each(function(){
			if($(this).attr("name")!="" && $(this).val()!="")
			{
				data+="&"+$(this).attr("name")+"="+$(this).val();
			}
		});
		need_form.find("input[type=text]").each(function(){
			if($(this).attr("name")!="")
			{
				data+="&"+$(this).attr("name")+"="+$(this).val();
				if($(this).hasClass("email_check") && check)
				{
					if($(this).val()!="" || ($(this).val()=="" && $(this).hasClass("req")))
					{
						var email=$(this).val();
						var regexp = /^[0-9a-z\-\._+]+@(?:[0-9a-z\-]+\.)+[a-z]+$/i;
						if(!regexp.test(email))
						{
							error=true;
							if($(this).parents(".input_block").length>0)
							{
								$(this).parents(".input_block").addClass("err");
							}
							else
							{
								$(this).addClass("err");
							}
							if(!show_errors)
							{
								if(flag_email==false)
								{
									flag_email=true;
									error_text+="E-mail введен неверно<br />";
								}
							}
							else
							{
								var error_field="E-mail введен не верно";
								if($(this).data("error"))
								{
									error_field=$(this).data("error");
								}
								$(this).next(show_errors).html(error_field);
							}
						}
					}
				}
				if($(this).hasClass("phone_check") && check)
				{
					if($(this).val()!="" || ($(this).val()=="" && $(this).hasClass("req")))
					{
						var phone=$(this).val();
						var regexp = /^([0-9+\-\s]*)$/i;
						if(!regexp.test(phone) || phone=="")
						{
							error=true;
							if($(this).parents(".input_block").length>0)
							{
								$(this).parents(".input_block").addClass("err");
							}
							else
							{
								$(this).addClass("err");
							}
							if(!show_errors)
							{
								if(flag_phone==false)
								{
									flag_phone=true;
									error_text+="Неверный формат телефона<br />";
								}
							}
							else
							{
								var error_field="Неверный формат телефона";
								if($(this).data("error"))
								{
									error_field=$(this).data("error");
								}
								$(this).next(show_errors).html(error_field);
							}
						}
					}
				}
				if($(this).hasClass("number_check") && check)
				{
					if($(this).val()!="" || ($(this).val()=="" && $(this).hasClass("req")))
					{
						var number=$(this).val();
						var regexp = /^\d+$/i;
						if(!regexp.test(number) || number=="")
						{
							error=true;
							if($(this).parents(".input_block").length>0)
							{
								$(this).parents(".input_block").addClass("err");
							}
							else
							{
								$(this).addClass("err");
							}
							if(!show_errors)
							{
								if(flag_number==false)
								{
									flag_number=true;
									error_text+="Проверьте правильность ввода числовых значений<br />";
								}
							}
							else
							{
								var error_field="Проверьте правильность ввода числовых значений";
								if($(this).data("error"))
								{
									error_field=$(this).data("error");
								}
								$(this).next(show_errors).html(error_field);
							}
						}
					}
				}
				if($(this).hasClass("req") && $(this).val()=="" && check)
				{
					if(!$(this).hasClass("email_check") && !$(this).hasClass("phone_check") && !$(this).hasClass("number_check"))
					{
						error=true;
						if($(this).parents(".input_block").length>0)
						{
							$(this).parents(".input_block").addClass("err");
						}
						else
						{
							$(this).addClass("err");
						}
						if(!show_errors)
						{
							if(flag_empty==false)
							{
								flag_empty=true;
								error_text+="Поля отмеченные звездочкой обязательны для заполнения<br />";
							}
						}
						else
						{
							var error_field="Поля отмеченные звездочкой обязательны для заполнения";
							if($(this).data("error"))
							{
								error_field=$(this).data("error");
							}
							$(this).next(show_errors).html(error_field);
						}
					}
				}
			}
		});
		need_form.find("input[type=password]").each(function(){
			if($(this).attr("name")!="" && $(this).val()!="")
			{
				data+="&"+$(this).attr("name")+"="+$(this).val();
			}
			if($(this).hasClass("req") && $(this).val()=="" && check)
			{
				error=true;
				if($(this).parents(".input_block").length>0)
				{
					$(this).parents(".input_block").addClass("err");
				}
				else
				{
					$(this).addClass("err");
				}
				if(!show_errors)
				{
					if(flag_empty==false)
					{
						flag_empty=true;
						error_text+="Поля отмеченные звездочкой обязательны для заполнения<br />";
					}
				}
				else
				{
					var error_field="Поля отмеченные звездочкой обязательны для заполнения";
					if($(this).data("error"))
					{
						error_field=$(this).data("error");
					}
					$(this).next(show_errors).html(error_field);
				}
			}
		});
		need_form.find("textarea").each(function(){
			if($(this).attr("name")!="" && $(this).val()!="")
			{
				data+="&"+$(this).attr("name")+"="+$(this).val();
			}
			if($(this).hasClass("req") && $(this).val()=="" && check)
			{
				error=true;
				if($(this).parents(".input_block").length>0)
				{
					$(this).parents(".input_block").addClass("err");
				}
				else
				{
					$(this).addClass("err");
				}
				if(!show_errors)
				{
					if(flag_empty==false)
					{
						flag_empty=true;
						error_text+="Поля отмеченные звездочкой обязательны для заполнения<br />";
					}
				}
				else
				{
					var error_field="Поля отмеченные звездочкой обязательны для заполнения";
					if($(this).data("error"))
					{
						error_field=$(this).data("error");
					}
					$(this).next(show_errors).html(error_field);
				}
			}
		});
		need_form.find(".check input[type=checkbox]:not(:checked)").each(function(){
				error=true;
				if($(this).parents(".input_block").length>0)
				{
					$(this).parents(".input_block").addClass("err");
				}
				else
				{
					$(this).addClass("err");
				}
					flag_empty=true;
					//var error_field="Согласитесь с условиями политики конфиденциальности и дайте согласие на обработку персональных данных";
					error_text+="Согласитесь с условиями политики конфиденциальности и дайте согласие на обработку персональных данных";
					/*if($(this).data("error"))
					{
						error_field=$(this).data("error");
					}
					$(this).next(show_errors).html(error_text);*/

		});
		need_form.find("input[type=checkbox]:checked").each(function(){
			if($(this).attr("name")!="" && $(this).val()!="")
			{
				data+="&"+$(this).attr("name")+"="+$(this).val();
			}
		});
		need_form.find("input[type=radio]:checked").each(function(){
			if($(this).attr("name")!="" && $(this).val()!="")
			{
				data+="&"+$(this).attr("name")+"="+$(this).val();
			}
		});
		if(error)
		{
			if(!show_errors)
			{
				alertify.error(error_text);
			}
			return "error";
		}
		else
		{
			return data;
		}
	}
	$(document).on("submit",".new_script",function(){
		var data=NewGetFormData($(this),true,".hint");
		console.log(data);
		return false;
	});





	$(document).on("blur",".big_basket .count input",function(){
		var quant=parseInt($(this).val());
 		var id=$(this).parents(".line").data("id");
		var data="id="+id+"&quant="+quant;
 		ChangeCount(data);
		return false;
	});

	$(document).on("change", ".count_block .value input", function(){
		var count = $(this).parents(".value").find("input").data("count");
		var rate = $(this).data("rate");
		var	cur_value;
		if (parseInt($(this).val())){ cur_value = $(this).val(); }else{ cur_value = rate; };

		var dif = cur_value % rate;
		var quant = cur_value - dif;
		if(quant < rate){ quant = rate; }
		if(count)
		{
			if(quant > count){ quant = count; alertify.error("Запрашиваемое кол-во превышает остаток. На складе: "+count); }
		}
		$(this).val(quant);
	});

	$(document).on("click",".count_block .value .plus",function(){

		//var count = 0;
		//count = $(this).parents(".value").find("input").data("count");

		//var rate = 0;
		//frate = $(this).parents(".value").find("input").data("rate");

		//var quant = 0;

		//console.log();

		quant = parseInt($(this).parents(".value").find("input").val())+rate;

		console.log('rate='+rate);

 		$(this).parents(".value").find("input").val(quant);

		return false;
	});

	$(document).on("click",".count_block .value .minus",function(){
		var rate = $(this).parents(".value").find("input").data("rate");
		var quant = parseInt($(this).parents(".value").find("input").val())-rate;
		if(quant >= rate)
 		{
	 		$(this).parents(".value").find("input").val(quant);
		}
		return false;
	});


	$(document).on("click",".big_basket .count_block .plus",function(){
		var count = $(this).parents(".value").find("input").data("count");
		var quant=parseInt($(this).parents(".value").find("input").val());
		if(count)
		{
			if(quant > count){ quant = count; alertify.error("Запрашиваемое кол-во превышает остаток. На складе: "+count);}
		}
 		$(this).parents(".value").find("input").val(quant);
 		var id=$(this).parents(".line").data("id");
		var data="id="+id+"&quant="+quant;
 		ChangeCount(data);
		return false;

	});

	$(document).on("click",".big_basket .count_block .minus",function(){

		var quant=parseInt($(this).parents(".value").find("input").val());
		if(quant>0)
 		{
	 		var id=$(this).parents(".line").data("id");
			var data="id="+id+"&quant="+quant;
	 		ChangeCount(data);
		}
		return false;

	});

	$(document).on("change", ".big_basket .count_block .value input", function(){
		var count = $(this).parents(".value").find("input").data("count");
		var quant = $(this).val();
		if(quant>0)
 		{
	 		var id=$(this).parents(".line").data("id");
			var data="id="+id+"&quant="+quant;
	 		ChangeCount(data);
		}

	});


	function ChangeCount(data)
	{
		$.ajax({
			type: "GET",
			url: "/ajax/change_count.php",
			data:data,
			success: function(msg){
				if(msg!="error")
				{
					if($(".big_basket").length==0)
					{
						UpdateSmallBasket();
					}
					else
					{
						UpdateBigBasket();
					}
				}
				else
				{
					alertify.error("");
				}
			}
		});
	}

	var rate = 1;

	// покупка
	$(document).on("click",".add2basket",function(e){
		rate = $(this).data("rate");
		if(!$(this).hasClass("null"))
		{
			if($(this).data("count")>0)
			{
				var width=100;
				var label="Много";
				var count=$(this).data("count");
				if(count<=9)
				{
					width=count*10;
				}
				if(count==0)
				{
					label="Нет в наличии";
				}
				else
				{
					if(count>0 && count<=2)
					{
						label="Мало";
					}
					else
					{
						if(count>2 && count<=5)
						{
							label="Заканчиваются";
						}
						else
						{
							if(count>5 && count<=9)
							{
								label="В достаточном количестве";
							}
						}
					}
				}
				$(".popup.add_count .item_id").val($(this).data("id"));
				$(".popup.add_count .value input").val(rate);
				$(".popup.add_count .value input").attr("data-rate", rate);
				$(".popup.add_count .indicator").attr("title",label);
				$(".popup.add_count .indicator span").css("width",width+"%");

				var left="0px";
				var top="0px";
				var $item=$(this).parents(".item");

				if($(this).parents(".carusel").length>0)
				{
					var $root=$(".main_container");
					var itemOffset=$item.offset();
					var rootOffset=$root.offset();
					left=Math.round(itemOffset.left-rootOffset.left)+"px";
					top=Math.round(itemOffset.top-rootOffset.top+$item.outerHeight()-132)+"px";
					$(".popup.add_count").addClass("wide");
				}
				else if($item.hasClass('slide'))
				{
					left=$item.offset().left-316+"px";
					top=$item.offset().top+$item.outerHeight()-275+"px";
					$(".popup.add_count").removeClass("wide");
				}
				else
				{
					left=$item.position().left+parseInt($item.css('marginLeft'), 10)+"px";
					top=$item.position().top+$item.outerHeight()-132+"px";
					$(".popup.add_count").removeClass("wide");
				}
				$(".popup.add_count").css({"top":top,"left":left}).fadeIn("fast");
			}
			else
			{
				var cur_but=$(this);
				var data="ID="+cur_but.data("id");
				if(cur_but.parents(".buy_block").find(".count_block input").length>0)
				{
					data+="&quantity="+cur_but.parents(".buy_block").find(".count_block input").val();
				}
				$.ajax({
					type: "GET",
					url: "/ajax/add2basket.php",
					data:data,
					success: function(msg){
						switch(msg){
							case "error":
							alertify.error("Произошла ошибка. Попробуйте повторить запрос позже");
							break;

							case "authorized":
								$(".popup#authorized").fadeIn("fast");
							break;

							case "success":
							UpdateSmallBasket();
							if($(document).width() > 768){
								alertify.success("<img src='/bitrix/templates/main/images/t_h_basket_ico.png'> Товар добавлен в корзину", 0, 0);
							}else{
								alertify.success("Добавлен <a href='/basket/'>перейти в корзину</a>", 0, 0);
							}
							
							ym(29264840,'reachGoal','DobavlenievKorzinu031024143836', {}, function () {
								console.log('запрос DobavlenievKorzinu031024143836 в Метрику успешно отправлен');
							});
							
							break;

							default:
							alertify.error(msg);
							break;
						}
					}
				});
			}
	 		return false;
		}
		else
		{
			alertify.error("Товар отстутствует в наличии");
			return false;
		}
 	});

	$(document).on("submit",".count_form",function(){
		var cur_form=$(this);
		var data=NewGetFormData(cur_form,true,false);
		if(data!="error")
		{
			cur_form.fadeTo("fast",0.3,function(){
				$.ajax({
					type: "GET",
					url: "/ajax/add2basket.php",
					data:data,
					success: function(msg){
						switch(msg){
							case "error":
							alertify.error("Произошла ошибка. Попробуйте повторить запрос позже");
							break;

							case "authorized":
								$(".popup#authorized").fadeIn("fast");
							break;

							case "success":
							UpdateSmallBasket();
							//alertify.success("Товар добавлен в корзину");

							if($(document).width() > 768){
								alertify.success("<img src='/bitrix/templates/main/images/t_h_basket_ico.png'> Товар добавлен в корзину", 0, 0);
							}else{
								alertify.success("Добавлен <a href='/basket/'>перейти в корзину</a>", 0, 0);
							}

							cur_form.parents(".popup").fadeOut("fast");
							cur_form.fadeTo("fast",1);
							
							ym(29264840,'reachGoal','DobavlenievKorzinu031024143836', {}, function () {
								console.log('запрос DobavlenievKorzinu031024143836 в Метрику успешно отправлен');
							});
							
							break;

							default:
							alertify.error(msg);
							cur_form.fadeTo("fast",1);
							break;
						}

					}
				});
			});
		}
		return false;
	});

 	function UpdateSmallBasket()
	{
		$(".menu_line .small_basket").fadeTo("fast",0.8,function(){
			$.ajax({
				type: "GET",
				url: "/ajax/small_basket.php",
				data:"",
				success: function(msg){
					if(msg!="error")
					{
						$(".menu_line .small_basket").html(msg).fadeTo("fast",1);
					}
					else
					{
						$(".menu_line .small_basket").fadeTo("fast",1);
						alertify.error("Произошла ошибка. Попробуйте повторить запрос позже");
					}
				}
			});
		});

		$(".fixed_basket").fadeTo("fast",0.8,function(){
			$.ajax({
				type: "GET",
				url: "/ajax/fixed_basket.php",
				data:"",
				success: function(msg){
					if(msg!="error")
					{
						$(".fixed_basket").html(msg).fadeTo("fast",1);
					}
					else
					{
						$(".fixed_basket").fadeTo("fast",1);
						alertify.error("Произошла ошибка. Попробуйте повторить запрос позже");
					}
				}
			});
		});

		$(".mobile_basket").fadeTo("fast",0.8,function(){
			$.ajax({
				type: "GET",
				url: "/ajax/mobile_basket.php",
				data:"",
				success: function(msg){
					if(msg!="error")
					{
						$(".mobile_basket").html(msg).fadeTo("fast",1);
					}
					else
					{
						$(".mobile_basket").fadeTo("fast",1);
						alertify.error("Произошла ошибка. Попробуйте повторить запрос позже");
					}
				}
			});
		});
	}

	function UpdateBigBasket()
	{
		$(".big_basket").fadeTo("fast",0.3,function(){
			$.ajax({
				type: "GET",
				url: "/ajax/big_basket.php",
				data:"",
				success: function(msg){
					if(msg!="error")
					{
						$(".big_basket").html(msg);
						$(".big_basket form[action='auto_load']").each(function(){
							$(this).reloadForm();
						});
						UpdateSmallBasket();
					}
					else
					{
						alertify.error("Произошла ошибка. Попробуйте повторить запрос позже");
					}
					$(".big_basket").fadeTo("fast",1);
				}
			});
		});
	}

	$(document).on("click",".big_basket .delete a.delete_item",function(e){
 		var id=$(this).parents(".line").data("id");
		var data="id="+id;
 		$.ajax({
			type: "GET",
			url: "/ajax/delete_item.php",
			data:data,
			success: function(msg){
				if(msg!="error")
				{
					if($(".big_basket").length==0)
					{
						UpdateSmallBasket();
					}
					else
					{
						UpdateBigBasket();
					}
				}
				else
				{
					alertify.error("Произошла ошибка. Попробуйте повторить запрос позже");
				}
			}
		});
 		return false;
 	});

 	$(document).on("click",".big_basket .clear_all_basket",function(e){
 		$.ajax({
			type: "GET",
			url: "/ajax/delete_all.php",
			data:"",
			success: function(msg){
				if(msg!="error")
				{
					if($(".big_basket").length==0)
					{
						UpdateSmallBasket();
					}
					else
					{
						UpdateBigBasket();
					}
				}
				else
				{
					alertify.error("Произошла ошибка. Попробуйте повторить запрос позже");
				}
			}
		});
 		return false;
 	});

	// быстрый заказ
	$(document).on("submit",".fast_order_form",function(){
		var cur_form=$(this);
		var data=NewGetFormData(cur_form,true,false);
		var cur_popup=$(this).parents(".popup");
		if(data!="error")
		{
			cur_form.fadeTo("fast",0.3,function(){
				$.ajax({
					type: "POST",
					url: "/ajax/fast_order.php",
					data:data,
					success: function(msg){
						if(msg.indexOf("error")==-1)
						{
							cur_form.fadeTo("fast",1,function(){
								cur_form.parents(".popup").find(".title").hide();
								cur_form.html(msg).parents(".popup").ShowSuccess().delay("3000").fadeOut("fast",function(){
									$(this).find("form").reloadForm();
									cur_form.parents(".popup").find(".title").show();
								});
							});
						}
						else
						{
							cur_form.fadeTo("fast",1);
							cur_popup.ShowError();
							alertify.error("Произошла ошибка. Повторите запрос позже");
						}
					}
				});
			});
		}
		else
		{
			cur_popup.ShowError();
		}
		return false;
	});

	// детальная страница товара
	if($(".catalog_detail .image_col .preview_images .inn li").length>0)
	{
		var max_height=0;
		$(".catalog_detail .image_col .preview_images .inn li").each(function(){
			if($(this).height()>max_height)
			{
				max_height=$(this).height();
			}
		});
		$(".catalog_detail .image_col .preview_images .inn li").css("height",max_height);
		$(".catalog_detail .image_col .preview_images .inn").jCarouselLite({
			auto: false,
			visible: 4,
			vertical: false,
			circular: false,
			scroll: 1,
			btnNext: ".catalog_detail .image_col .preview_images .man .right",
	        btnPrev: ".catalog_detail .image_col .preview_images .man .left"
		});
	}


	function vrnEhkInitLightboxClose()
	{
		function positionLightboxClose()
		{
			var $lightbox = $("#lightbox");
			if (!$lightbox.length || !$lightbox.is(":visible")) {
				return;
			}

			var $outer = $lightbox.find(".lb-outerContainer");
			var $close = $lightbox.find(".lb-closeContainer");
			if (!$outer.length || !$close.length) {
				return;
			}

			if ($close.parent()[0] !== $outer[0]) {
				$outer.append($close);
			}
		}

		var closePositionTimer = null;
		function startClosePositionWatcher()
		{
			positionLightboxClose();
			if (closePositionTimer) {
				return;
			}
			closePositionTimer = setInterval(function(){
				if ($("#lightbox").is(":visible")) {
					positionLightboxClose();
				} else {
					clearInterval(closePositionTimer);
					closePositionTimer = null;
				}
			}, 50);
		}

		$(document).off(".lbClose");
		$(window).off(".lbClose");

		document.addEventListener("click", function(e){
			var target = e.target;
			if (!target || !target.closest) {
				return;
			}
			if (target.closest('a[rel^=lightbox], a[data-lightbox], area[rel^=lightbox]')) {
				startClosePositionWatcher();
			}
		}, true);

		var lightboxNode = document.getElementById("lightbox");
		if (lightboxNode && window.MutationObserver) {
			new MutationObserver(function(){
				if ($("#lightbox").is(":visible")) {
					startClosePositionWatcher();
				}
			}).observe(lightboxNode, { attributes: true, attributeFilter: ["style", "class"], childList: true, subtree: true });
		}

		$(document).on("click.lbClose", "#lightbox .lb-prev, #lightbox .lb-next", function(){
			setTimeout(positionLightboxClose, 0);
		});
		$(window).on("resize.lbClose", positionLightboxClose);
	}

	vrnEhkInitLightboxClose();


	function vrnEhkInitLightboxZoom()
	{
		if (!window.matchMedia("(min-width: 769px)").matches) {
			return;
		}

		var zoomFactor = 2.5;
		var lensSize = 140;
		var $lightbox = $("#lightbox");

		function getZoomElements()
		{
			var $container = $lightbox.find(".lb-outerContainer");
			if (!$container.length) {
				return null;
			}

			if (!$container.find(".lb-zoom-result").length) {
				$container.append('<div class="lb-zoom-lens" aria-hidden="true"></div><div class="lb-zoom-result" aria-hidden="true"></div>');
			}

			return {
				$container: $container,
				$result: $container.find(".lb-zoom-result"),
				$lens: $container.find(".lb-zoom-lens"),
				$img: $container.find(".lb-image")
			};
		}

		function hideZoom()
		{
			var els = getZoomElements();
			if (!els) {
				return;
			}
			els.$result.removeClass("is-visible").css("background-image", "");
			els.$lens.hide();
			$lightbox.removeClass("is-zoomable");
		}

		function updateZoom(e)
		{
			if (!$lightbox.is(":visible")) {
				hideZoom();
				return;
			}

			var els = getZoomElements();
			if (!els || !els.$img.length) {
				return;
			}

			var img = els.$img[0];
			var src = img.currentSrc || img.src;
			if (!src || !img.complete) {
				return;
			}

			var imgRect = img.getBoundingClientRect();
			var containerRect = els.$container[0].getBoundingClientRect();
			var x = e.clientX - imgRect.left;
			var y = e.clientY - imgRect.top;

			if (x < 0 || y < 0 || x > imgRect.width || y > imgRect.height) {
				hideZoom();
				return;
			}

			$lightbox.addClass("is-zoomable");

			var rx = (x / imgRect.width) * 100;
			var ry = (y / imgRect.height) * 100;

			els.$result.css({
				"background-image": "url(" + src + ")",
				"background-size": (zoomFactor * 100) + "%",
				"background-position": rx + "% " + ry + "%"
			}).addClass("is-visible");

			var lensLeft = Math.max(0, Math.min(imgRect.width - lensSize, x - lensSize / 2));
			var lensTop = Math.max(0, Math.min(imgRect.height - lensSize, y - lensSize / 2));

			els.$lens.css({
				left: (imgRect.left - containerRect.left) + lensLeft,
				top: (imgRect.top - containerRect.top) + lensTop,
				width: lensSize,
				height: lensSize,
				display: "block"
			});
		}

		$(document).off(".lbZoom");
		$(document).on("mousemove.lbZoom", "#lightbox .lb-container", updateZoom);
		$(document).on("mouseleave.lbZoom", "#lightbox .lb-container", hideZoom);
		$(document).on("click.lbZoom", "#lightbox .lb-close, #lightboxOverlay", hideZoom);
		$(document).on("click.lbZoom", "#lightbox .lb-prev, #lightbox .lb-next", function(){
			setTimeout(hideZoom, 0);
		});
		$(window).on("resize.lbZoom", hideZoom);
	}

	vrnEhkInitLightboxZoom();


	$(document).on("click",".catalog_detail .image_col .preview_images .inn li a",function(){
 		if(!$(this).parents("li").hasClass("act"))
 		{
 			var need_index=$(".catalog_detail .image_col .preview_images .inn li a").index($(this));
 			$(".catalog_detail .image_col .big_images .big_image.active").css("z-index","1").animate({"opacity":0},"middle");
			$(".catalog_detail .image_col .big_images .big_image").eq(need_index).css("z-index","2").animate({"opacity":1}, "middle", function() {
				$(".catalog_detail .image_col .big_images .big_image.active").removeClass("active").removeAttr("style");
				$(this).addClass("active");
			});
			$(".catalog_detail .image_col .preview_images .inn li.act").removeClass("act");
			$(this).parents("li").addClass("act");
 		}
 		return false;
 	});

 	if($(".look_like .carusel .inn li").length>0)
	{
		var max_height=0;
		$(".look_like .carusel .inn li").each(function(){
			if($(this).height()>max_height)
			{
				max_height=$(this).height();
			}
		});
		$(".look_like .carusel .inn li").css("height",max_height);
		$(".look_like .carusel .inn").jCarouselLite({
			auto: 5000,
			vertical: false,
			circular: true,
			scroll: 1,
			visible: 5,
			btnNext: ".look_like .carusel .arrows .right",
	        btnPrev: ".look_like .carusel .arrows .left",
			afterEnd: function() {
				$('.popup.add_count').hide();
			}
		});
	}

	// страница галереи
	if($(".gallery_detail .image_col .preview_images .inn li").length>0)
	{
		var max_height=0;
		$(".gallery_detail .image_col .preview_images .inn li").each(function(){
			if($(this).height()>max_height)
			{
				max_height=$(this).height();
			}
		});
		$(".gallery_detail .image_col .preview_images .inn li").css("height",max_height);
		$(".gallery_detail .image_col .preview_images .inn").jCarouselLite({
			auto: false,
			visible: 4,
			vertical: false,
			circular: false,
			scroll: 1,
			btnNext: ".gallery_detail .image_col .preview_images .man .right",
	        btnPrev: ".gallery_detail .image_col .preview_images .man .left"
		});
	}


	$(document).on("click",".gallery_detail .image_col .preview_images .inn li a",function(){
 		if(!$(this).parents("li").hasClass("act"))
 		{
 			var need_index=$(".gallery_detail .image_col .preview_images .inn li a").index($(this));
 			$(".gallery_detail .image_col .big_images .big_image.active").css("z-index","1").animate({"opacity":0},"middle");
			$(".gallery_detail .image_col .big_images .big_image").eq(need_index).css("z-index","2").animate({"opacity":1}, "middle", function() {
				$(".gallery_detail .image_col .big_images .big_image.active").removeClass("active").removeAttr("style");
				$(this).addClass("active");
			});
			$(".gallery_detail .image_col .preview_images .inn li.act").removeClass("act");
			$(this).parents("li").addClass("act");
 		}
 		return false;
 	});

 	$(document).on("click",".gallery_detail .info_col .buttons_block a.show_hidden_text",function(){
 		var cur_but=$(this);
 		if(cur_but.parents(".info_col").find(".hidden_text").hasClass("opened"))
 		{
 			cur_but.parents(".info_col").find(".hidden_text").animate({"height":"70px"},"fast",function(){
				$(this).removeClass("opened");
				cur_but.text("Подробнее");
			});
 		}
 		else
		{
 			var need_height=cur_but.parents(".info_col").find(".hidden_text span").outerHeight()+15;
			cur_but.parents(".info_col").find(".hidden_text").animate({"height":need_height},"fast",function(){
				$(this).addClass("opened");
				cur_but.text("Скрыть");
			});
		}
 		return false;
 	});

 	// страница отзывов
 	$(document).on("click",".reviews_page .item .text_block .show_review a",function(){
 		var cur_but=$(this);
 		if(cur_but.parents(".text_block").find(".hidden_text").hasClass("opened"))
 		{
 			cur_but.parents(".text_block").find(".hidden_text").animate({"height":"144px"},"fast",function(){
				$(this).removeClass("opened");
				cur_but.text("Читать далее...");
			});
 		}
 		else
		{
 			var need_height=cur_but.parents(".text_block").find(".hidden_text span").outerHeight()+15;
			cur_but.parents(".text_block").find(".hidden_text").animate({"height":need_height},"fast",function(){
				$(this).addClass("opened");
				cur_but.text("Скрыть");
			});
		}
		return false;
 	});
 	var url=window.location.href;
 	if(url.indexOf("/reviews/")!==-1)
	{
		var hash=window.location.hash;
		if(hash.indexOf("show")!==-1)
		{
			var show_item=hash.substr(6);
			$(".reviews_page .item[data-id='"+show_item+"']").addClass("active");
			var need_height=$(".reviews_page .item[data-id='"+show_item+"']").find(".text_block .hidden_text span").outerHeight()+15;
			$(".reviews_page .item[data-id='"+show_item+"']").find(".text_block .hidden_text").animate({"height":need_height},"fast",function(){
				$("html,body").animate({"scrollTop":$(this).parents(".item").offset().top-30},"fast");
				$(this).addClass("opened");
				$(".reviews_page .item[data-id='"+show_item+"']").find(".text_block .show_review a").text("Скрыть");
			});
		}
	}

	// спецпредложения
	if($(".spec_carusel .inn .inn ul li").length>0)
	{
		var go_arr=[];
		$(".spec_carusel .man li a").each(function(i){
			go_arr[i]="#"+$(this).attr("id");
		});
		$(".spec_carusel .inn .inn").jCarouselLite({
			auto: false,
			visible: 1,
			vertical: false,
			circular: false,
			scroll: 1,
			btnGo: go_arr
		});
	}
	$(".spec_carusel .man li a").click(function(){
		if(!$(this).parents("li").hasClass("active"))
		{
			$(this).parents(".man").find(".active").removeClass("active");
			$(this).parents("li").addClass("active");
		}
	});

	// страница контактов
	$(".contacts_page select#select_contacts").selectbox();
	$(document).on("click",".contacts_page .selector input[type='submit']",function(){
		$(".contacts_page .info_content .tabs .tab.active").removeClass("active");
		var selector=".tab#page_"+$(this).parents(".selector").find("select").val()
		$(selector).addClass("active");
		return false;
	});

	$(document).on("submit",".callback_form",function(e){
		var data=NewGetFormData($(this),true,false);
		var cur_form=$(this);
		if(data!="error")
		{
			cur_form.fadeTo("fast",0.3,function(){
				$.ajax({
					type: "POST",
					url: "/ajax/feedback.php",
					data:data,
					success: function(msg){
						if(msg!="error")
						{
							cur_form.html(msg).fadeTo("fast",1,function(){
								setTimeout(function(){
									cur_form.reloadForm();
								},3000);
							});
						}
						else
						{
							alertify.error("Произошла ошибка. Повторите попытку позже");
						}
						cur_form.fadeTo("fast",1);
					}
				});
			});
		}
		return false;
	});

	// персональная страница
	$(document).on("submit",".personal_page .change_pass",function(){
		var data=NewGetFormData($(this),true,false);
		var cur_form=$(this);
		if(data!="error")
		{
			cur_form.fadeTo("fast",0.3,function(){
				$.ajax({
					type: "POST",
					url: "/ajax/change_pass.php",
					data:data,
					success: function(msg){
						if(msg.indexOf("error")===-1)
						{
							alertify.success("Пароль успешно изменен");
						}
						else
						{
							if(msg.indexOf("error_")!==-1)
							{
								alertify.error(msg.substr(6));
							}
							else
							{
								alertify.error("Произошла ошибка. Повторите попытку позже");
							}
						}
						cur_form.fadeTo("fast",1);
					}
				});
			});
		}
		return false;
	});

	$(document).on("submit",".personal_page .update_user",function(){
		var data=NewGetFormData($(this),true,false);
		var cur_form=$(this);
		if(data!="error")
		{
			cur_form.fadeTo("fast",0.3,function(){
				$.ajax({
					type: "POST",
					url: "/ajax/update_user.php",
					data:data,
					success: function(msg){
						if(msg.indexOf("error")===-1)
						{
							alertify.success("Данные успешно обновлены");
						}
						else
						{
							if(msg.indexOf("error_")!==-1)
							{
								alertify.error(msg.substr(6));
							}
							else
							{
								alertify.error("Произошла ошибка. Повторите попытку позже");
							}
						}
						cur_form.fadeTo("fast",1);
					}
				});
			});
		}
		return false;
	});

	$(document).on("change",".personal_page .pers_type input",function(){
		$(".personal_page .tabs .tab.active").removeClass("active");
		var selector=".personal_page .tabs .tab#tab_"+$(this).val();
		$(selector).addClass("active");
		console.log($(this).val());
	});
	$(document).on("click",".personal_page .blocks .block .title a",function(){
		if($(this).parents(".block").hasClass("active"))
		{
			$(this).parents(".block").removeClass("active");
		}
		else
		{
			$(this).parents(".block").addClass("active");
		}
		return false;
	});

	// главная
	if($(".catalog_spec_slider .tabs .tab.active.init .carusel .inn li").length>5)
	{
		var max_height=0;
		$(".catalog_spec_slider .tabs .tab.active.init .carusel .inn li").each(function(){
			if($(this).height()>max_height)
			{
				max_height=$(this).height();
			}
		});
		$(".catalog_spec_slider .tabs .tab.active.init .carusel .inn li").css("height",max_height);
		var selector=".catalog_spec_slider .tabs .tab .arrows#arr_"+$(".catalog_spec_slider .tabs .tab.active.init").data("id");
		$(".catalog_spec_slider .tabs .tab.active.init .carusel .inn").jCarouselLite({
			auto: 10000,
			vertical: false,
			circular: true,
			scroll: 1,
			visible: 5,
			btnNext: selector+" .right",
	        btnPrev: selector+" .left",
			afterEnd: function(a) {
				$('.popup.add_count').hide();
			}
		});
	}

	// товар под заказ
	$(document).on("submit",".no_item_form",function(){
		var cur_form=$(this);
		var data=NewGetFormData(cur_form,true,false);
		var cur_popup=$(this).parents(".popup");
		if(data!="error")
		{
			cur_form.fadeTo("fast",0.3,function(){
				$.ajax({
					type: "POST",
					url: "/ajax/order_item.php",
					data:data,
					success: function(msg){
						if(msg.indexOf("error")==-1)
						{
							cur_form.fadeTo("fast",1,function(){
								cur_form.parents(".popup").find(".title").hide();
								cur_form.html(msg).parents(".popup").ShowSuccess().delay("3000").fadeOut("fast",function(){
									$(this).find("form").reloadForm();
									cur_form.parents(".popup").find(".title").show();
								});
							});
						}
						else
						{
							cur_form.fadeTo("fast",1);
							cur_popup.ShowError();
							alertify.error("Произошла ошибка. Повторите запрос позже");
						}
					}
				});
			});
		}
		else
		{
			cur_popup.ShowError();
		}
		return false;
	});

	$('#mobile-menu-btn').click(function () {
		$(this).closest('.mobile-header').find('.drop-down-menu').toggle();
		return false;
	});


	var header__bottom = $( ".main_container .header" ).offset().top + 150;
	$(document).scroll(function() {
		if ($(this).scrollTop() >= header__bottom)
			$( ".main_container .fixed-header" ).addClass( 'active' );
		else
			$( ".main_container .fixed-header" ).removeClass( 'active' );

		updateSearchSidebarSticky();
	});

	function getSearchSidebarTopOffset() {
		var $fixedHeader = $('.main_container .fixed-header.active');
		if ($fixedHeader.length) {
			return $fixedHeader.outerHeight() + 10;
		}
		return 15;
	}

	function updateSearchSidebarSticky() {
		var $pageContent = $('.content#inner .page_content').has('.search-page');
		if (!$pageContent.length) {
			return;
		}

		var $sidebar = $pageContent.children('.right_col');
		var $placeholder = $pageContent.children('.right_col-sticky-placeholder');
		if (!$sidebar.length || !$placeholder.length) {
			return;
		}

		var topOffset = getSearchSidebarTopOffset();
		var scrollTop = $(window).scrollTop();
		var pageTop = $pageContent.offset().top;
		var pageBottom = pageTop + $pageContent.outerHeight();
		var sidebarHeight = $sidebar.outerHeight();
		var sidebarWidth = $placeholder.outerWidth();
		var sidebarLeft = $placeholder.offset().left;
		var stickStart = pageTop - topOffset;
		var stickEnd = pageBottom - sidebarHeight - topOffset;

		if (scrollTop <= stickStart) {
			$sidebar.removeClass('is-floating is-floating-end').css({width: '', left: '', top: '', right: ''});
			$placeholder.height(0);
		} else if (scrollTop >= stickEnd) {
			$sidebar.removeClass('is-floating').addClass('is-floating-end').css({width: sidebarWidth, left: '', top: '', right: 0});
			$placeholder.height(sidebarHeight);
		} else {
			$sidebar.removeClass('is-floating-end').addClass('is-floating').css({
				width: sidebarWidth,
				left: sidebarLeft,
				top: topOffset,
				right: 'auto'
			});
			$placeholder.height(sidebarHeight);
		}
	}

	function initSearchSidebarSticky() {
		var $pageContent = $('.content#inner .page_content').has('.search-page');
		if (!$pageContent.length) {
			return;
		}

		var $sidebar = $pageContent.children('.right_col');
		if (!$sidebar.length || $pageContent.children('.right_col-sticky-placeholder').length) {
			return;
		}

		$('<div class="right_col-sticky-placeholder"></div>').insertAfter($sidebar);
		$(window).on('resize', updateSearchSidebarSticky);
		updateSearchSidebarSticky();
	}

	initSearchSidebarSticky();

	$('body').on('click','form input[type="submit"]', function(e) {
		var form = $(this).closest('form');
		if (form.closest('#title-search, #title-search-fixed, #title-search-mobile, .search_block, .search').length) {
			return true;
		}
		if (form.attr('action') && form.attr('action').indexOf('/search') !== -1) {
			return true;
		}

		var self = $(this);
		grecaptcha.ready(function() {
			grecaptcha.execute('6Le8ZZ4aAAAAAMZUhsxou8BmT27TR4NaAh3HivxN', {action: 'submit'}).then(function(token) {
				// Add your logic to submit to your backend server here.
				var form = self.closest('form');
				form.prepend($('<input>').attr({name : 'g-recaptcha-response', type : 'hidden', value : token}));
				//self.remove();
				form.submit();
			});
		});
		return false;
	});

	$( "#tabs" ).tabs();

	function adjustPageTitle() {
		var $title = $('.content#inner .title_block .page_title');
		if (!$title.length) {
			return;
		}

		$title.removeClass('page_title--md page_title--sm page_title--xs');

		var len = $.trim($title.text()).length;
		if (len > 110) {
			$title.addClass('page_title--xs');
		} else if (len > 80) {
			$title.addClass('page_title--sm');
		} else if (len > 55) {
			$title.addClass('page_title--md');
		}
	}

	adjustPageTitle();

});
