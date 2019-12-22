<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?$j=0;?>
<div class="item-views <?=$arParams['VIEW_TYPE']?> <?=($arParams['SHOW_TABS'] == 'Y' ? 'with_tabs' : '')?> <?=($arParams['IMAGE_POSITION'] ? 'image_'.$arParams['IMAGE_POSITION'] : '')?> <?=($templateName = $component->{'__parent'}->{'__template'}->{'__name'})?>">
	<?// top pagination?>
	<?if($arParams['DISPLAY_TOP_PAGER']):?>
		<?=$arResult['NAV_STRING']?>
	<?endif;?>

	<?if($arResult['SECTIONS']):?>
		<?// tabs?>
		<?if($arParams['SHOW_TABS'] == 'Y'):?>
			<div class="tabs">
				<ul class="nav nav-tabs">
					<?$i = 0;?>
					<?foreach($arResult['SECTIONS'] as $SID => $arSection):?>
						<?if(!$SID) continue;?>
						<li class="<?=$i++ == 0 ? 'active' : ''?>"><a data-toggle="tab" href="#<?=$this->GetEditAreaId($arSection['ID'])?>"><?=$arSection['NAME']?></a></li>
					<?endforeach;?>
				</ul>
		<?endif;?>

				<div class="<?=($arParams['SHOW_TABS'] == 'Y' ? 'tab-content' : 'group-content')?>">
					<?// group elements by sections?>
					<?foreach($arResult['SECTIONS'] as $SID => $arSection):?>
						<?
						// edit/add/delete buttons for edit mode
						$arSectionButtons = CIBlock::GetPanelButtons($arSection['IBLOCK_ID'], 0, $arSection['ID'], array('SESSID' => false, 'CATALOG' => true));
						$this->AddEditAction($arSection['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['IBLOCK_ID'], 'SECTION_EDIT'));
						$this->AddDeleteAction($arSection['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arSection['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
						?>
						<div id="<?=$this->GetEditAreaId($arSection['ID'])?>" class="tab-pane <?=(!$si++ || !$arSection['ID'] ? 'active' : '')?>">

							<?if($arParams['SHOW_SECTION_PREVIEW_DESCRIPTION'] == 'Y'):?>
								<?// section name?>
								<?if(strlen($arSection['NAME'])):?>
									<h3 class="underline"><?=$arSection['NAME']?></h3>
								<?endif;?>

								<?// section description text/html?>
								<?if(strlen($arSection['DESCRIPTION'])):?>
									<div class="text_before_items">
										<?=$arSection['DESCRIPTION']?>
									</div>
								<?endif;?>
							<?endif;?>

							<?// show section items?>
							<?foreach($arSection['ITEMS'] as $i => $arItem):?>
								<?$j++;?>
								<?
								// edit/add/delete buttons for edit mode
								$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
								$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
								// use detail link?
								$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
								// preview picture
								$bImage = strlen($arItem['FIELDS']['PREVIEW_PICTURE']['SRC']);
								$imageSrc = ($bImage ? $arItem['FIELDS']['PREVIEW_PICTURE']['SRC'] : false);
								$imageDetailSrc = ($bImage ? $arItem['FIELDS']['DETAIL_PICTURE']['SRC'] : false);
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

									<?// element detail text?>
									<?if(strlen($arItem['FIELDS']['DETAIL_TEXT'])):?>
										<div class="previewtext">
											<?if($arItem['DETAIL_TEXT_TYPE'] == 'text'):?>
												<p><?=$arItem['FIELDS']['DETAIL_TEXT']?></p>
											<?else:?>
												<?=$arItem['FIELDS']['DETAIL_TEXT']?>
											<?endif;?>
										</div>
									<?endif;?>

									<?// element display properties?>
									<?if($arItem['DISPLAY_PROPERTIES']):?>
										<div class="properties">
											<hr />
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
									<button class="btn btn-default" data-event="jqm" data-name="resume" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['aspro_scorp_form']['aspro_scorp_resume'][0]?>" data-autoload-POST="<?=$arItem['NAME']?>" data-autohide=""><?=GetMessage('BUTTON')?></button>
								<?$textPart = ob_get_clean();?>

								<?ob_start();?>
									<?if($bImage):?>
										<div class="image">
											<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="blink">
											<?elseif($arItem['FIELDS']['DETAIL_PICTURE']):?><a href="<?=$imageDetailSrc?>" alt="<?=($bImage ? $arItem['FIELDS']['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['FIELDS']['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" class="img-inside fancybox">
											<?endif;?>
												<img src="<?=$imageSrc?>" alt="<?=($bImage ? $arItem['FIELDS']['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['FIELDS']['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" class="img-responsive" />
											<?if($bDetailLink):?></a>
											<?elseif($arItem['FIELDS']['DETAIL_PICTURE']):?><span class="zoom"><i class="fa fa-16 fa-white-shadowed fa-search"></i></span></a>
											<?endif;?>
										</div>
									<?endif;?>
								<?$imagePart = ob_get_clean();?>

								<?if($arParams['VIEW_TYPE'] == 'list'):?>
									<div class="item<?=($bImage ? '' : ' wti')?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
										<div class="row">
											<?if(!$bImage):?>
												<div class="col-md-12"><div class="text"><?=$textPart?></div></div>
											<?elseif($arParams['IMAGE_POSITION'] == 'right'):?>
												<div class="col-md-9 col-sm-9 col-xs-12"><div class="text"><?=$textPart?></div></div>
												<div class="col-md-3 col-sm-3 col-xs-12"><?=$imagePart?></div>
											<?else:?>
												<div class="col-md-3 col-sm-3 col-xs-12"><?=$imagePart?></div>
												<div class="col-md-9 col-sm-9 col-xs-12"><div class="text"><?=$textPart?></div></div>
											<?endif;?>
										</div>
									</div>
								<?elseif($arParams['VIEW_TYPE'] == 'accordion'):?>
									<div class="accordion-type-2">
										<div class="item<?=($bImage ? '' : ' wti')?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
											<div class="accordion-head <?=($j==1?'accordion-open':'accordion-close')?>" data-toggle="collapse" data-parent="#accordion<?=$arSection['ID']?>" href="#accordion<?=$arItem['ID']?>_<?=$arSection['ID']?>">
												<a href="#"><?=$arItem['NAME']?><i class="fa fa-angle-down"></i></a>
												<?if(in_array('PAY', $arParams['PROPERTY_CODE'])):?>
													<div class="pay">
														<?if($arItem['DISPLAY_PROPERTIES']['PAY']['VALUE']):?>
															<?=GetMessage('PAY_ABOUT')?>&nbsp;<b><?=$arItem['DISPLAY_PROPERTIES']['PAY']['VALUE']?></b>
														<?else:?>
															<?=GetMessage('PAY_INTERVIEWS')?>
														<?endif;?>
													</div>
												<?endif;?>
											</div>
											<div id="accordion<?=$arItem['ID']?>_<?=$arSection['ID']?>" class="panel-collapse <?=($j==1?'in':'collapse')?>">
												<div class="accordion-body">
													<div class="row">
														<?if(!$bImage):?>
															<div class="col-md-12"><div class="text"><?=$textPart?></div></div>
														<?elseif($arParams['IMAGE_POSITION'] == 'right'):?>
															<div class="col-md-9"><div class="text"><?=$textPart?></div></div>
															<div class="col-md-3"><?=$imagePart?></div>
														<?else:?>
															<div class="col-md-3"><?=$imagePart?></div>
															<div class="col-md-9"><div class="text"><?=$textPart?></div></div>
														<?endif;?>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?endif;?>
							<?endforeach;?>
						</div>
					<?endforeach;?>
				</div>
		<?if($arParams['SHOW_TABS'] == 'Y'):?>
			</div>
		<?endif;?>
	<?endif;?>

	<?// bottom pagination?>
	<?if($arParams['DISPLAY_BOTTOM_PAGER']):?>
		<?=$arResult['NAV_STRING']?>
	<?endif;?>
</div>