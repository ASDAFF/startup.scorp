<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$frame = $this->createFrame()->begin();
$frame->setAnimation(true);
?>
<div class="basket default">
	<div class="wrap cont">
		<?if($arResult['ITEMS']):?>
			<input type="hidden" value="<?=$APPLICATION->GetCurUri();?>" name="getPageUri">
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
						<div>
						<?foreach($arResult['ITEMS'] as $arItem):?>
							<?
							$arItemButtons = CIBlock::GetPanelButtons($arItem['IBLOCK_ID'], $arItem['ID'], 0, array('SESSID' => false, 'CATALOG' => true));
							$this->AddEditAction($arItem['ID'], $arItemButtons['edit']['edit_element']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
							$this->AddDeleteAction($arItem['ID'], $arItemButtons['edit']['delete_element']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
							
							$imageSrc = (is_array($arItem['PICTURE']) && strlen($arItem['PICTURE']['IMAGE_110']['src']) ? $arItem['PICTURE']['IMAGE_110']['src'] : SITE_TEMPLATE_PATH.'/images/noimage_product.png');
							$imageTitle = (is_array($arItem['PICTURE']) && strlen($arItem['PICTURE']['DESCRIPTION']) ? $arItem['PICTURE']['DESCRIPTION'] : $arItem['NAME']);
							$quantity = (isset($arItem['QUANTITY']) && $arItem['QUANTITY'] > 0 ? $arItem['QUANTITY'] : '');
							?>
							<div class="item" id="<?=$this->GetEditAreaId($arItem['ID'])?>" data-item='{"ID":"<?=$arItem['ID']?>"}'>
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
									<div class="buy_block lg box">
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
									<div class="clearfix"></div>
								</div>
							</div>
						<?endforeach;?>
						</div>
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
					<a class="btn btn-default btn-lg white pull-left" href="<?=$arParams['PATH_TO_CATALOG']?>"><?=GetMessage('T_BASKET_BUTTON_RETURN');?></a>
					<a class="btn btn-default btn-lg pull-right" href="<?=$arParams['PATH_TO_ORDER']?>"><?=GetMessage('T_BASKET_BUTTON_ORDER');?></a>
					<span class="btn btn-default btn-lg pull-right print btn-transparent"><span><?=GetMessage('T_BASKET_BUTTON_PRINT');?></span></span>
					<div class="clearfix"></div>
				</div>
			</div>
		<?endif;?>
		<div class="basket_empty"<?=($arResult['ITEMS'] ? ' style="display:none;"' : '')?>>
			<div class="wrap">
				<h4><?=GetMessage('T_BASKET_EMPTY_TITLE');?></h4>
				<div class="description"><?=GetMessage('T_BASKET_EMPTY_DESCRIPTION');?></div>
				<div class="button"><a class="btn btn-default" href="<?=$arParams['PATH_TO_CATALOG']?>"><?=GetMessage('T_BASKET_BUTTON_CATALOG');?></a></div>
			</div>
		</div>
	</div>
</div>
<?$frame->end();?>