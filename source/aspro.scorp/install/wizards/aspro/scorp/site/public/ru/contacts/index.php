<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");?>
<div class="row contacts" itemscope itemtype="http://schema.org/Organization">
	<div class="col-md-12">
		<h4 itemprop="name"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-name.php", Array(), Array("MODE" => "html", "NAME" => "Name"));?></h4>
	</div>
	<br />
	<div class="col-md-4">
		<div itemprop="description"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-about.php", Array(), Array("MODE" => "html", "NAME" => "Contacts about"));?></div>
		<br />
		<br />
		<table cellpadding="0" cellspasing="0">
			<tbody>
				<tr>
					<td align="left" valign="top"><i class="fa colored fa-map-marker"></i></td><td align="left" valign="top"><span class="dark_table">Адрес</span>
						<br />
						<span itemprop="address"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-address.php", Array(), Array("MODE" => "html", "NAME" => "Address"));?></span>
					</td>
				</tr>
				<tr>
					<td align="left" valign="top"><i class="fa colored fa-phone"></i></td><td align="left" valign="top"> <span class="dark_table">Телефон</span>
						<br />
						<span itemprop="telephone"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-phone.php", Array(), Array("MODE" => "html", "NAME" => "Phone"));?></span>
					</td>
				</tr>
				<tr>
					<td align="left" valign="top"><i class="fa colored fa-envelope"></i></td><td align="left" valign="top"> <span class="dark_table">E-mail</span>
						<br />
						<span itemprop="email"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-email.php", Array(), Array("MODE" => "html", "NAME" => "Email"));?></span>
					</td>
				</tr>
				<tr>
					<td align="left" valign="top"><i class="fa colored fa-clock-o"></i></td><td align="left" valign="top"> <span class="dark_table">Режим работы</span>
						<br />
						<?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-schedule.php", Array(), Array("MODE" => "html", "NAME" => "Schedule"));?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-8">
		<?$APPLICATION->IncludeComponent(
			"bitrix:map.yandex.view",
			".default",
			Array(
			"INIT_MAP_TYPE" => "MAP",
			"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:55.75365144278215;s:10:\"yandex_lon\";d:37.6204816153973;s:12:\"yandex_scale\";i:15;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:3:\"LON\";d:37.620438700053064;s:3:\"LAT\";d:55.753445723094714;s:4:\"TEXT\";s:10:\"Наша фирма\";}}}",
			"MAP_WIDTH" => "100%",
			"MAP_HEIGHT" => "500",
			"CONTROLS" => array(0=>"ZOOM",1=>"TYPECONTROL",2=>"SCALELINE",),
			"OPTIONS" => array(0=>"ENABLE_DBLCLICK_ZOOM",1=>"ENABLE_DRAGGING",),
			"MAP_ID" => ""
			)
			);
		?>
	</div>
</div>

		</div><?// class=col-md-12 col-sm-12 col-xs-12 content-md?>
	</div><?// class="maxwidth-theme?>
</div><?// class=row?>

<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("contacts-form-block");?>
<?$captcha = (in_array($arTheme['USE_CAPTCHA_FORM']['VALUE'], array('HIDDEN', 'IMAGE', 'RECAPTCHA')) ? $arTheme['USE_CAPTCHA_FORM']['VALUE'] : 'NONE');?>
<?$processing = ($arTheme['DISPLAY_PROCESSING_NOTE']['VALUE'] === 'Y' ? 'Y' : 'N');?>
<?$processing_checked = ($arTheme['PROCESSING_NOTE_CHECKED']['VALUE'] === 'Y' ? 'Y' : 'N');?>
<?$APPLICATION->IncludeComponent(
	"aspro:form.scorp",
	"contacts",
	array(
		"IBLOCK_TYPE" => "aspro_scorp_form",
		"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_scorp_form"]["aspro_scorp_question"][0],
		"USE_CAPTCHA" => $captcha,
		"DISPLAY_PROCESSING_NOTE" => $processing,
		"PROCESSING_NOTE_CHECKED" => $processing_checked,
		"IS_PLACEHOLDER" => "N",
		"SUCCESS_MESSAGE" => "<p>Спасибо! Ваше сообщение отправлено!</p>",
		"SEND_BUTTON_NAME" => "Отправить",
		"SEND_BUTTON_CLASS" => "btn btn-default",
		"DISPLAY_CLOSE_BUTTON" => "Y",
		"CLOSE_BUTTON_NAME" => "Обновить страницу",
		"CLOSE_BUTTON_CLASS" => "btn btn-default refresh-page",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "100000",
		"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);?>
<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("contacts-form-block", "");?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>