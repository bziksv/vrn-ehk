BX.namespace("BX.Catalog.SetConstructor");

BX.Catalog.SetConstructor = (function()
{
	var SetConstructor = function(params)
	{
		this.numSliderItems = params.numSliderItems || 0;
		this.numSetItems = params.numSetItems || 0;
		this.jsId = params.jsId || "";
		this.ajaxPath = params.ajaxPath || "";
		this.currency = params.currency || "";
		this.lid = params.lid || "";
		this.iblockId = params.iblockId || "";
		this.basketUrl = params.basketUrl || "";
		this.setIds = params.setIds || null;
		/*this.offersCartProps = params.offersCartProps || null;*/
		this.itemsRatio = params.itemsRatio || null;
		this.noFotoSrc = params.noFotoSrc || "";
		this.messages = params.messages;

		this.canBuy = params.canBuy;
		this.mainElementPrice = params.mainElementPrice || 0;
		this.mainElementOldPrice = params.mainElementOldPrice || 0;
		this.mainElementDiffPrice = params.mainElementDiffPrice || 0;
		this.mainElementBasketQuantity = params.mainElementBasketQuantity || 1;


		this.parentCont = BX(params.parentContId) || null;
		this.sliderParentCont = this.parentCont.querySelector("[data-role='slider-parent-container']");
		this.sliderItemsCont = this.parentCont.querySelector("[data-role='set-other-items']");
		this.setItemsCont = this.parentCont.querySelector("[data-role='set-items']");
		this.setPriceCont = this.parentCont.querySelector("[data-role='set-price']");
		this.setOldPriceCont = this.parentCont.querySelector("[data-role='set-old-price']");
		this.setDiffPriceCont = this.parentCont.querySelector("[data-role='set-diff-price']");
		this.setDiffPriceContRow = this.setDiffPriceCont.parentNode;
		this.infoCountItems = this.parentCont.querySelector("[data-role='info-count-items']");

		this.emptySetMessage = this.parentCont.querySelector("[data-set-message='empty-set']");

		this.notAvailProduct = this.sliderItemsCont.querySelector("[data-not-avail='yes']");


		
	

		BX.bindDelegate(this.setItemsCont, 'click', { 'attribute': 'data-role' }, BX.proxy(this.deleteFromSet, this));
		BX.bindDelegate(this.sliderItemsCont, 'click', { 'attribute': 'data-role' }, BX.proxy(this.addToSet, this));

		var buyButton = this.parentCont.querySelector("[data-role='set-buy-btn']");

		if (this.canBuy)
		{
			BX.show(buyButton);
			BX.bind(buyButton, "click", BX.proxy(this.addToBasket, this));
		}
		else
		{
			BX.hide(buyButton);
		}
	};

	SetConstructor.prototype.addToSet = function()
	{
		var target = BX.proxy_context;
		if (!!target && target.hasAttribute('data-role') && target.getAttribute('data-role') == 'set-add-btn')
		{
			var item,
				itemId,
				itemName,
				itemUrl,
				itemImg,
				itemPrintPrice,
				itemPrice,
				itemPrintOldPrice,
				itemOldPrice,
				itemDiffPrice,
				itemMeasure,
				itemBasketQuantity,
				i,
				l,
				newSliderNode,
				itemSkuList,
				itemSectionId,
				html = "",
				currentSection = "";

			item = target.parentNode;

			itemId = item.getAttribute("data-id");
			itemSectionId = item.getAttribute("data-section-id");
			itemName = item.getAttribute("data-name");
			itemUrl = item.getAttribute("data-url");
			itemImg = item.getAttribute("data-img");
			itemSkuList = item.getAttribute("data-sku-list");
			itemPrintPrice = item.getAttribute("data-print-price");
			itemPrice = item.getAttribute("data-price");
			itemPrintOldPrice = item.getAttribute("data-print-old-price");
			itemOldPrice = item.getAttribute("data-old-price");
			itemDiffPrice = item.getAttribute("data-diff-price");
			itemMeasure = item.getAttribute("data-measure");
			itemBasketQuantity = item.getAttribute("data-quantity");
			currentSection = $("input.curSetPropductsTabSection").val();



			html =  "<div class=\"row no-gutters\">"
						+ "<div class=\"col-3 wr-img\"><img class=\"d-block mx-auto\" src=\""+itemImg+"\" alt=\"\"></div>"
						+ "<div class=\"col-9 align-self-center\">"
							+ "<a class=\"name\" href=\""+itemUrl+"\" target=\"_blank\"><span>"+itemName+"</span>"
							+ "<div class=\"measure-label\">"+itemBasketQuantity+"&nbsp;"+itemMeasure+"</div>"
							+"</a>"
							+ (itemSkuList?"<div class=\"sku\">"+itemSkuList+"</div>":"")
							+ "<div class=\"wr-price row no-gutters\">"
								+ "<div class=\"price\">"
									+ "<span class=\"price-value\">" + itemPrintPrice + "</span>" + (itemMeasure?"<span class=\"measure\">&nbsp;/&nbsp;"+itemMeasure+"</span>":"")
								+ "</div>"
								+ (itemPrice != itemOldPrice?"<div class=\"old-price align-self-end\">"+itemPrintOldPrice+"</div>":"")
							+ "</div>"
						+ "</div>"
					+ "</div>"
					+ "<div class=\"item-delete\" data-role=\"set-delete-btn\"></div>";


			newSetNode = BX.create("div", {
				attrs: {
					className: "col-12 wr-product-item"
				},
				children: [
					BX.create("div", {
						attrs: {
							className: "product-item list phx-border",
							"data-id": itemId,
							"data-section-id": itemSectionId? itemSectionId : "",
							"data-img": itemImg ? itemImg : "",
							"data-url": itemUrl,
							"data-name": itemName,
							"data-sku-list": itemSkuList?itemSkuList:"",
							"data-print-price": itemPrintPrice,
							"data-print-old-price": itemPrintOldPrice,
							"data-price": itemPrice,
							"data-old-price": itemOldPrice,
							"data-diff-price": itemDiffPrice,
							"data-measure": itemMeasure,
							"data-quantity": itemBasketQuantity
						},
						html: html
					})
				]
			});



			this.setItemsCont.appendChild(newSetNode);

			this.numSliderItems--;
			this.numSetItems++;

			this.recountItemsSection(itemSectionId, false);
			
			BX.remove(item.parentNode);
			$(".tooltip").remove();

			this.setIds.push(itemId);
			this.recountPrice();

			if (this.numSetItems > 0)
				this.emptySetMessage.classList.add("d-none");

			console.log(this.numSetItems);

			
		}
	};

	SetConstructor.prototype.deleteFromSet = function()
	{
		var target = BX.proxy_context;

		if (target && target.hasAttribute('data-role') && target.getAttribute('data-role') == 'set-delete-btn')
		{

			var item,
				itemId,
				itemName,
				itemUrl,
				itemImg,
				itemPrintPrice,
				itemPrice,
				itemPrintOldPrice,
				itemOldPrice,
				itemDiffPrice,
				itemMeasure,
				itemBasketQuantity,
				i,
				l,
				newSliderNode,
				itemSkuList,
				itemSectionId,
				html = "",
				currentSection = "";


			item = target.parentNode;

			itemId = item.getAttribute("data-id");
			itemSectionId = item.getAttribute("data-section-id");
			itemName = item.getAttribute("data-name");
			itemUrl = item.getAttribute("data-url");
			itemImg = item.getAttribute("data-img");
			itemSkuList = item.getAttribute("data-sku-list");
			itemPrintPrice = item.getAttribute("data-print-price");
			itemPrice = item.getAttribute("data-price");
			itemPrintOldPrice = item.getAttribute("data-print-old-price");
			itemOldPrice = item.getAttribute("data-old-price");
			itemDiffPrice = item.getAttribute("data-diff-price");
			itemMeasure = item.getAttribute("data-measure");
			itemBasketQuantity = item.getAttribute("data-quantity");
			currentSection = $("input.curSetPropductsTabSection").val();


			html =  "<div class=\"row no-gutters\">"

					+"<div class=\"wr-img row no-gutters align-items-center\">"
						+ "<div class=\"col\"><img class=\"d-block mx-auto\" src=\""+itemImg+"\" alt=\"\"></div>"
					+ "</div>"
					+ "<a class=\"name\" href=\""+itemUrl+"\" target=\"_blank\"><span>"+itemName+"</span><div class=\"measure-label\">"+itemBasketQuantity+"&nbsp;"+itemMeasure+"</div>"+"</a>"
					+ (itemSkuList?"<div class=\"sku\">"+itemSkuList+"</div>":"")
					+ "<div class=\"wr-price row no-gutters\">"
						+ "<div class=\"price\">"
							+ "<span class=\"price-value\">" + itemPrintPrice + "</span>" + (itemMeasure?"<span class=\"measure\">&nbsp;/&nbsp;"+itemMeasure+"</span>":"")
						+ "</div>"
						+ (itemPrice != itemOldPrice?"<div class=\"old-price align-self-end\">"+itemPrintOldPrice+"</div>":"")
					+ "</div>"
				+ "</div>"
				+ "<a class=\"add-set-product-constructor main-color\" data-role=\"set-add-btn\" data-toggle=\"tooltip\" data-placement=\"right\" title=\""+this.messages.ADD_BUTTON+"\">+</a>";


			
			newSliderNode = BX.create("div", {
				attrs: {
					className: "col-lg-4 col-6 wr-product-item tab-item "+ this.jsId + (currentSection == itemSectionId ? "":" d-none"),
					"data-value": itemSectionId
				},
				children: [
					BX.create("div", {
						attrs: {
							className: "product-item flat",
							"data-id": itemId,
							"data-section-id": itemSectionId? itemSectionId : "",
							"data-img": itemImg ? itemImg : "",
							"data-url": itemUrl,
							"data-name": itemName,
							"data-sku-list": itemSkuList?itemSkuList:"",
							"data-print-price": itemPrintPrice,
							"data-print-old-price": itemPrintOldPrice,
							"data-price": itemPrice,
							"data-old-price": itemOldPrice,
							"data-diff-price": itemDiffPrice,
							"data-measure": itemMeasure,
							"data-quantity": itemBasketQuantity
						},
						html: html
					})
				]
			});


			

			if (!!this.notAvailProduct)
				this.sliderItemsCont.insertBefore(newSliderNode, this.notAvailProduct);
			else
				this.sliderItemsCont.appendChild(newSliderNode);


			$('[data-toggle="tooltip"]').tooltip({html:true});

			

			this.numSliderItems++;
			this.numSetItems--;
			this.recountItemsSection(itemSectionId, true);
			/*this.generateSliderStyles();*/
			BX.remove(item.parentNode);

			for (i = 0, l = this.setIds.length; i < l; i++)
			{
				if (this.setIds[i] == itemId)
					this.setIds.splice(i, 1);
			}

			this.recountPrice();

			if (this.numSetItems <= 0)
				this.emptySetMessage.classList.remove("d-none");

			
		}
	};

	SetConstructor.prototype.recountItemsSection = function(ID, up)
	{
		var currentValue = $("."+this.jsId+".set-products-other-tab[data-value='"+ID+"']").attr("data-count");

		currentValue = parseInt(currentValue);

		if(up)
			currentValue += 1;
		else
			currentValue -= 1;

		if(currentValue < 0)
			currentValue = 0;

		$("."+this.jsId+".set-products-other-tab[data-value='"+ID+"']").attr("data-count", currentValue);
		$("."+this.jsId+".set-products-other-tab[data-value='"+ID+"'] > span").text(currentValue);

	};

	SetConstructor.prototype.recountPrice = function()
	{
		var sumPrice = this.mainElementPrice*this.mainElementBasketQuantity,
			sumOldPrice = this.mainElementOldPrice*this.mainElementBasketQuantity,
			sumDiffDiscountPrice = this.mainElementDiffPrice*this.mainElementBasketQuantity,
			setItems = this.setItemsCont.querySelectorAll(".product-item"),
			i,
			l,
			ratio,
			totalItems = 1;


		if (setItems)
		{
			for(i = 0, l = setItems.length; i<l; i++)
			{
				ratio = Number(setItems[i].getAttribute("data-quantity")) || 1;
				sumPrice += Number(setItems[i].getAttribute("data-price"))*ratio;
				sumOldPrice += Number(setItems[i].getAttribute("data-old-price"))*ratio;
				sumDiffDiscountPrice += Number(setItems[i].getAttribute("data-diff-price"))*ratio;
			}

			totalItems = setItems.length + 1;
		}

		this.infoCountItems.innerHTML = totalItems+"&nbsp;"+getTermination(totalItems, [this.messages.SET_CONSTRUCTOR_CNT_1,this.messages.SET_CONSTRUCTOR_CNT_2,this.messages.SET_CONSTRUCTOR_CNT_3,this.messages.SET_CONSTRUCTOR_CNT_4]);


		this.setPriceCont.innerHTML = BX.Currency.currencyFormat(sumPrice, this.currency, true);
		
		if (Math.floor(sumDiffDiscountPrice*100) > 0)
		{
			this.setOldPriceCont.innerHTML = BX.Currency.currencyFormat(sumOldPrice, this.currency, true);
			this.setDiffPriceCont.innerHTML = BX.Currency.currencyFormat(sumDiffDiscountPrice, this.currency, true);
			this.setOldPriceCont.classList.remove("d-none");
			this.setDiffPriceContRow.classList.remove("d-none");
		}
		else
		{
			this.setOldPriceCont.classList.add("d-none");
			this.setDiffPriceContRow.classList.add("d-none");

			this.setOldPriceCont.innerHTML = '';
			this.setDiffPriceCont.innerHTML = '';
		}


	};


	SetConstructor.prototype.addToBasket = function()
	{
		var target = BX.proxy_context;


		showProcessLoad();
		BX.ajax.post(
			this.ajaxPath,
			{
				sessid: BX.bitrix_sessid(),
				action: 'catalogSetAdd2Basket',
				set_ids: this.setIds,
				lid: this.lid,
				iblockId: this.iblockId,
				/*setOffersCartProps: this.offersCartProps,*/
				itemsRatio: this.itemsRatio
			},
			BX.proxy(function(result)
			{
				closeProcessLoad();
				setInfoBasket();
				/*document.location.href = this.basketUrl;*/
			}, this)
		);
	};

	return SetConstructor;
})();

$(document).on("click", ".set-products-other-tab", function()
{
    var ID = $(this).attr("data-value");
    obj = "."+$(this).parents(".bx-set-constructor").attr("data-obj");



    $(obj+".tab-item").addClass("d-none");
    $(obj+".tab-item[data-value='"+ID+"']").removeClass("d-none");

    $(obj+".set-products-other-tab").removeClass("active");
    $(obj+".set-products-other-tab[data-value='"+ID+"']").addClass("active");

    $("input.curSetPropductsTabSection").val(ID);
});

$(document).on("click", ".show-set-products-list", function()
{
	$(this).parents(".bx-set-constructor").find(".set_product_other").removeClass("d-none");
	$(".show-set-products-list.button-gray").addClass("d-none");
	
});

$(document).on("click", ".hide-set-products-list", function()
{
	$(this).parents(".bx-set-constructor").find(".set_product_other").addClass("d-none");;
	$(".show-set-products-list.button-gray").removeClass('d-none');
	
});

$(document).ready(function()
{
	if($("a.show-set-products-list").length>0 && $(".set_product_other").length>0)
		$("a.show-set-products-list").attr("href", "#"+$(".set_product_other").attr("id"));

});