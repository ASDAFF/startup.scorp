<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?
// get element
$arItemFilter = CScorp::GetCurrentElementFilter($arResult['VARIABLES'], $arParams);
$arElement = CCache::CIblockElement_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'N')), $arItemFilter, false, false, array('ID', 'PREVIEW_TEXT', 'IBLOCK_SECTION_ID', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'DETAIL_PAGE_URL', 'LIST_PAGE_URL', 'PROPERTY_LINK_PROJECTS', 'PROPERTY_LINK_GOODS', 'PROPERTY_LINK_REVIEWS', 'PROPERTY_LINK_STAFF', 'PROPERTY_LINK_SERVICES'));
?>
<?if(!$arElement && $arParams['SET_STATUS_404'] !== 'Y'):?>
	<div class="alert alert-warning"><?=GetMessage("ELEMENT_NOTFOUND")?></div>
<?elseif(!$arElement && $arParams['SET_STATUS_404'] === 'Y'):?>
	<?CScorp::goto404Page();?>
<?else:?>
	<?// rss
	if($arParams['USE_RSS'] !== 'N'){
		CScorp::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);
	}?>
	<?CScorp::AddMeta(
		array(
			'og:description' => $arElement['PREVIEW_TEXT'],
			'og:image' => (($arElement['PREVIEW_PICTURE'] || $arElement['DETAIL_PICTURE']) ? CFile::GetPath(($arElement['PREVIEW_PICTURE'] ? $arElement['PREVIEW_PICTURE'] : $arElement['DETAIL_PICTURE'])) : false),
		)
	);?>
	<div class="detail <?=($templateName = $component->{'__template'}->{'__name'})?>">
		<?$APPLICATION->IncludeComponent(
			"bitrix:news.detail",
			"study",
			Array(
				"S_ASK_QUESTION" => $arParams["S_ASK_QUESTION"],
				"S_ORDER_STUDY" => $arParams["S_ORDER_STUDY"],
				"T_GALLERY" => $arParams["T_GALLERY"],
				"T_DOCS" => $arParams["T_DOCS"],
				"T_GOODS" => $arParams["T_GOODS"],
				"T_SERVICES" => $arParams["T_SERVICES"],
				"T_PROJECTS" => $arParams["T_PROJECTS"],
				"T_REVIEWS" => $arParams["T_REVIEWS"],
				"T_STAFF" => $arParams["T_STAFF"],
				"T_VIDEO" => $arParams["T_VIDEO"],
				"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
				"DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
				"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
				"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"FIELD_CODE" => $arParams["DETAIL_FIELD_CODE"],
				"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
				"DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
				"SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
				"META_KEYWORDS" => $arParams["META_KEYWORDS"],
				"META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
				"BROWSER_TITLE" => $arParams["BROWSER_TITLE"],
				"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
				"SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
				"STRICT_SECTION_CHECK" => $arParams["STRICT_SECTION_CHECK"],
				"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
				"SET_TITLE" => $arParams["SET_TITLE"],
				"SET_STATUS_404" => $arParams["SET_STATUS_404"],
				"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
				"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
				"ADD_ELEMENT_CHAIN" => $arParams["ADD_ELEMENT_CHAIN"],
				"ACTIVE_DATE_FORMAT" => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
				"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
				"DISPLAY_TOP_PAGER" => $arParams["DETAIL_DISPLAY_TOP_PAGER"],
				"DISPLAY_BOTTOM_PAGER" => $arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
				"PAGER_TITLE" => $arParams["DETAIL_PAGER_TITLE"],
				"PAGER_SHOW_ALWAYS" => "N",
				"PAGER_TEMPLATE" => $arParams["DETAIL_PAGER_TEMPLATE"],
				"PAGER_SHOW_ALL" => $arParams["DETAIL_PAGER_SHOW_ALL"],
				"CHECK_DATES" => $arParams["CHECK_DATES"],
				"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
				"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
				"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
				"USE_SHARE" 			=> $arParams["USE_SHARE"],
				"SHARE_HIDE" 			=> $arParams["SHARE_HIDE"],
				"SHARE_TEMPLATE" 		=> $arParams["SHARE_TEMPLATE"],
				"SHARE_HANDLERS" 		=> $arParams["SHARE_HANDLERS"],
				"SHARE_SHORTEN_URL_LOGIN"	=> $arParams["SHARE_SHORTEN_URL_LOGIN"],
				"SHARE_SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
			),
			$component
		);?>

		<?// projects links?>
		<?if(in_array('LINK_PROJECTS', $arParams['DETAIL_PROPERTY_CODE']) && $arElement['PROPERTY_LINK_PROJECTS_VALUE']):?>
			<?$arProjects = CCache::CIBlockElement_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag(CCache::$arIBlocks[SITE_ID]['aspro_scorp_content']['aspro_scorp_projects'][0]), 'MULTI' => 'Y')), array('ID' => $arElement['PROPERTY_LINK_PROJECTS_VALUE'], 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'), false, false, array('ID', 'NAME', 'IBLOCK_ID', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'DETAIL_PICTURE'));?>
			<div class="wraps nomargin">
				<hr />
				<h4 class="underline"><?=(strlen($arParams['T_PROJECTS']) ? $arParams['T_PROJECTS'] : GetMessage('T_PROJECTS'))?></h4>
				<div class="projects item-views table">
					<div class="row items">
						<?
						$itemsCount = count($arProjects);
						$arParams['COLUMN_COUNT'] = 3;
						//$arParams['COLUMN_COUNT'] = ($arParams['COLUMN_COUNT'] > 0 && $arParams['COLUMN_COUNT'] < 6) ? ($arParams['COLUMN_COUNT'] > $itemsCount ? $itemsCount : $arParams['COLUMN_COUNT']) : 3;
						$countmd = $arParams['COLUMN_COUNT'];
						$countsm = (($tmp = ceil($arParams['COLUMN_COUNT'] / 2)) > 2 ? $tmp : (!$tmp ? 1 : $tmp));
						$colmd = floor(12 / $countmd);
						$colsm = floor(12 / $countsm);
						?>
						<?foreach($arProjects as $arItem):?>
							<?
							// edit/add/delete buttons for edit mode
							$arItemButtons = CIBlock::GetPanelButtons($arItem['IBLOCK_ID'], $arItem['ID'], 0, array('SESSID' => false, 'CATALOG' => true));
							$this->AddEditAction($arItem['ID'], $arItemButtons['edit']['edit_element']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
							$this->AddDeleteAction($arItem['ID'], $arItemButtons['edit']['delete_element']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
							$thumb = CFile::GetPath($arItem['PREVIEW_PICTURE'] ? $arItem['PREVIEW_PICTURE'] : $arItem['DETAIL_PICTURE']);
							?>
							<div class="col-md-<?=$colmd?> col-sm-<?=$colsm?>">
								<div class="item noborder" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
									<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="blink">
										<?// preview picture?>
										<div class="image">
											<?if($thumb):?>
												<img src="<?=$thumb?>" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>" class="img-responsive" />
											<?else:?>
												<img class="img-responsive" src="<?=SITE_TEMPLATE_PATH?>/images/noimage.png" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>" />
											<?endif;?>
										</div>
										<div class="info">
											<?// element name?>
											<div class="title">
												<span><?=$arItem['NAME']?></span>
											</div>
										</div>
									</a>
								</div>
							</div>
						<?endforeach;?>
						<script type="text/javascript">
						$(document).ready(function(){
							$('.projects.item-views .item .image').sliceHeight({lineheight: -3});
							$('.projects.item-views .item .info').sliceHeight();
						});
						</script>
					</div>
				</div>
			</div>
		<?endif;?>

		<?// reviews links?>
		<?if(in_array('LINK_REVIEWS', $arParams['DETAIL_PROPERTY_CODE']) && $arElement['PROPERTY_LINK_REVIEWS_VALUE']):?>
			<?$arRevies = CCache::CIBlockElement_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag(CCache::$arIBlocks[SITE_ID]['aspro_scorp_content']['aspro_scorp_reviews'][0]), 'MULTI' => 'Y')), array('ID' => $arElement['PROPERTY_LINK_REVIEWS_VALUE'], 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'), false, false, array('ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_POST', 'PROPERTY_DOCUMENTS', 'PREVIEW_TEXT'));?>
			<div class="wraps nomargin">
				<hr />
				<h4 class="underline"><?=(strlen($arParams['T_REVIEWS']) ? $arParams['T_REVIEWS'] : GetMessage('T_REVIEWS'))?></h4>
				<div class="item-views image_left reviews">
					<div class="row items">
						<?$count = count($arRevies);?>
						<?foreach($arRevies as $arItem):?>
							<?
							// edit/add/delete buttons for edit mode
							$arItemButtons = CIBlock::GetPanelButtons($arItem['IBLOCK_ID'], $arItem['ID'], 0, array('SESSID' => false, 'CATALOG' => true));
							$this->AddEditAction($arItem['ID'], $arItemButtons['edit']['edit_element']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
							$this->AddDeleteAction($arItem['ID'], $arItemButtons['edit']['delete_element']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
							?>
							<div class="col-md-12 col-sm-12">
								<div class="item review" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
									<div class="it">
										<div class="text"><?=$arItem['PREVIEW_TEXT']?></div>
										<?if($arItem['PROPERTY_DOCUMENTS_VALUE']):?>
											<div class="row docs">
												<?foreach((array)$arItem['PROPERTY_DOCUMENTS_VALUE'] as $docID):?>
													<?$arFile = CScorp::get_file_info($docID);?>
													<div class="col-md-6 <?=$arFile['TYPE']?>">
														<?
														$fileName = substr($arFile['ORIGINAL_NAME'], 0, strrpos($arFile['ORIGINAL_NAME'], '.'));
														$fileTitle = (strlen($arFile['DESCRIPTION']) ? $arFile['DESCRIPTION'] : $fileName);
														?>
														<a href="<?=$arFile['SRC']?>" target="_blank" title="<?=$fileTitle?>"><?=$fileTitle?></a>
														<?=GetMessage('CT_NAME_SIZE')?>:
														<?=CScorp::filesize_format($arFile['FILE_SIZE']);?>
													</div>
												<?endforeach;?>
											</div>
										<?endif;?>
										<div class="border"></div>
									</div>
									<div class="info">
										<div class="title"><?=$arItem['NAME']?></div>
										<?if($arItem['PROPERTY_POST_VALUE']):?>
											<div class="post"><?=$arItem['PROPERTY_POST_VALUE']?></div>
										<?endif;?>
									</div>
								</div>
							</div>
						<?endforeach;?>
					</div>
					<?if($count > 1):?>
						<script type="text/javascript">
						$(document).ready(function(){
							$('.reviews.item-views .item .it').sliceHeight();
						});
						</script>
					<?endif;?>
				</div>
			</div>
		<?endif;?>

		<?// staff links?>
		<?if(in_array('LINK_STAFF', $arParams['DETAIL_PROPERTY_CODE']) && $arElement['PROPERTY_LINK_STAFF_VALUE']):?>
			<div class="wraps nomargin">
				<hr />
				<h4 class="underline"><?=(strlen($arParams['T_STAFF']) ? $arParams['T_STAFF'] : (count($arElement['PROPERTY_LINK_STAFF_VALUE']) > 1 ? GetMessage('T_STAFF2') : GetMessage('T_STAFF1')))?></h4>
				<?global $arrrFilter; $arrrFilter = array('ID' => $arElement['PROPERTY_LINK_STAFF_VALUE']);?>
				<?$APPLICATION->IncludeComponent("bitrix:news.list", "staff-linked", array(
					"IBLOCK_TYPE" => "aspro_scorp_content",
					"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_scorp_content"]["aspro_scorp_staff"][0],
					"NEWS_COUNT" => "30",
					"SORT_BY1" => "SORT",
					"SORT_ORDER1" => "DESC",
					"SORT_BY2" => "",
					"SORT_ORDER2" => "ASC",
					"FILTER_NAME" => "arrrFilter",
					"FIELD_CODE" => array(
						0 => "NAME",
						1 => "PREVIEW_TEXT",
						2 => "PREVIEW_PICTURE",
						3 => "",
					),
					"PROPERTY_CODE" => array(
						0 => "EMAIL",
						1 => "POST",
						2 => "PHONE",
						3 => "",
					),
					"CHECK_DATES" => "Y",
					"DETAIL_URL" => "",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "360000",
					"CACHE_FILTER" => "Y",
					"CACHE_GROUPS" => "N",
					"PREVIEW_TRUNCATE_LEN" => "",
					"ACTIVE_DATE_FORMAT" => "d.m.Y",
					"SET_TITLE" => "N",
					"SET_STATUS_404" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"ADD_SECTIONS_CHAIN" => "Y",
					"HIDE_LINK_WHEN_NO_DETAIL" => "N",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"INCLUDE_SUBSECTIONS" => "Y",
					"PAGER_TEMPLATE" => "",
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_BOTTOM_PAGER" => "Y",
					"PAGER_TITLE" => "Новости",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "N",
					"VIEW_TYPE" => "table",
					"SHOW_TABS" => "N",
					"SHOW_SECTION_PREVIEW_DESCRIPTION" => "N",
					"IMAGE_POSITION" => "left",
					"COUNT_IN_LINE" => "3",
					"AJAX_OPTION_ADDITIONAL" => ""
					),
					false, array("HIDE_ICONS" => "Y")
				);?>
			</div>
		<?endif;?>

		<?// goods links?>
		<?if(in_array('LINK_GOODS', $arParams['DETAIL_PROPERTY_CODE']) && $arElement['PROPERTY_LINK_GOODS_VALUE']):?>
			<div class="wraps nomargin">
				<hr />
				<h4 class="underline"><?=(strlen($arParams['T_GOODS']) ? $arParams['T_GOODS'] : GetMessage('T_GOODS'))?></h4>
				<?global $arrrFilter; $arrrFilter = array('ID' => $arElement['PROPERTY_LINK_GOODS_VALUE']);?>
				<?$APPLICATION->IncludeComponent(
					"bitrix:news.list",
					"catalog-linked",
					Array(
						"S_ORDER_PRODUCT" => $arParams["S_ORDER_PRODUCT"],
						"IBLOCK_TYPE" => "aspro_scorp_catalog",
						"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_scorp_catalog"]["aspro_scorp_catalog"][0],
						"NEWS_COUNT" => "20",
						"SORT_BY1" => "ACTIVE_FROM",
						"SORT_ORDER1" => "DESC",
						"SORT_BY2" => "SORT",
						"SORT_ORDER2" => "ASC",
						"FILTER_NAME" => "arrrFilter",
						"FIELD_CODE" => array(
							0 => "NAME",
							1 => "PREVIEW_TEXT",
							2 => "PREVIEW_PICTURE",
							3 => "DETAIL_PICTURE",
							4 => "",
						),
						"PROPERTY_CODE" => array(
							0 => "PRICE",
							1 => "PRICEOLD",
							2 => "ARTICLE",
							3 => "FORM_ORDER",
							4 => "STATUS",
							5 => "",
						),
						"CHECK_DATES" => "Y",
						"DETAIL_URL" => "",
						"PREVIEW_TRUNCATE_LEN" => "",
						"ACTIVE_DATE_FORMAT" => "d.m.Y",
						"SET_TITLE" => "N",
						"SET_STATUS_404" => "N",
						"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
						"ADD_SECTIONS_CHAIN" => "N",
						"HIDE_LINK_WHEN_NO_DETAIL" => "N",
						"PARENT_SECTION" => "",
						"PARENT_SECTION_CODE" => "",
						"INCLUDE_SUBSECTIONS" => "Y",
						"CACHE_TYPE" => "A",
						"CACHE_TIME" => "36000000",
						"CACHE_FILTER" => "Y",
						"CACHE_GROUPS" => "N",
						"PAGER_TEMPLATE" => ".default",
						"DISPLAY_TOP_PAGER" => "N",
						"DISPLAY_BOTTOM_PAGER" => "Y",
						"PAGER_TITLE" => "Новости",
						"PAGER_SHOW_ALWAYS" => "N",
						"PAGER_DESC_NUMBERING" => "N",
						"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
						"PAGER_SHOW_ALL" => "N",
						"AJAX_MODE" => "N",
						"AJAX_OPTION_JUMP" => "N",
						"AJAX_OPTION_STYLE" => "Y",
						"AJAX_OPTION_HISTORY" => "N",
						"SHOW_DETAIL_LINK" => "Y",
						"COUNT_IN_LINE" => "3",
						"IMAGE_POSITION" => "left",
					),
				false, array("HIDE_ICONS" => "Y")
				);?>
			</div>
		<?endif;?>

		<?// services links?>
		<?if(in_array('LINK_SERVICES', $arParams['DETAIL_PROPERTY_CODE']) && $arElement['PROPERTY_LINK_SERVICES_VALUE']):?>
			<div class="wraps nomargin">
				<hr />
				<h4 class="underline"><?=(strlen($arParams['T_SERVICES']) ? $arParams['T_SERVICES'] : GetMessage('T_SERVICES'))?></h4>
				<?global $arrrFilter; $arrrFilter = array("ID" => $arElement["PROPERTY_LINK_SERVICES_VALUE"]);?>
				<?$APPLICATION->IncludeComponent("bitrix:news.list", "services", array(
					"IBLOCK_TYPE" => "aspro_scorp_content",
					"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_scorp_content"]["aspro_scorp_services"][0],
					"NEWS_COUNT" => "20",
					"SORT_BY1" => "ACTIVE_FROM",
					"SORT_ORDER1" => "DESC",
					"SORT_BY2" => "SORT",
					"SORT_ORDER2" => "ASC",
					"FILTER_NAME" => "arrrFilter",
					"FIELD_CODE" => array(
						0 => "NAME",
						1 => "PREVIEW_TEXT",
						2 => "PREVIEW_PICTURE",
						3 => "",
					),
					"PROPERTY_CODE" => array(
						0 => "",
						1 => "",
					),
					"CHECK_DATES" => "Y",
					"DETAIL_URL" => "",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "36000000",
					"CACHE_FILTER" => "Y",
					"CACHE_GROUPS" => "N",
					"PREVIEW_TRUNCATE_LEN" => "",
					"ACTIVE_DATE_FORMAT" => "d.m.Y",
					"SET_TITLE" => "N",
					"SET_STATUS_404" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"HIDE_LINK_WHEN_NO_DETAIL" => "N",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"INCLUDE_SUBSECTIONS" => "Y",
					"PAGER_TEMPLATE" => ".default",
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_BOTTOM_PAGER" => "Y",
					"PAGER_TITLE" => "Новости",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "N",
					"VIEW_TYPE" => "list",
					"SHOW_TABS" => "N",
					"SHOW_IMAGE" => "Y",
					"SHOW_NAME" => "Y",
					"SHOW_DETAIL" => "Y",
					"IMAGE_POSITION" => "top",
					"COUNT_IN_LINE" => "3",
					"AJAX_OPTION_ADDITIONAL" => ""
					),
				false, array("HIDE_ICONS" => "Y")
				);?>
			</div>
		<?endif;?>
	</div>
	<?CScorp::CheckAdditionalChain($arResult, $arParams, $arElement['IBLOCK_SECTION_ID'], $arElement['ID']);?>
<?endif;?>
<div style="clear:both"></div>
<div class="row">
	<div class="col-md-6 share">
		<?if($arParams["USE_SHARE"] == "Y" && $arElement):?>
			<span class="text"><?=GetMessage('SHARE_TEXT')?></span>
			<script type="text/javascript" src="//yastatic.net/share2/share.js" async="async" charset="utf-8"></script>
			<div class="ya-share2" data-services="vkontakte,facebook,twitter,viber,whatsapp,odnoklassniki,moimir"></div>
		<?endif;?>
	</div>
	<div class="col-md-6">
		<a class="back-url" href="<?=$arResult['FOLDER'].$arResult['URL_TEMPLATES']['news']?>"><i class="fa fa-chevron-left"></i><?=GetMessage('BACK_LINK')?></a>
	</div>
</div>