<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?$this->setFrameMode(true);?>
<?
global $arTheme, $orderViewBasketHtml;
$bOrderViewBasket = (trim($arTheme['ORDER_VIEW']['VALUE']) === 'Y' && trim($arTheme['ORDER_BASKET_VIEW']['VALUE']) === 'HEADER');
$showBasket = (strlen(trim($arTheme['URL_BASKET_SECTION']['VALUE'])) && CSite::inDir($arTheme['URL_BASKET_SECTION']['VALUE']) || strlen(trim($arTheme['URL_ORDER_SECTION']['VALUE'])) && CSite::inDir($arTheme['URL_ORDER_SECTION']['VALUE']) ? 'N' : '');
?>
<?if($arResult):?>
	<div class="table-menu hidden-xs<?=($bOrderViewBasket  && $showBasket !== 'N' ? ' basketTrue' : '')?>">
		<table>
			<tr>
				<?foreach($arResult as $arItem):?>
					<?$bShowChilds = $arParams["MAX_LEVEL"] > 1;?>
					<td class="<?=($arItem["CHILD"] ? "dropdown" : "")?> <?=($arItem["SELECTED"] ? "active" : "")?>">
						<div class="wrap">
							<a class="<?=($arItem["CHILD"] && $bShowChilds ? "dropdown-toggle" : "")?>" href="<?=$arItem["LINK"]?>" title="<?=$arItem["TEXT"]?>">
								<?=$arItem["TEXT"]?>
								<?if($arItem["CHILD"] && $bShowChilds):?>
									&nbsp;<i class="fa fa-angle-down"></i>
								<?endif;?>
							</a>
							<?if($arItem["CHILD"] && $bShowChilds):?>
								<span class="tail"></span>
								<ul class="dropdown-menu">
									<?foreach($arItem["CHILD"] as $arSubItem):?>
										<?$bShowChilds = $arParams["MAX_LEVEL"] > 2;?>
										<li class="<?=($arSubItem["CHILD"] && $bShowChilds ? "dropdown-submenu" : "")?> <?=($arSubItem["SELECTED"] ? "active" : "")?>">
											<a href="<?=$arSubItem["LINK"]?>" title="<?=$arSubItem["TEXT"]?>"><?=$arSubItem["TEXT"]?></a>
											<?if($arSubItem["CHILD"] && $bShowChilds):?>
												<ul class="dropdown-menu">
													<?foreach($arSubItem["CHILD"] as $arSubSubItem):?>
														<?$bShowChilds = $arParams["MAX_LEVEL"] > 3;?>
														<li class="<?=($arSubSubItem["CHILD"] && $bShowChilds ? "dropdown-submenu" : "")?> <?=($arSubSubItem["SELECTED"] ? "active" : "")?>">
															<a href="<?=$arSubSubItem["LINK"]?>" title="<?=$arSubSubItem["TEXT"]?>"><?=$arSubSubItem["TEXT"]?></a>
															<?if($arSubSubItem["CHILD"] && $bShowChilds):?>
																<ul class="dropdown-menu">
																	<?foreach($arSubSubItem["CHILD"] as $arSubSubSubItem):?>
																		<li class="<?=($arSubSubSubItem["SELECTED"] ? "active" : "")?>">
																			<a href="<?=$arSubSubSubItem["LINK"]?>" title="<?=$arSubSubSubItem["TEXT"]?>"><?=$arSubSubSubItem["TEXT"]?></a>
																		</li>
																	<?endforeach;?>
																</ul>
															<?endif;?>
														</li>
													<?endforeach;?>
												</ul>
											<?endif;?>
										</li>
									<?endforeach;?>
								</ul>
							<?endif;?>
						</div>
					</td>
				<?endforeach;?>
				<td class="dropdown js-dropdown nosave" style="display:none;">
					<div class="wrap">
						<a class="dropdown-toggle more-items" href="#">
							<span>...</span>
						</a>
						<span class="tail"></span>
						<ul class="dropdown-menu"></ul>
					</div>
				</td>
				<td class="search-item nosave">
					<div class="wrap<?=($bOrderViewBasket && $showBasket !== 'N' ? ' clearfix' : '')?>">
						<a href="#" class="search-icon pull-left" title="<?=GetMessage("SEARCH")?>">
							<i class="fa fa-search"></i>
						</a>
					</div>
				</td>
			</tr>
		</table>
	</div>
<?endif;?>
<?if($arResult):?>
	<ul class="nav nav-pills responsive-menu visible-xs" id="mainMenu">
		<?foreach($arResult as $arItem):?>
			<?$bShowChilds = $arParams["MAX_LEVEL"] > 1;?>
			<li class="<?=($arItem["CHILD"] && $bShowChilds ? "dropdown" : "")?> <?=($arItem["SELECTED"] ? "active" : "")?>">
				<a class="<?=($arItem["CHILD"] && $bShowChilds ? "dropdown-toggle1" : "")?>" href="<?=$arItem["LINK"]?>" title="<?=$arItem["TEXT"]?>">
					<?=$arItem["TEXT"]?>
					<?if($arItem["CHILD"] && $bShowChilds):?>
						<i class="fa fa-angle-down dropdown-toggle"></i>
					<?endif;?>
				</a>
				<?if($arItem["CHILD"] && $bShowChilds):?>
					<ul class="dropdown-menu">
						<?foreach($arItem["CHILD"] as $arSubItem):?>
							<?$bShowChilds = $arParams["MAX_LEVEL"] > 2;?>
							<li class="<?=($arSubItem["CHILD"] && $bShowChilds ? "dropdown-submenu dropdown-toggle" : "")?> <?=($arSubItem["SELECTED"] ? "active" : "")?>">
								<a href="<?=$arSubItem["LINK"]?>" title="<?=$arSubItem["TEXT"]?>">
									<?=$arSubItem["TEXT"]?>
									<?if($arSubItem["CHILD"] && $bShowChilds):?>
										&nbsp;<i class="fa fa-angle-down"></i>
									<?endif;?>
								</a>
								<?if($arSubItem["CHILD"] && $bShowChilds):?>
									<ul class="dropdown-menu">
										<?foreach($arSubItem["CHILD"] as $arSubSubItem):?>
											<?$bShowChilds = $arParams["MAX_LEVEL"] > 3;?>
											<li class="<?=($arSubSubItem["CHILD"] && $bShowChilds ? "dropdown-submenu dropdown-toggle" : "")?> <?=($arSubSubItem["SELECTED"] ? "active" : "")?>">
												<a href="<?=$arSubSubItem["LINK"]?>" title="<?=$arSubSubItem["TEXT"]?>">
													<?=$arSubSubItem["TEXT"]?>
													<?if($arSubSubItem["CHILD"] && $bShowChilds):?>
														&nbsp;<i class="fa fa-angle-down"></i>
													<?endif;?>
												</a>
												<?if($arSubSubItem["CHILD"] && $bShowChilds):?>
													<ul class="dropdown-menu">
														<?foreach($arSubSubItem["CHILD"] as $arSubSubSubItem):?>
															<li class="<?=($arSubSubSubItem["SELECTED"] ? "active" : "")?>">
																<a href="<?=$arSubSubSubItem["LINK"]?>" title="<?=$arSubSubSubItem["TEXT"]?>"><?=$arSubSubSubItem["TEXT"]?></a>
															</li>
														<?endforeach;?>
													</ul>
												<?endif;?>
											</li>
										<?endforeach;?>
									</ul>
								<?endif;?>
							</li>
						<?endforeach;?>
					</ul>
				<?endif;?>
			</li>
		<?endforeach;?>
		<li class="search">
			<div class="search-input-div">
				<input class="search-input" type="text" autocomplete="off" maxlength="50" size="40" placeholder="<?=GetMessage("CT_BST_SEARCH_BUTTON")?>" value="" name="q">
			</div>
			<div class="search-button-div">
				<button class="btn btn-search btn-default" value="<?=GetMessage("CT_BST_SEARCH_BUTTON")?>" name="s" type="submit"><?=GetMessage("CT_BST_SEARCH_BUTTON")?></button>
			</div>
		</li>
	</ul>
<?endif;?>