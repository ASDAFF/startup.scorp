<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$frame = $this->createFrame()->begin();
$frame->setAnimation(true);
global $arTheme;
$menu_class = (isset($arTheme['TOP_MENU']) && strlen($arTheme['TOP_MENU']['VALUE']) ? $arTheme['TOP_MENU']['VALUE'] : '');
$title_text = GetMessage("TITLE_BASKET", array("#SUMM#" => $arResult['ALL_SUM']));
if(intval($arResult['ITEMS_COUNT']) <= 0)
	$title_text = GetMessage("EMPTY_BASKET");
?>
<div class="basket fly<?=(strlen($menu_class) ? ' '.$menu_class : '')?>">
	<div class="wrap cont">
		<span class="opener" title="<?=$title_text ;?>">
			<span class="count<?=(intval($arResult['ITEMS_COUNT']) <= 0 ? ' empted' : '')?>"><?=$arResult['ITEMS_COUNT']?></span>
		</span>
		<h4><?=GetMessage('T_BASKET_TITLE');?></h4>
		<?if($arResult['ITEMS']):?>
			<div class="basket_wrap">
				<div class="items_wrap">
					<div class="items">
						<div class="head">
							<div class="title box"><?=GetMessage('T_HEAD_TITLE_NAME')?></div>
							<div class="title box prices"><?=GetMessage('T_HEAD_TITLE_PRICE')?></div>
							<div class="title box counter_t"><?=GetMessage('T_HEAD_TITLE_QUANTITY')?></div>
							<div class="title box prices summ"><?=GetMessage('T_HEAD_TITLE_SUMM')?></div>
							<div class="remove_bl box"></div>
						</div>
						<?foreach($arResult['ITEMS'] as $arItem):?>
							<?
							$imageSrc = (is_array($arItem['PICTURE']) && strlen($arItem['PICTURE']['IMAGE_70']['src']) ? $arItem['PICTURE']['IMAGE_70']['src'] : SITE_TEMPLATE_PATH.'/images/noimage_product.png');
							$imageTitle = (is_array($arItem['PICTURE']) && strlen($arItem['PICTURE']['DESCRIPTION']) ? $arItem['PICTURE']['DESCRIPTION'] : $arItem['NAME']);
							$quantity = (isset($arItem['QUANTITY']) && $arItem['QUANTITY'] > 0 ? $arItem['QUANTITY'] : '');
							?>
							<div class="item" data-item='{"ID":"<?=$arItem['ID']?>"}'>
								<div class="wrap">
									<div class="box">
										<div class="image"><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><img class="img-responsive" src="<?=$imageSrc;?>" alt="<?=$imageTitle;?>" title="<?=$imageTitle;?>" /></a></div>
										<div class="description">
											<div class="name"><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a></div>
											<div class="props">
												<?if(isset($arItem['PROPERTY_STATUS']) && strlen($arItem['PROPERTY_STATUS']['VALUE'])):?>
													<span class="label label-<?=$arItem['PROPERTY_STATUS']['XML_ID']?>"><?=$arItem['PROPERTY_STATUS']['VALUE']?></span>
												<?endif;?>
												<?if(isset($arItem['PROPERTY_ARTICLE_VALUE']) && strlen($arItem['PROPERTY_ARTICLE_VALUE'])):?>
													<span class="article"><?=GetMessage('S_ARTICLE')?>:&nbsp;<span><?=$arItem['PROPERTY_ARTICLE_VALUE']?></span></span>
												<?endif;?>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="prices box">
										<?if(isset($arItem['PROPERTY_PRICE_VALUE']) && strlen($arItem['PROPERTY_PRICE_VALUE'])):?>
											<div class="price_new">
												<span class="price_val"><?=$arItem['PROPERTY_PRICE_VALUE']?></span>
											</div>
										<?endif;?>
										<?if(isset($arItem['PROPERTY_PRICEOLD_VALUE']) && strlen($arItem['PROPERTY_PRICEOLD_VALUE'])):?>
											<div class="price_old">
												<span class="price_val"><?=$arItem['PROPERTY_PRICEOLD_VALUE']?></span>
											</div>
										<?endif;?>
									</div>
									<div class="buy_block box">
										<div class="counter">
											<div class="wrap">
												<span class="minus ctrl bgtransition"></span>
												<div class="input"><input type="text" value="<?=$quantity;?>" class="count" maxlength="5" /></div>
												<span class="plus ctrl bgtransition"></span>
												<?if(isset($arItem['SUMM']) && $arItem['SUMM'] > 0):?>
													<input type="hidden" name="PRICE" value="<?=$arItem['PROPERTY_FILTER_PRICE_VALUE']?>" />
												<?endif;?>
											</div>
										</div>
									</div>
									<div class="prices summ box">
										<?if(isset($arItem['SUMM']) && $arItem['SUMM'] > 0):?>
											<div class="price_new">
												<span class="price_val"><?=$arItem['SUMM'];?></span>
											</div>
										<?endif;?>
									</div>
									<div class="remove_bl box">
										<div class="wrap">
											<span class="remove"></span>
										</div>
									</div>
								</div>
							</div>
						<?endforeach;?>
					</div>
				</div>
				<div class="foot">
					<span class="remove all btn btn-default btn-sm pull-left" data-remove_all="Y"><span><?=GetMessage('T_BUTTON_REMOVE_ALL');?></span></span>
					<?if(isset($arResult['ALL_SUM']) && strlen($arResult['ALL_SUM'])):?>
						<div class="total pull-right"><?=GetMessage('T_BASKET_TOTAL_TITLE');?>: <span><?=$arResult['ALL_SUM']?></span></div>
					<?endif;?>
					<div class="clearfix"></div>
				</div>
				<div class="buttons">
					<span class="btn btn-default pull-left close_block"><?=GetMessage('T_BASKET_BUTTON_RETURN');?></span>
					<a class="btn btn-default pull-right" href="<?=$arParams['PATH_TO_ORDER']?>"><?=GetMessage('T_BASKET_BUTTON_ORDER');?></a>
					<a class="btn btn-default white pull-right" href="<?=$arParams['PATH_TO_BASKET']?>"><?=GetMessage('T_BASKET_BUTTON_BASKET');?></a>
					<div class="clearfix"></div>
				</div>
			</div>
		<?endif;?>
		<div class="basket_empty"<?=($arResult['ITEMS'] ? ' style="display:none;"' : '')?>>
			<div class="wrap">
				<h4><?=GetMessage('T_BASKET_EMPTY_TITLE');?></h4>
				<div class="description"><?=GetMessage('T_BASKET_EMPTY_DESCRIPTION');?></div>
				<div class="button"><a class="btn btn-default" href="<?SITE_DIR?>/catalog/"><?=GetMessage('T_BASKET_BUTTON_CATALOG');?></a></div>
			</div>
		</div>
	</div>
	<script>
	$(document).ready(function(){
		$('.close_block').on('click', function(){
			$('.basket.fly .opener').trigger('click');
		});
		$('.basket.fly .opener').on('click', function(){
			$(this).closest('.ajax_basket').toggleClass('opened');
		});
	});
	</script>
</div>
<?$frame->end();?>