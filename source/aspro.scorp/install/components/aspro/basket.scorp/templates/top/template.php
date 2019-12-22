<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$frame = $this->createFrame()->begin();
$frame->setAnimation(true);

$bItems = ($arResult['ITEMS_COUNT'] > 0 ? true : false);

$title_text = GetMessage("TITLE_BASKET", array("#SUMM#" => $arResult['ALL_SUM']));
if(intval($arResult['ITEMS_COUNT']) <= 0)
	$title_text = GetMessage("EMPTY_BASKET");
?>
<div class="basket_top pull-right">
	<div class="b_wrap">
		<a href="<?=$arParams['PATH_TO_BASKET']?>" class="icon" title="<?=$title_text;?>"><span class="count"><?=($bItems ? $arResult['ITEMS_COUNT'] : '0')?></span></a>
		<?if($bItems):?>
			<div class="dropdown">
				<ul class="items">
					<?foreach($arResult['ITEMS'] as $arItem):?>
						<?
						$imageSrc = (is_array($arItem['PICTURE']) && strlen($arItem['PICTURE']['IMAGE_70']['src']) ? $arItem['PICTURE']['IMAGE_70']['src'] : SITE_TEMPLATE_PATH.'/images/noimage_product.png');
						$imageTitle = (is_array($arItem['PICTURE']) && strlen($arItem['PICTURE']['DESCRIPTION']) ? $arItem['PICTURE']['DESCRIPTION'] : $arItem['NAME']);
						?>
						<li class="item clearfix" id="<?=$this->GetEditAreaId($arItem['ID'])?>" data-item='{"ID":"<?=$arItem['ID']?>"}'>
							<div class="image"><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><img class="img-responsive" src="<?=$imageSrc;?>" alt="<?=$imageTitle;?>" title="<?=$imageTitle;?>" /></a></div>
							<div class="foot">
								<div class="title"><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME'];?></a></div>
								<?if(strlen($arItem['PROPERTY_PRICE_VALUE'])):?>
									<div class="prices row">
										<div class="price col-md-6 pull-left"><?=$arItem['PROPERTY_PRICE_VALUE']?><?=($arItem['QUANTITY'] ? ' x '.$arItem['QUANTITY'] : '')?></div>
										<?if(strlen($arItem['SUMM'])):?>
											<div class="summ col-md-6 pull-right text-right"><?=$arItem['SUMM']?></div>
										<?endif;?>
									</div>
								<?endif;?>
							</div>
							<span class="remove"></span>
						</li>
					<?endforeach;?>
				</ul>
				<div class="buttons"><a class="btn btn-default" href="<?=$arParams['PATH_TO_BASKET']?>"><?=GetMessage('PATH_TO_BASKET_TITLE');?></a></div>
			</div>
		<?endif;?>
	</div>
</div>

<?$frame->end();?>