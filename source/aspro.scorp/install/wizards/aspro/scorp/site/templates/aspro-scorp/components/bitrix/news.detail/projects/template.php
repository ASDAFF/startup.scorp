<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
$frame = $this->createFrame()->begin('');
$frame->setBrowserStorage(true);
?>
<?// element name?>
<?if($arParams['DISPLAY_NAME'] != 'N' && strlen($arResult['NAME'])):?>
	<h2 class="underline"><?=$arResult['NAME']?></h2>
<?endif;?>

<?if($arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE_XML_ID'] == 'YES'):?>
	<?ob_start();?>
		<span class="btn btn-default wc vert" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['aspro_scorp_form']['aspro_scorp_question'][0]?>" data-autoload-need_product="<?=$arResult['NAME']?>" data-name="question"><i class="fa fa-comment "></i><span><?=(strlen($arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : GetMessage('S_ASK_QUESTION'))?></span></span>
		<div class="margin-bottom-20">
			<?$APPLICATION->IncludeComponent(
				'bitrix:main.include',
				'',
				Array(
					'AREA_FILE_SHOW' => 'file',
					'PATH' => SITE_DIR.'include/ask_question.php',
					'EDIT_TEMPLATE' => ''
				)
			);?>
		</div>
	<?$askPart = ob_get_clean();?>
<?endif;?>

<?if($arResult['GALLERY']):?>
	<div class="head">
		<div class="row">
			<?if($arResult['GALLERY']):?>
				<div class="<?=($arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE_XML_ID'] == 'YES' ? 'col-md-10' : 'col-md-12')?>">
					<div class="row galery">
						<div class="inner">
							<div class="flexslider unstyled row" id="slider" data-plugin-options='{"animation": "slide", "directionNav": true, "controlNav" :false, "animationLoop": true, "sync": ".detail .galery #carousel", "slideshow": false, "counts": [1, 1, 1]}'>
								<ul class="slides items">
									<?$countAll = count($arResult['GALLERY']);?>
									<?foreach($arResult['GALLERY'] as $i => $arPhoto):?>
										<li class="col-md-1 col-sm-1 item">
											<a href="<?=$arPhoto['DETAIL']['SRC']?>" class="fancybox" rel="gallery" target="_blank" title="<?=$arPhoto['TITLE']?>">
												<img src="<?=$arPhoto['PREVIEW']['src']?>" class="img-responsive inline" title="<?=$arPhoto['TITLE']?>" alt="<?=$arPhoto['ALT']?>" />
												<span class="zoom">
													<i class="fa fa-16 fa-white-shadowed fa-search-plus"></i>
												</span>
											</a>
										</li>
									<?endforeach;?>
								</ul>
							</div>
							<?if(count($arResult["GALLERY"]) > 1):?>
								<div class="thmb flexslider unstyled" id="carousel">
									<ul class="slides">
										<?foreach($arResult["GALLERY"] as $arPhoto):?>
											<li class="blink">
												<img class="img-responsive inline" border="0" src="<?=$arPhoto["THUMB"]["src"]?>" title="<?=$arPhoto['TITLE']?>" alt="<?=$arPhoto['ALT']?>" />
											</li>
										<?endforeach;?>
									</ul>
								</div>
								<style type="text/css">
								.projects.detail .galery #carousel.flexslider{max-width:<?=ceil(((count($arResult['GALLERY']) <= 4 ? count($arResult['GALLERY']) : 4) * 107.5) - 7.5 + 60)?>px;}
								@media (max-width: 991px){
									.projects.detail .galery #carousel.flexslider{max-width:<?=ceil(((count($arResult['GALLERY']) <= 2 ? count($arResult['GALLERY']) : 2) * 107.5) - 7.5 + 60)?>px;}
								}
								</style>
							<?endif;?>
						</div>
						<script type="text/javascript">
						$(document).ready(function(){
							InitFlexSlider(); // for ajax mode
							$('.detail .galery .item').sliceHeight({slice: <?=$countAll?>, lineheight: -3});
							$('.detail .galery #carousel').flexslider({
								animation: 'slide',
								controlNav: false,
								animationLoop: true,
								slideshow: false,
								itemWidth: 100,
								itemMargin: 7.5,
								minItems: 2,
								maxItems: 4,
								asNavFor: '.detail .galery #slider'
							});
						});
						</script>
					</div>
				</div>
			<?endif;?>

			<?if($arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE_XML_ID'] == 'YES'):?>
				<style type="text/css">
				@media (min-width:992px){
					.projects.detail .galery .inner {padding-right: 50px;}
				}
				</style>
				<div class="<?=($arResult['GALLERY'] ? 'col-md-2 hidden-sm hidden-xs' : 'col-md-12');?>">
					<div class="info">
						<?// ask question?>
						<div class="ask_a_question ">
							<div class="inner">
								<?=$askPart?>
							</div>
						</div>
					</div>
				</div>
			<?endif;?>
		</div>
	</div>
<?endif;?>

<?// ask question?>
<?if($arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE_XML_ID'] == 'YES'):?>
	<div class="ask_a_question <?=($arResult['GALLERY'] ? 'visible-sm visible-xs' : '')?>">
		<div class="inner">
			<?=$askPart?>
		</div>
	</div>
<?endif;?>

<?if(strlen($arResult['FIELDS']['DETAIL_TEXT'])):?>
	<div class="content">
		<?// element detail text?>
		<?if($arResult['DETAIL_TEXT_TYPE'] == 'text'):?>
			<p><?=$arResult['FIELDS']['DETAIL_TEXT'];?></p>
		<?else:?>
			<?=$arResult['FIELDS']['DETAIL_TEXT'];?>
		<?endif;?>
	</div>
<?endif;?>

<?// order?>
<?if($arResult['DISPLAY_PROPERTIES']['FORM_PROJECT']['VALUE_XML_ID'] == 'YES'):?>
	<div class="order-block">
		<div class="row">
			<div class="col-md-4 col-sm-4 col-xs-5 valign">
				<span class="btn btn-default btn-lg" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['aspro_scorp_form']['aspro_scorp_order_project'][0]?>" data-name="order_project" data-autoload-project="<?=$arResult['NAME']?>"><?=(strlen($arParams['S_ORDER_PROJECT']) ? $arParams['S_ORDER_PROJECT'] : GetMessage('S_ORDER_PROJECT'))?></span>
			</div>
			<div class="col-md-8 col-sm-8 col-xs-7 valign">
				<div class="text">
					<?$APPLICATION->IncludeComponent(
						'bitrix:main.include',
						'',
						Array(
							'AREA_FILE_SHOW' => 'file',
							'PATH' => SITE_DIR.'include/ask_project.php',
							'EDIT_TEMPLATE' => ''
						)
					);?>
				</div>
			</div>
		</div>
	</div>
<?endif;?>

<?// characteristics?>
<?if($arResult['CHARACTERISTICS']):?>
	<div class="wraps">
		<hr />
		<h4 class="underline"><?=(strlen($arParams['T_CHARACTERISTICS']) ? $arParams['T_CHARACTERISTICS'] : GetMessage('T_CHARACTERISTICS'))?></h4>
		<div class="row chars">
			<div class="col-md-12">
				<div class="char-wrapp">
					<table class="props_table">
						<?foreach($arResult['CHARACTERISTICS'] as $PCODE => $arProp):?>
							<tr class="char">
								<td class="char_name">
									<?if($arProp['HINT']):?>
										<div class="hint">
											<span class="icons" data-toggle="tooltip" data-placement="top" title="<?=$arProp['HINT']?>"></span>
										</div>
									<?endif;?>
									<span><?=$arProp['NAME']?>:&nbsp;</span>
								</td>
								<td class="char_value">
									<span>
										<?if(is_array($arProp["DISPLAY_VALUE"])):?>
											<?$val = implode("&nbsp;/ ", $arProp["DISPLAY_VALUE"]);?>
										<?else:?>
											<?$val = $arProp["DISPLAY_VALUE"];?>
										<?endif;?>
										<?if($PCODE == "SITE"):?>
											<!--noindex-->
											<?=str_replace("href=", "rel='nofollow' target='_blank' href=", $val);?>
											<!--/noindex-->
										<?elseif($PCODE == "EMAIL"):?>
											<a href="mailto:<?=$val?>"><?=$val?></a>
										<?else:?>
											<?=$val?>
										<?endif;?>
									</span>
								</td>
							</tr>
						<?endforeach;?>
					</table>
				</div>
			</div>
		</div>
	</div>
<?endif;?>

<?// docs files?>
<?if($arResult['DISPLAY_PROPERTIES']['DOCUMENTS']['VALUE']):?>
	<div class="wraps">
		<hr />
		<h4 class="underline"><?=(strlen($arParams['T_DOCS']) ? $arParams['T_DOCS'] : GetMessage('T_DOCS'))?></h4>
		<div class="row docs">
			<?foreach($arResult['PROPERTIES']['DOCUMENTS']['VALUE'] as $docID):?>
				<?$arItem = CScorp::get_file_info($docID);?>
				<div class="col-md-6 <?=$arItem['TYPE']?>">
					<?
					$fileName = substr($arItem['ORIGINAL_NAME'], 0, strrpos($arItem['ORIGINAL_NAME'], '.'));
					$fileTitle = (strlen($arItem['DESCRIPTION']) ? $arItem['DESCRIPTION'] : $fileName);
					?>
					<a href="<?=$arItem['SRC']?>" target="_blank" title="<?=$fileTitle?>"><?=$fileTitle?></a>
					<?=GetMessage('CT_NAME_SIZE')?>:
					<?=CScorp::filesize_format($arItem['FILE_SIZE']);?>
				</div>
			<?endforeach;?>
		</div>
	</div>
<?endif;?>

<?// video?>
<?if($arResult['VIDEO']):?>
	<div class="wraps">
		<hr />
		<h4 class="underline"><?=(strlen($arParams['T_VIDEO']) ? $arParams['T_VIDEO'] : GetMessage('T_VIDEO'))?></h4>
		<div class="row video">
			<?foreach($arResult['VIDEO'] as $i => $arVideo):?>
				<div class="col-md-6 item">
					<div class="video_body">
						<video id="js-video_<?=$i?>" width="350" height="217"  class="video-js" controls="controls" preload="metadata" data-setup="{}">
							<source src="<?=$arVideo["path"]?>" type='video/mp4' />
							<p class="vjs-no-js">
								To view this video please enable JavaScript, and consider upgrading to a web browser that supports HTML5 video
							</p>
						</video>
					</div>
					<div class="title"><?=(strlen($arVideo["title"]) ? $arVideo["title"] : $i)?></div>
				</div>
			<?endforeach;?>
		</div>
	</div>
<?endif;?>
<?$frame->end();?>