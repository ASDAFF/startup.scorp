<?
/**
 * Copyright (c) 22/12/2019 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

if(!defined('SCORP_MODULE_ID')){
	define('SCORP_MODULE_ID', 'startup.scorp');
}

IncludeModuleLangFile(__FILE__);
use \Bitrix\Main\Type\Collection;

// initialize module parametrs list and default values
include_once __DIR__.'/../../parametrs.php';

class CScorp{
	const MODULE_ID = SCORP_MODULE_ID;
	const PARTNER_NAME = 'startup';
	const SOLUTION_NAME	= 'scorp';
	const devMode = false; // set to false before release

	static $arParametrsList = array();
	private static $arMetaParams = array();

	public function checkModuleRight($reqRight = 'R', $bShowError = false){
		if($GLOBALS['APPLICATION']->GetGroupRight(self::MODULE_ID) < $reqRight){
			if($bShowError){
				$GLOBALS['APPLICATION']->AuthForm(GetMessage('SCORP_ACCESS_DENIED'));
			}

			return false;
		}

		return true;
	}

	function ClearSomeComponentsCache($SITE_ID){
		CBitrixComponent::clearComponentCache('bitrix:news.list', $SITE_ID);
		CBitrixComponent::clearComponentCache('bitrix:news.detail', $SITE_ID);
	}

	function GetBackParametrsValues($SITE_ID, $bStatic = true){
		static $arValues;

		if(($bStatic && $arValues === NULL) || !$bStatic){
			$arDefaultValues = $arValues = array();
			if(self::$arParametrsList && is_array(self::$arParametrsList)){
				foreach(self::$arParametrsList as $blockCode => $arBlock){
					if($arBlock['OPTIONS'] && is_array($arBlock['OPTIONS'])){
						foreach($arBlock['OPTIONS'] as $optionCode => $arOption){
							$arDefaultValues[$optionCode] = $arOption['DEFAULT'];
							$bMultiple = $arOption['TYPE'] === 'multiselectbox';
							$dbValue = COption::GetOptionString(self::MODULE_ID, $optionCode, ($bMultiple ? serialize($arOption['DEFAULT']) : $arOption['DEFAULT']), $SITE_ID);
							$arValues[$optionCode] = $bMultiple ? unserialize($dbValue)	: $dbValue;
						}
					}
				}
			}

			if(!defined('ADMIN_SECTION')){
				// replace #SITE_DIR#
				if($arValues && is_array($arValues)){
					foreach($arValues as $optionCode => $arOption){
						if(!is_array($arOption)){
							$arValues[$optionCode] = str_replace('#SITE_DIR#', SITE_DIR, $arOption);
						}
					}
				}

				// define RECAPTCHA CONST
				if($arValues['USE_CAPTCHA_FORM'] === 'RECAPTCHA'){
					if(!defined('RECAPTCHA_SITE_KEY')){
						define('RECAPTCHA_SITE_KEY', $arValues['RECAPTCHA_SITE_KEY']);
					}
					if(!defined('RECAPTCHA_SECRET_KEY')){
						define('RECAPTCHA_SECRET_KEY', $arValues['RECAPTCHA_SECRET_KEY']);
					}
				}

				if(!defined('ADD_SITE_NAME_IN_TITLE')){
					define('ADD_SITE_NAME_IN_TITLE', ($arValues['ADD_SITE_NAME_IN_TITLE'] !== 'N' ? 'Y' : 'N'));
				}
			}
		}

		return $arValues;
	}

	function GetFrontParametrsValues($SITE_ID){
		if(!strlen($SITE_ID)){
			$SITE_ID = SITE_ID;
		}
		$arBackParametrs = self::GetBackParametrsValues($SITE_ID);
		if($arBackParametrs['THEME_SWITCHER'] === 'Y'){
			$arValues = array_merge((array)$arBackParametrs, (array)$_SESSION['THEME'][$SITE_ID]);
		}
		else{
			$arValues = (array)$arBackParametrs;
		}
		return $arValues;
	}

	function CheckColor($strColor){
		$strColor = substr(str_replace('#', '', $strColor), 0, 6);
		$strColor = base_convert(base_convert($strColor, 16, 2), 2, 16);
		for($i = 0, $l = 6 - (function_exists('mb_strlen') ? mb_strlen($strColor) : strlen($strColor)); $i < $l; ++$i)
			$strColor = '0'.$strColor;
		return $strColor;
	}

	function UpdateFrontParametrsValues(){
		$arBackParametrs = self::GetBackParametrsValues(SITE_ID);
		if($arBackParametrs['THEME_SWITCHER'] === 'Y'){
			if($_REQUEST){
				if($_REQUEST['THEME'] === 'default'){
					if(self::$arParametrsList && is_array(self::$arParametrsList)){
						foreach(self::$arParametrsList as $blockCode => $arBlock){
							unset($_SESSION['THEME'][SITE_ID]);
							$_SESSION['THEME'][SITE_ID] = null;
						}
					}
					COption::SetOptionString(self::MODULE_ID, "NeedGenerateCustomTheme", 'Y', '', SITE_ID);
				}
				else{
					if(self::$arParametrsList && is_array(self::$arParametrsList)){
						foreach(self::$arParametrsList as $blockCode => $arBlock){
							if($arBlock['OPTIONS'] && is_array($arBlock['OPTIONS'])){
								foreach($arBlock['OPTIONS'] as $optionCode => $arOption){
									if($arOption['THEME'] === 'Y'){
										if(isset($_REQUEST[$optionCode])){
											if($optionCode == 'BASE_COLOR_CUSTOM'){
												$_REQUEST[$optionCode] = self::CheckColor($_REQUEST[$optionCode]);
											}
											if($optionCode == 'BASE_COLOR' && $_REQUEST[$optionCode] === 'CUSTOM'){
												COption::SetOptionString(self::MODULE_ID, "NeedGenerateCustomTheme", 'Y', '', SITE_ID);
											}
											if(isset($arOption['LIST'])){
												if(isset($arOption['LIST'][$_REQUEST[$optionCode]])){
													$_SESSION['THEME'][SITE_ID][$optionCode] = $_REQUEST[$optionCode];
												}
												else{
													$_SESSION['THEME'][SITE_ID][$optionCode] = $arOption['DEFAULT'];
												}
											}
											else{
												$_SESSION['THEME'][SITE_ID][$optionCode] = $_REQUEST[$optionCode];
											}
											if($optionCode == 'ORDER_VIEW'){
												self::ClearSomeComponentsCache(SITE_ID);
											}

											$bChanged = true;
										}

									}
								}
							}

						}

					}
				}
				if(isset($_REQUEST["BASE_COLOR"]) && $_REQUEST["BASE_COLOR"]){
					LocalRedirect($_SERVER["HTTP_REFERER"]);
				}
			}
		}
		else{
			unset($_SESSION['THEME'][SITE_ID]);
		}
	}

	function GenerateThemes(){
		$arBackParametrs = self::GetBackParametrsValues(SITE_ID);
		$arBaseColors = self::$arParametrsList['MAIN']['OPTIONS']['BASE_COLOR']['LIST'];
		$isCustomTheme = $_SESSION['THEME'][SITE_ID]['BASE_COLOR'] === 'CUSTOM';

		$bNeedGenerateAllThemes = COption::GetOptionString(self::MODULE_ID, 'NeedGenerateThemes', 'N', SITE_ID) === 'Y';
		$bNeedGenerateCustomTheme = COption::GetOptionString(self::MODULE_ID, 'NeedGenerateCustomTheme', 'N', SITE_ID) === 'Y';

		$baseColorCustom = '';
		$lastGeneratedBaseColorCustom = COption::GetOptionString(self::MODULE_ID, 'LastGeneratedBaseColorCustom', '', SITE_ID);
		if(isset(self::$arParametrsList['MAIN']['OPTIONS']['BASE_COLOR_CUSTOM'])){
			$baseColorCustom = $arBackParametrs['BASE_COLOR_CUSTOM'] = str_replace('#', '', $arBackParametrs['BASE_COLOR_CUSTOM']);
			if($arBackParametrs['THEME_SWITCHER'] === 'Y' && strlen($_SESSION['THEME'][SITE_ID]['BASE_COLOR_CUSTOM'])){
				$baseColorCustom = $_SESSION['THEME'][SITE_ID]['BASE_COLOR_CUSTOM'] = str_replace('#', '', $_SESSION['THEME'][SITE_ID]['BASE_COLOR_CUSTOM']);
			}
		}

		$bGenerateAll = self::devMode || $bNeedGenerateAllThemes;
		$bGenerateCustom = $bGenerateAll || $bNeedGenerateCustomTheme || ($arBackParametrs['THEME_SWITCHER'] === 'Y' && $isCustomTheme && strlen($baseColorCustom) && $baseColorCustom != $lastGeneratedBaseColorCustom);
		if($arBaseColors && is_array($arBaseColors) && ($bGenerateAll || $bGenerateCustom)){
			if(!class_exists('lessc')){
				include_once 'lessc.inc.php';
			}
			$less = new lessc;
			try{
				foreach($arBaseColors as $colorCode => $arColor){
					if(($bCustom = ($colorCode == 'CUSTOM')) && $bGenerateCustom){
						if(strlen($baseColorCustom)){
							$less->setVariables(array('bcolor' => (strlen($baseColorCustom) ? '#'.$baseColorCustom : $arBaseColors[self::$arParametrsList['MAIN']['OPTIONS']['BASE_COLOR']['DEFAULT']]['COLOR'])));
						}
					}
					elseif($bGenerateAll){
						$less->setVariables(array('bcolor' => $arColor['COLOR']));
					}

					if($bGenerateAll || ($bCustom && $bGenerateCustom)){
						if(defined('SITE_TEMPLATE_PATH')){
							$themeDirPath = $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/themes/'.$colorCode.($colorCode !== 'CUSTOM' ? '' : '_'.SITE_ID).'/';
							if(!is_dir($themeDirPath)) mkdir($themeDirPath, 0755, true);
							$output = $less->compileFile(__DIR__.'/../../css/colors.less', $themeDirPath.'colors.css');
							if($output && $bCustom){
								COption::SetOptionString(self::MODULE_ID, 'LastGeneratedBaseColorCustom', $baseColorCustom, '', SITE_ID);
							}
						}
					}
				}
			}
			catch(exception $e){
				echo 'Fatal error: '.$e->getMessage();
				die();
			}

			if($bNeedGenerateAllThemes){
				COption::SetOptionString(self::MODULE_ID, "NeedGenerateThemes", 'N', '', SITE_ID);
			}
			if($bNeedGenerateCustomTheme){
				COption::SetOptionString(self::MODULE_ID, "NeedGenerateCustomTheme", 'N', '', SITE_ID);
			}
		}
	}

	function start($siteID){
		return true;
	}


	public function correctInstall(){
		if(CModule::IncludeModule('main')){
			if(COption::GetOptionString(self::MODULE_ID, 'WIZARD_DEMO_INSTALLED') == 'Y'){
				require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/wizard.php');
				@set_time_limit(0);
				if(!CWizardUtil::DeleteWizard(self::PARTNER_NAME.':'.self::SOLUTION_NAME)){
					if(!DeleteDirFilesEx($_SERVER['DOCUMENT_ROOT'].'/bitrix/wizards/'.self::PARTNER_NAME.'/'.self::SOLUTION_NAME.'/')){
						self::removeDirectory($_SERVER['DOCUMENT_ROOT'].'/bitrix/wizards/'.self::PARTNER_NAME.'/'.self::SOLUTION_NAME.'/');
					}
				}

				UnRegisterModuleDependences('main', 'OnBeforeProlog', self::MODULE_ID, __CLASS__, 'correctInstall');
				COption::SetOptionString(self::MODULE_ID, 'WIZARD_DEMO_INSTALLED', 'N');
			}
		}
	}

	protected function getBitrixEdition(){
		$edition = 'UNKNOWN';

		if(CModule::IncludeModule('main')){
			include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/update_client.php');
			$arUpdateList = CUpdateClient::GetUpdatesList(($errorMessage = ''), 'ru', 'Y');
			if(array_key_exists('CLIENT', $arUpdateList) && $arUpdateList['CLIENT'][0]['@']['LICENSE']){
				$edition = $arUpdateList['CLIENT'][0]['@']['LICENSE'];
			}
		}

		return $edition;
	}

	protected function removeDirectory($dir){
		if($objs = glob($dir.'/*')){
			foreach($objs as $obj){
				if(is_dir($obj)){
					self::removeDirectory($obj);
				}
				else{
					if(!@unlink($obj)){
						if(chmod($obj, 0777)){
							@unlink($obj);
						}
					}
				}
			}
		}
		if(!@rmdir($dir)){
			if(chmod($dir, 0777)){
				@rmdir($dir);
			}
		}
	}

	function cacheElement($arOrder = array('SORT' => 'ASC'), $arrFilter = array(), $arGroupBy = false, $arNavStartParams = false, $arSelectFields = array(), $tag_cache = ''){
		if(!is_array($arOrder)){
			$arOrder = array('SORT' => 'ASC');
		}

		CModule::IncludeModule('iblock');

		$cache = new CPHPCache();
		$cache_time = 250000;
		$cache_path = 'startup_cache_element';

		$cache_id = 'startup_cache_element_'.serialize($arOrder).serialize($arrFilter).serialize($arGroupBy).serialize($arNavStartParams).serialize($arSelectFields);
		if(COption::GetOptionString('main', 'component_cache_on', 'Y') == 'Y' && $cache->InitCache($cache_time, $cache_id, $cache_path)){
			$res = $cache->GetVars();
			$arRes = $res['arRes'];
		}
		else{
			$rsRes = CIBlockElement::GetList($arOrder, $arrFilter, $arGroupBy, $arNavStartParams, $arSelectFields);
			while($obj = $rsRes->GetNextElement()){
				$res = $obj->GetFields();
				$res['PROPERTIES'] = $obj->GetProperties();
				$arRes[$res['ID']] = $res;
			}
			if(COption::GetOptionString('main', 'component_cache_on', 'Y') == 'Y' && $cache_time > 0){
				$cache->StartDataCache( $cache_time, $cache_id, $cache_path );

				if(!empty($tag_cache)){
					global $CACHE_MANAGER;
					$CACHE_MANAGER->StartTagCache( $cache_path );
					$CACHE_MANAGER->RegisterTag( $tag_cache );
					$CACHE_MANAGER->EndTagCache();
				}

				$cache->EndDataCache(
					array(
						'arRes' => $arRes
					)
				);
			}
		}
		return $arRes;
	}

	function cacheSection($arOrder = array('SORT'=>'ASC'), $arrFilter = array(), $bincCount = false, $arSelect = array(), $single = false, $tag_cache = ''){
		if(!is_array($arOrder)){
			$arOrder = array('SORT' => 'ASC');
		}

		CModule::IncludeModule('iblock');

		$cache = new CPHPCache();
		$cache_time = 250000;
		$cache_path = 'startup_cache_section';

		$cache_id = 'startup_cache_section_'.serialize($arOrder).serialize($arrFilter).$bincCount.serialize($arSelect);
		if(COption::GetOptionString('main', 'component_cache_on', 'Y') == 'Y' && $cache->InitCache($cache_time, $cache_id, $cache_path)){
			$res = $cache->GetVars();
			$arRes = $res['arRes'];
		}
		else{
			$rsRes = CIBlockSection::GetList($arOrder, $arrFilter, $bincCount, $arSelect);
			if($single){
				$arRes = $rsRes->GetNext();
			}
			else{
				while($res = $rsRes->GetNext()){
					$arRes[$res['ID']] = $res;
				}
			}

			if(COption::GetOptionString('main', 'component_cache_on', 'Y') == 'Y' && $cache_time > 0){
				$cache->StartDataCache($cache_time, $cache_id, $cache_path);

				if(!empty($tag_cache)){
					global $CACHE_MANAGER;
					$CACHE_MANAGER->StartTagCache($cache_path);
					$CACHE_MANAGER->RegisterTag($tag_cache);
					$CACHE_MANAGER->EndTagCache();
				}

				$cache->EndDataCache(
					array(
						'arRes' => $arRes
					)
				);
			}
		}
		return $arRes;
	}

	function get_file_info($fileID){
		$file = CFile::GetFileArray($fileID);
		$pos = strrpos($file['FILE_NAME'], '.');
		$file['FILE_NAME'] = substr($file['FILE_NAME'], $pos);
		if(!$file['FILE_SIZE']){
			// bx bug in some version
			$file['FILE_SIZE'] = filesize($_SERVER['DOCUMENT_ROOT'].$file['SRC']);
		}
		$frm = explode('.', $file['FILE_NAME']);
		$frm = $frm[1];
		if($frm == 'doc' || $frm == 'docx'){
			$type = 'doc';
		}
		elseif($frm == 'xls' || $frm == 'xlsx'){
			$type = 'xls';
		}
		elseif($frm == 'jpg' || $frm == 'jpeg'){
			$type = 'jpg';
		}
		elseif($frm == 'png'){
			$type = 'png';
		}
		elseif($frm == 'ppt'){
			$type = 'ppt';
		}
		elseif($frm == 'tif'){
			$type = 'tif';
		}
		elseif($frm == 'txt'){
			$type = 'txt';
		}
		else{
			$type = 'pdf';
		}
		return $arr = array('TYPE' => $type, 'FILE_SIZE' => $file['FILE_SIZE'], 'SRC' => $file['SRC'], 'DESCRIPTION' => $file['DESCRIPTION'], 'ORIGINAL_NAME' => $file['ORIGINAL_NAME']);
	}

	function filesize_format($filesize){
		$formats = array(GetMessage('CT_NAME_b'), GetMessage('CT_NAME_KB'), GetMessage('CT_NAME_MB'), GetMessage('CT_NAME_GB'), GetMessage('CT_NAME_TB'));
		$format = 0;
		while($filesize > 1024 && count($formats) != ++$format){
			$filesize = round($filesize / 1024, 1);
		}
		$formats[] = GetMessage('CT_NAME_TB');
		return $filesize.' '.$formats[$format];
	}

	function getChilds($input, &$start = 0, $level = 0){
		$arIblockItemsMD5 = array();

		if(!$level){
			$lastDepthLevel = 1;
			if($input && is_array($input)){
				foreach($input as $i => $arItem){
					if($arItem['DEPTH_LEVEL'] > $lastDepthLevel){
						if($i > 0){
							$input[$i - 1]['IS_PARENT'] = 1;
						}
					}
					$lastDepthLevel = $arItem['DEPTH_LEVEL'];
				}
			}
		}

		$childs = array();
		$count = count($input);
		for($i = $start; $i < $count; ++$i){
			$item = $input[$i];
			if(!isset($item)){
				continue;
			}
			if($level > $item['DEPTH_LEVEL'] - 1){
				break;
			}
			else{
				if(!empty($item['IS_PARENT'])){
					$i++;
					$item['CHILD'] = self::getChilds($input, $i, $level + 1);
					$i--;
				}

				$childs[] = $item;
			}
		}
		$start = $i;

		if(is_array($childs)){
			foreach($childs as $j => $item){
				if($item['PARAMS']){
					$md5 = md5($item['TEXT'].$item['LINK'].$item['SELECTED'].$item['PERMISSION'].$item['ITEM_TYPE'].$item['IS_PARENT'].serialize($item['ADDITIONAL_LINKS']).serialize($item['PARAMS']));

					// check if repeat in one section chids list
					if(isset($arIblockItemsMD5[$md5][$item['PARAMS']['DEPTH_LEVEL']])){
						if(isset($arIblockItemsMD5[$md5][$item['PARAMS']['DEPTH_LEVEL']][$level]) || ($item['DEPTH_LEVEL'] === 1 && !$level)){
							unset($childs[$j]);
							continue;
						}
					}
					if(!isset($arIblockItemsMD5[$md5])){
						$arIblockItemsMD5[$md5] = array($item['PARAMS']['DEPTH_LEVEL'] => array($level => true));
					}
					else{
						$arIblockItemsMD5[$md5][$item['PARAMS']['DEPTH_LEVEL']][$level] = true;
					}
				}
			}
		}

		if(!$level){
			$arIblockItemsMD5 = array();

			self::checkChildsSelected($childs);
			//echo '<pre>';print_r($childs);echo '</pre>';
		}

		return $childs;
	}

	function sort_sections_by_field($arr, $name){
		$count = count($arr);
		for($i = 0; $i < $count; $i++){
			for($j = 0; $j < $count; $j++){
				if(strtoupper($arr[$i]['NAME']) < strtoupper($arr[$j]['NAME'])){
					$tmp = $arr[$i];
					$arr[$i] = $arr[$j];
					$arr[$j] = $tmp;
				}
			}
		}
		return $arr;
	}

	function getIBItems($prop, $checkNoImage){
		$arID = array();
		$arItems = array();
		$arAllItems = array();

		if($prop && is_array($prop)){
			foreach($prop as $reviewID){
				$arID[]=$reviewID;
			}
		}
		if($checkNoImage) $empty=false;
		$arItems = self::cacheElement(false, array('ID' => $arID, 'ACTIVE' => 'Y'));
		if($arItems && is_array($arItems)){
			foreach($arItems as $key => $arItem){
				if($checkNoImage){
					if(empty($arProject['PREVIEW_PICTURE'])){
						$empty=true;
					}
				}
				$arAllItems['ITEMS'][$key] = $arItem;
				if($arItem['DETAIL_PICTURE']) $arAllItems['ITEMS'][$key]['DETAIL'] = CFile::GetFileArray( $arItem['DETAIL_PICTURE'] );
				if($arItem['PREVIEW_PICTURE']) $arAllItems['ITEMS'][$key]['PREVIEW'] = CFile::ResizeImageGet( $arItem['PREVIEW_PICTURE'], array('width' => 425, 'height' => 330), BX_RESIZE_IMAGE_EXACT, true );
			}
		}
		if($checkNoImage) $arAllItems['NOIMAGE'] = 'YES';

		return $arAllItems;
	}

	function getSectionChilds($PSID, &$arSections, &$arSectionsByParentSectionID, &$arItemsBySectionID, &$aMenuLinksExt){
		if($arSections && is_array($arSections)){
			foreach($arSections as $arSection){
				if($arSection['IBLOCK_SECTION_ID'] == $PSID){
					$arItem = array($arSection['NAME'], $arSection['SECTION_PAGE_URL'], array(), array('FROM_IBLOCK' => 1, 'DEPTH_LEVEL' => $arSection['DEPTH_LEVEL']));
					$arItem[3]['IS_PARENT'] = (isset($arItemsBySectionID[$arSection['ID']]) || isset($arSectionsByParentSectionID[$arSection['ID']]) ? 1 : 0);
					$aMenuLinksExt[] = $arItem;
					if($arItem[3]['IS_PARENT']){
						// subsections
						self::getSectionChilds($arSection['ID'], $arSections, $arSectionsByParentSectionID, $arItemsBySectionID, $aMenuLinksExt);
						// section elements
						if($arItemsBySectionID[$arSection['ID']] && is_array($arItemsBySectionID[$arSection['ID']])){
							foreach($arItemsBySectionID[$arSection['ID']] as $arItem){
								if(is_array($arItem['DETAIL_PAGE_URL'])){
									if(isset($arItem['CANONICAL_PAGE_URL']) && strlen($arItem['CANONICAL_PAGE_URL'])){
										$arItem['DETAIL_PAGE_URL'] = $arItem['CANONICAL_PAGE_URL'];
									}
									else{
										$arItem['DETAIL_PAGE_URL'] = $arItem['DETAIL_PAGE_URL'][$arSection['ID']];
									}
								}
								$aMenuLinksExt[] = array($arItem['NAME'], $arItem['DETAIL_PAGE_URL'], array(), array('FROM_IBLOCK' => 1, 'DEPTH_LEVEL' => ($arSection['DEPTH_LEVEL'] + 1), 'IS_ITEM' => 1));
							}
						}
					}
				}
			}
		}
	}

	function isChildsSelected($arChilds){
		if($arChilds && is_array($arChilds)){
			foreach($arChilds as $arChild){
				if($arChild['SELECTED']){
					return $arChild;
				}
			}
		}
		return false;
	}

	function checkChildsSelected(&$arChilds){
		$bSelected = false;

		if($arChilds && is_array($arChilds)){
			foreach($arChilds as &$arChild){
				if($arChild['IS_PARENT'] && $arChild['CHILD'] && is_array($arChild['CHILD'])){
					$arChild['SELECTED'] |= self::checkChildsSelected($arChild['CHILD']);
				}

				$bSelected |= $arChild['SELECTED'];
			}
		}

		return $bSelected;
	}

	function SetJSOptions(){
		$arFrontParametrs = CScorp::GetFrontParametrsValues(SITE_ID);
		$tmp = $arFrontParametrs['DATE_FORMAT'];
		$DATE_MASK = ($tmp == 'DOT' ? 'd.m.y' : ($tmp == 'HYPHEN' ? 'd-m-y' : ($tmp == 'SPACE' ? 'd m y' : ($tmp == 'SLASH' ? 'd/m/y' : 'd:m:y'))));
		$VALIDATE_DATE_MASK = ($tmp == 'DOT' ? '^[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{4}$' : ($tmp == 'HYPHEN' ? '^[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}$' : ($tmp == 'SPACE' ? '^[0-9]{1,2} [0-9]{1,2} [0-9]{4}$' : ($tmp == 'SLASH' ? '^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$' : '^[0-9]{1,2}\:[0-9]{1,2}\:[0-9]{4}$'))));
		$DATE_PLACEHOLDER = ($tmp == 'DOT' ? GetMessage('DATE_FORMAT_DOT') : ($tmp == 'HYPHEN' ? GetMessage('DATE_FORMAT_HYPHEN') : ($tmp == 'SPACE' ? GetMessage('DATE_FORMAT_SPACE') : ($tmp == 'SLASH' ? GetMessage('DATE_FORMAT_SLASH') : GetMessage('DATE_FORMAT_COLON')))));
		$DATETIME_MASK = ($tmp == 'DOT' ? 'd.m.y' : ($tmp == 'HYPHEN' ? 'd-m-y' : ($tmp == 'SPACE' ? 'd m y' : ($tmp == 'SLASH' ? 'd/m/y' : 'd:m:y')))).' h:s';
		$DATETIME_PLACEHOLDER = ($tmp == 'DOT' ? GetMessage('DATE_FORMAT_DOT') : ($tmp == 'HYPHEN' ? GetMessage('DATE_FORMAT_HYPHEN') : ($tmp == 'SPACE' ? GetMessage('DATE_FORMAT_SPACE') : ($tmp == 'SLASH' ? GetMessage('DATE_FORMAT_SLASH') : GetMessage('DATE_FORMAT_COLON'))))).' '.GetMessage('TIME_FORMAT_COLON');
		$VALIDATE_DATETIME_MASK = ($tmp == 'DOT' ? '^[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$' : ($tmp == 'HYPHEN' ? '^[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$' : ($tmp == 'SPACE' ? '^[0-9]{1,2} [0-9]{1,2} [0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$' : ($tmp == 'SLASH' ? '^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$' : '^[0-9]{1,2}\:[0-9]{1,2}\:[0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$'))));
		?>
		<script type='text/javascript'>
		var arBasketItems = {};
		var arScorpOptions = ({
			'SITE_DIR' : '<?=SITE_DIR?>',
			'SITE_ID' : '<?=SITE_ID?>',
			'SITE_TEMPLATE_PATH' : '<?=SITE_TEMPLATE_PATH?>',
			'THEME' : ({
				'THEME_SWITCHER' : '<?=$arFrontParametrs['THEME_SWITCHER']?>',
				'BASE_COLOR' : '<?=$arFrontParametrs['BASE_COLOR']?>',
				'BASE_COLOR_CUSTOM' : '<?=$arFrontParametrs['BASE_COLOR_CUSTOM']?>',
				'TOP_MENU' : '<?=$arFrontParametrs['TOP_MENU']?>',
				'TOP_MENU_FIXED' : '<?=$arFrontParametrs['TOP_MENU_FIXED']?>',
				'COLORED_LOGO' : '<?=$arFrontParametrs['COLORED_LOGO']?>',
				'SIDE_MENU' : '<?=$arFrontParametrs['SIDE_MENU']?>',
				'SCROLLTOTOP_TYPE' : '<?=$arFrontParametrs['SCROLLTOTOP_TYPE']?>',
				'SCROLLTOTOP_POSITION' : '<?=$arFrontParametrs['SCROLLTOTOP_POSITION']?>',
				'ADD_SITE_NAME_IN_TITLE' : '<?=$arFrontParametrs['ADD_SITE_NAME_IN_TITLE']?>',
				'USE_CAPTCHA_FORM' : '<?=$arFrontParametrs['USE_CAPTCHA_FORM']?>',
				'DISPLAY_PROCESSING_NOTE' : '<?=$arFrontParametrs['DISPLAY_PROCESSING_NOTE']?>',
				'PROCESSING_NOTE_CHECKED' : '<?=$arFrontParametrs['PROCESSING_NOTE_CHECKED']?>',
				'PHONE_MASK' : '<?=$arFrontParametrs['PHONE_MASK']?>',
				'VALIDATE_PHONE_MASK' : '<?=$arFrontParametrs['VALIDATE_PHONE_MASK']?>',
				'DATE_MASK' : '<?=$DATE_MASK?>',
				'DATE_PLACEHOLDER' : '<?=$DATE_PLACEHOLDER?>',
				'VALIDATE_DATE_MASK' : '<?=($VALIDATE_DATE_MASK)?>',
				'DATETIME_MASK' : '<?=$DATETIME_MASK?>',
				'DATETIME_PLACEHOLDER' : '<?=$DATETIME_PLACEHOLDER?>',
				'VALIDATE_DATETIME_MASK' : '<?=($VALIDATE_DATETIME_MASK)?>',
				'VALIDATE_FILE_EXT' : '<?=$arFrontParametrs['VALIDATE_FILE_EXT']?>',
				'SOCIAL_VK' : '<?=$arFrontParametrs['SOCIAL_VK']?>',
				'SOCIAL_FACEBOOK' : '<?=$arFrontParametrs['SOCIAL_FACEBOOK']?>',
				'SOCIAL_TWITTER' : '<?=$arFrontParametrs['SOCIAL_TWITTER']?>',
				'SOCIAL_YOUTUBE' : '<?=$arFrontParametrs['SOCIAL_YOUTUBE']?>',
				'SOCIAL_ODNOKLASSNIKI' : '<?=$arFrontParametrs['SOCIAL_ODNOKLASSNIKI']?>',
				'SOCIAL_GOOGLEPLUS' : '<?=$arFrontParametrs['SOCIAL_GOOGLEPLUS']?>',
				'BANNER_WIDTH' : '<?=$arFrontParametrs['BANNER_WIDTH']?>',
				'TEASERS_INDEX' : '<?=$arFrontParametrs['TEASERS_INDEX']?>',
				'CATALOG_INDEX' : '<?=$arFrontParametrs['CATALOG_INDEX']?>',
				'CATALOG_FAVORITES_INDEX' : '<?=$arFrontParametrs['CATALOG_FAVORITES_INDEX']?>',
				'BIGBANNER_ANIMATIONTYPE' : '<?=$arFrontParametrs['BIGBANNER_ANIMATIONTYPE']?>',
				'BIGBANNER_SLIDESSHOWSPEED' : '<?=$arFrontParametrs['BIGBANNER_SLIDESSHOWSPEED']?>',
				'BIGBANNER_ANIMATIONSPEED' : '<?=$arFrontParametrs['BIGBANNER_ANIMATIONSPEED']?>',
				'PARTNERSBANNER_SLIDESSHOWSPEED' : '<?=$arFrontParametrs['PARTNERSBANNER_SLIDESSHOWSPEED']?>',
				'PARTNERSBANNER_ANIMATIONSPEED' : '<?=$arFrontParametrs['PARTNERSBANNER_ANIMATIONSPEED']?>',
				'ORDER_VIEW' : '<?=$arFrontParametrs['ORDER_VIEW']?>',
				'ORDER_BASKET_VIEW' : '<?=$arFrontParametrs['ORDER_BASKET_VIEW']?>',
				'URL_BASKET_SECTION' : '<?=$arFrontParametrs['URL_BASKET_SECTION']?>',
				'URL_ORDER_SECTION' : '<?=$arFrontParametrs['URL_ORDER_SECTION']?>',
				'USE_YA_COUNTER' : '<?=$arFrontParametrs['USE_YA_COUNTER']?>',
				'YA_COUNTER_ID' : '<?=$arFrontParametrs['YA_COUNTER_ID']?>',
				'USE_FORMS_GOALS' : '<?=$arFrontParametrs['USE_FORMS_GOALS']?>',
				'USE_SALE_GOALS' : '<?=$arFrontParametrs['USE_SALE_GOALS']?>',
				'USE_DEBUG_GOALS' : '<?=$arFrontParametrs['USE_DEBUG_GOALS']?>',
			})
		});

		$(document).ready(function(){
			if($.trim(arScorpOptions['THEME']['ORDER_VIEW']) === 'Y' && ($.trim(window.location.pathname) != $.trim(arScorpOptions['THEME']['URL_BASKET_SECTION'])) && ($.trim(window.location.pathname) != $.trim(arScorpOptions['THEME']['URL_ORDER_SECTION']))){
				if(arScorpOptions['THEME']['ORDER_BASKET_VIEW'] === 'FLY'){
					$.ajax({
						url: arScorpOptions['SITE_DIR'] + 'ajax/basket_items.php',
						type: 'POST',
						success: function(html){
							$('body').prepend('<div class="ajax_basket">' + html + '</div>');
							setTimeout(function(){
								$('.ajax_basket').addClass('ready');
								$('.basket.fly>.wrap').addClass(arScorpOptions['THEME']['TOP_MENU']);
								$('.basket_top.basketFlyTrue').removeClass('hidden').find('.count').text($('.basket .count').text());
							}, 50);
						}
					});

					/*if($('header .top-callback').length){
						var htmlMedia = '<div class="basket_top basketFlyTrue pull-right hidden-lg hidden-md hidden-sm">'
											+'<div class="b_wrap">'
												+'<a href="'+$.trim(arScorpOptions['THEME']['URL_BASKET_SECTION'])+'" class="icon"><span class="count"></span></a>'
											+'</div>'
										+'</div>';
						$('header .top-callback').prepend(htmlMedia);
					}*/
				}
				else if(arScorpOptions['THEME']['ORDER_BASKET_VIEW'] === 'HEADER'){
					$.ajax({
						url: arScorpOptions['SITE_DIR'] + 'ajax/basket_items.php ',
						type: 'POST',
						success: function(html){
							$('.mega-menu .table-menu.basketTrue table td.search-item>.wrap').append('<div class="ajax_basket">' + html + '</div>');
							$('header .logo-row .top-callback').prepend('<div class="ajax_basket">' + html + '</div>');
							setTimeout(function(){
								$('.ajax_basket').addClass('ready');
							}, 50);
						}
					});
				}
			}

		});

		</script>
		<?
		Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID('options-block');
		self::checkBasketItems();
		Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID('options-block', '');
	}

	function IsCompositeEnabled(){
		if(class_exists('CHTMLPagesCache')){
			if(method_exists('CHTMLPagesCache', 'GetOptions')){
				if($arHTMLCacheOptions = CHTMLPagesCache::GetOptions()){
					if(method_exists('CHTMLPagesCache', 'isOn')){
						if (CHTMLPagesCache::isOn()){
							if(isset($arHTMLCacheOptions['AUTO_COMPOSITE']) && $arHTMLCacheOptions['AUTO_COMPOSITE'] === 'Y'){
								return 'AUTO_COMPOSITE';
							}
							else{
								return 'COMPOSITE';
							}
						}
					}
					else{
						if($arHTMLCacheOptions['COMPOSITE'] === 'Y'){
							return 'COMPOSITE';
						}
					}
				}
			}
		}

		return false;
	}

	function EnableComposite($auto = false){
		if(class_exists('CHTMLPagesCache')){
			if(method_exists('CHTMLPagesCache', 'GetOptions')){
				if($arHTMLCacheOptions = CHTMLPagesCache::GetOptions()){
					$arHTMLCacheOptions['COMPOSITE'] = 'Y';
					$arHTMLCacheOptions['AUTO_UPDATE'] = 'Y'; // standart mode
					$arHTMLCacheOptions['AUTO_UPDATE_TTL'] = '0'; // no ttl delay
					$arHTMLCacheOptions['AUTO_COMPOSITE'] = ($auto ? 'Y' : 'N'); // auto composite mode
					CHTMLPagesCache::SetEnabled(true);
					CHTMLPagesCache::SetOptions($arHTMLCacheOptions);
					bx_accelerator_reset();
				}
			}
		}
	}

	function GetCurrentElementFilter(&$arVariables, &$arParams){
        $arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'INCLUDE_SUBSECTIONS' => 'Y');
        if($arParams['CHECK_DATES'] == 'Y'){
            $arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'SECTION_GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
        }
        if($arVariables['ELEMENT_ID']){
            $arFilter['ID'] = $arVariables['ELEMENT_ID'];
        }
        elseif(strlen($arVariables['ELEMENT_CODE'])){
            $arFilter['CODE'] = $arVariables['ELEMENT_CODE'];
        }
		if($arVariables['SECTION_ID']){
			$arFilter['SECTION_ID'] = ($arVariables['SECTION_ID'] ? $arVariables['SECTION_ID'] : false);
		}
		if($arVariables['SECTION_CODE']){
			$arFilter['SECTION_CODE'] = ($arVariables['SECTION_CODE'] ? $arVariables['SECTION_CODE'] : false);
		}
        if(!$arFilter['SECTION_ID'] && !$arFilter['SECTION_CODE']){
            unset($arFilter['SECTION_GLOBAL_ACTIVE']);
        }
        return $arFilter;
    }

	function GetCurrentSectionFilter(&$arVariables, &$arParams){
		$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID']);
		if($arParams['CHECK_DATES'] == 'Y'){
			$arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
		}
		if($arVariables['SECTION_ID']){
			$arFilter['ID'] = $arVariables['SECTION_ID'];
		}
		if(strlen($arVariables['SECTION_CODE'])){
			$arFilter['CODE'] = $arVariables['SECTION_CODE'];
		}
		if(!$arVariables['SECTION_ID'] && !strlen($arFilter['CODE'])){
			$arFilter['ID'] = 0; // if section not found
		}
		return $arFilter;
	}

	function GetCurrentSectionElementFilter(&$arVariables, &$arParams, $CurrentSectionID = false){
		$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'INCLUDE_SUBSECTIONS' => 'N');
		if($arParams['CHECK_DATES'] == 'Y'){
			$arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'SECTION_GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
		}
		if(!($arFilter['SECTION_ID'] = ($CurrentSectionID !== false ? $CurrentSectionID : ($arVariables['SECTION_ID'] ? $arVariables['SECTION_ID'] : false)))){
			if(strlen($arVariables['SECTION_CODE'])){
				$arFilter['SECTION_CODE'] = $arVariables['SECTION_CODE'];
			}
			else{
				unset($arFilter['SECTION_GLOBAL_ACTIVE']);
			}
		}
		if(($arFilter['SECTION_ID'] || $arFilter['SECTION_CODE']) && $arParams['INCLUDE_SUBSECTIONS'] === 'Y'){
			$arFilter['INCLUDE_SUBSECTIONS'] = 'Y';
		}
		if(strlen($arParams['FILTER_NAME'])){
			$GLOBALS[$arParams['FILTER_NAME']] = (array)$GLOBALS[$arParams['FILTER_NAME']];
			foreach($arUnsetFilterFields = array('SECTION_ID', 'SECTION_CODE', 'SECTION_ACTIVE', 'SECTION_GLOBAL_ACTIVE') as $filterUnsetField){
				foreach($GLOBALS[$arParams['FILTER_NAME']] as $filterField => $filterValue){
					if(($p = strpos($filterUnsetField, $filterField)) !== false && $p < 2){
						unset($GLOBALS[$arParams['FILTER_NAME']][$filterField]);
					}
				}
			}
			if($GLOBALS[$arParams['FILTER_NAME']]){
				$arFilter = array_merge($arFilter, $GLOBALS[$arParams['FILTER_NAME']]);
			}
		}

		return $arFilter;
	}

	function GetCurrentSectionSubSectionFilter(&$arVariables, &$arParams, $CurrentSectionID = false){
		$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID']);
		if($arParams['CHECK_DATES'] == 'Y'){
			$arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
		}
		if(!$arFilter['SECTION_ID'] = ($CurrentSectionID !== false ? $CurrentSectionID : ($arVariables['SECTION_ID'] ? $arVariables['SECTION_ID'] : false))){
			$arFilter['INCLUDE_SUBSECTIONS'] = 'N';array_merge($arFilter, array('INCLUDE_SUBSECTIONS' => 'N', 'DEPTH_LEVEL' => '1'));
			$arFilter['DEPTH_LEVEL'] = '1';
			unset($arFilter['GLOBAL_ACTIVE']);
		}
		return $arFilter;
	}

	function GetIBlockAllElementsFilter(&$arParams){
		$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'INCLUDE_SUBSECTIONS' => 'Y');
		if($arParams['CHECK_DATES'] == 'Y'){
			$arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
		}
		if(strlen($arParams['FILTER_NAME']) && (array)$GLOBALS[$arParams['FILTER_NAME']]){
			$arFilter = array_merge($arFilter, (array)$GLOBALS[$arParams['FILTER_NAME']]);
		}
		return $arFilter;
	}

	function CheckSmartFilterSEF($arParams, $component){
		if($arParams['SEF_MODE'] === 'Y' && strlen($arParams['FILTER_URL_TEMPLATE']) && is_object($component)){
			$arVariables = $arDefaultUrlTemplates404 = $arDefaultVariableAliases404 = $arDefaultVariableAliases = array();
			$smartBase = ($arParams["SEF_URL_TEMPLATES"]["section"] ? $arParams["SEF_URL_TEMPLATES"]["section"] : "#SECTION_ID#/");
			$arParams["SEF_URL_TEMPLATES"]["smart_filter"] = $smartBase."filter/#SMART_FILTER_PATH#/apply/";
			$arComponentVariables = array("SECTION_ID", "SECTION_CODE", "ELEMENT_ID", "ELEMENT_CODE", "action");
			$engine = new CComponentEngine($component);
			$engine->addGreedyPart("#SECTION_CODE_PATH#");
			$engine->addGreedyPart("#SMART_FILTER_PATH#");
			$engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
			$arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);
			$componentPage = $engine->guessComponentPath($arParams["SEF_FOLDER"], $arUrlTemplates, $arVariables);
			if($componentPage === 'smart_filter'){
				$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases404, $arParams["VARIABLE_ALIASES"]);
				CComponentEngine::InitComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);
				return $arResult = array("FOLDER" => $arParams["SEF_FOLDER"], "URL_TEMPLATES" => $arUrlTemplates, "VARIABLES" => $arVariables, "ALIASES" => $arVariableAliases);
			}
		}

		return false;
	}

	function AddMeta($arParams = array()){
		self::$arMetaParams = array_merge((array)self::$arMetaParams, (array)$arParams);
	}

	function SetMeta(){
		global $arSite;

		$PageH1 = $GLOBALS['APPLICATION']->GetTitle();
		$PageMetaTitleBrowser = $GLOBALS['APPLICATION']->GetPageProperty('title');
		$DirMetaTitleBrowser = $GLOBALS['APPLICATION']->GetDirProperty('title');
		$PageMetaDescription = $GLOBALS['APPLICATION']->GetPageProperty('description');
		$DirMetaDescription = $GLOBALS['APPLICATION']->GetDirProperty('description');

		// set title
		$title = strlen($PageMetaTitleBrowser) ? $PageMetaTitleBrowser : (strlen($DirMetaTitleBrowser) ? $DirMetaTitleBrowser : (strlen($PageH1) ? $PageH1 : ''));
		if($bAddSiteNameInTitle = !defined('ADD_SITE_NAME_IN_TITLE') || ADD_SITE_NAME_IN_TITLE !== 'N'){
			$title = CSite::inDir(SITE_DIR.'index.php') ? implode(' - ', array($arSite['SITE_NAME'], $title)) : implode(' - ', array($title, $arSite['SITE_NAME']));
		}
		$GLOBALS['APPLICATION']->SetPageProperty('title', $title);

		//print_r(array($PageH1, $PageMetaTitleBrowser, $DirMetaTitleBrowser, $title));

		// check Open Graph required meta properties
		if(!strlen(self::$arMetaParams['og:title'])){
			self::$arMetaParams['og:title'] = $title;
		}
		if(!strlen(self::$arMetaParams['og:type'])){
			self::$arMetaParams['og:type'] = 'article';
		}
		if(!strlen(self::$arMetaParams['og:image'])){
			self::$arMetaParams['og:image'] = SITE_DIR.'logo.png'; // site logo
		}
		if(!strlen(self::$arMetaParams['og:url'])){
			self::$arMetaParams['og:url'] = $_SERVER['REQUEST_URI'];
		}
		if(!strlen(self::$arMetaParams['og:description'])){
			self::$arMetaParams['og:description'] = (strlen($PageMetaDescription) ? $PageMetaDescription : $DirMetaDescription);
		}

		foreach(self::$arMetaParams as $metaName => $metaValue){
			if(strlen($metaValue = strip_tags($metaValue))){
				$GLOBALS['APPLICATION']->AddHeadString('<meta property="'.$metaName.'" content="'.$metaValue.'" />', true);
				if($metaName === 'og:image'){
					$GLOBALS['APPLICATION']->AddHeadString('<link rel="image_src" href="'.$metaValue.'"  />', true);
				}
			}
		}
	}

	static function CheckAdditionalChain($arResult, $arParams, $sectionID = false, $elementID = false){
		$bsetFromName = isset($arParams['SET_BREADCRUMBS_CHAIN_FROM']) && $arParams['SET_BREADCRUMBS_CHAIN_FROM'] === "NAME";
		$bMultiSection = is_array($sectionID) && count($sectionID) > 1;

		$GLOBALS['APPLICATION']->arAdditionalChain = false;

		if($elementID){
			$arElement = CCache::CIBlockElement_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'N')), array('ID' => $elementID), false, false, array('ID', 'NAME', 'DETAIL_PAGE_URL', 'LIST_PAGE_URL'));
		}

		if($arParams['INCLUDE_IBLOCK_INTO_CHAIN'] == 'Y' && isset(CCache::$arIBlocksInfo[$arParams['IBLOCK_ID']]['NAME'])){
			$GLOBALS['APPLICATION']->AddChainItem(CCache::$arIBlocksInfo[$arParams['IBLOCK_ID']]['NAME'], $arElement['~LIST_PAGE_URL']);
		}

		if($arParams['ADD_SECTIONS_CHAIN'] == 'Y' && $sectionID){
			if($bMultiSection){
				$arSection = CCache::CIBlockSection_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'N')), self::GetCurrentSectionFilter($arResult['VARIABLES'], $arParams), false, array('ID', 'NAME'));
			}
			else{
				$arSection = CCache::CIBlockSection_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'N')), array('ID' => $sectionID), false, array('ID', 'NAME'));
			}

			$rsPath = CIBlockSection::GetNavChain($arParams['IBLOCK_ID'], $arSection['ID']);
			$rsPath->SetUrlTemplates('', $arParams['SECTION_URL']);
			while($arPath = $rsPath->GetNext()){
				$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arParams['IBLOCK_ID'], $arPath['ID']);
				$arPath['IPROPERTY_VALUES'] = $ipropValues->getValues();
				$arSection['PATH'][] = $arPath;
			}

			foreach($arSection['PATH'] as $arPath){
				if($bsetFromName || !strlen($arPath['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'])){
					$GLOBALS['APPLICATION']->AddChainItem($arPath['NAME'], $arPath['~SECTION_PAGE_URL']);
				}
				else{
					$GLOBALS['APPLICATION']->AddChainItem($arPath['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'], $arPath['~SECTION_PAGE_URL']);
				}
			}
		}

		if($arParams['ADD_ELEMENT_CHAIN'] == 'Y' && $elementID){
			$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arParams['IBLOCK_ID'], $elementID);
			$arElementIPROPERTY_VALUES = $ipropValues->getValues();

			if($bsetFromName || !strlen($arElementIPROPERTY_VALUES['ELEMENT_PAGE_TITLE'])){
				$GLOBALS['APPLICATION']->AddChainItem($arElement['NAME'], $arElement['DETAIL_PAGE_URL']);
			}
			else{
				$GLOBALS['APPLICATION']->AddChainItem($arElementIPROPERTY_VALUES['ELEMENT_PAGE_TITLE'], $arElement['DETAIL_PAGE_URL']);
			}
		}
	}

	// if there are some elements in multiple sections and need correct detail page url in current section context, than
	// use this function in bitrix:news.list (result_modifier.php)
	function CheckDetailPageUrlInMultilevel(&$arResult){
		if($arResult['ITEMS']){
			$arItemsIDs = $arItems = array();
			$CurrentSectionID = false;

			foreach($arResult['ITEMS'] as $arItem){
				$arItemsIDs[] = $arItem['ID'];
			}

			$arItems = CCache::CIBLockElement_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N')), array('ID' => $arItemsIDs), false, false, array('ID', 'IBLOCK_SECTION_ID', 'DETAIL_PAGE_URL'));

			if($arResult['SECTION']['PATH']){
				for($i = count($arResult['SECTION']['PATH']) - 1; $i >= 0; --$i){
					if(CSite::InDir($arResult['SECTION']['PATH'][$i]['SECTION_PAGE_URL'])){
						$CurrentSectionID = $arResult['SECTION']['PATH'][$i]['ID'];
						break;
					}
				}
			}

			foreach($arResult['ITEMS'] as $i => $arItem){
				if(is_array($arItems[$arItem['ID']]['IBLOCK_SECTION_ID'])){
					$arResult['ITEMS'][$i]['IBLOCK_SECTION_ID'] = $CurrentSectionID;

					if(is_array($arItems[$arItem['ID']]['DETAIL_PAGE_URL'])){
						if($arItems[$arItem['ID']]['DETAIL_PAGE_URL'][$CurrentSectionID]){
							$arResult['ITEMS'][$i]['DETAIL_PAGE_URL'] = $arItems[$arItem['ID']]['DETAIL_PAGE_URL'][$CurrentSectionID];
						}
					}
				}
			}
		}
	}

	function FormatSumm($strPrice, $quantity){
		$strSumm = '';

		if(strlen($strPrice = trim($strPrice))){
			$currency = '';
			$price = floatval(str_replace(' ', '', $strPrice));
			$summ = $price * $quantity;
			$strSumm = str_replace(trim(str_replace($currency, '', $strPrice)), str_replace('.00', '', number_format($summ, 2, '.', ' ')), $strPrice);
		}

		return $strSumm;
	}

	function FormatPriceShema($strPrice = ''){
		if(strlen($strPrice = trim($strPrice))){
			$arCur = array(
				'$' => 'USD',
				GetMessage('SCORP_CUR_EUR1') => 'EUR',
				GetMessage('SCORP_CUR_RUB1') => 'RUB',
				GetMessage('SCORP_CUR_RUB2') => 'RUB',
				GetMessage('SCORP_CUR_UAH1') => 'UAH',
				GetMessage('SCORP_CUR_UAH2') => 'UAH',
				GetMessage('SCORP_CUR_RUB3') => 'RUB',
				GetMessage('SCORP_CUR_RUB4') => 'RUB',
				GetMessage('SCORP_CUR_RUB5') => 'RUB',
				GetMessage('SCORP_CUR_RUB6') => 'RUB',
				GetMessage('SCORP_CUR_RUB3') => 'RUB',
				GetMessage('SCORP_CUR_UAH3') => 'UAH',
				GetMessage('SCORP_CUR_RUB5') => 'RUB',
				GetMessage('SCORP_CUR_UAH6') => 'UAH',
			);

			foreach($arCur as $curStr => $curCode){
				if(strpos($strPrice, $curStr) !== false){
					$priceVal = str_replace($curStr, '', $strPrice);
					return str_replace(array($curStr, $priceVal), array('<span class="currency" itemprop="priceCurrency" content="'.$curCode.'">'.$curStr.'</span>', '<span itemprop="price" content="'.$priceVal.'">'.$priceVal.'</span>'), $strPrice);
				}
			}
		}
		return $strPrice;
	}

	function GetBannerStyle($bannerwidth, $topmenu){
        $style = "";

        if($bannerwidth == "WIDE"){
            $style = ".maxwidth-banner{max-width: 1480px;}";
        }
        elseif($bannerwidth == "MIDDLE"){
            $style = ".maxwidth-banner{max-width: 1280px;}";
        }
        elseif($bannerwidth == "NARROW"){
            $style = ".maxwidth-banner{max-width: 1006px; padding: 0 15px;}";
			if($topmenu !== 'LIGHT'){
				$style .= ".banners-big{margin-top:20px;}";
			}
        }
        else{
            $style = ".maxwidth-banner{max-width: auto;}";
        }

        return "<style>".$style."</style>";
    }

    function GetBodyClass(){
    	$arClass = array('body');
    	$arFrontParametrs = self::GetFrontParametrsValues(SITE_ID);

    	if(self::IsIndex()){
    		$arClass[] = 'index';
    	}

    	if($arFrontParametrs['ORDER_VIEW'] === 'Y' && !self::IsBasketSection() && !self::IsOrderSection()){
    		$arClass[] = 'wbasket';
    	}

    	return implode(' ', $arClass);
    }

    function IsIndex(){
    	static $result;

    	if(!isset($result)){
    		$result = CSite::inDir(SITE_DIR.'index.php');
    	}

    	return $result;
    }

    function IsBasketSection(){
    	static $result;

    	if(!isset($result)){
	    	$arBackParametrs = self::GetBackParametrsValues(SITE_ID);
	    	$result = CSite::inDir(str_replace('//', '/', SITE_DIR.trim($arBackParametrs['URL_BASKET_SECTION'])));
    	}

    	return $result;
    }

    function IsOrderSection(){
    	static $result;

    	if(!isset($result)){
	    	$arBackParametrs = self::GetBackParametrsValues(SITE_ID);
	    	$result = CSite::inDir(str_replace('//', '/', SITE_DIR.trim($arBackParametrs['URL_ORDER_SECTION'])));
    	}

    	return $result;
    }

	function GetDirMenuParametrs($dir){
		if(strlen($dir)){
			$file = str_replace('//', '/', $dir.'/.section.php');
			if(file_exists($file)){
				@include($file);
				return $arDirProperties;
			}
		}

		return false;
	}

	function goto404Page(){
		if($_SESSION['SESS_INCLUDE_AREAS']){
			echo '</div>';
		}
		echo '</div>';
		$GLOBALS['APPLICATION']->IncludeFile(SITE_DIR.'404.php', array(), array('MODE' => 'html'));
		die();
	}

	function checkRestartBuffer(){
		static $bRestarted;

		if($bRestarted){
			die();
		}

		if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y')){
			$GLOBALS['APPLICATION']->RestartBuffer();
			$bRestarted = true;
		}
	}

	function UpdateFormEvent(&$arFields){
		if($arFields['ID'] && $arFields['IBLOCK_ID']){
			// find startup form event for this iblock
			$arEventIDs = array('STARTUP_SEND_FORM_'.$arFields['IBLOCK_ID'], 'STARTUP_SEND_FORM_ADMIN_'.$arFields['IBLOCK_ID']);
			$arLangIDs = array('ru', 'en');
			static $arEvents;
			if($arEvents == NULL){
				foreach($arEventIDs as $EVENT_ID){
					foreach($arLangIDs as $LANG_ID){
						$resEvents = CEventType::GetByID($EVENT_ID, $LANG_ID);
						$arEvents[$EVENT_ID][$LANG_ID] = $resEvents->Fetch();
					}
				}
			}
			if($arEventIDs){
				foreach($arEventIDs as $EVENT_ID){
					foreach($arLangIDs as $LANG_ID){
						if($arEvent = &$arEvents[$EVENT_ID][$LANG_ID]){
							if(strpos($arEvent['DESCRIPTION'], $arFields['NAME'].': #'.$arFields['CODE'].'#') === false){
								$arEvent['DESCRIPTION'] = str_replace('#'.$arFields['CODE'].'#', '-', $arEvent['DESCRIPTION']);
								$arEvent['DESCRIPTION'] .= $arFields['NAME'].': #'.$arFields['CODE']."#\n";
								CEventType::Update(array('ID' => $arEvent['ID']), $arEvent);
							}
						}
					}
				}
			}
		}
	}

	static function OnBeforeSubscriptionAddHandler(&$arFields){
		if(!defined('ADMIN_SECTION')){
			global $APPLICATION;
			if(!isset($_REQUEST['licenses_subscribe'])){
				$arBackParametrs = self::GetBackParametrsValues(SITE_ID);
				if($arBackParametrs['DISPLAY_PROCESSING_NOTE'] === 'Y'){
					$GLOBALS['APPLICATION']->ThrowException(GetMessage('ERROR_FORM_LICENSE'));
				}

				return false;
			}
		}
	}

	function checkBasketItems(){
		global $APPLICATION, $arSite, $USER;
		CModule::IncludeModule('iblock');

		if(!defined(ADMIN_SECTION) && !CSite::inDir(SITE_DIR.'/ajax/')){
			$userID = CUser::GetID();
			$userID = ($userID > 0 ? $userID : 0);
			$arBackParametrs = self::GetFrontParametrsValues(SITE_ID);
			$bOrderViewBasket = ($arBackParametrs['ORDER_VIEW'] == 'Y' ? true : false);

			if($bOrderViewBasket && isset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) && is_array($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) && $_SESSION[SITE_ID][$userID]['BASKET_ITEMS']){
				$arIBlocks = $arBasketItemsIDs = array();

				foreach($_SESSION[SITE_ID][$userID]['BASKET_ITEMS'] as $arBasketItem){
					if(isset($arBasketItem['IBLOCK_ID']) && intval($arBasketItem['IBLOCK_ID']) > 0 && !in_array($arBasketItem['IBLOCK_ID'], $arIBlocks)){
						$arIBlocks[] = $arBasketItem['IBLOCK_ID'];
					}
					$arBasketItemsIDs[] = $arBasketItem['ID'];
				}

				$dbRes = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $arIBlocks, 'ID' => $arBasketItemsIDs, 'PROPERTY_FORM_ORDER_VALUE' => false), false, false, array('ID'));
				while($arRes = $dbRes->Fetch()){
					unset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS'][$arRes['ID']]);
				}
				?>
				<script>
					var arBasketItems = <?=CUtil::PhpToJSObject($_SESSION[SITE_ID][$userID]['BASKET_ITEMS'], false)?>;
				</script>
				<?
			}
		}
	}

	function processBascket(){
		global $USER;
		$userID = CUser::GetID();
		$userID = ($userID > 0 ? $userID : 0);

		$_REQUEST['itemData'] = array_map('self::conv', $_REQUEST['itemData']);
		json_decode($_REQUEST['itemData'], true);

		if(isset($_REQUEST['removeAll']) && $_REQUEST['removeAll'] === 'Y'){
			unset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']);
		}
		elseif(isset($_REQUEST['itemData']['ID']) && intval($_REQUEST['itemData']['ID']) > 0){
			if(!is_array($_SESSION[SITE_ID][$userID]['BASKET_ITEMS'])){
				$_SESSION[SITE_ID][$userID]['BASKET_ITEMS'] = array();
			}

			if(isset($_REQUEST['remove']) && $_REQUEST['remove'] === 'Y'){
				if(isset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) && isset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS'][$_REQUEST['itemData']['ID']])){
					unset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS'][$_REQUEST['itemData']['ID']]);
				}
			}
			elseif(isset($_REQUEST['quantity']) && floatval($_REQUEST['quantity']) > 0){
				$_SESSION[SITE_ID][$userID]['BASKET_ITEMS'][$_REQUEST['itemData']['ID']] = (isset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS'][$_REQUEST['itemData']['ID']]) ? $_SESSION[SITE_ID][$userID]['BASKET_ITEMS'][$_REQUEST['itemData']['ID']] : $_REQUEST['itemData']);
				$_SESSION[SITE_ID][$userID]['BASKET_ITEMS'][$_REQUEST['itemData']['ID']]['QUANTITY'] = $_REQUEST['quantity'];

			}
		}
		//unset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']);
		return $_SESSION[SITE_ID][$userID]['BASKET_ITEMS'];
	}

	public static function conv($n){
		return iconv('UTF-8', SITE_CHARSET, $n);
	}

	public static function getDataItem($el){
		$dataItem = array(
			"IBLOCK_ID" => $el['IBLOCK_ID'],
			"ID" => $el['ID'],
			"NAME" => $el['NAME'],
			"DETAIL_PAGE_URL" => $el['DETAIL_PAGE_URL'],
			"PREVIEW_PICTURE" => $el['PREVIEW_PICTURE']['ID'],
			"DETAIL_PICTURE" => $el['DETAIL_PICTURE']['ID'],
			"PROPERTY_FILTER_PRICE_VALUE" => $el['PROPERTIES']['FILTER_PRICE']['VALUE'],
			"PROPERTY_PRICE_VALUE" => $el['PROPERTIES']['PRICE']['VALUE'],
			"PROPERTY_PRICEOLD_VALUE" => $el['PROPERTIES']['PRICEOLD']['VALUE'],
			"PROPERTY_ARTICLE_VALUE" => $el['PROPERTIES']['ARTICLE']['VALUE'],
			"PROPERTY_STATUS_VALUE" => $el['PROPERTIES']['STATUS']['VALUE_ENUM_ID'],
		);

		$dataItem = $GLOBALS['APPLICATION']->ConvertCharsetArray($dataItem, SITE_CHARSET, 'UTF-8');
		$dataItem = htmlspecialchars(json_encode($dataItem));
		return $dataItem;
	}

	static function ShowRSSIcon($href){
		?>
		<style type="text/css">h1{padding-right:50px;}</style>
		<script type="text/javascript">
		$(document).ready(function () {
			$('h1').before('<a class="rss" href="<?=$href?>" title="rss" target="_blank">RSS <i class="fa fa-rss"></i></a>');
		});
		</script>
		<?
		$GLOBALS['APPLICATION']->AddHeadString('<link rel="alternate" type="application/rss+xml" title="rss" href="'.$href.'" />');
	}

	static function getFieldImageData(array &$arItem, array $arKeys, $entity = 'ELEMENT', $ipropertyKey = 'IPROPERTY_VALUES'){
		if (empty($arItem) || empty($arKeys))
            return;

        $entity = (string)$entity;
        $ipropertyKey = (string)$ipropertyKey;

        foreach ($arKeys as $fieldName)
        {
            if(!isset($arItem[$fieldName]) || (!isset($arItem['~'.$fieldName]) || !$arItem['~'.$fieldName]))
                continue;
            $imageData = false;
            $imageId = (int)$arItem['~'.$fieldName];
            if ($imageId > 0)
                $imageData = \CFile::getFileArray($imageId);
            unset($imageId);
            if (is_array($imageData))
            {
                if (isset($imageData['SAFE_SRC']))
                {
                    $imageData['UNSAFE_SRC'] = $imageData['SRC'];
                    $imageData['SRC'] = $imageData['SAFE_SRC'];
                }
                else
                {
                    $imageData['UNSAFE_SRC'] = $imageData['SRC'];
                    $imageData['SRC'] = \CHTTP::urnEncode($imageData['SRC'], 'UTF-8');
                }
                $imageData['ALT'] = '';
                $imageData['TITLE'] = '';

                if ($ipropertyKey != '' && isset($arItem[$ipropertyKey]) && is_array($arItem[$ipropertyKey]))
                {
                    $entityPrefix = $entity.'_'.$fieldName;
                    if (isset($arItem[$ipropertyKey][$entityPrefix.'_FILE_ALT']))
                        $imageData['ALT'] = $arItem[$ipropertyKey][$entityPrefix.'_FILE_ALT'];
                    if (isset($arItem[$ipropertyKey][$entityPrefix.'_FILE_TITLE']))
                        $imageData['TITLE'] = $arItem[$ipropertyKey][$entityPrefix.'_FILE_TITLE'];
                    unset($entityPrefix);
                }
                if ($imageData['ALT'] == '' && isset($arItem['NAME']))
                    $imageData['ALT'] = $arItem['NAME'];
                if ($imageData['TITLE'] == '' && isset($arItem['NAME']))
                    $imageData['TITLE'] = $arItem['NAME'];
            }
            $arItem[$fieldName] = $imageData;
            unset($imageData);
        }
        // print_r($arItem);
        unset($fieldName);
	}


	// DO NOT USE - FOR OLD VERSIONS
	public function showPanel(){
	}

	// DO NOT USE - FOR OLD VERSIONS
	function SetSeoMetaTitle(){
		global $arSite;
		if(!CSite::inDir(SITE_DIR.'index.php')){
			$PageH1 = $GLOBALS['APPLICATION']->GetTitle();
			if(!strlen($PageMetaTitleBrowser = $GLOBALS['APPLICATION']->GetPageProperty('title'))){
				if(!strlen($DirMetaTitleBrowser = $GLOBALS['APPLICATION']->GetDirProperty('title'))){
					$GLOBALS['APPLICATION']->SetPageProperty('title', $PageH1.((strlen($PageH1) && strlen($arSite['SITE_NAME'])) ? ' - ' : '' ).$arSite['SITE_NAME']);
				}
			}
		}
		else{
			if(!strlen($PageMetaTitleBrowser = $GLOBALS['APPLICATION']->GetPageProperty('title'))){
				if(!strlen($DirMetaTitleBrowser = $GLOBALS['APPLICATION']->GetDirProperty('title'))){
					$PageH1 = $GLOBALS['APPLICATION']->GetTitle();
					$GLOBALS['APPLICATION']->SetPageProperty('title', $arSite['SITE_NAME'].((strlen($arSite['SITE_NAME']) && strlen($PageH1)) ? ' - ' : '' ).$PageH1);
				}
			}
		}
	}

	// DO NOT USE - FOR OLD VERSIONS
	function linkShareImage($previewPictureID = false, $detailPictureID = false){
		if($linkSaherImageID = ($detailPictureID ? $detailPictureID : ($previewPictureID ? $previewPictureID : false))){
			$GLOBALS['APPLICATION']->AddHeadString('<link rel="image_src" href="'.CFile::GetPath($linkSaherImageID).'"  />', true);
		}
	}

	// DO NOT USE - FOR OLD VERSIONS
	function CheckAdditionalChainInMultiLevel(&$arResult, &$arParams, &$arElement){
		$GLOBALS['APPLICATION']->arAdditionalChain = false;
		if($arParams['INCLUDE_IBLOCK_INTO_CHAIN'] == 'Y' && isset(CCache::$arIBlocksInfo[$arParams['IBLOCK_ID']]['NAME'])){
			$GLOBALS['APPLICATION']->AddChainItem(CCache::$arIBlocksInfo[$arParams['IBLOCK_ID']]['NAME'], $arElement['~LIST_PAGE_URL']);
		}
		if($arParams['ADD_SECTIONS_CHAIN'] == 'Y'){
			if($arSection = CCache::CIBlockSection_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arElement['IBLOCK_ID']), 'MULTI' => 'N')), self::GetCurrentSectionFilter($arResult['VARIABLES'], $arParams), false, array('ID', 'NAME'))){
				$rsPath = CIBlockSection::GetNavChain($arParams['IBLOCK_ID'], $arSection['ID']);
				$rsPath->SetUrlTemplates('', $arParams['SECTION_URL']);
				while($arPath = $rsPath->GetNext()){
					$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arParams['IBLOCK_ID'], $arPath['ID']);
					$arPath['IPROPERTY_VALUES'] = $ipropValues->getValues();
					$arSection['PATH'][] = $arPath;
					$arSection['SECTION_URL'] = $arPath['~SECTION_PAGE_URL'];
				}

				foreach($arSection['PATH'] as $arPath){
					if($arPath['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'] != ''){
						$GLOBALS['APPLICATION']->AddChainItem($arPath['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'], $arPath['~SECTION_PAGE_URL']);
					}
					else{
						$GLOBALS['APPLICATION']->AddChainItem($arPath['NAME'], $arPath['~SECTION_PAGE_URL']);
					}
				}
			}
		}
		if($arParams['ADD_ELEMENT_CHAIN'] == 'Y'){
			$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arParams['IBLOCK_ID'], $arElement['ID']);
			$arElement['IPROPERTY_VALUES'] = $ipropValues->getValues();
			if($arElement['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''){
				$GLOBALS['APPLICATION']->AddChainItem($arElement['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']);
			}
			else{
				$GLOBALS['APPLICATION']->AddChainItem($arElement['NAME']);
			}
		}
	}
}
?>