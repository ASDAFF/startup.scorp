<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<div class="item-views list <?=($arParams['IMAGE_POSITION'] ? 'image_'.$arParams['IMAGE_POSITION'] : '')?> <?=($templateName = $component->{'__parent'}->{'__template'}->{'__name'})?>">
	<?// top pagination?>
	<?if($arParams['DISPLAY_TOP_PAGER']):?>
		<?=$arResult['NAV_STRING']?>
	<?endif;?>

	<?if($arResult['ITEMS']):?>
		<div class="items row">
			<?// show section items?>
			<?foreach($arResult['ITEMS'] as $i => $arItem):?>
				<?
				// edit/add/delete buttons for edit mode
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				// use detail link?
				$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
				$bImage = strlen($arItem['FIELDS']['PREVIEW_PICTURE']['SRC']);
				$imageSrc = ($bImage ? $arItem['FIELDS']['PREVIEW_PICTURE']['SRC'] : false);
				$imageDetailSrc = ($bImage ? $arItem['FIELDS']['DETAIL_PICTURE']['SRC'] : false);
				// show active date period
				$bActiveDate = strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']) || ($arItem['DISPLAY_ACTIVE_FROM'] && in_array('DATE_ACTIVE_FROM', $arParams['FIELD_CODE']));
				?>

				<?ob_start();?>
					<?// element name?>
					<?if(strlen($arItem['FIELDS']['NAME'])):?>
						<div class="title">
							<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?endif;?>
								<?=$arItem['NAME']?>
							<?if($bDetailLink):?></a><?endif;?>
						</div>
					<?endif;?>

					<?// date active period?>
					<?if($bActiveDate):?>
						<div class="period">
							<?if(strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE'])):?>
								<span class="label label-info"><?=$arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']?></span>
							<?else:?>
								<span class="label"><?=$arItem['DISPLAY_ACTIVE_FROM']?></span>
							<?endif;?>
						</div>
					<?endif;?>

					<?// element preview text?>
					<?if(strlen($arItem['FIELDS']['PREVIEW_TEXT'])):?>
						<div class="previewtext">
							<?if($arItem['PREVIEW_TEXT_TYPE'] == 'text'):?>
								<p><?=$arItem['FIELDS']['PREVIEW_TEXT']?></p>
							<?else:?>
								<?=$arItem['FIELDS']['PREVIEW_TEXT']?>
							<?endif;?>
						</div>
					<?endif;?>

					<?// element display properties?>
					<?if($arItem['DISPLAY_PROPERTIES']):?>
						<div class="properties">
							<?foreach($arItem['DISPLAY_PROPERTIES'] as $PCODE => $arProperty):?>
								<?if(in_array($PCODE, array('PERIOD', 'TITLE_BUTTON', 'LINK_BUTTON'))) continue;?>
								<div class="property">
									<?if($arProperty['XML_ID']):?>
										<i class="fa <?=$arProperty['XML_ID']?>"></i>&nbsp;
									<?else:?>
										<?=$arProperty['NAME']?>:&nbsp;
									<?endif;?>
									<?if(is_array($arProperty['DISPLAY_VALUE'])):?>
										<?$val = implode('&nbsp;/&nbsp;', $arProperty['DISPLAY_VALUE']);?>
									<?else:?>
										<?$val = $arProperty['DISPLAY_VALUE'];?>
									<?endif;?>
									<?if($PCODE == 'SITE'):?>
										<!--noindex-->
										<?=str_replace("href=", "rel='nofollow' target='_blank' href=", $val);?>
										<!--/noindex-->
									<?elseif($PCODE == 'EMAIL'):?>
										<a href="mailto:<?=$val?>"><?=$val?></a>
									<?else:?>
										<?=$val?>
									<?endif;?>
								</div>
							<?endforeach;?>
						</div>
					<?endif;?>
					<?if($bDetailLink):?>
						<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="btn btn-default btn-sm"><span><?=GetMessage('TO_ALL')?></span></a>
					<?endif;?>
				<?$textPart = ob_get_clean();?>

				<?ob_start();?>
					<?if($bImage):?>
						<div class="image">
							<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="blink">
							<?elseif($arItem['FIELDS']['DETAIL_PICTURE']):?><a href="<?=$imageDetailSrc?>" alt="<?=($bImage ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" class="img-inside fancybox">
							<?endif;?>
								<img src="<?=$imageSrc?>" alt="<?=($bImage ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" class="img-responsive" />
							<?if($bDetailLink):?></a>
							<?elseif($arItem['FIELDS']['DETAIL_PICTURE']):?><span class="zoom"><i class="fa fa-16 fa-white-shadowed fa-search"></i></span></a>
							<?endif;?>
						</div>
					<?endif;?>
				<?$imagePart = ob_get_clean();?>
				<div class="col-md-12">
					<?if($i):?>
						<hr />
					<?endif;?>
					<div class="item noborder<?=($bImage ? '' : ' wti')?><?=($bActiveDate ? ' wdate' : '')?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
						<div class="row">
							<?if(!$bImage):?>
								<div class="col-md-12"><div class="text"><?=$textPart?></div></div>
							<?elseif($arParams['IMAGE_POSITION'] == 'right'):?>
								<div class="col-md-8 col-sm-8 col-xs-12"><div class="text"><?=$textPart?></div></div>
								<div class="col-md-4 col-sm-4 col-xs-12"><?=$imagePart?></div>
							<?else:?>
								<div class="col-md-4 col-sm-4 col-xs-12"><?=$imagePart?></div>
								<div class="col-md-8 col-sm-8 col-xs-12"><div class="text"><?=$textPart?></div></div>
							<?endif;?>
						</div>
					</div>
				</div>
			<?endforeach;?>
		</div>
	<?endif;?>

	<?// bottom pagination?>
	<?if($arParams['DISPLAY_BOTTOM_PAGER']):?>
		<?=$arResult['NAV_STRING']?>
	<?endif;?>

	<?// section description?>
	<?if(is_array($arResult['SECTION']['PATH'])):?>
		<?$arCurSectionPath = end($arResult['SECTION']['PATH']);?>
		<?if(strlen($arCurSectionPath['DESCRIPTION']) && strpos($_SERVER['REQUEST_URI'], 'PAGEN') === false):?>
			<div class="cat-desc"><hr style="<?=(strlen($arResult['NAV_STRING']) && $arParams['DISPLAY_BOTTOM_PAGER'] ? 'margin-top:16px;' : '')?>" /><?=$arCurSectionPath['DESCRIPTION']?></div>
		<?endif;?>
	<?endif;?>
</div>