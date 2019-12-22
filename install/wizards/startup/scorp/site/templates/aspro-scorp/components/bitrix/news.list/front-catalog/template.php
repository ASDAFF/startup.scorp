<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

global $arTheme;
$bShowImage = in_array('PREVIEW_PICTURE', $arParams['FIELD_CODE']);
$bShowBasket = $arTheme['ORDER_VIEW']['VALUE'] === 'Y';
$basketURL = (strlen(trim($arTheme['URL_BASKET_SECTION']['VALUE'])) ? trim($arTheme['URL_BASKET_SECTION']['VALUE']) : '');
?>
<div>&nbsp;
<?if($arResult['SECTIONS'] &&  $arParams["SHOW_SECTIONS"] != "N"):?>
	<?
	$qntyItems = count($arResult['SECTIONS']);
	$colmd = 6;
	$colsm = 6;
	$colxs = 12;
	?>
	<div class="item-views catalog sections front" style="display:none;">
		<?if($arParams['PAGER_SHOW_ALL']):?>
			<a href="<?=str_replace('#SITE'.'_DIR#', SITE_DIR, $arResult['LIST_PAGE_URL'])?>" class="btn btn-default white btn-xs"><span><?=GetMessage('S_TO_ALL_CATALOG')?></span></a>
		<?endif;?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:main.include",
			"",
			Array(
				"AREA_FILE_SHOW" => "file",
				"PATH" => SITE_DIR."include/front-catalog.php",
				"EDIT_TEMPLATE" => "standard.php"
			)
		);?>
		<div class="items row">
			<?foreach($arResult['SECTIONS'] as $arItem):?>
				<?
				// edit/add/delete buttons for edit mode
				$arSectionButtons = CIBlock::GetPanelButtons($arItem['IBLOCK_ID'], 0, $arItem['ID'], array('SESSID' => false, 'CATALOG' => true));
				$this->AddEditAction($arItem['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'SECTION_EDIT'));
				$this->AddDeleteAction($arItem['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				// preview picture
				if($bSectionImage = strlen($arItem['~PICTURE']) && in_array('PREVIEW_PICTURE', $arParams['FIELD_CODE'])){
					$arSectionImage = CFile::ResizeImageGet($arItem['~PICTURE'], array('width' => 138, 'height' => 110), BX_RESIZE_IMAGE_PROPORTIONAL, true);
					$imageSectionSrc = $arSectionImage['src'];
				}
				?>
				<div class="col-md-<?=$colmd?> col-sm-<?=$colsm?> col-xs-<?=$colxs?>">
					<div class="item<?=($bSectionImage ? '' : ' wti')?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
						<?// icon or preview picture?>
						<?if($bSectionImage):?>
							<div class="image">
								<a href="<?=$arItem['SECTION_PAGE_URL']?>">
									<img src="<?=$imageSectionSrc?>" alt="<?=($arItem['PICTURE']['ALT'] ? $arItem['PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($arItem['PICTURE']['TITLE'] ? $arItem['PICTURE']['TITLE'] : $arItem['NAME'])?>" class="img-responsive" />
								</a>
							</div>
						<?endif;?>

						<div class="info">
							<?// section name?>
							<?if(in_array('NAME', $arParams['FIELD_CODE'])):?>
								<div class="title">
									<a href="<?=$arItem['SECTION_PAGE_URL']?>">
										<?=$arItem['NAME']?>
									</a>
								</div>
							<?endif;?>

							<?// section preview text?>
							<?if(strlen($arItem['UF_INFOTEXT'])):?>
								<div class="text">
									<?=$arItem['UF_INFOTEXT']?>
								</div>
							<?endif;?>
						</div>
					</div>
				</div>
			<?endforeach;?>
		</div>
		<script type="text/javascript">
		$(document).ready(function(){
			$('.catalog.item-views.sections .item .title').sliceHeight();
			$('.catalog.item-views.sections .item').sliceHeight();
		});
		</script>
	</div>
<?endif;?>
<?if(($arResult['SECTIONS'] &&  $arParams["SHOW_SECTIONS"] != "N") && ($arResult['ITEMS'] && $arParams["SHOW_GOODS"] != "N")):?>
	<hr id="front_catalog_separator" />
<?endif;?>
<?
$frame = $this->createFrame()->begin();
$frame->setAnimation(true);
?>
<?if($arResult['ITEMS'] && $arParams["SHOW_GOODS"] != "N"):?>
	<?
	$qntyItems = count($arResult['ITEMS']);
	$countmd = 4;
	$countsm = 3;
	$countxs = 2;
	$colmd = 3;
	$colsm = 4;
	$colxs = 6;
	$bShowImage = in_array('PREVIEW_PICTURE', $arParams['FIELD_CODE']);
	?>
	<div class="catalog item-views table front" style="display:none;">
		<?$APPLICATION->IncludeComponent(
			"bitrix:main.include",
			"",
			Array(
				"AREA_FILE_SHOW" => "file",
				"PATH" => SITE_DIR."include/front-catalog-favorites.php",
				"EDIT_TEMPLATE" => "standard.php"
			)
		);?>
		<div class="flexslider unstyled row" data-plugin-options='{"animation": "slide", "directionNav": true, "controlNav" :true, "animationLoop": true, "slideshow": false, "counts": [<?=$countmd?>, <?=$countsm?>, <?=$countxs?>]}'>
			<ul class="slides" itemscope itemtype="http://schema.org/ItemList">
				<?foreach($arResult["ITEMS"] as $i => $arItem):?>
					<?
					// edit/add/delete buttons for edit mode
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					// use detail link?
					$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
					// preview image
					if($bShowImage){
						$bImage = strlen($arItem['FIELDS']['PREVIEW_PICTURE']['SRC']);
						$arImage = ($bImage ? CFile::ResizeImageGet($arItem['FIELDS']['PREVIEW_PICTURE']['ID'], array('width' => 160, 'height' => 160), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());
						$imageSrc = ($bImage ? $arImage['src'] : SITE_TEMPLATE_PATH.'/images/noimage_product.png');
						$imageDetailSrc = ($bImage ? $arItem['FIELDS']['DETAIL_PICTURE']['SRC'] : false);
					}
					// use order button?
					$bOrderButton = !$bShowBasket && ($arItem["DISPLAY_PROPERTIES"]["FORM_ORDER"]["VALUE_XML_ID"] == "YES");
					// use buy button?
					if($bBuyButton = $bShowBasket && ($arItem["DISPLAY_PROPERTIES"]["FORM_ORDER"]["VALUE_XML_ID"] == "YES")){
						$dataItem = CScorp::getDataItem($arItem);
					}
					?>
					<li class="col-md-<?=$colmd?> col-sm-<?=$colsm?> col-xs-<?=$colxs?>">
						<div class="item<?=($bShowImage ? '' : ' wti')?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>"<?=($bBuyButton ? ' data-item="'.$dataItem.'"' : '')?> itemprop="itemListElement" itemscope="" itemtype="http://schema.org/Product">
							<?if($bShowImage):?>
								<div class="image">
									<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="blink" itemprop="url">
									<?elseif($imageDetailSrc):?><a href="<?=$imageDetailSrc?>" alt="<?=($bImage ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" class="img-inside fancybox" itemprop="url">
									<?endif;?>
										<img class="img-responsive" src="<?=$imageSrc?>" alt="<?=($bImage ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" itemprop="image" />
									<?if($bDetailLink):?></a>
									<?elseif($imageDetailSrc):?><span class="zoom"><i class="fa fa-16 fa-white-shadowed fa-search"></i></span></a>
									<?endif;?>
								</div>
							<?endif;?>

							<div class="text">
								<div class="cont">
									<?// element name?>
									<?if(strlen($arItem['FIELDS']['NAME'])):?>
										<div class="title">
											<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>" itemprop="url"><?endif;?>
												<span itemprop="name"><?=$arItem['NAME']?></span>
											<?if($bDetailLink):?></a><?endif;?>
										</div>
									<?endif;?>

									<?// element section name?>
									<?if(strlen($arItem['SECTION_NAME'])):?>
										<div class="section_name"><?=$arItem['SECTION_NAME']?></div>
									<?endif;?>

									<?// element status?>
									<?if(strlen($arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE'])):?>
										<span class="label label-<?=$arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE_XML_ID']?>" itemprop="description"><?=$arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE']?></span>
									<?endif;?>

									<?// element article?>
									<?if(strlen($arItem['DISPLAY_PROPERTIES']['ARTICLE']['VALUE'])):?>
										<span class="article" itemprop="description"><?=GetMessage('S_ARTICLE')?>:&nbsp;<span><?=$arItem['DISPLAY_PROPERTIES']['ARTICLE']['VALUE']?></span></span>
									<?endif;?>

									<?/*
									<?// element preview text?>
									<?if(strlen($arItem['FIELDS']['PREVIEW_TEXT'])):?>
										<div class="description" itemprop="description">
											<?if($arItem['PREVIEW_TEXT_TYPE'] == 'text'):?>
												<p><?=$arItem['FIELDS']['PREVIEW_TEXT']?></p>
											<?else:?>
												<?=$arItem['FIELDS']['PREVIEW_TEXT']?>
											<?endif;?>
										</div>
									<?endif;?>
									*/?>
								</div>

								<div class="row foot">
									<div class="<?=(!$bOrderButton ? 'col-md-12 col-sm-12 col-xs-12 clearfix slice_price' : 'col-md-6 col-sm-12 col-xs-12 slice_price pull-left')?>">
										<?// element price?>
										<?if(strlen($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'])):?>
											<div class="price<?=($bBuyButton ? '  inline' : '')?>" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
												<div class="price_new">
													<span class="price_val"><?=CScorp::FormatPriceShema($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'])?></span>
												</div>
												<?if($arItem['DISPLAY_PROPERTIES']['PRICEOLD']['VALUE']):?>
													<div class="price_old">
														<span class="price_val"><?=$arItem['DISPLAY_PROPERTIES']['PRICEOLD']['VALUE']?></span>
													</div>
												<?endif;?>
											</div>
										<?endif;?>
									</div>

									<?if($bOrderButton || $bBuyButton):?>
										<div class="<?=($bOrderButton ? 'col-md-6 col-sm-5 col-xs-12 pull-right' : 'col-md-12 col-sm-12 col-xs-12')?>">
											<?// element order button?>
											<?if($bOrderButton):?>
												<span class="btn btn-default btn-sm pull-right" <?=(strlen(($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE']) && strlen($arItem['DISPLAY_PROPERTIES']['PRICEOLD']['VALUE'])) ? 'style="margin-top:16px;"' : '')?> data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]["startup_scorp_form"]["startup_scorp_order_product"][0]?>" data-product="<?=$arItem["NAME"]?>" data-name="order_product"><?=GetMessage("TO_ORDER")?></span>
											<?// element buy block?>
											<?else:?>
												<div class="buy_block clearfix">
													<div class="counter pull-left">
														<div class="wrap">
															<span class="minus ctrl bgtransition"></span>
															<div class="input"><input type="text" value="1" class="count" maxlength="20" /></div>
															<span class="plus ctrl bgtransition"></span>
														</div>
													</div>
													<div class="buttons pull-right">
														<span class="btn btn-default btn-sm to_cart" data-quantity="1"><span><?=GetMessage('BUTTON_TO_CART')?></span></span>
														<a href="<?=$basketURL;?>" class="btn btn-default btn-sm in_cart"><span><?=GetMessage('BUTTON_IN_CART')?></span></a>
													</div>
												</div>
											<?endif;?>
										</div>
									<?endif;?>
								</div>
							</div>
						</div>
					</li>
				<?endforeach;?>
			</ul>
		</div>
		<script type="text/javascript">
		$(document).ready(function(){
			setBasketItemsClasses();
			$('.catalog.item-views.table .item .image').sliceHeight({slice: <?=$qntyItems?>, autoslicecount: false, lineheight: -3});
			$('.catalog.item-views.table .item .title').sliceHeight({slice: <?=$qntyItems?>, autoslicecount: false});
			$('.catalog.item-views.table .item .cont').sliceHeight({slice: <?=$qntyItems?>, autoslicecount: false});
			$('.catalog.item-views.table .item .slice_price').sliceHeight({slice: <?=$qntyItems?>, autoslicecount: false});
			$('.catalog.item-views.table .item').sliceHeight({slice: <?=$qntyItems?>, autoslicecount: false});
		});
		</script>
	</div>
<?endif;?>
<script type="text/javascript">
$(document).ready(function() {
	try{
		if(arScorpOptions.THEME.CATALOG_INDEX == 'Y'){
			$('.catalog.item-views.sections.front').show();
			if(arScorpOptions.THEME.TEASERS_INDEX == 'NONE'){
				$('.catalog.item-views.sections.front').css('margin-top', '47px');
			}
		}
		else{
			$('.catalog.item-views.sections.front').remove();
			$('#front_catalog_separator').remove();
		}
		if(arScorpOptions.THEME.CATALOG_FAVORITES_INDEX == 'Y'){
			$('.catalog.item-views.table.front').show();
			if(arScorpOptions.THEME.TEASERS_INDEX == 'NONE' && arScorpOptions.THEME.CATALOG_INDEX == 'N'){
				$('.catalog.item-views.table.front').css('margin-top', '47px');
			}
			InitFlexSlider();
			$('.catalog.item-views.table.front .blink img').blink();
		}
		else{
			$('.catalog.item-views.table.front').remove();
			$('#front_catalog_separator').remove();
		}
	}
	catch(e){}
});
</script>
<?$frame->end();?>
</div>