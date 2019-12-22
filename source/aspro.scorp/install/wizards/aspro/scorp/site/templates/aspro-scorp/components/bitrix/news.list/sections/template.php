<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?if($arResult['SECTIONS']):?>
	<div class="item-views sections">	
		<?
		$qntyItems = count($arResult['SECTIONS']);
		$colmd = ($qntyItems > 1 ? 6 : 12);
		$colsm = ($qntyItems > 1 ? 6 : 12);
		$bShowSectionImage = in_array('PREVIEW_PICTURE', $arParams['FIELD_CODE']);
		?>
		<div class="items row">
			<?foreach($arResult['SECTIONS'] as $arSection):?>
				<?
				// edit/add/delete buttons for edit mode
				$arSectionButtons = CIBlock::GetPanelButtons($arSection['IBLOCK_ID'], 0, $arSection['ID'], array('SESSID' => false, 'CATALOG' => true));
				$this->AddEditAction($arSection['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['IBLOCK_ID'], 'SECTION_EDIT'));
				$this->AddDeleteAction($arSection['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				// preview picture
				if($bShowSectionImage){
					$bImage = strlen($arSection['~PICTURE']);
					$arSectionImage = ($bImage ? CFile::ResizeImageGet($arSection['~PICTURE'], array('width' => 200, 'height' => 133), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());
					$imageSectionSrc = ($bImage ? $arSectionImage['src'] : SITE_TEMPLATE_PATH.'/images/noimage_sections.png');
				}
				?>
				<div class="col-md-<?=$colmd?> col-sm-<?=$colsm?>">
					<div class="item noborder<?=($bShowSectionImage ? '' : ' wti')?>" id="<?=$this->GetEditAreaId($arSection['ID'])?>">
						<?// icon or preview picture?>
						<?if($bShowSectionImage):?>
							<div class="image">
								<a href="<?=$arSection['SECTION_PAGE_URL']?>" class="blink">
									<img src="<?=$imageSectionSrc?>" alt="<?=$arSection['PICTURE']['ALT']?>" title="<?=$arSection['PICTURE']['TITLE']?>" class="img-responsive" />
								</a>
							</div>
						<?endif;?>
						
						<div class="info">
							<?// section name?>
							<?if(in_array('NAME', $arParams['FIELD_CODE'])):?>
								<div class="title">
									<a href="<?=$arSection['SECTION_PAGE_URL']?>">
										<?=$arSection['NAME']?>
									</a>
								</div>
							<?endif;?>

							<?// section info text?>
							<?if(in_array('PREVIEW_TEXT', $arParams['FIELD_CODE']) && strlen($arSection['UF_INFOTEXT'])):?>
								<div class="text">
									<p><?=$arSection['UF_INFOTEXT']?></p>
								</div>
							<?endif;?>
						</div>
					</div>
				</div>
			<?endforeach;?>
		</div>
		<script type="text/javascript">
		$(document).ready(function(){
			$('.item-views.sections .item .title').sliceHeight();
			$('.item-views.sections .item').sliceHeight();
		});
		</script>
	</div>
<?endif;?>