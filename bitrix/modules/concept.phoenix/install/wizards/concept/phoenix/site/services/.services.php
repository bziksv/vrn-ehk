<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

set_time_limit(0);


$arServices = Array(
      "main" => Array(
            "NAME" => GetMessage("SERVICE_MAIN_SETTINGS"),
            "STAGES" => Array(
                  "files.php", // Copy bitrix files
                  "template.php", // Install template
                  "macro.php"

            ),
      ),
      'forms' => array(
            'NAME' => GetMessage('SERVICE_STEP_FORMS'),
            'STAGES' => array(
                  'forms.php',
            ),
            'MODULE_ID' => 'main',
      ),
      "sale" => Array(
            "NAME" => GetMessage("SERVICE_SALE_DEMO_DATA"),
            "STAGES" => Array(
                  "price.php",
                  "settings.php",
                  "mail.php"
            ),
      ), 
      "iblock" => Array(
            "NAME" => GetMessage("SERVICE_IBLOCK_DEMO_DATA"),
            "STAGES" => Array( 

                  "references_color.php",

                  "types.php", 

                  "requests_0.php", 

                  "regions_0.php",
                  "regions_1.php",
                  "regions_close.php",

                  "agreements_0.php",
                  "agreements_1.php",
                  "agreements_close.php",

                  "advantages_0.php",
                  "advantages_1.php",
                  "advantages_close.php",

                  "faq_0.php",
                  "faq_1.php",
                  "faq_close.php",

                  "modals_0.php",
                  "modals_1.php",
                  "modals_close.php",

                  "forms_0.php",
                  "forms_1.php",
                  "forms_close.php",
                  
                  "employee_0.php",
                  "employee_1.php",
                  "employee_close.php",

                  "banners_0.php",
                  "banners_1.php",
                  "banners_close.php",

                  "brands_0.php",
                  "brands_1.php",
                  "brands_close.php",

                  "news_0.php",
                  "news_1.php",
                  "news_close.php",

                  "action_0.php",
                  "action_1.php",
                  "action_close.php",

                  "blog_0.php",
                  "blog_1.php",
                  "blog_close.php",

                  "catalog_0.php",
                  "catalog_1.php",
                  "catalog_close.php",

                  "catalog_sku_0.php",
                  "catalog_sku_1.php",
                  "catalog_sku_close.php",

                  "land_0.php",
                  "land_1.php",
                  "land_2.php",
                  "land_3.php",
                  "land_4.php",
                  "land_5.php",
                  "land_6.php",
                  "land_7.php",
                  "land_8.php",
                  "land_close.php",

                  "menu_0.php",
                  "menu_1.php",
                  "menu_close.php",

                  "footer_menu_0.php",
                  "footer_menu_1.php",
                  "footer_menu_close.php",

                  "seo.php",
                  "reviews.php",
                  "reviews_info.php",
                  "comments.php",
                  
                  "settings.php",
                  "relates.php", 

                  "main_settings.php"               
        
            ),
      ),  
       
    
      
);
?>