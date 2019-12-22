<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?if($arResult['ITEMS']):?>
	<?
	$qntyItems = count($arResult['ITEMS']);
	$colmd = ($qntyItems > 3 ? 3 : ($qntyItems > 2 ? 4 : ($qntyItems > 1 ? 6 : 12)));
	$colsm = ($qntyItems > 2 ? 6 : ($qntyItems > 1 ? 6 : 12));
	?>
	<div class="row">
		<div class="maxwidth-theme">
			<div class="col-md-12">
				<div class="banners-small front">
					<div class="items row">
						<?foreach($arResult['ITEMS'] as $arItem):?>
							<?
							$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
							$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
							// preview image
							$bImage = strlen($arItem['DISPLAY_PROPERTIES']['ICON']['VALUE']);
							$arImage = ($bImage ? CFile::ResizeImageGet($arItem['DISPLAY_PROPERTIES']['ICON']['VALUE'], array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());
							$imageSrc = ($bImage ? $arImage['src'] : false);
							// link
							$bLink = strlen($arItem['DISPLAY_PROPERTIES']['LINK']['VALUE']);
							?>
							<div class="col-md-<?=$colmd?> col-sm-<?=$colsm?>">
								<div class="item<?=($bImage ? '' : ' wti')?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
									<?if($bImage):?>
										<div class="image">
											<img src=<?=$imageSrc?> alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>" />
										</div>
									<?endif;?>
									<div class="title">
										<?if($bLink):?><a href="<?=$arItem['DISPLAY_PROPERTIES']['LINK']['VALUE']?>"><?endif;?>
											<?=$arItem['NAME']?>
										<?if($bLink):?></a><?endif;?>
									</div>
								</div>
							</div>
						<?endforeach;?>
					</div>
					<script type="text/javascript">
					BX.addCustomEvent('onWindowResize', function(eventdata) {
						try{
							ignoreResize.push(true);
							var ih = $('.banners-small.front .item').height();
							$('.banners-small.front .item .title').each(function() {
								var h = $(this).height();
								var p = 33;
								//if(h > 45){
									if((p = Math.floor((ih - h) / 2)) < 0){
										p = 0;
									}
								//}
								$(this).css('padding-top', p + 'px');
							});
						}
						catch(e){}
						finally{
							ignoreResize.pop();
						}
					});
					</script>
				</div>
			</div>
		</div>
	</div>
<?endif;?>