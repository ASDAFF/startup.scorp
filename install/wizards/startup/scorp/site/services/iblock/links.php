<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if(!CModule::IncludeModule("iblock")) return;
if(!CModule::IncludeModule("startup.scorp")) return;
	
if(!defined("WIZARD_SITE_ID")) return;
if(!defined("WIZARD_SITE_DIR")) return;
if(!defined("WIZARD_SITE_PATH")) return;
if(!defined("WIZARD_TEMPLATE_ID")) return;
if(!defined("WIZARD_TEMPLATE_ABSOLUTE_PATH")) return;
if(!defined("WIZARD_THEME_ID")) return;

$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID."/";
//$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"]."/local/templates/".WIZARD_TEMPLATE_ID."/";

// iblocks ids
$servicesIBlockID = CCache::$arIBlocks[WIZARD_SITE_ID]["startup_scorp_content"]["startup_scorp_services"][0];
$studyIBlockID = CCache::$arIBlocks[WIZARD_SITE_ID]["startup_scorp_catalog"]["startup_scorp_study"][0];
$staffIBlockID = CCache::$arIBlocks[WIZARD_SITE_ID]["startup_scorp_content"]["startup_scorp_staff"][0];
$reviewsIBlockID = CCache::$arIBlocks[WIZARD_SITE_ID]["startup_scorp_content"]["startup_scorp_reviews"][0];
$projectsIBlockID = CCache::$arIBlocks[WIZARD_SITE_ID]["startup_scorp_content"]["startup_scorp_projects"][0];
$catalogIBlockID = CCache::$arIBlocks[WIZARD_SITE_ID]["startup_scorp_catalog"]["startup_scorp_catalog"][0];
$stockIBlockID = CCache::$arIBlocks[WIZARD_SITE_ID]["startup_scorp_content"]["startup_scorp_stock"][0];
$newsIBlockID = CCache::$arIBlocks[WIZARD_SITE_ID]["startup_scorp_content"]["startup_scorp_news"][0];
$articlesIBlockID = CCache::$arIBlocks[WIZARD_SITE_ID]["startup_scorp_content"]["startup_scorp_articles"][0];

// XML_ID => ID (here XML_ID - old ID, ID - new ID)
if(!CModule::IncludeModule("startup.scorp")) return;
$arServices = CCache::CIBlockElement_GetList(array("CACHE" => array("TIME" => 0, "TAG" => CCache::GetIBlockCacheTag($servicesIBlockID), "GROUP" => array("XML_ID"), "RESULT" => array("ID"))), array("IBLOCK_ID" => $servicesIBlockID), false, false, array("ID", "XML_ID"));
$arStudy = CCache::CIBlockElement_GetList(array("CACHE" => array("TIME" => 0, "TAG" => CCache::GetIBlockCacheTag($studyIBlockID), "GROUP" => array("XML_ID"), "RESULT" => array("ID"))), array("IBLOCK_ID" => $studyIBlockID), false, false, array("ID", "XML_ID"));
$arStaff = CCache::CIBlockElement_GetList(array("CACHE" => array("TIME" => 0, "TAG" => CCache::GetIBlockCacheTag($staffIBlockID), "GROUP" => array("XML_ID"), "RESULT" => array("ID"))), array("IBLOCK_ID" => $staffIBlockID), false, false, array("ID", "XML_ID"));
$arReviews = CCache::CIBlockElement_GetList(array("CACHE" => array("TIME" => 0, "TAG" => CCache::GetIBlockCacheTag($reviewsIBlockID), "GROUP" => array("XML_ID"), "RESULT" => array("ID"))), array("IBLOCK_ID" => $reviewsIBlockID), false, false, array("ID", "XML_ID"));
$arProjects = CCache::CIBlockElement_GetList(array("CACHE" => array("TIME" => 0, "TAG" => CCache::GetIBlockCacheTag($projectsIBlockID), "GROUP" => array("XML_ID"), "RESULT" => array("ID"))), array("IBLOCK_ID" => $projectsIBlockID), false, false, array("ID", "XML_ID"));
$arCatalog = CCache::CIBlockElement_GetList(array("CACHE" => array("TIME" => 0, "TAG" => CCache::GetIBlockCacheTag($catalogIBlockID), "GROUP" => array("XML_ID"), "RESULT" => array("ID"))), array("IBLOCK_ID" => $catalogIBlockID), false, false, array("ID", "XML_ID"));
$arStock = CCache::CIBlockElement_GetList(array("CACHE" => array("TIME" => 0, "TAG" => CCache::GetIBlockCacheTag($stockIBlockID), "GROUP" => array("XML_ID"), "RESULT" => array("ID"))), array("IBLOCK_ID" => $stockIBlockID), false, false, array("ID", "XML_ID"));
$arNews = CCache::CIBlockElement_GetList(array("CACHE" => array("TIME" => 0, "TAG" => CCache::GetIBlockCacheTag($newsIBlockID), "GROUP" => array("XML_ID"), "RESULT" => array("ID"))), array("IBLOCK_ID" => $newsIBlockID), false, false, array("ID", "XML_ID"));
$arArticles = CCache::CIBlockElement_GetList(array("CACHE" => array("TIME" => 0, "TAG" => CCache::GetIBlockCacheTag($articlesIBlockID), "GROUP" => array("XML_ID"), "RESULT" => array("ID"))), array("IBLOCK_ID" => $articlesIBlockID), false, false, array("ID", "XML_ID"));

// update links in articles
CIBlockElement::SetPropertyValuesEx($arArticles["142"], $articlesIBlockID, array("LINK_GOODS" => array($arCatalog["255"], $arCatalog["203"], $arCatalog["202"])));

// update links in stock
CIBlockElement::SetPropertyValuesEx($arStock["185"], $stockIBlockID, array("LINK_GOODS" => array($arCatalog["231"], $arCatalog["232"], $arCatalog["233"])));

// update links in services
CIBlockElement::SetPropertyValuesEx($arServices["11"], $servicesIBlockID, array("LINK_STAFF" => array($arStaff['228'], $arStaff['229'], $arStaff['230']), "LINK_PROJECTS" => array($arProjects['211'], $arProjects['213'], $arProjects['214']), "LINK_GOODS" => array($arCatalog["180"], $arCatalog["231"], $arCatalog["232"], $arCatalog["233"])));
CIBlockElement::SetPropertyValuesEx($arServices["13"], $servicesIBlockID, array("LINK_PROJECTS" => array($arProjects['151'], $arProjects['152'], $arProjects['153'])));
CIBlockElement::SetPropertyValuesEx($arServices["206"], $servicesIBlockID, array("LINK_PROJECTS" => array($arProjects['152'], $arProjects['153'], $arProjects['211'])));
CIBlockElement::SetPropertyValuesEx($arServices["207"], $servicesIBlockID, array("LINK_GOODS" => array($arCatalog['202'], $arCatalog['255'], $arCatalog['203'])));
CIBlockElement::SetPropertyValuesEx($arServices["208"], $servicesIBlockID, array("LINK_GOODS" => array($arCatalog['203'], $arCatalog['202'], $arCatalog['255'])));

// update links in study
CIBlockElement::SetPropertyValuesEx($arStudy["149"], $studyIBlockID, array("LINK_STAFF" => array($arStaff['229'], $arStaff['228'], $arStaff['230'])));
CIBlockElement::SetPropertyValuesEx($arStudy["150"], $studyIBlockID, array("LINK_STAFF" => array($arStaff['229'], $arStaff['228'], $arStaff['230'])));
CIBlockElement::SetPropertyValuesEx($arStudy["180"], $studyIBlockID, array("LINK_STAFF" => array($arStaff['229'], $arStaff['228'], $arStaff['230'])));
CIBlockElement::SetPropertyValuesEx($arStudy["202"], $studyIBlockID, array("LINK_STAFF" => array($arStaff['229'], $arStaff['228'], $arStaff['230'])));
CIBlockElement::SetPropertyValuesEx($arStudy["203"], $studyIBlockID, array("LINK_STAFF" => array($arStaff['229'], $arStaff['228'], $arStaff['230'])));
CIBlockElement::SetPropertyValuesEx($arStudy["225"], $studyIBlockID, array("LINK_STAFF" => array($arStaff['229'], $arStaff['228'], $arStaff['230'])));
CIBlockElement::SetPropertyValuesEx($arStudy["197"], $studyIBlockID, array("LINK_STAFF" => array($arStaff['229'], $arStaff['228'], $arStaff['230'])));
CIBlockElement::SetPropertyValuesEx($arStudy["198"], $studyIBlockID, array("LINK_STAFF" => array($arStaff['229'], $arStaff['228'], $arStaff['230'])));
CIBlockElement::SetPropertyValuesEx($arStudy["199"], $studyIBlockID, array("LINK_STAFF" => array($arStaff['229'], $arStaff['228'], $arStaff['230'])));
CIBlockElement::SetPropertyValuesEx($arStudy["193"], $studyIBlockID, array("LINK_STAFF" => array($arStaff['229'], $arStaff['228'], $arStaff['230'])));
CIBlockElement::SetPropertyValuesEx($arStudy["226"], $studyIBlockID, array("LINK_STAFF" => array($arStaff['229'], $arStaff['228'], $arStaff['230'])));
CIBlockElement::SetPropertyValuesEx($arStudy["227"], $studyIBlockID, array("LINK_STAFF" => array($arStaff['229'], $arStaff['228'], $arStaff['230'])));

// update links in projects
CIBlockElement::SetPropertyValuesEx($arProjects["152"], $projectsIBlockID, array("LINK_PROJECTS" => array($arProjects["151"], $arProjects["215"], $arProjects["153"])));

// update links in catalog
CIBlockElement::SetPropertyValuesEx($arCatalog["149"], $catalogIBlockID, array("LINK_PROJECTS" => array($arProjects["211"], $arProjects["213"], $arProjects["214"])));

// iblock user fields
$dbSite = CSite::GetByID(WIZARD_SITE_ID);
if($arSite = $dbSite -> Fetch()) $lang = $arSite["LANGUAGE_ID"];
if(!strlen($lang)) $lang = "ru";
WizardServices::IncludeServiceLang("links", $lang);

// clear and update list of UF_VIEWTYPE in some catalog sections
$arUserFieldViewType = CUserTypeEntity::GetList(array(), array("ENTITY_ID" => "IBLOCK_".$catalogIBlockID."_SECTION", "FIELD_NAME" => "UF_VIEWTYPE"))->Fetch();
$resUserFieldViewTypeEnum = CUserFieldEnum::GetList(array(), array("USER_FIELD_ID" => $arUserFieldViewType["ID"]));
while($arUserFieldViewTypeEnum = $resUserFieldViewTypeEnum->GetNext()){
	$obEnum = new CUserFieldEnum;
	$obEnum->SetEnumValues($arUserFieldViewType["ID"], array($arUserFieldViewTypeEnum["ID"] => array("DEL" => "Y")));
}
$obEnum = new CUserFieldEnum;
$obEnum->SetEnumValues($arUserFieldViewType["ID"], array(
	"n0" => array(
		"VALUE" => GetMessage("WZD_UFIELDENUM_TABLE"),
		"XML_ID" => "table",
	),
	"n1" => array(
		"VALUE" => GetMessage("WZD_UFIELDENUM_LIST"),
		"XML_ID" => "list",
	),
	"n2" => array(
		"VALUE" => GetMessage("WZD_UFIELDENUM_PRICE"),
		"XML_ID" => "price",
	),
));
$resUserFieldViewTypeEnum = CUserFieldEnum::GetList(array(), array("USER_FIELD_ID" => $arUserFieldViewType["ID"]));
while($arUserFieldViewTypeEnum = $resUserFieldViewTypeEnum->GetNext()){
	$arUserFieldViewTypeEnums[$arUserFieldViewTypeEnum["XML_ID"]] = $arUserFieldViewTypeEnum["ID"];
}

$arCatalog = CCache::CIBlockSection_GetList(array("CACHE" => array("TIME" => 0, "TAG" => CCache::GetIBlockCacheTag($catalogIBlockID), "GROUP" => array("XML_ID"), "RESULT" => array("ID"))), array("IBLOCK_ID" => $catalogIBlockID), false, array("ID", "XML_ID"));
$bs = new CIBlockSection;
$res = $bs->Update($arCatalog["19"], array("UF_VIEWTYPE" => $arUserFieldViewTypeEnums["list"]));
$res = $bs->Update($arCatalog["31"], array("UF_VIEWTYPE" => $arUserFieldViewTypeEnums["list"]));
?>