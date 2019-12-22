<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<div class="item-views <?=$arParams['VIEW_TYPE']?> <?=($arParams['IMAGE_POSITION'] ? 'image_'.$arParams['IMAGE_POSITION'] : '')?> <?=($templateName = $component->{'__parent'}->{'__template'}->{'__name'})?>">
	<?// top pagination?>
	<?if($arParams['DISPLAY_TOP_PAGER']):?>
		<?=$arResult['NAV_STRING']?>
	<?endif;?>

	<?if($arResult['SECTIONS']):?>
		<div class="group-content">
			<?// group elements by sections?>
			<?foreach($arResult['SECTIONS'] as $si => $arSection):?>
				<?if($arParams['SHOW_SECTION_PREVIEW_DESCRIPTION'] == 'Y'):?>
					<?// section name?>
					<?if(strlen($arSection['NAME'])):?>
						<h3><?=$arSection['NAME']?></h3>
					<?endif;?>

					<?// section description text/html?>
					<?if(strlen($arSection['DESCRIPTION'])):?>
						<div class="text_before_items">
							<?=$arSection['DESCRIPTION']?>
						</div>
					<?endif;?>
				<?endif;?>

				<?// show section items?>
				<div class="row sid-<?=$arSection['ID']?> items">
					<?foreach($arSection['ITEMS'] as $i => $arItem):?>
						<?
						// edit/add/delete buttons for edit mode
						$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
						$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
						// post
						$post = $arItem['DISPLAY_PROPERTIES']['POST']['VALUE'];
						?>
						<div class="col-md-12">
							<div class="item review" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
								<div class="it">
									<?// element preview text?>
									<div class="text"><?=$arItem['FIELDS']['PREVIEW_TEXT']?></div>
									<?// docs files?>
									<?if($arItem['DISPLAY_PROPERTIES']['DOCUMENTS']['VALUE']):?>
										<div class="row docs">
											<?foreach((array)$arItem['DISPLAY_PROPERTIES']['DOCUMENTS']['VALUE'] as $docID):?>
												<?$arFile = CScorp::get_file_info($docID);?>
												<div class="col-md-6 <?=$arFile['TYPE']?>">
													<?
													$fileName = substr($arFile['ORIGINAL_NAME'], 0, strrpos($arFile['ORIGINAL_NAME'], '.'));
													$fileTitle = (strlen($arFile['DESCRIPTION']) ? $arFile['DESCRIPTION'] : $fileName);
													?>
													<a href="<?=$arFile['SRC']?>" target="_blank" title="<?=$fileTitle?>"><?=$fileTitle?></a>
													<?=GetMessage('CT_NAME_SIZE')?>:
													<?=CScorp::filesize_format($arFile['FILE_SIZE']);?>
												</div>
											<?endforeach;?>
										</div>
									<?endif;?>
									<div class="border"></div>
								</div>
								<div class="info">
									<?// element name?>
									<?if(strlen($arItem['FIELDS']['NAME'])):?>
										<div class="title"><?=$arItem['NAME']?></div>
									<?endif;?>
									<div class="post"><?=$post?></div>
								</div>
							</div>
						</div>
					<?endforeach;?>
				</div>
			<?endforeach;?>
		</div>
	<?endif;?>

	<?// bottom pagination?>
	<?if($arParams['DISPLAY_BOTTOM_PAGER']):?>
		<?=$arResult['NAV_STRING']?>
	<?endif;?>
</div>