<?if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true ) die();?>
<?$this->setFrameMode(false);?>
<div class="form inline<?=($arResult['isFormNote'] == 'Y' ? ' success' : '')?><?=($arResult['isFormErrors'] == 'Y' ? ' error' : '')?>">
	<?if($arResult["isFormNote"] == "Y"){?>
		<div class="form-header">
			<i class="fa fa-check"></i>
			<div class="text">
				<div class="title"><?=GetMessage("SUCCESS_TITLE")?></div>
				<?=$arResult["FORM_NOTE"]?>
			</div>
		</div>
		<script type="text/javascript">
		$(document).ready(function(){
			if(arScorpOptions['THEME']['USE_FORMS_GOALS'] !== 'NONE'){
				var eventdata = {goal: 'goal_webform_success' + (arScorpOptions['THEME']['USE_FORMS_GOALS'] === 'COMMON' ? '' : '_<?=$arParams["IBLOCK_ID"]?>'), params: <?=CUtil::PhpToJSObject($arParams, false)?>, result: <?=CUtil::PhpToJSObject($arResult, false)?>};
				BX.onCustomEvent('onCounterGoals', [eventdata]);
			}
		});
		</script>
		<?if( $arParams["DISPLAY_CLOSE_BUTTON"] == "Y" ){?>
			<div class="form-footer" style="text-align: center;">
				<?=str_replace('class="', 'class="btn-lg ', $arResult["CLOSE_BUTTON"])?>
			</div>
		<?}
	}else{?>
		<?=$arResult["FORM_HEADER"]?>
			<div class="form-header">
				<i class="fa fa-phone"></i>
				<div class="text">
					<?if( $arResult["isIblockTitle"] ){?>
						<div class="title"><?=$arResult["IBLOCK_TITLE"]?></div>
					<?}?>
					<?if( $arResult["isIblockDescription"] ){
						if( $arResult["IBLOCK_DESCRIPTION_TYPE"] == "text" ){?>
							<p><?=$arResult["IBLOCK_DESCRIPTION"]?></p>
						<?}else{?>
							<?=$arResult["IBLOCK_DESCRIPTION"]?>
						<?}
					}?>
				</div>
			</div>
			<?if($arResult['isFormErrors'] == 'Y'):?>
				<div class="form-error alert alert-danger">
					<?=$arResult['FORM_ERRORS_TEXT']?>
				</div>
			<?endif;?>
			<div class="form-body">
				<?if(is_array($arResult["QUESTIONS"])):?>
					<?foreach( $arResult["QUESTIONS"] as $FIELD_SID => $arQuestion ){
						if( $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden' ){
							echo $arQuestion["HTML_CODE"];
						}else{?>
							<div class="row" data-SID="<?=$FIELD_SID?>">
								<div class="form-group">
									<div class="col-md-12">
										<?=$arQuestion["CAPTION"]?>
										<div class="input">
											<?=$arQuestion["HTML_CODE"]?>
										</div>
										<?if( !empty( $arQuestion["HINT"] ) ){?>
											<div class="hint"><?=$arQuestion["HINT"]?></div>
										<?}?>
									</div>
								</div>
							</div>
						<?}
					}?>
				<?endif;?>
				<?if($arResult["isUseCaptcha"] === "Y"):?>
					<div class="row <?=($arResult["isUseReCaptcha"] === 'Y' ? 'recaptcha-row' : 'captcha-row')?>">
						<div class="form-group">
							<div class="col-md-12">
								<?=$arResult["CAPTCHA_CAPTION"]?>
								<?if($arResult["isUseReCaptcha"] === "Y"):?>
									<div class="input <?=($arResult['CAPTCHA_ERROR'] == 'Y' ? 'error' : '')?>">
										<input type="hidden" class="recaptcha" name="recaptcha" id="recaptcha">
										<div class="g-recaptcha" data-sitekey="<?=RECAPTCHA_SITE_KEY?>" data-callback="reCaptchaVerify" data-theme="light" data-size="normal"></div>
									</div>
								<?else:?>
									<div class="row">
										<div class="col-md-6 col-sm-6 col-xs-6">
											<?=$arResult["CAPTCHA_IMAGE"]?>
											<span class="refresh"><a href="javascript:;" rel="nofollow"><?=GetMessage("REFRESH")?></a></span>
										</div>
										<div class="col-md-6 col-sm-6 col-xs-6">
											<div class="input <?=($arResult['CAPTCHA_ERROR'] == 'Y' ? 'error' : '')?>">
												<?=$arResult["CAPTCHA_FIELD"]?>
											</div>
										</div>
									</div>
								<?endif;?>
							</div>
						</div>
					</div>
				<?endif;?>
				<?if($arParams["DISPLAY_PROCESSING_NOTE"] === "Y"):?>
					<div class="row processing-block">
						<div class="form-group">
							<div class="col-md-12">
								<div class="input">
									<input type="checkbox" class="processing_approval" id="processing_approval" name="processing_approval" value="Y"<?=($arParams["PROCESSING_NOTE_CHECKED"] === 'Y' ? ' checked' : '')?>>
									<label for="processing_approval"><?$APPLICATION->IncludeFile(SITE_DIR."include/processing_note.php", Array(), Array("MODE" => "html"))?></label>
								</div>
							</div>
						</div>
					</div>
				<?endif;?>
			</div>
			<div class="form-footer clearfix">
				<div class="pull-left required-fileds">
					<i class="star">*</i><?=GetMessage("FORM_REQUIRED_FILEDS")?>
				</div>
				<div class="pull-right">
					<?=str_replace('class="', 'class="btn-lg ', $arResult["SUBMIT_BUTTON"])?>
				</div>
			</div>
		<?=$arResult["FORM_FOOTER"]?>
	<?}?>
</div>
<script type="text/javascript">
$(document).ready(function(){
	if(arScorpOptions['THEME']['USE_CAPTCHA_FORM'] == 'RECAPTCHA'){
		reCaptchaRender();
	}

	$('.inline form[name="<?=$arResult["IBLOCK_CODE"]?>"]').validate({
		ignore: ".ignore",
		highlight: function( element ){
			$(element).parent().addClass('error');
		},
		unhighlight: function( element ){
			$(element).parent().removeClass('error');
		},
		submitHandler: function( form ){
			if( $('.inline form[name="<?=$arResult["IBLOCK_CODE"]?>"]').valid() ){
				$(form).find('button[type="submit"]').attr('disabled', 'disabled');
				form.submit();
			}
		},
		errorPlacement: function( error, element ){
			error.insertBefore(element);
		}
	});

	if(arScorpOptions['THEME']['PHONE_MASK'].length){
		var base_mask = arScorpOptions['THEME']['PHONE_MASK'].replace( /(\d)/g, '_' );
		$('.inline form[name="<?=$arResult['IBLOCK_CODE']?>"] input.phone').inputmask('mask', {'mask': arScorpOptions['THEME']['PHONE_MASK'] });
		$('.inline form[name="<?=$arResult['IBLOCK_CODE']?>"] input.phone').blur(function(){
			if( $(this).val() == base_mask || $(this).val() == '' ){
				if( $(this).hasClass('required') ){
					$(this).parent().find('div.error').html(BX.message('JS_REQUIRED'));
				}
			}
		});
	}

	$('.inline form[name="<?=$arResult["IBLOCK_CODE"]?>"] input.date').inputmask(arScorpOptions['THEME']['DATE_MASK'], { 'placeholder': arScorpOptions['THEME']['DATE_PLACEHOLDER'] });
	$('.inline form[name="<?=$arResult["IBLOCK_CODE"]?>"] input.datetime').inputmask(arScorpOptions['THEME']['DATETIME_MASK'], { 'placeholder': arScorpOptions['THEME']['DATETIME_PLACEHOLDER'] });

	$('.jqmClose').closest('.jqmWindow').jqmAddClose('.jqmClose');

	$('input[type=file]').uniform({fileButtonHtml: BX.message('JS_FILE_BUTTON_NAME'), fileDefaultHtml: BX.message('JS_FILE_DEFAULT')});
});
</script>