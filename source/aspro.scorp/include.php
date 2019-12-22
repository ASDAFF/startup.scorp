<?php
/**
 * SCorp module
 * @copyright 2015 Aspro
 */
 
CModule::AddAutoloadClasses(
	'aspro.scorp',
	array(
		'scorp' => 'install/index.php',
		'CScorp' => 'classes/general/CScorp.php',
		'CCache' => 'classes/general/CCache.php',
		'CScorpTools' => 'classes/general/CScorpTools.php',
	)
);

// include common aspro functions
include_once __DIR__ .'/classes/general/CCache.php';

CScorp::UpdateFrontParametrsValues();
CScorp::GenerateThemes();

// event handlers for component aspro:form.scorp
AddEventHandler('iblock', 'OnAfterIBlockPropertyUpdate', array('CScorp', 'UpdateFormEvent'));
AddEventHandler('iblock', 'OnAfterIBlockPropertyAdd', array('CScorp', 'UpdateFormEvent'));