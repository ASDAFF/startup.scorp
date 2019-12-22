<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(false);?>
<?$customColorExist = isset($arResult['BASE_COLOR']['LIST']['CUSTOM']) && isset($arResult['BASE_COLOR_CUSTOM']);?>
<div class="style-switcher <?=$_COOKIE['styleSwitcher'] == 'open' ? 'active' : ''?>">
	<div class="header"><?=GetMessage('THEME_MODIFY')?><span class="switch"><i class="fa fa-cogs"></i></span></div>
	<form method="POST" name="style-switcher">
		<?foreach($arResult as $optionCode => $arOption):?>
			<?if($arOption['THEME'] === 'Y' && $optionCode !== 'BASE_COLOR_CUSTOM'):?>
				<div class="block clearfix">
					<div class="block-title"><?=$arOption['TITLE']?></div>
					<div class="options" data-code="<?=$optionCode?>">
						<?if($optionCode == 'BASE_COLOR'):?>
							<input type="hidden" id="<?=$optionCode?>" name="<?=$optionCode?>" value="<?=$arOption['VALUE']?>" />
							<?foreach($arOption['LIST'] as $colorCode => $arColor):?>
								<?if($colorCode !== 'CUSTOM'):?>
									<div class="base_color <?=($arColor['CURRENT'] == 'Y' ? 'current' : '')?>" data-value="<?=$colorCode?>" data-color="<?=$arColor['COLOR']?>">
										<a href="javascript:;" data-option-id="<?=$optionCode?>" data-option-value="<?=$colorCode?>" title="<?=$arColor['TITLE']?>" style="background-color: <?=$arColor['COLOR']?>;"></a>
									</div>
								<?endif;?>
							<?endforeach;?>
							<?if($customColorExist):?>
								<?$customColor = str_replace('#', '', (strlen($arResult['BASE_COLOR_CUSTOM']['VALUE']) ? $arResult['BASE_COLOR_CUSTOM']['VALUE'] : $arResult['BASE_COLOR']['LIST'][$arResult['BASE_COLOR']['DEFAULT']]['COLOR']));?>	
								<?$arColor = $arOption['LIST']['CUSTOM'];?>
								<div class="base_color base_color_custom <?=($arColor['CURRENT'] == 'Y' ? 'current' : '')?>" data-value="CUSTOM" data-color="#<?=$customColor?>">
									<a href="javascript:;" data-option-id="<?=$optionCode?>" data-option-value="CUSTOM" title="<?=$arColor['TITLE']?>" style="background-color: #<?=$customColor?>;"></a>
									<input type="hidden" id="custom_picker" name="BASE_COLOR_CUSTOM" value="<?=$customColor?>" />
								</div>
							<?endif;?>
						<?else:?>
							<?if($arOption['TYPE'] == 'checkbox'):?>
								<input type="hidden" id="<?=$optionCode?>" name="<?=$optionCode?>" value="<?=$arOption['VALUE']?>" />
								<a href="javascript:;" data-option-id="<?=$optionCode?>" data-option-value="Y" class="<?=$arOption['VALUE'] == 'Y' ? 'current' : ''?>" data-value="Y"><?=GetMessage('S_YES')?></a>
								<a href="javascript:;" data-option-id="<?=$optionCode?>" data-option-value="N" class="<?=$arOption['VALUE'] !== 'Y' ? 'current' : ''?>" data-value="N"><?=GetMessage('S_NO')?></a>
							<?elseif($arOption['TYPE'] == 'selectbox' || $arOption['TYPE'] == 'multiselectbox'):?>
								<input type="hidden" id="<?=$optionCode?>" name="<?=$optionCode?>" value="<?=$arOption['VALUE']?>" />
								<?foreach($arOption['LIST'] as $variantCode => $arVariant):?>
									<a href="javascript:;" data-option-id="<?=$optionCode?>" data-option-value="<?=$variantCode?>" class="<?=$arVariant['CURRENT'] == 'Y' ? 'current' : ''?>"><?=$arVariant['TITLE']?></a>
								<?endforeach;?>
							<?elseif($arOption['TYPE'] == 'text'):?>
								<input id="<?=$optionCode?>" name="<?=$optionCode?>" value="<?=$arOption['VALUE']?>" />
							<?elseif($arOption['TYPE'] == 'textarea'):?>
							<?endif;?>
						<?endif;?>
					</div>
				</div>
			<?endif;?>
		<?endforeach;?>
		<div class="block">
			<div class="buttons">
				<a class="reset" href="javascript:;"><?=GetMessage('THEME_RESET')?><i class="fa fa-refresh"></i></a>
			</div>
		</div>
	</form>
	<script type="text/javascript">
	$(document).ready(function() {
		if($.cookie('styleSwitcher') == 'open'){
			$('.style-switcher').addClass('active');
		}

		$('#custom_picker').spectrum({
			preferredFormat: 'hex',
			showButtons: true,
			showInput: true,
			showPalette: false, 
			chooseText: '<?=GetMessage('CUSTOM_COLOR_CHOOSE')?>',
			cancelText: '<?=GetMessage('CUSTOM_COLOR_CANCEL')?>',
			containerClassName: 'custom_picker_container',
			replacerClassName: 'custom_picker_replacer',
			clickoutFiresChange: false,
			move: function(color) {
				var colorCode = color.toHexString();
				$('.base_color.base_color_custom a').attr('style', 'background:' + colorCode);
			},
			hide: function(color) {
				var colorCode = color.toHexString();
				$('.base_color.base_color_custom a').attr('style', 'background:' + colorCode);
			},
			change: function(color) {
				$('.base_color_custom').addClass('current').siblings().removeClass('current');
				$('form[name=style-switcher] input[name=' + $('.base_color_custom a').data('option-id') + ']').val($('.base_color_custom a').data('option-value'));
				$('form[name=style-switcher]').submit();
			}
		});

		$('.base_color_custom').click(function(e) {
			e.preventDefault();
			$('#custom_picker').spectrum('toggle');
			return false;
		});

		var curcolor = $('.base_color.current').data('color');
		if(curcolor != undefined && curcolor.length){
			$('#custom_picker').spectrum('set', curcolor);
			$('.base_color_custom a').attr('style', 'background:' + curcolor);
		}
		
		$('.style-switcher .switch').click(function(e){
			e.preventDefault();
			var styleswitcher = $(this).closest('.style-switcher');
			if(styleswitcher.hasClass('active')){
				styleswitcher.animate({left: '-' + styleswitcher.outerWidth() + 'px'}, 300).removeClass('active');
				$.removeCookie('styleSwitcher', {path: '/'});
			}
			else{
				styleswitcher.animate({left: '0'}, 300).addClass('active');
				var pos = styleswitcher.offset().top;
				if($(window).scrollTop() > pos){
					$('html, body').animate({scrollTop: pos}, 500);
				}
				$.cookie('styleSwitcher', 'open', {path: '/'});
			}
		});
		
		$('.style-switcher .reset').click(function(e){
			$('form[name=style-switcher]').append('<input type="hidden" name="THEME" value="default" />');
			$('form[name=style-switcher]').submit();
		});
		
		$('.style-switcher .options > a,.style-switcher .options > div:not(.base_color_custom) a').click(function(e){
			$(this).addClass('current').siblings().removeClass('current');
			$('form[name=style-switcher] input[name=' + $(this).data('option-id') + ']').val($(this).data('option-value'));
			$('form[name=style-switcher]').submit();
		});
	});
	</script>
</div>