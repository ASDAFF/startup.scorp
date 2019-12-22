<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<div class="item-views list <?=($arParams['IMAGE_POSITION'] ? 'image_'.$arParams['IMAGE_POSITION'] : '')?> <?=($templateName = $component->{'__parent'}->{'__template'}->{'__name'})?>">
	<?// top pagination?>
	<?if($arParams['DISPLAY_TOP_PAGER']):?>
		<?=$arResult['NAV_STRING']?>
	<?endif;?>

	<div class="row items">
		<?foreach($arResult['ITEMS'] as $arItem):?>
			<?
			// edit/add/delete buttons for edit mode
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			// show preview picture?
			$bImage = strlen($arItem['FIELDS']['PREVIEW_PICTURE']['SRC']);
			$arImage = ($bImage ? CFile::ResizeImageGet($arItem['FIELDS']['PREVIEW_PICTURE']['ID'], array('width' => 148, 'height' => 148), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());
			$imageSrc = ($bImage ? $arImage['src'] : false);
			?>

			<?ob_start();?>
				<?// element name?>
				<?if(strlen($arItem['FIELDS']['NAME'])):?>
					<div class="title">
						<?=$arItem['NAME']?>
					</div>
				<?endif;?>
			<?$namePart = ob_get_clean();?>

			<?ob_start();?>
				<div class="text">
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
				</div>
			<?$textPart = ob_get_clean();?>

			<?ob_start();?>
				<?if($bImage):?>
					<div class="image">
						<?if($arItem['FIELDS']['DETAIL_PICTURE']):?><a href="<?=$arItem['FIELDS']['DETAIL_PICTURE']['SRC']?>" alt="<?=($bImage ? $arItem['FIELDS']['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['FIELDS']['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" class="img-inside fancybox"><?endif;?>
							<img src="<?=$imageSrc?>" alt="<?=($bImage ? $arItem['FIELDS']['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['FIELDS']['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" class="img-responsive" />
						<?if($arItem['FIELDS']['DETAIL_PICTURE']):?><span class="zoom"><i class="fa fa-16 fa-white-shadowed fa-search"></i></span></a><?endif;?>
					</div>
				<?endif;?>
			<?$imagePart = ob_get_clean();?>

			<div class="col-md-12">
				<div class="item<?=($bImage ? '' : ' wti')?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
					<?=$namePart?>
					<div class="info">
						<div class="row">
							<?if(!$bImage):?>
								<div class="col-md-12 col-sm-12 col-xs-12"><?=$textPart?></div>
							<?elseif($arParams['IMAGE_POSITION'] == 'right'):?>
								<div class="col-md-8 col-sm-12 col-xs-12"><?=$textPart?></div>
								<div class="col-md-4 col-sm-12 col-xs-12"><?=$imagePart?></div>
							<?else:?>
								<div class="col-md-4 col-sm-12 col-xs-12"><?=$imagePart?></div>
								<div class="col-md-8 col-sm-12 col-xs-12"><?=$textPart?></div>
							<?endif;?>
						</div>
					</div>
				</div>
			</div>
		<?endforeach;?>
	</div>

	<?// bottom pagination?>
	<?if($arParams['DISPLAY_BOTTOM_PAGER']):?>
		<?=$arResult['NAV_STRING']?>
	<?endif;?>
</div>