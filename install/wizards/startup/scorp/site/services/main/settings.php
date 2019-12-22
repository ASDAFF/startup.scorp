<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
	
if(!defined("WIZARD_SITE_ID")) return;

COption::SetOptionString("main", "captcha_registration", "N");
COption::SetOptionString("iblock", "use_htmledit", "Y");
COption::SetOptionString("fileman", "propstypes", serialize(array("description"=>GetMessage("MAIN_OPT_DESCRIPTION"), "keywords"=>GetMessage("MAIN_OPT_KEYWORDS"), "title"=>GetMessage("MAIN_OPT_TITLE"), "keywords_inner"=>GetMessage("MAIN_OPT_KEYWORDS_INNER"))), false, WIZARD_SITE_ID);
COption::SetOptionInt("search", "suggest_save_days", 250);
COption::SetOptionString("search", "use_tf_cache", "Y");
COption::SetOptionString("search", "use_word_distance", "Y");
COption::SetOptionString("search", "use_social_rating", "Y");

// social auth services
if (COption::GetOptionString("socialservices", "auth_services") == ""){
	$bRu = (LANGUAGE_ID == 'ru');
	$arServices = array(
		"VKontakte" => "Y",  
		"MyMailRu" => "Y",
		"Twitter" => "Y",
		"Facebook" => "Y",
		"Livejournal" => "Y",
		"YandexOpenID" => ($bRu? "Y":"N"),
		"Rambler" => ($bRu? "Y":"N"),
		"MailRuOpenID" => ($bRu? "Y":"N"),
		"Liveinternet" => ($bRu? "Y":"N"),
		"Blogger" => "N",
		"OpenID" => "Y",
		"LiveID" => "N",
	);
	COption::SetOptionString("socialservices", "auth_services", serialize($arServices));
}


// set subscribe url
if(\Bitrix\Main\ModuleManager::isModuleInstalled('subscribe')){
	\Bitrix\Main\Config\Option::set('subscribe', 'subscribe_section', '#SITE_DIR#personal/subscribe/');

	//add rubric and post
	if(\Bitrix\Main\Loader::includeModule('subscribe'))
	{
		$rsRubric = CRubric::GetList(array("SORT"=>"ASC", "NAME"=>"ASC"), array("ACTIVE"=>"Y", "NAME"=>GetMessage("SUBSCRIBE_FIRST_RUBRIC"), "LID" => WIZARD_SITE_ID));
		if(!$rsRubric->Fetch())
		{
			$rubric = new CRubric;
			$arFields = Array(
			    "ACTIVE" => "Y",
			    "VISIBLE" => "Y",
			    "NAME" => GetMessage("SUBSCRIBE_FIRST_RUBRIC"),
			    "SORT" => 1,
			    "DESCRIPTION" => GetMessage("SUBSCRIBE_DESC_RUBRIC"),
			    "LID" => WIZARD_SITE_ID
			);
			$rubric_id = $rubric->Add($arFields);
			if($rubric_id)
			{
				$arSite = CSite::GetList($by="sort", $order="desc", array("LID" => WIZARD_SITE_ID))->Fetch();
				$email_blank = ($arSite["EMAIL"] ? $arSite["EMAIL"] : "test@test.ru");
				$posting = new CPosting;
				$arFields = Array(
				    "FROM_FIELD" => \Bitrix\Main\Config\Option::get("main", "email_from", $email_blank),
				    "SUBJECT" => GetMessage("SUBSCRIBE_FIRST_POST"),
				    "BODY_TYPE" => "html",
				    "BODY" => GetMessage("SUBSCRIBE_DESC_POST"),
				    "DIRECT_SEND" => "N",
				    "GROUP_ID" => array(2,3,4),
				    "RUB_ID" => $rubric_id 
				);
				$ID = $posting->Add($arFields);
			}
		}
	}
}

// enable composite
if(class_exists("CHTMLPagesCache")){
	if(method_exists("CHTMLPagesCache", "GetOptions")){
		if($arHTMLCacheOptions = CHTMLPagesCache::GetOptions()){
			if($arHTMLCacheOptions["COMPOSITE"] !== "Y"){
				$arDomains = array();
				
				$arSites = array();
				$dbRes = CSite::GetList($by="sort", $order="desc", array("ACTIVE" => "Y"));
				while($item = $dbRes->Fetch()){
					$arSites[$item["LID"]] = $item;
				}
				
				if($arSites){
					foreach($arSites as $arSite){
						if(strlen($serverName = trim($arSite["SERVER_NAME"], " \t\n\r"))){
							$arDomains[$serverName] = $serverName;
						}
						if(strlen($arSite["DOMAINS"])){
							foreach(explode("\n", $arSite["DOMAINS"]) as $domain){
								if(strlen($domain = trim($domain, " \t\n\r"))){
									$arDomains[$domain] = $domain;
								}
							}
						}
					}
				}
				
				if(!$arDomains){
					$arDomains[$_SERVER["SERVER_NAME"]] = $_SERVER["SERVER_NAME"];
				}
				
				if(!$arHTMLCacheOptions["GROUPS"]){
					$arHTMLCacheOptions["GROUPS"] = array();
				}
				$rsGroups = CGroup::GetList(($by="id"), ($order="asc"), array());
				while($arGroup = $rsGroups->Fetch()){
					if($arGroup["ID"] > 2){
						if(in_array($arGroup["STRING_ID"], array("RATING_VOTE_AUTHORITY", "RATING_VOTE")) && !in_array($arGroup["ID"], $arHTMLCacheOptions["GROUPS"])){
							$arHTMLCacheOptions["GROUPS"][] = $arGroup["ID"];
						}
					}
				}
				
				$arHTMLCacheOptions["COMPOSITE"] = "Y";
				$arHTMLCacheOptions["DOMAINS"] = array_merge((array)$arHTMLCacheOptions["DOMAINS"], (array)$arDomains);
				CHTMLPagesCache::SetEnabled(true);
				CHTMLPagesCache::SetOptions($arHTMLCacheOptions);
				bx_accelerator_reset();
			}
		}
	}
}
?>