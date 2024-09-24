BX.namespace("BX.Iblock.Catalog");

BX.Iblock.Catalog.CompareClass = (function()
{
	var CompareClass = function(wrapObjId)
	{
		this.wrapObjId = wrapObjId;
	};

	CompareClass.prototype.MakeAjaxAction = function(url, refresh)
	{
		BX.showWait(BX(this.wrapObjId));

		showProcessLoad();

        
		BX.ajax.post(
			url,
			{
				ajax_action: 'Y'
			},
			BX.proxy(function(result)
			{
				BX(this.wrapObjId).innerHTML = result;
				BX.closeWait();

				closeProcessLoad();
                
                setTimeout(
                    function()
                    {
                        createTableCompare($('.data_table_props:not(.clone)'), $('.prop_title_table'), $('.data_table_props.clone'));
                        initSly();

                        arStatusBasketPhoenix = {};
						setInfoBasket();

                    },
                    200
                );
                
			}, this)
		);
        
        BX.closeWait();
        
        
        setTimeout(
            function()
            {
                createTableCompare($('.data_table_props:not(.clone)'), $('.prop_title_table'), $('.data_table_props.clone'));
                initSly();
            },
            200
        );
        
	};

	return CompareClass;
})();