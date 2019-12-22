<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$frame = $this->createFrame()->begin();
$frame->setAnimation(true);

global $arTheme;
$bShowImage = in_array('PREVIEW_PICTURE', $arParams['FIELD_CODE']);
$bShowBasket = $arTheme['ORDER_VIEW']['VALUE'] === 'Y';
$basketURL = (strlen(trim($arTheme['URL_BASKET_SECTION']['VALUE'])) ? trim($arTheme['URL_BASKET_SECTION']['VALUE']) : '');
?>
<div class="catalog item-views list image-<?=$arParams['IMAGE_POSITION']?>">
	<?if($arResult['ITEMS']):?>
		<?if($arParams['DISPLAY_TOP_PAGER']):?>
			<?=$arResult['NAV_STRING']?>
		<?endif;?>

		<div class="row items" itemscope itemtype="http://schema.org/ItemList">
			<?foreach($arResult['ITEMS'] as $arItem):?>
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
				<?ob_start();?>
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
				<?$imgPart = ob_get_clean();?>

				<?ob_start();?>
					<div class="text">
						<div class="row">
							<?$colmd = 12 - (strlen($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE']) || $bOrderButton || $bBuyButton ? ($bShowImage ? 3 : 2) : 0);?>
							<div class="col-md-<?=$colmd?> col-sm-<?=$colmd - 1?> col-xs-12">
								<div class="cont">
									<?// element name?>
									<?if(strlen($arItem['FIELDS']['NAME'])):?>
										<div class="title">
											<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>" itemprop="url"><?endif;?>
												<span itemprop="name"><?=$arItem['NAME']?></span>
											<?if($bDetailLink):?></a><?endif;?>
										</div>
									<?endif;?>

									<?/*
									<?// element section name?>
									<?if(strlen($arItem['SECTION_NAME'])):?>
										<div class="section_name"><?=$arItem['SECTION_NAME']?></div>
									<?endif;?>
									*/?>

									<?// element status?>
									<?if(strlen($arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE'])):?>
										<span class="label label-<?=$arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE_XML_ID']?>" itemprop="description"><?=$arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE']?></span>
									<?endif;?>

									<?// element article?>
									<?if(strlen($arItem['DISPLAY_PROPERTIES']['ARTICLE']['VALUE'])):?>
										<span class="article" itemprop="description"><?=GetMessage('S_ARTICLE')?>:&nbsp;<span><?=$arItem['DISPLAY_PROPERTIES']['ARTICLE']['VALUE']?></span></span>
									<?endif;?>

									<?// element preview text?>
									<?if(strlen($arItem['FIELDS']['PREVIEW_TEXT'])):?>
										<div class="description" itemprop="description">
											<hr />
											<?if($arItem['PREVIEW_TEXT_TYPE'] == 'text'):?>
												<p><?=$arItem['FIELDS']['PREVIEW_TEXT']?></p>
											<?else:?>
												<?=$arItem['FIELDS']['PREVIEW_TEXT']?>
											<?endif;?>
										</div>
									<?endif;?>
								</div>
							</div>

							<?if(strlen($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE']) || $bOrderButton || $bBuyButton):?>
								<div class="<?=($bShowImage ? 'col-md-3 col-sm-4 col-xs-12' : 'col-md-2 col-sm-3 col-xs-12')?>">
									<div class="foot">
										<?// element price?>
										<?if(strlen($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'])):?>
											<div class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
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

										<?// element order button?>
										<?if($bOrderButton):?>
											<span class="btn btn-default btn-sm pull-left" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['aspro_scorp_form']['aspro_scorp_order_product'][0]?>" data-product="<?=$arItem['NAME']?>" data-name="order_product"><?=(strlen($arParams['S_ORDER_PRODUCT']) ? $arParams['S_ORDER_PRODUCT'] : GetMessage('S_ORDER_PRODUCT'))?></span>
										<?// element buy block?>
										<?elseif($bBuyButton):?>
											<div class="buy_block clearfix">
												<div class="counter">
													<div class="wrap">
														<span class="minus ctrl bgtransition"></span>
														<div class="input"><input type="text" value="1" class="count" maxlength="20" /></div>
														<span class="plus ctrl bgtransition"></span>
													</div>
												</div>
												<div class="buttons">
													<span class="btn btn-default btn-sm to_cart" data-quantity="1"><span><?=GetMessage('BUTTON_TO_CART')?></span></span>
													<a href="<?=$basketURL;?>" class="btn btn-default btn-sm in_cart"><span><?=GetMessage('BUTTON_IN_CART')?></span></a>
												</div>
											</div>
										<?endif;?>
									</div>
								</div>
							<?endif;?>
						</div>
					</div>
				<?$textPart = ob_get_clean();?>

				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="item<?=($bShowImage ? '' : ' wti')?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>"<?=($bBuyButton ? ' data-item="'.$dataItem.'"' : '')?> itemprop="itemListElement" itemscope="" itemtype="http://schema.org/Product">
						<div class="row">
							<?if(!$bShowImage):?>
								<div class="col-md-12">
									<?=$textPart?>
								</div>
							<?elseif($arParams['IMAGE_POSITION'] == 'right'):?>
								<div class="col-md-9 col-sm-9">
									<?=$textPart?>
								</div>
								<div class="col-md-3 col-sm-3">
									<?=$imgPart?>
								</div>
							<?else:?>
								<div class="col-md-3 col-sm-3">
									<?=$imgPart?>
								</div>
								<div class="col-md-9 col-sm-9">
									<?=$textPart?>
								</div>
							<?endif;?>
						</div>
					</div>
				</div>
			<?endforeach;?>
			<script>
			$(document).ready(function(){
				setBasketItemsClasses();
			});
			</script>
		</div>

		<?if($arParams['DISPLAY_BOTTOM_PAGER']):?>
			<?=$arResult['NAV_STRING']?>
		<?endif;?>
	<?endif;?>

	<?// section description?>
	<?if(is_array($arResult['SECTION']['PATH'])):?>
		<?$arCurSectionPath = end($arResult['SECTION']['PATH']);?>
		<?if(strlen($arCurSectionPath['DESCRIPTION']) && strpos($_SERVER['REQUEST_URI'], 'PAGEN') === false):?>
			<div class="cat-desc"><hr style="<?=(strlen($arResult['NAV_STRING']) && $arParams['DISPLAY_BOTTOM_PAGER'] ? 'margin-top:2px;' : 'border-color:transparent;margin-top:0;')?>" /><?=$arCurSectionPath['DESCRIPTION']?></div>
		<?endif;?>
	<?endif;?>
</div>
<?$frame->end();?>