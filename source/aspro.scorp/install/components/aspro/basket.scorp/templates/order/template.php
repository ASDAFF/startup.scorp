<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$frame = $this->createFrame()->begin();
$frame->setAnimation(true);

global $USER;
$userID = CUser::GetID();
$userID = ($userID > 0 ? $userID : 0);

if(isset($_REQUEST['RESULT_ID']) && intval($_REQUEST['RESULT_ID']) > 0){
	$arRes = CIBlockElement::GetList(false, array('ID' => intval($_REQUEST['RESULT_ID'])), false, false, array('PROPERTY_SESSION_ID'))->Fetch();
	$checkSessionID = (bitrix_sessid() === trim($arRes['PROPERTY_SESSION_ID_VALUE']) ? true : false);
	
	if($checkSessionID && isset($_REQUEST['formresult']) && trim($_REQUEST['formresult']) === 'ADDOK'){
		$addOrder = true;
	}
}

if(!$arResult['ITEMS'] && !$addOrder){
	LocalRedirect($arParams['PATH_TO_BASKET']);
}
?>
<div class="basket_order">
	<div class="row">
		<div class="col-md-<?=(!$addOrder ? '8' : '12')?> col-sm-<?=(!$addOrder ? '8' : '12')?>">
			<?
			if($addOrder){
				include_once('confirm.php');
			}
			else{
				include_once('form.php');
			}
			?>
		</div>
		<?if($arResult['ITEMS'] && !$addOrder):?>
			<div class="col-md-4 col-sm-4">
				<div class="items">
					<div class="head">
						<div class="title pull-left"><?=GetMessage('T_HEAD_TITLE_USER_ORDER')?></div>
						<a class="btn btn-default btn-sm pull-right" href="<?=$arParams['PATH_TO_BASKET']?>"><?=GetMessage('T_HEAD_CHANGE_ORDER');?></a>
						<div class="clearfix"></div>
					</div>
					<div class="wrap">
						<?foreach($arResult['ITEMS'] as $arItem):?>
							<div class="item">
								<div class="name"><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a></div>
								<?if(strlen($arItem['PROPERTY_PRICE_VALUE'])):?>
									<div class="prices row">
										<div class="price col-md-6 col-sm-6 pull-left"><?=$arItem['PROPERTY_PRICE_VALUE']?><?=($arItem['QUANTITY'] ? ' x '.$arItem['QUANTITY'] : '')?></div>
										<?if(strlen($arItem['SUMM'])):?>
											<div class="summ col-md-6 col-sm-6 pull-right text-right"><?=$arItem['SUMM']?></div>
										<?endif;?>
									</div>
								<?endif;?>
							</div>
						<?endforeach;?>
					</div>
					<?if(isset($arResult['ALL_SUM']) && strlen($arResult['ALL_SUM'])):?>
						<div class="foot">
							<div class="total row">
								<span class="title pull-left col-md-6 col-sm-6"><?=GetMessage('T_BASKET_TOTAL_TITLE');?>:</span>
								<span class="summ pull-right col-md-6 col-sm-6 text-right"><?=$arResult['ALL_SUM']?></span>
							</div>
						</div>
					<?endif;?>
				</div>
			</div>
		<?endif;?>
	</div>
	<script>
		$(document).ready(function(){
			var index = 0,
				inputOrder = $('input#ORDER_LIST'),
				arItems = <?=CUtil::PhpToJSObject($arResult['ITEMS'], false)?>;
			
			if(inputOrder.length){
				var inputOrderName = inputOrder.attr('name');
				
				for (key in arItems) {
					var inputValue = ''
						+(typeof(arItems[key].ID) !== 'undefined' && parseInt(arItems[key].ID) > 0 ? 'ID: '+arItems[key].ID : '')
						+(typeof(arItems[key].PROPERTY_ARTICLE_VALUE) !== 'undefined' && arItems[key].PROPERTY_ARTICLE_VALUE.length ? ', '+BX.message('T_JS_ARTICLE')+arItems[key].PROPERTY_ARTICLE_VALUE : '')
						+(typeof(arItems[key].NAME) !== 'undefined' && arItems[key].NAME.length ? ', '+BX.message('T_JS_NAME')+arItems[key].NAME : '')
						+(typeof(arItems[key].PROPERTY_PRICE_VALUE) !== 'undefined' && arItems[key].PROPERTY_PRICE_VALUE.length ? ', '+BX.message('T_JS_PRICE')+arItems[key].PROPERTY_PRICE_VALUE : '')
						+(typeof(arItems[key].QUANTITY) !== 'undefined' && parseFloat(arItems[key].QUANTITY) > 0 ? ', '+BX.message('T_JS_QUANTITY')+arItems[key].QUANTITY : '')
						+(typeof(arItems[key].SUMM) !== 'undefined' && arItems[key].SUMM.length ? ', '+BX.message('T_JS_SUMM')+arItems[key].SUMM : '');

					inputOrder.clone().attr('name', inputOrderName+'['+index+']').val(inputValue).appendTo(inputOrder.parent());
					++index;
				}
				
				inputOrder.detach();
			}
		});
		$(window).resize(function(){
			if(window.matchMedia('(max-width: 767px)').matches){
				$('.basket_order .items').height('auto');
			}
			else{
				var orderHeight = $('.basket_order').outerHeight();
				$('.basket_order .items').outerHeight(orderHeight);
			}
		});
	</script>
</div>
<?$frame->end();?>