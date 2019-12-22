<?if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true ) die();?>
<?$this->setFrameMode(true);?>
<?
$arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y", "ACTIVE_DATE" => "Y", "DEPTH_LEVEL" => 1);
$arSections = CCache::CIBLockSection_GetList(array("SORT" => "ASC", "NAME" => "ASC", "CACHE" => array("TAG" => CCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "GROUP" => array("ID"), "MULTI" => "N")), $arFilter, false, array("ID", "NAME", 'IBLOCK_ID', 'SECTION_PAGE_URL'));

// rss
if($arParams['USE_RSS'] !== 'N'){
	CScorp::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);
}
?>
<?if(!$arSections):?>
	<div class="alert alert-warning"><?=GetMessage("SECTION_EMPTY")?></div>
<?else:?>
	<?foreach($arSections as $arSection):?>
		<?
		// edit/add/delete buttons for edit mode
		$arSectionButtons = CIBlock::GetPanelButtons($arSection['IBLOCK_ID'], 0, $arSection['ID'], array('SESSID' => false, 'CATALOG' => true));
		$this->AddEditAction($arSection['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['IBLOCK_ID'], 'SECTION_EDIT'));
		$this->AddDeleteAction($arSection['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
		<div class="row projectslist" id="<?=$this->GetEditAreaId($arSection['ID'])?>">
			<div class="col-md-12">
				<a href="<?=$arSection['SECTION_PAGE_URL']?>" class="btn btn-default white btn-xs"><span><?=GetMessage('ALL_PROJECTS')?></span></a>
				<h3 class="underline"><?=$arSection["NAME"]?></h3>
				<?// section elements?>
				<?$arItemFilter = array("SECTION_ID" => $arSection["ID"], 'INCLUDE_SUBSECTIONS' => 'Y');?>
				<?if(strlen($arParams["FILTER_NAME"])):?>
					<?$GLOBALS[$arParams["FILTER_NAME"]] = array_merge((array)$GLOBALS[$arParams["FILTER_NAME"]], $arItemFilter);?>
				<?else:?>
					<?$arParams["FILTER_NAME"] = "arrFilter";?>
					<?$GLOBALS[$arParams["FILTER_NAME"]] = $arItemFilter;?>
				<?endif;?>
				<?$APPLICATION->IncludeComponent(
					"bitrix:news.list",
					"projects",
					Array(
						"S_ASK_QUESTION" => $arParams["S_ASK_QUESTION"],
						"S_ORDER_PROJECT" => $arParams["S_ORDER_PROJECT"],
						"T_GALLERY" => $arParams["T_GALLERY"],
						"T_DOCS" => $arParams["T_DOCS"],
						"T_PROJECTS" => $arParams["T_PROJECTS"],
						"T_CHARACTERISTICS" => $arParams["T_CHARACTERISTICS"],
						"SHOW_DETAIL" => $arParams["SHOW_DETAIL"],
						"SHOW_IMAGE" => $arParams["SHOW_IMAGE"],
						"IMAGE_POSITION" => $arParams["IMAGE_POSITION"],
						"IBLOCK_TYPE"	=>	$arParams["IBLOCK_TYPE"],
						"IBLOCK_ID"	=>	$arParams["IBLOCK_ID"],
						"NEWS_COUNT"	=>	2,
						"SORT_BY1"	=>	$arParams["SORT_BY1"],
						"SORT_ORDER1"	=>	$arParams["SORT_ORDER1"],
						"SORT_BY2"	=>	$arParams["SORT_BY2"],
						"SORT_ORDER2"	=>	$arParams["SORT_ORDER2"],
						"FIELD_CODE"	=>	$arParams["LIST_FIELD_CODE"],
						"PROPERTY_CODE"	=>	$arParams["LIST_PROPERTY_CODE"],
						"DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
						"SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
						"IBLOCK_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
						"DISPLAY_PANEL"	=>	$arParams["DISPLAY_PANEL"],
						"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
						"SET_TITLE"	=>	'N',
						"SET_STATUS_404" => $arParams["SET_STATUS_404"],
						"INCLUDE_IBLOCK_INTO_CHAIN"	=>	$arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
						"CACHE_TYPE"	=>	$arParams["CACHE_TYPE"],
						"CACHE_TIME"	=>	$arParams["CACHE_TIME"],
						"CACHE_FILTER"	=>	$arParams["CACHE_FILTER"],
						"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
						"DISPLAY_TOP_PAGER"	=>	"N",
						"DISPLAY_BOTTOM_PAGER"	=>	"N",
						"PAGER_TITLE"	=>	$arParams["PAGER_TITLE"],
						"PAGER_TEMPLATE"	=>	$arParams["PAGER_TEMPLATE"],
						"PAGER_SHOW_ALWAYS"	=>	$arParams["PAGER_SHOW_ALWAYS"],
						"PAGER_DESC_NUMBERING"	=>	$arParams["PAGER_DESC_NUMBERING"],
						"PAGER_DESC_NUMBERING_CACHE_TIME"	=>	$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
						"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
						"DISPLAY_DATE"	=>	$arParams["DISPLAY_DATE"],
						"DISPLAY_NAME"	=>	"Y",
						"DISPLAY_PICTURE"	=>	$arParams["DISPLAY_PICTURE"],
						"DISPLAY_PREVIEW_TEXT"	=>	$arParams["DISPLAY_PREVIEW_TEXT"],
						"PREVIEW_TRUNCATE_LEN"	=>	$arParams["PREVIEW_TRUNCATE_LEN"],
						"ACTIVE_DATE_FORMAT"	=>	$arParams["LIST_ACTIVE_DATE_FORMAT"],
						"USE_PERMISSIONS"	=>	$arParams["USE_PERMISSIONS"],
						"GROUP_PERMISSIONS"	=>	$arParams["GROUP_PERMISSIONS"],
						"FILTER_NAME"	=>	$arParams["FILTER_NAME"],
						"HIDE_LINK_WHEN_NO_DETAIL"	=>	$arParams["HIDE_LINK_WHEN_NO_DETAIL"],
						"CHECK_DATES"	=>	$arParams["CHECK_DATES"],
						"NO_SHOW_MORE"	=>	"Y",
						"INCLUDE_SUBSECTIONS" => "Y",
						"SHOW_DETAIL_LINK" => $arParams["SHOW_DETAIL_LINK"],
					),
					$component
				);?>
				<hr />
			</div>
		</div>
		<br />
		<br />
	<?endforeach;?>
<?endif;?>