					<?CScorp::checkRestartBuffer();?>
					<?IncludeTemplateLangFile(__FILE__);?>
					<?if(!$isIndex):?>
								<?if(!$isMenu):?>
									</div><?// class=col-md-12 col-sm-12 col-xs-12 content-md?>
								<?elseif($isMenu && $arTheme["SIDE_MENU"]["VALUE"] == "LEFT"):?>
									</div><?// class=col-md-9 col-sm-9 col-xs-8 content-md?>
								<?elseif($isMenu && $arTheme["SIDE_MENU"]["VALUE"] == "RIGHT"):?>
									</div><?// class=col-md-9 col-sm-9 col-xs-8 content-md?>
									<div class="col-md-3 col-sm-3 col-xs-4 right-menu-md">
										<?$APPLICATION->IncludeComponent("bitrix:menu", "left", array(
											"ROOT_MENU_TYPE" => "left",
											"MENU_CACHE_TYPE" => "A",
											"MENU_CACHE_TIME" => "3600",
											"MENU_CACHE_USE_GROUPS" => "Y",
											"MENU_CACHE_GET_VARS" => array(
											),
											"MAX_LEVEL" => "4",
											"CHILD_MENU_TYPE" => "subleft",
											"USE_EXT" => "Y",
											"DELAY" => "N",
											"ALLOW_MULTI_SELECT" => "Y"
											),
											false
										);?>
										<div class="sidearea">
											<?$APPLICATION->ShowViewContent('under_sidebar_content');?>
											<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/under_sidebar.php"), false);?>
										</div>
									</div>
								<?endif;?>
						<?if(!$isContacts):?>
							</div><?// class="maxwidth-theme?>
						</div><?// class=row?>
						<?endif;?>
					<?endif;?>
				</div><?// class=container?>
				<?if($isIndex):?>
					<?=$indexEpilog; // buffered from indexblocks.php?>
				<?endif;?>
			</div><?// class=main?>
		</div><?// class=body?>
		<footer id="footer">
			<?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
					"AREA_FILE_SHOW" => "file",
					"PATH" => SITE_DIR."include/footer/subscribe.php",
					"EDIT_TEMPLATE" => "standard.php"
				)
			);?>
			<div class="container">
				<div class="row">
					<div class="maxwidth-theme">
						<div class="col-md-3 hidden-sm hidden-xs">
							<div class="copy">
								<?$APPLICATION->IncludeFile(SITE_DIR."include/copy.php", Array(), Array(
										"MODE" => "php",
										"NAME" => "Copyright",
									)
								);?>
							</div>
							<div id="bx-composite-banner"></div>
						</div>
						<div class="col-md-9 col-sm-12">
							<div class="row">
								<div class="col-md-9 col-sm-9">
									<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", array(
										"ROOT_MENU_TYPE" => "bottom",
										"MENU_CACHE_TYPE" => "A",
										"MENU_CACHE_TIME" => "3600000",
										"MENU_CACHE_USE_GROUPS" => "N",
										"MENU_CACHE_GET_VARS" => array(
										),
										"MAX_LEVEL" => "1",
										"CHILD_MENU_TYPE" => "",
										"USE_EXT" => "Y",
										"DELAY" => "N",
										"ALLOW_MULTI_SELECT" => "Y"
										),
										false
									);?>
								</div>
								<div class="col-md-3 col-sm-3">
									<div class="info">
										<div class="phone">
											<i class="fa fa-phone"></i> 
											<?$APPLICATION->IncludeFile(SITE_DIR."include/site-phone.php", array(), array(
													"MODE" => "html",
													"NAME" => "Phone",
												)
											);?>
										</div>
										<div class="email">
											<i class="fa fa-envelope"></i>
											<?$APPLICATION->IncludeFile(SITE_DIR."include/site-email.php", array(), array(
													"MODE" => "html",
													"NAME" => "E-mail",
												)
											);?>
										</div>
									</div>
									<div class="social">
										<?if(!IsModuleInstalled("subscribe")):?>
											<?$APPLICATION->IncludeComponent(
												"aspro:social.info.scorp",
												".default",
												array(
													"CACHE_TYPE" => "A",
													"CACHE_TIME" => "3600000",
													"CACHE_GROUPS" => "N",
													"COMPONENT_TEMPLATE" => ".default"
												),
												false
											);?>
										<?endif;?>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-3 hidden-md hidden-lg">
							<div class="copy">
								<?$APPLICATION->IncludeFile(SITE_DIR."include/copy.php", Array(), Array(
										"MODE" => "php",
										"NAME" => "Copyright",
									)
								);?>
							</div>
							<div id="bx-composite-banner"></div>
						</div>
					</div>
				</div>
			</div>
		</footer>
		<div class="bx_areas">
			<?$APPLICATION->IncludeFile(SITE_DIR."include/invis-counter.php", Array(), Array(
					"MODE" => "text",
					"NAME" => "Counters place for Yandex.Metrika, Google.Analytics",
				)
			);?>
		</div>
		<?CScorp::SetMeta();?>
	</body>
</html>