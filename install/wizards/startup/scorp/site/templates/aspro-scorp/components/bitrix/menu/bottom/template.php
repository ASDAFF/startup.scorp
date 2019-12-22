<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
$this->setFrameMode(true);
$colmd = 4;
$colsm = 6;
?>
<?if($arResult):?>
	<div class="bottom-menu">
		<div class="items row">
			<?foreach($arResult as $arItem):?>
				<?$bLink = strlen($arItem['LINK']);?>
				<div class="col-md-<?=$colmd?> col-sm-<?=$colsm?>">
					<div class="item<?=($arItem["SELECTED"] ? " active" : "")?>">
						<div class="title">
							<?if($bLink):?>
								<a href="<?=$arItem['LINK']?>"><?=$arItem['TEXT']?></a>
							<?else:?>
								<span><?=$arItem['TEXT']?></span>
							<?endif;?>
						</div>
					</div>
				</div>
			<?endforeach;?>
		</div>
	</div>
<?endif;?>