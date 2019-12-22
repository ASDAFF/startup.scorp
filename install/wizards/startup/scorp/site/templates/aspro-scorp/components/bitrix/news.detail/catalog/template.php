<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?
global $arTheme;
$bOrderViewBasket = $arParams['ORDER_VIEW'];
$basketURL = (isset($arTheme['URL_BASKET_SECTION']) && strlen(trim($arTheme['URL_BASKET_SECTION']['VALUE'])) ? $arTheme['URL_BASKET_SECTION']['VALUE'] : SITE_DIR.'cart/');
$dataItem = ($bOrderViewBasket ? CScorp::getDataItem($arResult) : false);
?>
<div class="item" data-id="<?=$arResult['ID']?>"<?=($bOrderViewBasket ? ' data-item="'.$dataItem.'"' : '')?>>
	<?// element name?>
	<?if($arParams['DISPLAY_NAME'] != 'N' && strlen($arResult['NAME'])):?>
		<h2 class="underline" itemprop="name"><?=$arResult['NAME']?></h2>
	<?endif;?>
	<div class="head<?=($arResult['GALLERY'] ? '' : ' wti')?>">
		<div class="row">
			<?if($arResult['GALLERY']):?>
				<div class="col-md-6 col-sm-6">
					<div class="row galery">
						<div class="inner">
							<div class="flexslider unstyled row" id="slider" data-plugin-options='{"animation": "slide", "directionNav": true, "controlNav" :false, "animationLoop": true, "sync": ".detail .galery #carousel", "slideshow": false, "counts": [1, 1, 1]}'>
								<ul class="slides items">
									<?$countAll = count($arResult['GALLERY']);?>
									<?foreach($arResult['GALLERY'] as $i => $arPhoto):?>
										<li class="col-md-1 col-sm-1 item">
											<a href="<?=$arPhoto['DETAIL']['SRC']?>" class="fancybox blink" rel="gallery" target="_blank" title="<?=$arPhoto['TITLE']?>">
												<img src="<?=$arPhoto['PREVIEW']['src']?>" class="img-responsive inline" title="<?=$arPhoto['TITLE']?>" alt="<?=$arPhoto['ALT']?>" itemprop="image" />
												<span class="zoom">
													<i class="fa fa-16 fa-white-shadowed fa-search-plus"></i>
												</span>
											</a>
										</li>
									<?endforeach;?>
								</ul>
							</div>
							<?if(count($arResult["GALLERY"]) > 1):?>
								<div class="thmb flexslider unstyled" id="carousel">
									<ul class="slides">
										<?foreach($arResult["GALLERY"] as $arPhoto):?>
											<li class="blink">
												<img class="img-responsive inline" border="0" src="<?=$arPhoto["THUMB"]["src"]?>" title="<?=$arPhoto['TITLE']?>" alt="<?=$arPhoto['ALT']?>" />
											</li>
										<?endforeach;?>
									</ul>
								</div>
								<style type="text/css">
								.catalog.detail .galery #carousel.flexslider{max-width:<?=ceil(((count($arResult['GALLERY']) <= 3 ? count($arResult['GALLERY']) : 3) * 84.5) - 7.5 + 60)?>px;}
								@media (max-width: 991px){
									.catalog.detail .galery #carousel.flexslider{max-width:<?=ceil(((count($arResult['GALLERY']) <= 2 ? count($arResult['GALLERY']) : 2) * 84.5) - 7.5 + 60)?>px;}
								}
								</style>
							<?endif;?>
						</div>
						<script type="text/javascript">
						$(document).ready(function(){
							InitFlexSlider(); // for ajax mode
							$('.detail .galery .item').sliceHeight({slice: <?=$countAll?>, lineheight: -3});
							$('.detail .galery #carousel').flexslider({
								animation: 'slide',
								controlNav: false,
								animationLoop: true,
								slideshow: false,
								itemWidth: 77,
								itemMargin: 7.5,
								minItems: 2,
								maxItems: 3,
								asNavFor: '.detail .galery #slider'
							});
						});
						</script>
					</div>
				</div>
			<?endif;?>

			<div class="<?=($arResult['GALLERY'] ? 'col-md-6 col-sm-6' : 'col-md-12 col-sm-12');?>">
				<div class="info" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
					<?
					$frame = $this->createFrame('info')->begin('');
					$frame->setAnimation(true);
					?>
					<?if($arResult['DISPLAY_PROPERTIES']['STATUS']['VALUE_XML_ID'] || strlen($arResult['DISPLAY_PROPERTIES']['ARTICLE']['VALUE'])):?>
						<div class="hh">
							<?if(strlen($arResult['DISPLAY_PROPERTIES']['STATUS']['VALUE'])):?>
								<span class="label label-<?=$arResult['DISPLAY_PROPERTIES']['STATUS']['VALUE_XML_ID']?>" itemprop="availability" href="http://schema.org/InStock"><?=$arResult['DISPLAY_PROPERTIES']['STATUS']['VALUE']?></span>
							<?endif;?>
							<?if(strlen($arResult['DISPLAY_PROPERTIES']['ARTICLE']['VALUE'])):?>
								<span class="article">
									<?=GetMessage('ARTICLE')?>&nbsp;<span><?=$arResult['DISPLAY_PROPERTIES']['ARTICLE']['VALUE']?></span>
								</span>
							<?endif;?>
							<hr/>
						</div>
					<?endif;?>
					<?if(strlen($arResult['FIELDS']['PREVIEW_TEXT'])):?>
						<div class="previewtext" itemprop="description">
							<?// element detail text?>
							<?if($arResult['DETAIL_TEXT_TYPE'] == 'text'):?>
								<p><?=$arResult['FIELDS']['PREVIEW_TEXT'];?></p>
							<?else:?>
								<?=$arResult['FIELDS']['PREVIEW_TEXT'];?>
							<?endif;?>
						</div>
					<?endif;?>
					<?if(strlen($arResult['DISPLAY_PROPERTIES']['PRICE']['VALUE'])):?>
						<div class="price">
							<div class="price_new"><span class="price_val"><?=CScorp::FormatPriceShema($arResult['DISPLAY_PROPERTIES']['PRICE']['VALUE'])?></span></div>
							<?if(strlen($arResult['DISPLAY_PROPERTIES']['PRICEOLD']['VALUE'])):?>
								<div class="price_old"><?=GetMessage('DISCOUNT_PRICE')?>&nbsp;<span class="price_val"><?=$arResult['DISPLAY_PROPERTIES']['PRICEOLD']['VALUE']?></span></div>
							<?endif;?>
						</div>
					<?endif;?>
					<?// element buy block?>
					<?if($bOrderViewBasket && $arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES'):?>
						<div class="buy_block lg clearfix">
							<div class="counter pull-left">
								<div class="wrap">
									<span class="minus ctrl bgtransition"></span>
									<input type="text" value="1" class="count" />
									<span class="plus ctrl bgtransition"></span>
								</div>
							</div>
							<div class="buttons pull-right">
								<span class="btn btn-default pull-right to_cart" data-quantity="1"><span><?=GetMessage('BUTTON_TO_CART')?></span></span>
								<a href="<?=$basketURL?>" class="btn btn-default pull-right in_cart"><span><?=GetMessage('BUTTON_IN_CART')?></span></a>
							</div>
						</div>
					<?endif;?>
					<?if($arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES' || $arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE_XML_ID'] == 'YES'):?>
						<div class="order<?=($bOrderViewBasket ? ' basketTrue' : '')?>">
							<?if($arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES' && !$bOrderViewBasket):?>
								<span class="btn btn-default" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['startup_scorp_form']['startup_scorp_order_product'][0]?>" data-name="order_product" data-product="<?=$arResult['NAME']?>"><?=(strlen($arParams['S_ORDER_PRODUCT']) ? $arParams['S_ORDER_PRODUCT'] : GetMessage('S_ORDER_PRODUCT'))?></span>
							<?endif;?>
							<?if($arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE_XML_ID'] == 'YES'):?>
								<span class="btn btn-default white" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['startup_scorp_form']['startup_scorp_question'][0]?>" data-name="question" data-autoload-NEED_PRODUCT="<?=$arResult['NAME']?>"><?=(strlen($arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : GetMessage('S_ASK_QUESTION'))?></span>
							<?endif;?>
							<?/*<div class="text"><?=GetMessage('MORE_TEXT')?></div>*/?>
						</div>
					<?endif;?>
					<?if($arParams['USE_SHARE'] == 'Y'):?>
						<div class="share">
							<hr />
							<span class="text"><?=GetMessage('SHARE_TEXT')?></span>
							<div class="ya-share2" data-services="vkontakte,facebook,twitter,viber,whatsapp,odnoklassniki,moimir"></div>
						</div>
					<?endif;?>
					<?$frame->end();?>
				</div>
			</div>
		</div>
	</div>
	<?if(strlen($arResult['FIELDS']['DETAIL_TEXT'])):?>
		<div class="content" itemprop="description">
			<?// element detail text?>
			<?if($arResult['DETAIL_TEXT_TYPE'] == 'text'):?>
				<p><?=$arResult['FIELDS']['DETAIL_TEXT'];?></p>
			<?else:?>
				<?=$arResult['FIELDS']['DETAIL_TEXT'];?>
			<?endif;?>
		</div>
	<?endif;?>

	<?
	$frame = $this->createFrame('order')->begin('');
	$frame->setAnimation(true);
	?>
	<?// order?>
	<?if($arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES' && !$bOrderViewBasket):?>
		<div class="order-block">
			<div class="row">
				<div class="col-md-4 col-sm-4 col-xs-5 valign">
					<span class="btn btn-default btn-lg" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['startup_scorp_form']['startup_scorp_order_product'][0]?>" data-name="order_product" data-product="<?=$arResult['NAME']?>"><?=(strlen($arParams['S_ORDER_PRODUCT']) ? $arParams['S_ORDER_PRODUCT'] : GetMessage('S_ORDER_PRODUCT'))?></span>
				</div>
				<div class="col-md-8 col-sm-8 col-xs-7 valign">
					<div class="text">
						<?$APPLICATION->IncludeComponent(
							'bitrix:main.include',
							'',
							Array(
								'AREA_FILE_SHOW' => 'file',
								'PATH' => SITE_DIR.'include/ask_product.php',
								'EDIT_TEMPLATE' => ''
							)
						);?>
					</div>
				</div>
			</div>
		</div>
	<?endif;?>
	<?$frame->end();?>

	<?// characteristics?>
	<?if($arResult['CHARACTERISTICS']):?>
		<div class="wraps">
			<hr />
			<h4 class="underline"><?=(strlen($arParams['T_CHARACTERISTICS']) ? $arParams['T_CHARACTERISTICS'] : GetMessage('T_CHARACTERISTICS'))?></h4>
			<div class="row chars">
				<div class="col-md-12">
					<div class="char-wrapp">
						<table class="props_table">
							<?foreach($arResult['CHARACTERISTICS'] as $arProp):?>
								<tr class="char">
									<td class="char_name">
										<?if($arProp['HINT']):?>
											<div class="hint">
												<span class="icons" data-toggle="tooltip" data-placement="top" title="<?=$arProp['HINT']?>"></span>
											</div>
										<?endif;?>
										<span><?=$arProp['NAME']?></span>
									</td>
									<td class="char_value">
										<span>
											<?if(is_array($arProp['DISPLAY_VALUE'])):?>
												<?foreach($arProp['DISPLAY_VALUE'] as $key => $value):?>
													<?if($arProp['DISPLAY_VALUE'][$key + 1]):?>
														<?=$value.'&nbsp;/ '?>
													<?else:?>
														<?=$value?>
													<?endif;?>
												<?endforeach;?>
											<?else:?>
												<?=$arProp['DISPLAY_VALUE']?>
											<?endif;?>
										</span>
									</td>
								</tr>
							<?endforeach;?>
						</table>
					</div>
				</div>
			</div>
		</div>
	<?endif;?>

	<?// docs files?>
	<?if($arResult['DISPLAY_PROPERTIES']['DOCUMENTS']['VALUE']):?>
		<div class="wraps">
			<hr />
			<h4 class="underline"><?=(strlen($arParams['T_DOCS']) ? $arParams['T_DOCS'] : GetMessage('T_DOCS'))?></h4>
			<div class="row docs">
				<?foreach($arResult['PROPERTIES']['DOCUMENTS']['VALUE'] as $docID):?>
					<?$arItem = CScorp::get_file_info($docID);?>
					<div class="col-md-6 <?=$arItem['TYPE']?>">
						<?
						$fileName = substr($arItem['ORIGINAL_NAME'], 0, strrpos($arItem['ORIGINAL_NAME'], '.'));
						$fileTitle = (strlen($arItem['DESCRIPTION']) ? $arItem['DESCRIPTION'] : $fileName);
						?>
						<a href="<?=$arItem['SRC']?>" target="_blank" title="<?=$fileTitle?>"><?=$fileTitle?></a>
						<?=GetMessage('CT_NAME_SIZE')?>:
						<?=CScorp::filesize_format($arItem['FILE_SIZE']);?>
					</div>
				<?endforeach;?>
			</div>
		</div>
	<?endif;?>

	<?
	$frame = $this->createFrame('video')->begin('');
	$frame->setAnimation(true);
	?>
	<?// video?>
	<?if($arResult['VIDEO']):?>
		<div class="wraps">
			<hr />
			<h4 class="underline"><?=(strlen($arParams['T_VIDEO']) ? $arParams['T_VIDEO'] : GetMessage('T_VIDEO'))?></h4>
			<div class="row video">
				<?foreach($arResult['VIDEO'] as $i => $arVideo):?>
					<div class="col-md-6 item">
						<div class="video_body">
							<video id="js-video_<?=$i?>" width="350" height="217"  class="video-js" controls="controls" preload="metadata" data-setup="{}">
								<source src="<?=$arVideo["path"]?>" type='video/mp4' />
								<p class="vjs-no-js">
									To view this video please enable JavaScript, and consider upgrading to a web browser that supports HTML5 video
								</p>
							</video>
						</div>
						<div class="title"><?=(strlen($arVideo["title"]) ? $arVideo["title"] : $i)?></div>
					</div>
				<?endforeach;?>
			</div>
		</div>
	<?endif;?>
	<?$frame->end();?>
	<script type="text/javascript">
	$(document).ready(function(){
		setBasketItemsClasses();
	});
	</script>
</div>