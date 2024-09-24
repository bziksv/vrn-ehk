<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/install/wizard_sol/wizard.php");


class SelectSiteStep extends CSelectSiteWizardStep
{
	function InitStep()
	{
		parent::InitStep();

		$wizard =& $this->GetWizard();
		$wizard->solutionName = "phoenix";

		$this->SetStepID("site_init");
		$this->SetNextStep("site_settings");
	}
}


class SiteSettingsStep extends CSiteSettingsWizardStep
{

	function InitStep()
	{

		if(CModule::IncludeModule("concept.phoenix"))
		{
			$wizard =& $this->GetWizard();
			$siteID = $wizard->GetVar("siteID");
			parent::InitStep();


			$this->SetStepID("site_settings");
			$this->SetTitle(GetMessage("WIZ_STEP_SS"));

			$this->SetPrevStep("site_init");
			$this->SetNextStep("person_type");

			$this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));
			$this->SetNextCaption(GetMessage("NEXT_BUTTON"));

			$wizard->SetDefaultVars(
				Array(
					"installPriceBASE" => COption::GetOptionString("phoenix", "installPriceBASE", "Y", $siteID)
				)
			);
		}

		
               
    }


	function ShowStep()
	{

        $wizard =& $this->GetWizard();

        $firstStep = COption::GetOptionString("main", "wizard_first" . substr($wizard->GetID(), 7)  . "_" . $wizard->GetVar("siteID"), false, $wizard->GetVar("siteID"));
				   
        $this->content .= '
			   <div class="wizard-input-form-block">
					<label><b>'.GetMessage("WIZ_SHOP_DEMO").'</b></label><br><br>
					<div class="wizard-input-form-block-content">
					'. GetMessage("WIZ_SHOP_DEMO_TEXT") .'<br><br>
						'. $this->ShowCheckboxField("installDemoData", "Y",
						(array("id" => "install-demo-data", "checked" => "checked")))
						. ' <label for="install-demo-data">'.GetMessage("WIZ_STRUCTURE_DATA").'</label><br />

					</div>
				</div><br><br>';      


		if (CModule::IncludeModule("catalog")){
				$db_res = CCatalogGroup::GetGroupsList(array("CATALOG_GROUP_ID"=>'1', "BUY"=>"Y", "GROUP_ID"=>2));
				if (!$db_res->Fetch()){
					$this->content .= '
					<div class="wizard-input-form-block">
						<label><b>'.GetMessage("WIZ_SHOP_PRICE_BASE_TITLE").'</b></label><br><br>
						<div class="wizard-input-form-block-content">
							'. GetMessage("WIZ_SHOP_PRICE_BASE_TEXT1") .'<br><br>
							'. $this->ShowCheckboxField("installPriceBASE", "Y",
							(array("id" => "install-price", "checked" => "checked")))
							. ' <label for="install-price">'.GetMessage("WIZ_SHOP_PRICE_BASE_TEXT2").'</label><br />

						</div>
					</div>';
				}
			}

		$formName = $wizard->GetFormName();
		$installCaption = $this->GetNextCaption();
		$nextCaption = GetMessage("NEXT_BUTTON");

	}

	function OnPostForm()
	{
		$wizard =& $this->GetWizard();
                
                
	}
}


class PersonType extends CWizardStep
{

	function InitStep()
	{
		$wizard =& $this->GetWizard();

		$this->SetStepID("person_type");
		$this->SetTitle(GetMessage("WIZ_STEP_PT"));

		$this->SetPrevStep("site_settings");
		$this->SetNextStep("pay_system");
		
		$this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));
		$this->SetNextCaption(GetMessage("NEXT_BUTTON"));

		$wizard->SetDefaultVars(
				
				Array(
					"personType" => Array(
						"fiz" => "Y",
						"ur" => "Y",
					)
				)
			);
	}

	function ShowStep()
	{
		$wizard =& $this->GetWizard();


		$this->content .= '<div class="wizard-input-form">';
		$this->content .= '
		<div class="wizard-input-form-block">
			<div style="padding-top:15px">
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">
					<div class="wizard-catalog-form-item">
						'.$this->ShowCheckboxField('personType[fiz]', 'Y', (array("id" => "personTypeF"))).' 
						<label for="personTypeF">'.GetMessage("WIZ_PERSON_TYPE_FIZ").'</label>
					</div>
					<div class="wizard-catalog-form-item">
						'.$this->ShowCheckboxField('personType[ur]', 'Y', (array("id" => "personTypeU"))).' 
						<label for="personTypeU">'.GetMessage("WIZ_PERSON_TYPE_UR").'</label>
					</div>';
					$this->content .= 
				'</div>
				<div class="wizard-catalog-form-item" style="font-size: 14px;">'.GetMessage("WIZ_PERSON_TYPE").'</div>
			</div>
		</div>';
		$this->content .= '</div>';



		$this->content .= '<div class="inst-note-block inst-note-block-red">
						<div class="inst-note-block-icon"></div>
						
						<div class="inst-note-block-text">'.GetMessage("WIZ_PERSON_TEXT").'</div>
					</div>';
	}
	
	function OnPostForm()
	{
		$wizard = &$this->GetWizard();
		$personType = $wizard->GetVar("personType");

		/*if(empty($personType["fiz"]) && empty($personType["ur"]))
			$this->SetError(GetMessage('WIZ_NO_PT'));*/
	}
}

class PaySystem extends CWizardStep
{
	
	function InitStep()
	{
		$wizard =& $this->GetWizard();

		$this->SetStepID("pay_system");
		$this->SetTitle(GetMessage("WIZ_STEP_PS"));
		
		$this->SetPrevStep("person_type");
		$this->SetNextStep("data_install");

		$this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));
		$this->SetNextCaption(GetMessage("NEXT_BUTTON"));
		
		$wizard->SetDefaultVars(
			Array(
				"paysystem" => Array(
					"cash" => "Y",
					"sber" => "Y",
					"bill" => "Y",
				),
				"delivery" => Array(
					"courier" => "Y",
					"self" => "Y",
					"russianpost" => "N",
				)
			)
		);
		

	}
	
	

	function ShowStep()
	{

		$wizard =& $this->GetWizard();

		$personType = $wizard->GetVar("personType");
		
		$this->content .= '<div class="wizard-input-form">';
		$this->content .= '
		<div class="wizard-input-form-block">
			<div class="wizard-catalog-title">'.GetMessage("WIZ_PAY_SYSTEM_TITLE").'</div>
			<div class="wizard-input-form-field wizard-input-form-field-checkbox">
				<div class="wizard-catalog-form-item">
					'.$this->ShowCheckboxField('paysystem[cash]', 'Y', (array("id" => "paysystemC"))).' <label for="paysystemC">'.GetMessage("WIZ_PAY_SYSTEM_C").'</label></div>';
				
					/*if ($personType["fiz"] == "Y")
						$this->content .= '<div class="wizard-catalog-form-item">'.$this->ShowCheckboxField('paysystem[sber]', 'Y', (array("id" => "paysystemS"))).' <label for="paysystemS">'.GetMessage("WIZ_PAY_SYSTEM_S").'</label></div>';
					if($personType["ur"] == "Y")
						$this->content .= '<div class="wizard-catalog-form-item">'.$this->ShowCheckboxField('paysystem[bill]', 'Y', (array("id" => "paysystemB"))).' <label for="paysystemB">'.GetMessage("WIZ_PAY_SYSTEM_B").'</label></div>';*/
					
					$this->content .= '
					<div class="wizard-catalog-form-item" style="font-size: 14px;">'.GetMessage("WIZ_PAY_SYSTEM").'</div>
			</div>
		</div>';
		$this->content .= '
		<div class="wizard-input-form-block">
			<div class="wizard-catalog-title">'.GetMessage("WIZ_DELIVERY_TITLE").'</div>
			<div class="wizard-input-form-field wizard-input-form-field-checkbox">
				<div class="wizard-catalog-form-item">'.$this->ShowCheckboxField('delivery[courier]', 'Y', (array("id" => "deliveryC"))).' <label for="deliveryC">'.GetMessage("WIZ_DELIVERY_C").'</label></div>
				<div class="wizard-catalog-form-item">'.$this->ShowCheckboxField('delivery[self]', 'Y', (array("id" => "deliveryS"))).' <label for="deliveryS">'.GetMessage("WIZ_DELIVERY_S").'</label></div>';
					
					$this->content .= '
				<div class="wizard-catalog-form-item" style="font-size: 14px;">'.GetMessage("WIZ_DELIVERY").'</div>	
			</div>
		</div>';

		
	}

	function OnPostForm(){
		$wizard = &$this->GetWizard();
		$paysystem = $wizard->GetVar("paysystem");

		/*if (empty($paysystem["cash"]) && empty($paysystem["sber"]) && empty($paysystem["bill"]) && empty($paysystem["paypal"]))
			$this->SetError(GetMessage('WIZ_NO_PS'));*/
	}
}


class DataInstallStep extends CDataInstallWizardStep
{
	function CorrectServices(&$arServices)
	{
		$wizard =& $this->GetWizard();
		
		if($wizard->GetVar("installDemoData") != "Y")
		{
			
		}
	}
}

class FinishStep extends CFinishWizardStep
{
}
?>