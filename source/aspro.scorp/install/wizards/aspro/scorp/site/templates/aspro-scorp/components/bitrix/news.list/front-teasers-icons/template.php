<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
$this->setFrameMode(true);
?>
<?if($arResult['ITEMS']):?>
	<?
	$qntyItems = count($arResult['ITEMS']);
	$colmd = ($qntyItems > 2 ? 4 : ($qntyItems > 1 ? 6 : 12));
	$colsm = ($qntyItems > 1 ? 6 : 12);
	?>
	<div class="item-views sections teasers front icons">
		<?$APPLICATION->IncludeComponent(
			"bitrix:main.include",
			"",
			Array(
				"AREA_FILE_SHOW" => "file",
				"PATH" => SITE_DIR."include/front-teasers.php",
				"EDIT_TEMPLATE" => ""
			)
		);?>
		<?if($arParams['PAGER_SHOW_ALL']):?>
			<a href="<?=str_replace('#SITE'.'_DIR#', SITE_DIR, $arResult['LIST_PAGE_URL'])?>" class="btn btn-default white btn-xs"><span><?=GetMessage('S_TO_ALL_SERVICES')?></span></a>
		<?endif;?>
		<div class="items row">
			<?foreach($arResult['ITEMS'] as $arItem):?>
				<?
				// edit/add/delete buttons for edit mode
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				// icon
				$bIcon = strlen(trim($arItem['DISPLAY_PROPERTIES']['ICON']['VALUE']));
				$iconSrc = ($bIcon ? $arItem['DISPLAY_PROPERTIES']['ICON']['VALUE'] : false);
				// link
				$bLink = strlen($arItem['DISPLAY_PROPERTIES']['LINK']['VALUE']);
				?>
				<div class="col-md-<?=$colmd?> col-sm-<?=$colsm?>">
					<div class="item noborder<?=($bIcon ? '' : ' wti')?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
						<?// icon or preview picture?>
						<?if($bIcon):?>
							<div class="image">
								<i class="fa <?=$iconSrc?>"></i>
							</div>
						<?endif;?>
						
						<div class="info">
							<?// element name?>
							<?if(strlen($arItem['FIELDS']['NAME'])):?>
								<div class="title">
									<?if($bLink):?><a href="<?=$arItem['DISPLAY_PROPERTIES']['LINK']['VALUE']?>"><?endif;?>
										<?=$arItem['NAME']?>
									<?if($bLink):?></a><?endif;?>
								</div>
							<?endif;?>

							<?// element preview text?>
							<?if(strlen($arItem['FIELDS']['PREVIEW_TEXT'])):?>
								<div class="text">
									<?=$arItem['FIELDS']['PREVIEW_TEXT']?>
								</div>
							<?endif;?>
						</div>
					</div>
				</div>
			<?endforeach;?>
		</div>
		<script type="text/javascript">
		$(document).ready(function(){
			$('.teasers .item .title').sliceHeight();
			$('.teasers .item').sliceHeight();
		});
		</script>
		<hr />
	</div>
<?endif;?>