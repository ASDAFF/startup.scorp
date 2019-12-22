<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(empty($arResult))
	return "";

$strReturn = '<ul class="breadcrumb" id="navigation">';

for($index = 0, $itemSize = count($arResult); $index < $itemSize; ++$index){
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	if(strlen($arResult[$index]["LINK"]) && $arResult[$index]['LINK'] != GetPagePath() && $arResult[$index]['LINK']."index.php" != GetPagePath())
		$strReturn .= '<li itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.$arResult[$index]["LINK"].'" title="'.$title.'" itemprop="url"><span itemprop="title">'.$title.'</span></a></li>';
	else{
		$strReturn .= '<li class="active" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.$arResult[$index]['LINK'].'" title="'.$title.'" itemprop="url" style="display:none;"></a><span itemprop="title">'.$title.'</span></li>';
		break;
	}
}

$strReturn .= '</ul>';
return $strReturn;?>