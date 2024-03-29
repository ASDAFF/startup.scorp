<?
/**
 * Copyright (c) 22/12/2019 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');

$moduleClass = "CScorp";
$moduleID = "startup.scorp";
global  $APPLICATION;
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/options.php");
$APPLICATION->SetAdditionalCSS($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$moduleID."/css/style.css");
$APPLICATION->AddHeadScript($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$moduleID."/js/jquery.cookie.js");
CModule::IncludeModule($moduleID);
IncludeModuleLangFile(__FILE__);

$RIGHT = $APPLICATION->GetGroupRight($moduleID);
$GLOBALS['APPLICATION']->SetTitle(GetMessage('SCORP_OPTIONS_TITLE_MAIN'));

if($RIGHT >= "R"){
	$by = "id";
	$sort = "asc";

	$arSites = array();
	$db_res = CSite::GetList($by , $sort ,array("ACTIVE"=>"Y"));
	while($res = $db_res->Fetch()){
		$res['DIR'] = $res['DIR'] = str_replace('//', '/', '/'.str_replace('//', '/', $res['DIR'].'/'));
		$arSites[] = $res;
	}

	$arTabs = array();
	foreach($arSites as $key => $arSite){
		$arBackParametrs = $moduleClass::GetBackParametrsValues($arSite["ID"], false);
		$arTabs[] = array(
			"DIV" => "edit".($key+1),
			"TAB" => GetMessage("MAIN_OPTIONS_SITE_TITLE", array("#SITE_NAME#" => $arSite["NAME"], "#SITE_ID#" => $arSite["ID"])),
			"ICON" => "settings",
			"TITLE" => GetMessage("MAIN_OPTIONS_TITLE"),
			"PAGE_TYPE" => "site_settings",
			"SITE_ID" => $arSite["ID"],
			"SITE_DIR" => $arSite["DIR"],
			"OPTIONS" => $arBackParametrs,
		);
	}

	$tabControl = new CAdminTabControl("tabControl", $arTabs);

	if($REQUEST_METHOD == "POST" && strlen($Update.$Apply.$RestoreDefaults) && $RIGHT >= "W" && check_bitrix_sessid()){
		global $APPLICATION;
		if(strlen($RestoreDefaults)){
			COption::RemoveOption($moduleID, "OPTION");
			COption::RemoveOption($moduleID, "NeedGenerateCustomTheme");
			$APPLICATION->DelGroupRight($moduleID);
		}
		else{
			COption::RemoveOption($moduleID, "sid");
			foreach($arTabs as $key => $arTab){
				$optionsSiteID = $arTab["SITE_ID"];
				foreach($moduleClass::$arParametrsList as $blockCode => $arBlock){
					foreach($arBlock["OPTIONS"] as $optionCode => $arOption){
						$bMultiple = $arOption['TYPE'] === 'multiselectbox';
						if($optionCode == "BASE_COLOR_CUSTOM"){
							$moduleClass::CheckColor($_REQUEST[$optionCode."_".$optionsSiteID]);
						}
						if($optionCode == "BASE_COLOR" && $_REQUEST[$optionCode."_".$optionsSiteID] === 'CUSTOM'){
							COption::SetOptionString($moduleID, "NeedGenerateCustomTheme", 'Y', '', $arTab["SITE_ID"]);
						}

						$newVal = $_REQUEST[$optionCode."_".$optionsSiteID];

						if($optionCode == "YA_COUNTER_ID" && strlen($newVal)){
							$newVal = str_replace('yaCounter', '', $newVal);
						}

						if($optionCode == "USE_CAPTCHA_FORM" && $newVal == 'RECAPTCHA'){
							$siteKeyCode = 'RECAPTCHA_SITE_KEY_'.$optionsSiteID;
							$secretKeyCode = 'RECAPTCHA_SECRET_KEY_'.$optionsSiteID;
							if(!(isset($_REQUEST[$siteKeyCode]) && strlen($_REQUEST[$siteKeyCode]) && isset($_REQUEST[$secretKeyCode]) && strlen($_REQUEST[$secretKeyCode]))){
								$newVal = 'IMAGE';
							}
						}

						if($arOption["TYPE"] == "checkbox"){
							if(!strlen($newVal) || $newVal != "Y"){
								$newVal = "N";
							}
						}
						$arTab["OPTIONS"][$optionCode] = $newVal;
						COption::SetOptionString($moduleID, $optionCode, ($bMultiple ? serialize($newVal) : $newVal), "", $arTab["SITE_ID"]);
					}
				}
				COption::SetOptionString($moduleID, "OPTIONS", serialize((array)$arTab["OPTIONS"]), "", $arTab["SITE_ID"]);
				$moduleClass::ClearSomeComponentsCache($optionsSiteID);
				$arTabs[$key] = $arTab;
			}
		}

		if($compositeMode = $moduleClass::IsCompositeEnabled()){
			$obCache = new CPHPCache();
			$obCache->CleanDir('', 'html_pages');
			$moduleClass::EnableComposite($compositeMode === 'AUTO_COMPOSITE');
		}

		$APPLICATION->RestartBuffer();
	}

	CJSCore::Init(array("jquery"));
	CAjax::Init();
	$tabControl->Begin();
	?>
	<style type="text/css">
	*[id^=wait_window_div],.waitwindow{display:none;}
	</style>
	<form method="post" action="<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?=LANGUAGE_ID?>">
		<?=bitrix_sessid_post();?>
		<?
		foreach($arTabs as $key => $arTab){
			$tabControl->BeginNextTab();
			if($arTab["SITE_ID"]){
				$optionsSiteID = $arTab["SITE_ID"];
				$optionsSiteDir = $arTab["SITE_DIR"];
				foreach($moduleClass::$arParametrsList as $blockCode => $arBlock){
					?>
					<tr class="heading"><td colspan="2"><?=$arBlock["TITLE"]?></td></tr>
					<?
					foreach($arBlock["OPTIONS"] as $optionCode => $arOption){
						if(array_key_exists($optionCode, $arTab["OPTIONS"])){
							$arControllerOption = CControllerClient::GetInstalledOptions($module_id);
							if(isset($arOption["NOTE"])){
								if($optionCode == 'GOALS_NOTE'){
									$FORMS_GOALS_LIST = '';
									if(CCache::$arIBlocks[$optionsSiteID]['startup_scorp_form'] && is_array(CCache::$arIBlocks[$optionsSiteID]['startup_scorp_form'])){
										foreach(CCache::$arIBlocks[$optionsSiteID]['startup_scorp_form'] as $arIDs){
											if($arIDs && is_array($arIDs)){
												foreach($arIDs as $IBLOCK_ID){
													if(CCache::$arIBlocksInfo && CCache::$arIBlocksInfo[$IBLOCK_ID] && is_array(CCache::$arIBlocksInfo[$IBLOCK_ID])){
														$FORMS_GOALS_LIST .= CCache::$arIBlocksInfo[$IBLOCK_ID]['NAME'].' - <i>goal_webform_success_'.$IBLOCK_ID.'</i><br />';
													}
												}
											}
										}
									}
									$arOption["NOTE"] = str_replace('#FORMS_GOALS_LIST#', $FORMS_GOALS_LIST, $arOption["NOTE"]);
								}
								?>
								<tr data-optioncode="<?=$optionCode?>">
									<td colspan="2" align="center">
										<?=BeginNote('align="center"');?>
										<?=$arOption["NOTE"]?>
										<?=EndNote();?>
									</td>
								</tr>
								<?
							}
							else{
								$optionName = $arOption["TITLE"];
								$optionType = $arOption["TYPE"];
								$optionList = $arOption["LIST"];
								$optionDefault = $arOption["DEFAULT"];
								$optionVal = $arTab["OPTIONS"][$optionCode];
								$optionSize = $arOption["SIZE"];
								$optionCols = $arOption["COLS"];
								$optionRows = $arOption["ROWS"];
								$optionChecked = $optionVal == "Y" ? "checked" : "";
								$optionDisabled = isset($arControllerOption[$optionCode]) || array_key_exists("DISABLED", $arOption) && $arOption["DISABLED"] == "Y" ? "disabled" : "";
								$optionSup_text = array_key_exists("SUP", $arOption) ? $arOption["SUP"] : "";
								$optionController = isset($arControllerOption[$optionCode]) ? "title='".GetMessage("MAIN_ADMIN_SET_CONTROLLER_ALT")."'" : ""
								?>
								<tr data-optioncode="<?=$optionCode?>">
									<td class="<?=(in_array($optionType, array("multiselectbox", "textarea", "statictext", "statichtml")) ? "adm-detail-valign-top" : "")?>" width="50%">
										<?if($optionType == "checkbox"):?>
											<label for="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>"><?=$optionName?></label>
										<?else:?>
											<?=$optionName.($optionCode == "BASE_COLOR_CUSTOM" ? ' #' : '')?>
										<?endif;?>
										<?if(strlen($optionSup_text)):?>
											<span class="required"><sup><?=$optionSup_text?></sup></span>
										<?endif;?>
									</td>
									<td width="50%">
										<?if($optionType == "checkbox"):?>
											<input type="checkbox" <?=$optionController?> id="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>" name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>" value="Y" <?=$optionChecked?> <?=$optionDisabled?> <?=(strlen($optionDefault) ? $optionDefault : "")?>>
										<?elseif($optionType == "text" || $optionType == "password"):?>
											<input type="<?=$optionType?>" <?=$optionController?> size="<?=$optionSize?>" maxlength="255" value="<?=htmlspecialcharsbx($optionVal)?>" name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>" <?=$optionDisabled?> <?=($optionCode == "password" ? "autocomplete='off'" : "")?>>
										<?elseif($optionType == "selectbox"):?>
											<?
											if(!is_array($optionList)) $optionList = (array)$optionList;
											$arr_keys = array_keys($optionList);
											?>
											<select name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>" <?=$optionController?> <?=$optionDisabled?>>
												<?for($j = 0, $c = count($arr_keys); $j < $c; ++$j):?>
													<option value="<?=$arr_keys[$j]?>" <?if($optionVal == $arr_keys[$j]) echo "selected"?>><?=htmlspecialcharsbx((is_array($optionList[$arr_keys[$j]]) ? $optionList[$arr_keys[$j]]["TITLE"] : $optionList[$arr_keys[$j]]))?></option>
												<?endfor;?>
											</select>
										<?elseif($optionType == "multiselectbox"):?>
											<?
											if(!is_array($optionList)) $optionList = (array)$optionList;
											$arr_keys = array_keys($optionList);
											if(!is_array($optionVal)) $optionVal = (array)$optionVal;
											?>
											<select size="<?=$optionSize?>" <?=$optionController?> <?=$optionDisabled?> multiple name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>[]" >
												<?for($j = 0, $c = count($arr_keys); $j < $c; ++$j):?>
													<option value="<?=$arr_keys[$j]?>" <?if(in_array($arr_keys[$j], $optionVal)) echo "selected"?>><?=htmlspecialcharsbx((is_array($optionList[$arr_keys[$j]]) ? $optionList[$arr_keys[$j]]["TITLE"] : $optionList[$arr_keys[$j]]))?></option>
												<?endfor;?>
											</select>
										<?elseif($optionType == "textarea"):?>
											<textarea <?=$optionController?> <?=$optionDisabled?> rows="<?=$optionRows?>" cols="<?=$optionCols?>" name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>"><?=htmlspecialcharsbx($optionVal)?></textarea>
										<?elseif($optionType == "statictext"):?>
											<?=htmlspecialcharsbx($optionVal)?>
										<?elseif($optionType == "statichtml"):?>
											<?=$optionVal?>
										<?elseif($optionType === 'includefile'):?>
											<?
											if(!is_array($arOption['INCLUDEFILE'])){
												$arOption['INCLUDEFILE'] = array($arOption['INCLUDEFILE']);
											}
											foreach($arOption['INCLUDEFILE'] as $includefile){
												$includefile = str_replace('//', '/', str_replace('#SITE_DIR#', $optionsSiteDir, $includefile));
												if(strpos($includefile, '#') === false){
													$template = (isset($arOption['TEMPLATE']) && strlen($arOption['TEMPLATE']) ? 'include_area.php' : $arOption['TEMPLATE']);
													$href = (!strlen($includefile) ? "javascript:;" : "javascript: new BX.CAdminDialog({'content_url':'/bitrix/admin/public_file_edit.php?site=".$optionsSiteID."&bxpublic=Y&from=includefile&templateID=".TEMPLATE_NAME."&path=".$includefile."&lang=".LANGUAGE_ID."&template=".$template."&subdialog=Y&siteTemplateId=".TEMPLATE_NAME."','width':'1009','height':'503'}).Show();");
													?><a class="adm-btn" href="<?=$href?>" name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>" title="<?=GetMessage('OPTIONS_EDIT_BUTTON_TITLE')?>"><?=GetMessage('OPTIONS_EDIT_BUTTON_TITLE')?></a>&nbsp;<?
												}
											}
											?>
										<?endif;?>
									</td>
								</tr>
								<?
							}
						}
					}
				}
			}
		}
		?>
		<?
		if($REQUEST_METHOD == "POST" && strlen($Update.$Apply.$RestoreDefaults) && check_bitrix_sessid()){
			if(strlen($Update) && strlen($_REQUEST["back_url_settings"]))
				LocalRedirect($_REQUEST["back_url_settings"]);
			else
				LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());
		}
		$tabControl->Buttons();
		?>
		<input <?if($RIGHT < "W") echo "disabled"?> type="submit" name="Apply" class="submit-btn" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
		<?if(strlen($_REQUEST["back_url_settings"])):?>
			<input type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?=htmlspecialchars(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
			<input type="hidden" name="back_url_settings" value="<?=htmlspecialchars($_REQUEST["back_url_settings"])?>">
		<?endif;?>
		<script type="text/javascript">
		function checkGoalsNote(){
			var inUAC = $('.adm-detail-content-table:visible').first().find('tr[data-optioncode=USE_YA_COUNTER] input');
			var itrYACID = $('.adm-detail-content-table:visible').first().find('tr[data-optioncode=YA_COUNTER_ID]');
			var itrGNote = $('.adm-detail-content-table:visible').first().find('tr[data-optioncode=GOALS_NOTE]');
			var itrUFG = $('.adm-detail-content-table:visible').first().find('tr[data-optioncode=USE_FORMS_GOALS]');
			var itrUSG = $('.adm-detail-content-table:visible').first().find('tr[data-optioncode=USE_SALE_GOALS]');
			var itrUDG = $('.adm-detail-content-table:visible').first().find('tr[data-optioncode=USE_DEBUG_GOALS]');

			if(inUAC.length && inUAC.attr('checked')){
				var bShowNote = 3;

				if(itrUFG.find('select').val().indexOf('NONE') == -1){
					itrGNote.find('[data-goal=form]').show();
				}
				else{
					itrGNote.find('[data-goal=form]').hide();
					--bShowNote;
				}

				if(itrUSG.find('input').attr('checked')){
					itrGNote.find('[data-goal=sale]').show();
				}
				else{
					itrGNote.find('[data-goal=sale]').hide();
					--bShowNote;
				}

				if(itrUDG.find('input').attr('checked')){
					itrGNote.find('[data-goal=debug]').show();
				}
				else{
					itrGNote.find('[data-goal=debug]').hide();
					--bShowNote;
				}

				if(bShowNote){
					itrGNote.fadeIn();
				}
				else{
					itrGNote.fadeOut();
				}
			}
			else{
				itrGNote.fadeOut();
			}
		}

		$(document).ready(function() {
			$('select[name^="SCROLLTOTOP_TYPE"]').change(function() {
				var posSelect = $(this).parents('table').first().find('select[name^="SCROLLTOTOP_POSITION"]');
				if(posSelect){
					var posSelectTr = posSelect.parents('tr').first();
					var isNone = $(this).val().indexOf('NONE') != -1;
					if(isNone){
						if(posSelectTr.is(':visible')){
							posSelectTr.fadeOut();
						}
					}
					else{
						if(!posSelectTr.is(':visible')){
							posSelectTr.fadeIn();
						}
						var isRound = $(this).val().indexOf('ROUND') != -1;
						var isTouch = posSelect.val().indexOf('TOUCH') != -1;
						if(isRound && !!posSelect){
							posSelect.find('option[value^="TOUCH"]').attr('disabled', 'disabled');
							if(isTouch){
								posSelect.val(posSelect.find('option[value^="PADDING"]').first().attr('value'));
							}
						}
						else{
							posSelect.find('option[value^="TOUCH"]').removeAttr('disabled');
						}
					}
				}
			});

			$('input[name^="DISPLAY_PROCESSING_NOTE"]').change(function() {
				var itrChecked = $(this).parents('table').first().find('tr[data-optioncode=PROCESSING_NOTE_CHECKED]');
				var itrGFile = $(this).parents('table').first().find('tr[data-optioncode=FILE_PROCESSING_NOTE]');
				var ischecked = $(this).attr('checked');
				if(ischecked){
					itrChecked.fadeIn();
					itrGFile.fadeIn();
				}
				else{
					itrChecked.fadeOut();
					itrGFile.fadeOut();
				}
			});

			$('select[name^="USE_CAPTCHA_FORM"]').change(function() {
				var isReCaptcha = $(this).val().indexOf('RECAPTCHA') != -1;
				var itrRNote = $(this).parents('table').first().find('tr[data-optioncode=RECAPTCHA_NOTE]');
				var itrRSK = $(this).parents('table').first().find('tr[data-optioncode=RECAPTCHA_SITE_KEY]');
				var itrRSRK = $(this).parents('table').first().find('tr[data-optioncode=RECAPTCHA_SECRET_KEY]');
				if(isReCaptcha){
					itrRSK.fadeIn();
					itrRSRK.fadeIn();
					itrRNote.fadeIn();
				}
				else{
					itrRSK.fadeOut();
					itrRSRK.fadeOut();
					itrRNote.fadeOut();
				}

				checkGoalsNote();
			});

			$('input[name^="USE_YA_COUNTER"]').change(function() {
				var itrYCC = $(this).parents('table').first().find('tr[data-optioncode=YANDEX_COUNTER]');
				var itrYACID = $(this).parents('table').first().find('tr[data-optioncode=YA_COUNTER_ID]');
				var itrUFG = $(this).parents('table').first().find('tr[data-optioncode=USE_FORMS_GOALS]');
				var itrUSG = $(this).parents('table').first().find('tr[data-optioncode=USE_SALE_GOALS]');
				var itrUDG = $(this).parents('table').first().find('tr[data-optioncode=USE_DEBUG_GOALS]');
				var itrGNote = $(this).parents('table').first().find('tr[data-optioncode=GOALS_NOTE]');
				var ischecked = $(this).attr('checked');
				if(typeof(ischecked) != 'undefined'){
					itrYCC.fadeIn();
					itrYACID.fadeIn();
					itrUFG.fadeIn();
					var valUFG = itrUFG.find('select').val();
					if(valUFG.indexOf('NONE') == -1){
						var isCommon = valUFG.indexOf('COMMON') != -1;
						if(isCommon){
							itrGNote.find('[data-value=common]').show();
							itrGNote.find('[data-value=single]').hide();
						}
						else{
							itrGNote.find('[data-value=common]').hide();
							itrGNote.find('[data-value=single]').show();
						}
					}
					itrUSG.fadeIn();
					itrUDG.fadeIn();
				}
				else{
					itrYCC.fadeOut();
					itrYACID.fadeOut();
					itrUFG.fadeOut();
					itrUSG.fadeOut();
					itrUDG.fadeOut();
					itrGNote.fadeOut();
				}

				checkGoalsNote();
			});

			$('select[name^="USE_FORMS_GOALS"]').change(function() {
				var inUAC = $(this).parents('table').first().find('tr[data-optioncode=USE_YA_COUNTER] input');
				if(inUAC.length && inUAC.attr('checked')){
					var isNone = $(this).val().indexOf('NONE') != -1;
					var isCommon = $(this).val().indexOf('COMMON') != -1;
					var itrGNote = $(this).parents('table').first().find('tr[data-optioncode=GOALS_NOTE]');
					if(!isNone){
						if(isCommon){
							itrGNote.find('[data-value=common]').show();
							itrGNote.find('[data-value=single]').hide();
						}
						else{
							itrGNote.find('[data-value=common]').hide();
							itrGNote.find('[data-value=single]').show();
						}
						itrGNote.find('[data-goal=form]').show();
					}
					else{
						itrGNote.find('[data-goal=form]').hide();
					}
				}

				checkGoalsNote();
			});

			$('input[name^="USE_SALE_GOALS"]').change(function() {
				var inUAC = $(this).parents('table').first().find('tr[data-optioncode=USE_YA_COUNTER] input');
				if(inUAC.length && inUAC.attr('checked')){
					var itrGNote = $(this).parents('table').first().find('tr[data-optioncode=GOALS_NOTE]');
					var ischecked = $(this).attr('checked');
					if(typeof(ischecked) != 'undefined'){
						itrGNote.find('[data-goal=sale]').show();
					}
					else{
						itrGNote.find('[data-goal=sale]').hide();
					}
				}

				checkGoalsNote();
			});

			$('input[name^="USE_DEBUG_GOALS"]').change(function() {
				var inUAC = $(this).parents('table').first().find('tr[data-optioncode=USE_YA_COUNTER] input');
				if(inUAC.length && inUAC.attr('checked')){
					var itrGNote = $(this).parents('table').first().find('tr[data-optioncode=GOALS_NOTE]');
					var ischecked = $(this).attr('checked');
					if(typeof(ischecked) != 'undefined'){
						itrGNote.find('[data-goal=debug]').show();
					}
					else{
						itrGNote.find('[data-goal=debug]').hide();
					}
				}

				checkGoalsNote();
			});

			$('select[name^="SCROLLTOTOP_TYPE"]').change();
			$('input[name^="DISPLAY_PROCESSING_NOTE"]').change();
			$('select[name^="USE_CAPTCHA_FORM"]').change();
			$('input[name^="USE_YA_COUNTER"]').change();
			$('select[name^="USE_FORMS_GOALS"]').change();
			$('input[name^="USE_SALE_GOALS"]').change();
			$('input[name^="USE_DEBUG_GOALS"]').change();
		});
		</script>
		<?if($moduleClass::IsCompositeEnabled()):?>
			<div class="adm-info-message"><?=GetMessage("WILL_CLEAR_HTML_CACHE_NOTE")?></div><div style="clear:both;"></div>
			<script type="text/javascript">
			$(document).ready(function() {
				$('input[name^="THEME_SWITCHER"]').change(function() {
					var ischecked = $(this).attr('checked');
					if(typeof(ischecked) != 'undefined'){
						if(!confirm("<?=GetMessage("NO_COMPOSITE_NOTE")?>")){
							$(this).removeAttr('checked');
						}
					}
				});
			});
			</script>
		<?endif;?>
	</form>
	<?$tabControl->End();?>
	<?
}
else{
	CAdminMessage::ShowMessage(GetMessage('NO_RIGHTS_FOR_VIEWING'));
}