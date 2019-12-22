<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>
<?
$this->setFrameMode(true);
if($arResult['ITEMS']){
	foreach($arResult['ITEMS'] as $i => $arItem){
		if(!is_array($arItem['FIELDS']['PREVIEW_PICTURE'])){
			unset($arResult['ITEMS'][$i]);
		}
	}
}
?>
<?if($arResult['ITEMS']):?>
	<?
	$qntyItems = count($arResult['ITEMS']);
	$countmd = ($qntyItems > 4 ? 5 : ($qntyItems > 3 ? 4 : ($qntyItems > 2 ? 3 : ($qntyItems > 1 ? 2 : 1))));
	$countsm = ($qntyItems > 2 ? 3 : ($qntyItems > 1 ? 2 : 1));
	$colmd = ($qntyItems > 4 ? 2 : ($qntyItems > 3 ? 3 : ($qntyItems > 2 ? 4 : ($qntyItems > 1 ? 6 : 12))));
	$colsm = ($qntyItems > 4 ? 4 : ($qntyItems > 3 ? 6 : 12));

	global $arTheme;
	$slideshowSpeed = abs(intval($arTheme['PARTNERSBANNER_SLIDESSHOWSPEED']['VALUE']));
	$animationSpeed = abs(intval($arTheme['PARTNERSBANNER_ANIMATIONSPEED']['VALUE']));
	$bAnimation = (bool)$slideshowSpeed;
	?>
	<div class="partners front">
		<hr>
		<div class="flexslider unstyled row" data-plugin-options='{"directionNav": false, "controlNav" :true, "animationLoop": true, <?=($bAnimation ? '"slideshow": true,' : '"slideshow": false,')?> <?=($slideshowSpeed >= 0 ? '"slideshowSpeed": '.$slideshowSpeed.',' : '')?> <?=($animationSpeed >= 0 ? '"animationSpeed": '.$animationSpeed.',' : '')?> "counts": [<?=$countmd?>, <?=$countsm?>, 1]}'>
			<ul class="slides items">
				<?foreach($arResult['ITEMS'] as $i => $arItem):?>
					<?
					// edit/add/delete buttons for edit mode
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					// use detail link?
					$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
					// preview image
					$bImage = strlen($arItem['FIELDS']['PREVIEW_PICTURE']['SRC']);
					$arImage = ($bImage ? CFile::ResizeImageGet($arItem['FIELDS']['PREVIEW_PICTURE']['ID'], array('width' => 166, 'height' => 90), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());
					$imageSrc = ($bImage ? $arImage['src'] : SITE_TEMPLATE_PATH.'/images/noimage.png');
					?>
					<li class="col-md-<?=$colmd?> col-sm-<?=$colsm?>">
						<div class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
							<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?endif;?>
								<img class="img-responsive" src="<?=$imageSrc?>" alt="<?=($bImage ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" />
							<?if($bDetailLink):?></a><?endif;?>
						</div>
					</li>
				<?endforeach;?>
			</ul>
		</div>
	</div>
<?endif;?>