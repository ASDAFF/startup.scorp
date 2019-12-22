<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оформление");?>
<div class="row">
	<div class="col-md-6">
		<h1>Заголовок H1</h1>
		<p>Pellentesque pellentesque eget tempor tellus. Fusce lacllentesque eget tempor tellus ellentesque pelleinia tempor malesuada. Pellentesque pellentesque eget tempor tellus ellentesque pellentesque eget tempor tellus. Fusce lacinia tempor malesuada.</p>
		<h2>Заголовок H2</h2>
		<p>Pellentesque pellentesque usce lacllentesque eget tempor tellus ellentesque pelleinia tempor malesuada. Pellentesque pellentesque eget tempor tellus ellentesque pellentesque eget tempor tellus. tellus eget tempor. Fusce lacinia tempor malesuada.</p>
		<h3>Заголовок H3</h3>
		<p>Pellentesque tempor tellus eget pellentesque. usce lacllentesque eget tempor tellus ellentesque pelleinia tempor malesuada. Pellentesque pellentesque eget tempor tellus ellentesque pellentesque eget tempor tellus. Fusce lacinia tempor malesuada.</p>
		<h4>Заголовок H4</h4>
		<p>Pellentesque pellentesque tempor tellus eget fermentum. usce lacllentesque eget tempor tellus ellentesque pelleinia tempor malesuada. Pellentesque pellentesque eget tempor tellus ellentesque pellentesque eget tempor tellus. </p>
		<h5>Заголовок H5</h5>
		<p>Pellentesque pellentesque tempor llentesque pellentesque tempor tellus eget libero llentesque pellentesque tempor tellus eget libero tellus ementellentesque tempor tellus eget fermentum. usce lacllentesque eget tempor tellus ellenellentesque tempor tellus eget fermentum. usce lacllentesque eget tempor tellus ellenum.</p>
		<h6>Заголовок H6</h6>
		<p>Pellentesque pellentesque tempor tellus eget libero</p>
	</div>
	<div class="col-md-6">
		<h2 class="spaced">Heading Helper Classes</h2>
		<p>
			<span class="label label-info">Heads up!</span> You can use helper classes in order to align better the titles.<br />
			<strong>Such as:</strong> short, shorter, tall, taller, spaced, more-spaced and page-header.
		</p>
		<h2 class="spaced">Отзыв</h2>
		<div class="item review">
			<div class="it">
				<div class="text">Mauris aliquet ultricies ante, non faucibus ante gravida sed. Sed ultrices pellentesque purus, vulputate volutpat ipsum hendrerit sed neque sed sapien rutrum laoreet justo ultrices. In pellentesque lorem condimentum dui morbi pulvinar dui non quam pretium ut lacinia tortor.</div>
				<div class="border"></div>
			</div>
			<div class="info">
				<div class="title">Константин Константинопольский</div>
				<div class="post">Директор магазина></div>
			</div>
		</div>
		<h2 class="more-spaced">Цитата</h2>
		<blockquote><div>Mauris aliquet ultricies ante, non faucibus ante gravida sed. Sed ultrices pellentesque purus, vulputate volutpat ipsum hendrerit sed neque sed sapien rutrum laoreet justo ultrices. In pellentesque lorem condimentum dui morbi pulvinar dui non quam pretium ut lacinia tortor.<small>Someone famous <cite title="Source Title">Source Title</cite></small></div></blockquote>
		<h3 class="underline">Заголовок блока</h3>
		<h4 class="underline">Заголовок второстепенных блоков</h4>
		<h5 class="underline">Заголовок характеристик</h5>
	</div>
</div>
<hr class="tall" />
<div class="row">
	<div class="col-md-12">
		<h2 class="underline">Drop Caps</h2>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<p class="drop-caps">Pellentesque pellentesque eget tempor tellus. Fusce lacllentesque eget tempor tellus ellentesque pelleinia tempor malesuada. Pellentesque pellentesque eget tempor tellus ellentesque pellentesque eget tempor tellus. Fusce lacinia tempor malesuada. Pellentesque pellentesque eget tempor tellus ellentesque pellentesque eget tempor tellus. Fusce lacinia tempor malesuada.</p>
	</div>
	<div class="col-md-6">
		<p class="drop-caps secundary">Pellentesque pellentesque eget tempor tellus. Fusce lacllentesque eget tempor tellus ellentesque pelleinia tempor malesuada. Pellentesque pellentesque eget tempor tellus ellentesque pellentesque eget tempor tellus. Fusce lacinia tempor malesuada. Pellentesque pellentesque eget tempor tellus ellentesque pellentesque eget tempor tellus. Fusce lacinia tempor malesuada.</p>
	</div>
</div>
<hr class="tall" />
<div class="row">
	<div class="col-md-12">
		<h2 class="underline">Списки</h2>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
	<h4>Unordered</h4>
	<ul>
		<li>Lorem ipsum dolor sit amet</li>
		<li>Consectetur adipiscing elit</li>
		<li>Integer molestie lorem at massa</li>
		<li>Facilisis in pretium nisl aliquet</li>
		<li>Nulla volutpat aliquam velit
			<ul>
				<li>Phasellus iaculis neque</li>
				<li>Purus sodales ultricies</li>
				<li>Vestibulum laoreet porttitor sem</li>
				<li>Ac tristique libero volutpat at</li>
			</ul>
		</li>
		<li>Faucibus porta lacus fringilla vel</li>
		<li>Aenean sit amet erat nunc</li>
		<li>Eget porttitor lorem</li>
	</ul>
	<h4 class="spaced">Unstyled</h4>
	<ul class="list-unstyled">
		<li>Lorem ipsum dolor sit amet</li>
		<li>Consectetur adipiscing elit</li>
		<li>Integer molestie lorem at massa</li>
		<li>Facilisis in pretium nisl aliquet</li>
		<li>Faucibus porta lacus fringilla vel</li>
		<li>Aenean sit amet erat nunc</li>
		<li>Eget porttitor lorem</li>
	</ul>
	<h4 class="spaced">Description</h4>
	<dl><dt>Description lists</dt><dd>A description list is perfect for defining terms.</dd><dt>Euismod</dt><dd>Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.</dd> 			<dd>Donec id elit non mi porta gravida at eget metus.</dd><dt>Malesuada porta</dt><dd>Etiam porta sem malesuada magna mollis euismod.</dd></dl></div>
	<div class="col-md-6">
		<h4 class="spaced">Ordered</h4>
		<ol>
			<li>Lorem ipsum dolor sit amet</li>
			<li>Consectetur adipiscing elit</li>
			<li>Integer molestie lorem at massa</li>
			<li>Facilisis in pretium nisl aliquet</li>
			<li>Faucibus porta lacus fringilla vel</li>
			<li>Aenean sit amet erat nunc</li>
			<li>Eget porttitor lorem</li>
			<li>Consectetur adipiscing elit</li>
			<li>Integer molestie lorem at massa</li>
			<li>Facilisis in pretium nisl aliquet</li>
			<li>Faucibus porta lacus fringilla vel</li>
			<li>Aenean sit amet erat nunc</li>
		</ol>
		<h4 class="spaced">Icons</h4>
		<ul class="list icons list-unstyled">
			<li><i class="fa fa-check"></i> Fusce sit amet orci quis arcu vestibulum vestibulum sed ut felis. Phasellus in risus quis lectus iaculis vulputate id quis nisl.</li>
			<li><i class="fa fa-cloud-download"></i> Phasellus in risus quis lectus iaculis vulputate id quis nisl.</li>
			<li><i class="fa fa-envelope"></i> Fusce sit amet orci quis arcu vestibulum vestibulum sed ut felis. </li>
			<li><i class="fa fa-film"></i> Phasellus in risus quis lectus iaculis vulputate id quis nisl.</li>
			<li><a href="#SITE_DIR#info/more/icons/" >...more icons</a></li>
		</ul>
		<h4 class="spaced">Inline</h4>
		<ul class="inline">
			<li>Lorem ipsum</li>
			<li>Phasellus iaculis</li>
			<li>Nulla volutpat</li>
		</ul>
	</div>
 </div>
 <hr class="tall" />
<div class="row">
	<div class="col-md-12">
		<h2 class="underline">Code</h2>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<h4>Inline</h4>
		<p>For example, <code>&lt;section&gt;</code> should be wrapped as inline.</p>
	</div>
	<div class="col-md-6">
		<h4>Basic Block</h4>
		<pre>&lt;p&gt;Sample text here...&lt;/p&gt;</pre>
	</div>
</div>
<hr class="tall" />
<div class="row">
	<div class="col-md-12">
		<h2 class="underline">Таблицы</h2>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<h4>Таблица без рамки</h4>
		<table class="table table-striped">
			<thead>
				<tr><th>#</th><th>First Name</th><th>Last Name</th><th>Username</th></tr>
			</thead>
			<tbody>
				<tr><td>1</td><td>Mark</td><td>Otto</td><td>@mdo</td></tr>
				<tr><td>2</td><td>Jacob</td><td>Thornton</td><td>@fat</td></tr>
				<tr><td>3</td><td>Larry</td><td>the Bird</td><td>@twitter</td></tr>
			</tbody>
		</table>
		<h4 class="spaced">Таблица с рамкой</h4>
		<table class="table table-bordered">
			<thead>
			<tr><th>#</th><th>First Name</th><th>Last Name</th><th>Username</th></tr>
			</thead>
			<tbody>
				<tr><td>1</td><td>Mark</td><td>Otto</td><td>@mdo</td></tr>
				<tr><td>2</td><td>Jacob</td><td>Thornton</td><td>@fat</td></tr>
				<tr><td>3</td><td>Larry</td><td>the Bird</td><td>@twitter</td></tr>
			</tbody>
		</table>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>