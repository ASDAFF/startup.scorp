<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//***********************************
//status and unsubscription/activation section
//***********************************
?>
<div class="status-block">
	<div class="subtitle"><?echo GetMessage("subscr_title_status")?></div>
	<form action="<?=$arResult["FORM_ACTION"]?>" method="get">
		<div class="row">
			<div class="col-md-6 col-sm-6">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="data-table">
					<tr valign="top">
						<td nowrap><?echo GetMessage("subscr_conf")?></td>
						<td nowrap class="<?echo ($arResult["SUBSCRIPTION"]["CONFIRMED"] == "Y"? "notetext":"errortext")?>"><?echo ($arResult["SUBSCRIPTION"]["CONFIRMED"] == "Y"? GetMessage("subscr_yes"):GetMessage("subscr_no"));?></td>
					</tr>
					<tr>
						<td nowrap><?echo GetMessage("subscr_act")?></td>
						<td nowrap class="<?echo ($arResult["SUBSCRIPTION"]["ACTIVE"] == "Y"? "notetext":"errortext")?>"><?echo ($arResult["SUBSCRIPTION"]["ACTIVE"] == "Y"? GetMessage("subscr_yes"):GetMessage("subscr_no"));?></td>
					</tr>
					<tr>
						<td nowrap><?echo GetMessage("adm_id")?></td>
						<td nowrap><?echo $arResult["SUBSCRIPTION"]["ID"];?>&nbsp;</td>
					</tr>
					<tr>
						<td nowrap><?echo GetMessage("subscr_date_add")?></td>
						<td nowrap><?echo $arResult["SUBSCRIPTION"]["DATE_INSERT"];?>&nbsp;</td>
					</tr>
					<tr>
						<td nowrap><?echo GetMessage("subscr_date_upd")?></td>
						<td nowrap><?echo $arResult["SUBSCRIPTION"]["DATE_UPDATE"];?>&nbsp;</td>
					</tr>
				</table>				
			</div>
			<div class="col-md-6  col-sm-6">
				<?if($arResult["SUBSCRIPTION"]["CONFIRMED"] <> "Y"):?>
					<p><?echo GetMessage("subscr_title_status_note1")?></p>
				<?elseif($arResult["SUBSCRIPTION"]["ACTIVE"] == "Y"):?>
					<p><?echo GetMessage("subscr_title_status_note2")?></p>
					<p><?echo GetMessage("subscr_status_note3")?></p>
				<?else:?>
					<p><?echo GetMessage("subscr_status_note4")?></p>
					<p><?echo GetMessage("subscr_status_note5")?></p>
				<?endif;?>
			</div>
		</div>

	<?if($arResult["SUBSCRIPTION"]["CONFIRMED"] == "Y"):?>	
		<div class="button-block">
			<?if($arResult["SUBSCRIPTION"]["ACTIVE"] == "Y"):?>
				<input type="submit" class="btn btn-default btn-md" name="unsubscribe" value="<?echo GetMessage("subscr_unsubscr")?>" />
				<input type="hidden" name="action" value="unsubscribe" />
			<?else:?>
				<input type="submit" class="btn btn-default btn-md" name="activate" value="<?echo GetMessage("subscr_activate")?>" />
				<input type="hidden" name="action" value="activate" />
			<?endif;?>
		</div>
	<?endif;?>
	<input type="hidden" name="ID" value="<?echo $arResult["SUBSCRIPTION"]["ID"];?>" />
	<?echo bitrix_sessid_post();?>
	</form>
</div>