<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Кнопки");
?>
<div class="row">
	<div class="col-md-6">
		<h2>Buttons</h2>
		<button type="button" class="btn btn-default white">Default white</button>
		<button type="button" class="btn btn-default">Default</button>
		<button type="button" class="btn btn-primary">Primary</button>
		<button type="button" class="btn btn-success">Success</button>
		<button type="button" class="btn btn-info">Info</button>
		<button type="button" class="btn btn-warning">Warning</button>
		<button type="button" class="btn btn-danger">Danger</button>
		<button type="button" class="btn btn-transparent">Transparent</button>
		<button type="button" class="btn btn-link">Link</button>
		<h2 class="spaced">Buttons Disabled</h2>
		<button type="button" class="btn btn-default " disabled="disabled">Default Button</button>
		<button type="button" class="btn btn-primary" disabled="disabled">Primary button</button>
		<h2 class="spaced">Buttons with icon</h2>
		<p>
			<a class="btn btn-default wc" href=""><i class="fa fa-check "></i><span>Order project</span></a>
			<a class="btn btn-default wc" href="" disabled="disabled"><i class="fa fa-check "></i><span>Order project</span></a>
			<br />
			<a class="btn btn-default btn-xs wc" href=""><i class="fa fa-angle-right"></i><span>All news</span></a>
			<a class="btn btn-default btn-xs wc" href="" disabled="disabled"><i class="fa fa-angle-right"></i><span>All news</span></a>
		</p>
	</div>

	<div class="col-md-6">
		<h2>Buttons Sizes</h2>
		<p>
			<button type="button" class="btn btn-default btn-lg">Large button</button>
			<button type="button" class="btn btn-default btn-lg" disabled="disabled">Large button</button>
			<br />
			<button type="button" class="btn btn-default">Default button</button>
			<button type="button" class="btn btn-default" disabled="disabled">Default button</button>
			<br />
			<button type="button" class="btn btn-default btn-sm">Small button</button>
			<button type="button" class="btn btn-default btn-sm" disabled="disabled">Small button</button>
			<br />
			<button type="button" class="btn btn-default btn-xs">Extra small button</button>
			<button type="button" class="btn btn-default btn-xs" disabled="disabled">Extra small button</button>
		</p>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>