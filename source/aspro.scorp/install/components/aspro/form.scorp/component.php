<?if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true ) die();

if( CModule::IncludeModule("iblock") ){
	$GLOBALS['strError'] = '';
	$antiSpamHiddenFieldCode = 'not_send_confirm_text';
	$processingApprovalFieldCode = 'processing_approval';

	if( !is_set( $arParams["CACHE_TIME"] ) ){
		$arParams["CACHE_TIME"] = "3600";
	}

	$bCache = !( $_SERVER["REQUEST_METHOD"] == "POST" && !empty( $_REQUEST["form_submit"] ) || $_REQUEST['formresult'] == 'ADDOK' ) && !( $arParams["CACHE_TYPE"] == "N" || ( $arParams["CACHE_TYPE"] == "A" && COption::GetOptionString("main", "component_cache_on", "Y") == "N" ) || ( $arParams["CACHE_TYPE"] == "Y" && intval($arParams["CACHE_TIME"]) <= 0 ) );

	if( $bCache ){
		$arCacheParams = array();
		foreach( $arParams as $key => $value )
			if( substr($key, 0, 1) != "~" )
				$arCacheParams[$key] = $value;
		$obFormCache = new CPHPCache;
		$CACHE_ID = SITE_ID."|".$componentName."|".md5(serialize($arCacheParams));
		if( ( $tzOffset = CTimeZone::GetOffset() ) <> 0 )
			$CACHE_ID .= "|".$tzOffset;
		$CACHE_PATH = "/".SITE_ID.CComponentEngine::MakeComponentPath( $componentName );
	}

	if( $bCache && $obFormCache->InitCache( $arParams["CACHE_TIME"], $CACHE_ID, $CACHE_PATH ) ){
		$arCacheVars = $obFormCache->GetVars();
		$bVarsFromCache = true;

		$arResult = $arCacheVars["arResult"];
		$arResult["FORM_NOTE"] = "";
		$arResult["isFormNote"] = "N";
	}else{
		$bVarsFromCache = false;

		if( $arParams["IBLOCK_ID"] > 0 ){
			$arResult["F_RIGHT"] = CIBlock::GetPermission( $arParams["IBLOCK_ID"] );

			if( $arResult["F_RIGHT"] == "D" ){
				$arResult["ERROR"] = "FORM_ACCESS_DENIED";
			}
		}else{
			$arResult["ERROR"] = "FORM_NOT_FOUND";
		}

		$arResult["EVENT_TYPE"] = "ASPRO_SEND_FORM";

		$arIBlock = CIBlock::GetList( false, array( "ID" => $arParams["IBLOCK_ID"] ) )->Fetch();
		$arResult["IBLOCK_CODE"] = $arIBlock["CODE"];
		$arResult["IBLOCK_TITLE"] = $arIBlock["NAME"];
		$arResult["IBLOCK_DESCRIPTION"] = $arIBlock["DESCRIPTION"];
		$arResult["IBLOCK_DESCRIPTION_TYPE"] = $arIBlock["DESCRIPTION_TYPE"];

		if($arIBlock['IBLOCK_TYPE_ID']){
			$arResult["IBLOCK_TYPE_STRING"] = CIBlockType::GetByID($arIBlock['IBLOCK_TYPE_ID'])->Fetch();
		}

		$rsProp = CIBlock::GetProperties( $arParams["IBLOCK_ID"], array( "SORT"=> "ASC" ) , array("ACTIVE" => "Y"));
		while( $arProp = $rsProp->Fetch() ){
			$arResult["arQuestions"][] = $arProp;
		}

		if(is_array($arResult["arQuestions"])){
			foreach( $arResult["arQuestions"] as $key => $arQuestion ){
				$tmp = array(
					"NAME" => $arQuestion["NAME"],
					"CODE" => $arQuestion["CODE"],
					"IS_REQUIRED" => $arQuestion["IS_REQUIRED"],
					"HINT" => $arQuestion["HINT"],
					"DEFAULT" => $arQuestion["DEFAULT_VALUE"],
					"ICON" => $arQuestion["XML_ID"]
				);
				if( $arQuestion["PROPERTY_TYPE"] == "S" && empty( $arQuestion["USER_TYPE"] ) ){
					$tmp["FIELD_TYPE"] = "text";
				}elseif( $arQuestion["PROPERTY_TYPE"] == "S" && !empty( $arQuestion["USER_TYPE"] ) && $arQuestion["USER_TYPE"] == "Date" ){
					$tmp["FIELD_TYPE"] = "date";
				}elseif( $arQuestion["PROPERTY_TYPE"] == "S" && !empty( $arQuestion["USER_TYPE"] ) && $arQuestion["USER_TYPE"] == "DateTime" ){
					$tmp["FIELD_TYPE"] = "datetime";
				}elseif( $arQuestion["PROPERTY_TYPE"] == "S" && !empty( $arQuestion["USER_TYPE"] ) && $arQuestion["USER_TYPE"] == "HTML" ){
					$tmp["FIELD_TYPE"] = "html";
				}elseif( $arQuestion["PROPERTY_TYPE"] == "N" ){
					$tmp["FIELD_TYPE"] = "integer";
				}elseif( $arQuestion["PROPERTY_TYPE"] == "L" && $arQuestion["LIST_TYPE"] == "L" ){
					$tmp["FIELD_TYPE"] = "list";
				}elseif( $arQuestion["PROPERTY_TYPE"] == "L" && $arQuestion["LIST_TYPE"] == "C" ){
					$tmp["FIELD_TYPE"] = "checkbox";
				}elseif( $arQuestion["PROPERTY_TYPE"] == "F" ){
					$tmp["FIELD_TYPE"] = "file";
				}else{
					continue;
				}

				$tmp["MULTIPLE"] = $arQuestion["MULTIPLE"];

				if( $tmp["FIELD_TYPE"] != 'checkbox' ){
					$tmp["CAPTION"] = '<label for="'.$arQuestion["CODE"].'">'.$arQuestion["NAME"].': '.($arQuestion["IS_REQUIRED"] == "Y" ? '<span class="required-star">*</span>' : '').'</label>';
				}

				$arResult["QUESTIONS"][$arQuestion["CODE"]] = $tmp;
			}
		}

		$arResult["SITE"] = CSite::GetByID(SITE_ID)->Fetch();

		if( $bCache ){
			$obFormCache->StartDataCache();
			$GLOBALS['CACHE_MANAGER']->StartTagCache($CACHE_PATH);
			$GLOBALS['CACHE_MANAGER']->RegisterTag("forms");
			$GLOBALS['CACHE_MANAGER']->RegisterTag("form_".$arParams["IBLOCK_ID"]);
			$GLOBALS['CACHE_MANAGER']->EndTagCache();
			$obFormCache->EndDataCache(
				array(
					"arResult" => $arResult,
				)
			);
		}
	}

	$eventDesc = $eventDescAdmin = $messBody = $messBodyAdmin = '';
	if(is_array($arResult["QUESTIONS"])){
		foreach($arResult["QUESTIONS"] as $FIELD_CODE => $arQuestion){
			$eventDesc .= $arQuestion["NAME"].": "."#".$FIELD_CODE."#\n";
			if($arQuestion["FIELD_TYPE"] == "html"){
				$messBody .= $arQuestion["NAME"].":\n"."#".$FIELD_CODE."#\n";
			}
			else{
				$messBody .= $arQuestion["NAME"].": "."#".$FIELD_CODE."#\n";
			}
		}
	}
	$eventDesc .= GetMessage("FORM_ET_DESCRIPTION");
	$eventDescAdmin = $eventDesc.GetMessage("FORM_ET_DESCRIPTION_ADMIN");
	$messBodyAdmin = $messBody;
	$messBodyAdmin .= GetMessage("FORM_EM_ADMIN_LINK");

	$event_name = $arResult["EVENT_TYPE"]."_".$arParams["IBLOCK_ID"];
	$arEvent = CEventType::GetByID( $event_name, $arResult["SITE"]["LANGUAGE_ID"] )->Fetch();
	if( !is_array( $arEvent ) ){
		$et = new CEventType;
		$arEventFields = array(
			"LID" => $arResult["SITE"]["LANGUAGE_ID"],
			"EVENT_NAME" => $event_name,
			"NAME" => GetMessage("FORM_ET_NAME")." \"".$arResult["IBLOCK_TITLE"]."\"",
			"DESCRIPTION" => $eventDesc,
		);
		$et->Add($arEventFields);
		$arEventFields["LID"] = ($arResult["SITE"]["LANGUAGE_ID"] == 'ru' ? 'en' : 'ru');
		$et->Add($arEventFields);
	}

	$arMess = CEventMessage::GetList( $arResult["SITE"]["ID"], $order="desc", array( "TYPE_ID" => $event_name ) )->Fetch();
	if( !is_array( $arMess ) ){
		$em = new CEventMessage;
		$arMess = array();
		$arMess["ID"] = $em->Add( array(
			"ACTIVE" => "Y",
			"EVENT_NAME" => $event_name,
			"LID" => array( $arResult["SITE"]["LID"] ),
			"EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
			"EMAIL_TO" => "#EMAIL#",
			"BCC" => "",
			"SUBJECT" => GetMessage("FORM_EM_NAME"),
			"BODY_TYPE" => "text",
			"MESSAGE" => GetMessage("FORM_EM_START").$messBody.GetMessage("FORM_EM_END"),
		) );
		$arMess["EVENT_NAME"] = $event_name;
	}

	$event_name_admin = $arResult["EVENT_TYPE"]."_ADMIN_".$arParams["IBLOCK_ID"];
	$arEvent = CEventType::GetByID( $event_name_admin, $arResult["SITE"]["LANGUAGE_ID"] )->Fetch();
	if( !is_array( $arEvent ) ){
		$et = new CEventType;
		$arEventFields = array(
			"LID" => $arResult["SITE"]["LANGUAGE_ID"],
			"EVENT_NAME" => $event_name_admin,
			"NAME" => GetMessage("FORM_ET_NAME")." \"".$arResult["IBLOCK_TITLE"]."\"",
			"DESCRIPTION" => $eventDescAdmin,
		);
		$et->Add($arEventFields);
		$arEventFields["LID"] = ($arResult["SITE"]["LANGUAGE_ID"] == 'ru' ? 'en' : 'ru');
		$et->Add($arEventFields);
	}

	$arMessAdmin = CEventMessage::GetList( $arResult["SITE"]["ID"], $order="desc", array( "TYPE_ID" => $event_name_admin ) )->Fetch();
	if( !is_array( $arMessAdmin ) ){
		$em = new CEventMessage;
		$arMessAdmin = array();
		$arMessAdmin["ID"] = $em->Add( array(
			"ACTIVE" => "Y",
			"EVENT_NAME" => $event_name_admin,
			"LID" => array( $arResult["SITE"]["LID"] ),
			"EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
			"EMAIL_TO" => "#DEFAULT_EMAIL_FROM#",
			"BCC" => "",
			"SUBJECT" => GetMessage("FORM_EM_NAME"),
			"BODY_TYPE" => "text",
			"MESSAGE" => GetMessage("FORM_EM_START_ADMIN").$messBodyAdmin.GetMessage("FORM_EM_END"),
		) );
		$arMessAdmin["EVENT_NAME"] = $event_name_admin;
	}

	if(is_array($arResult["QUESTIONS"])){
		foreach( $arResult["QUESTIONS"] as $FIELD_CODE => $arQuestion ){
			if(isset($_REQUEST[$FIELD_CODE])){
				$val = !empty( $_REQUEST[$FIELD_CODE] ) ? $_REQUEST[$FIELD_CODE] : $arQuestion["DEFAULT"];
				if(!is_array($val)){
					$val = htmlspecialchars($val, (ENT_COMPAT | ENT_HTML401), LANG_CHARSET);
				}
			}
			elseif($arQuestion["FIELD_TYPE"] === "file"){
				$val = $valFile = $arQuestion["DEFAULT"];
				if($arQuestion["MULTIPLE"] == "Y"){
					$valFile = array('n0' => $arQuestion["DEFAULT"], 'n1' => $arQuestion["DEFAULT"], 'n2' => $arQuestion["DEFAULT"], 'n3' => $arQuestion["DEFAULT"]);
				}
			}

			$required = $arQuestion["IS_REQUIRED"] == "Y" ? 'required' : '';
			$phone = strpos( $arQuestion["CODE"], "PHONE" ) !== false ? 'phone' : '';
			$placeholder = $arParams["IS_PLACEHOLDER"] == "Y" ? 'placeholder="'.$arQuestion["NAME"].'"' : '';
			$icon = !empty( $arQuestion["ICON"] ) ? '<i class="fa '.$arQuestion["ICON"].'"></i>' : '';
			$html = '';

			switch( $arQuestion["FIELD_TYPE"] ){
				case "hidden":
					$html = '<input type="hidden" id="'.$arQuestion["CODE"].'" name="'.$arQuestion["CODE"].'" class="form-control ignore '.$required.' '.$phone.'" '.$placeholder.' value="'.$val.'" />'.$icon;
					break;
				case "text":
					$html = '<input type="'.( $arQuestion["CODE"] == "EMAIL" ? "email" : "text" ).'" id="'.$arQuestion["CODE"].'" name="'.$arQuestion["CODE"].'" class="form-control '.$required.' '.$phone.'" '.$placeholder.' value="'.$val.'" />'.$icon;
					break;
				case "integer":
					$html = '<input type="number" id="'.$arQuestion["CODE"].'" name="'.$arQuestion["CODE"].'" class="form-control '.$required.'" '.$placeholder.' value="'.$val.'" />'.$icon;
					break;
				case "date":
					$html = '<input type="text" id="'.$arQuestion["CODE"].'" name="'.$arQuestion["CODE"].'" class="form-control date '.$required.'" '.$placeholder.' value="'.$val.'" />'.$icon;
					break;
				case "datetime":
					$html = '<input type="text" id="'.$arQuestion["CODE"].'" name="'.$arQuestion["CODE"].'" class="form-control datetime '.$required.'" '.$placeholder.' value="'.$val.'" />'.$icon;
					break;
				case "html":
					$html = '<textarea id="'.$arQuestion["CODE"].'" rows="3" name="'.$arQuestion["CODE"].'" class="form-control '.$required.'" '.$placeholder.'>'.$val.'</textarea>'.$icon;
					break;
				case "list":
					$rsValue = CIBlockProperty::GetPropertyEnum( $arQuestion["CODE"], array( "SORT" => "ASC", "ID" => "ASC" ), array("IBLOCK_ID" => $arParams["IBLOCK_ID"]));
					$multiple = $arQuestion["MULTIPLE"] == "Y" ? ' multiple ' : '';
					$html = '<select id="'.$arQuestion["CODE"].'" name="'.$arQuestion["CODE"].($arQuestion["MULTIPLE"] == "Y" ? '[]' : '').'" class="form-control '.$required.'" '.$multiple.$placeholder.'>';
					while( $arValue = $rsValue->Fetch() ){
						$selected = '';
						if( !empty( $val ) && (!is_array($val) ? ($arValue["ID"] == $val) : (in_array($arValue["ID"], $val))) ){
							$selected = 'selected="selected"';
						}
						if( empty( $val ) && $arValue["DEF"] == "Y" ){
							$selected = 'selected="selected"';
						}
						$html .= '<option value="'.$arValue["ID"].'" '.$selected.' >'.$arValue["VALUE"].'</option>';
						$arResult["QUESTIONS"][$FIELD_CODE]["ENUMS"][$arValue["ID"]] = $arValue["VALUE"];
					}
					$html .= '</select>'.$icon;
					break;
				case "checkbox":
					$html = '';
					$rsValue = CIBlockProperty::GetPropertyEnum( $arQuestion["CODE"], array( "SORT" => "ASC", "ID" => "ASC" ), array("IBLOCK_ID" => $arParams["IBLOCK_ID"]));
					$count = 0;
					while( $arValue = $rsValue->Fetch() ){
						$count++;
					}
					$rsValue = CIBlockProperty::GetPropertyEnum( $arQuestion["CODE"], array( "SORT" => "ASC", "ID" => "ASC" ), array("IBLOCK_ID" => $arParams["IBLOCK_ID"]) );
					while( $arValue = $rsValue->Fetch() ){
						$artmpValue = $arValue;
						$checked = '';
						if( !empty( $val ) && (!is_array($val) ? ($arValue["ID"] == $val) : (in_array($arValue["ID"], $val))) ){
							$checked = 'checked="checked"';
						}
						if( empty( $val ) && $arValue["DEF"] == "Y" ){
							$checked = 'checked="checked"';
						}
						$html .= '<input class="'.$required.'" id="'.$arValue["ID"].'" name="'.$arQuestion["CODE"].($arQuestion["MULTIPLE"] == "Y" ? '[]' : '').'" type="checkbox" value="'.$arValue["ID"].'" '.$checked.' />';
						if( $count == 1 ){
							$html .= '<label for="'.$arValue["ID"].'">'.$arQuestion["NAME"].'</label>';
						}else{
							$html .= '<label for="'.$arValue["ID"].'">'.$arValue["VALUE"].'</label><br />';
						}
						$arResult["QUESTIONS"][$FIELD_CODE]["ENUMS"][$arValue["ID"]] = $arValue["VALUE"];
					}
					break;
				case "file":
					if( $arQuestion["MULTIPLE"] == "Y" ){
						$html = '';
						for( $i = 0; $i < 4; ++$i ){
							$html .= '<input type="file" id="'.$arQuestion["CODE"].'" name="'.$arQuestion["CODE"].'_n'.$i.'" '.$required.' '.$placeholder.' class="inputfile" value="'.$valFile['n'.$i].'" />'.$icon;
						}
					}else{
						$html = '<input type="file" id="'.$arQuestion["CODE"].'" name="'.$arQuestion["CODE"].'" '.$required.' '.$placeholder.' class="inputfile" value="'.$valFile.'" />'.$icon;
					}
					break;
			}

			$arResult["QUESTIONS"][$FIELD_CODE]["VALUE"] = $val;
			$arResult["QUESTIONS"][$FIELD_CODE]["HTML_CODE"] = $html;
		}

		if($arParams['USE_CAPTCHA'] === 'HIDDEN'){
			$val = ((is_array($_REQUEST) && isset($_REQUEST[$antiSpamHiddenFieldCode]) && strlen($_REQUEST[$antiSpamHiddenFieldCode])) ? htmlspecialcharsbx($_REQUEST[$antiSpamHiddenFieldCode]) : '');
			$arResult['QUESTIONS'][strtoupper($antiSpamHiddenFieldCode)] = array(
				'HTML_CODE' => '<div style="display:none;"><textarea name="'.$antiSpamHiddenFieldCode.'" class="form-control required ignore">'.$val.'</textarea></div>',
				'CAPTION' => '',
				'FIELD_TYPE' => 'hidden',
				'STRUCTURE' => array(
					array(
						'FIELD_TYPE' => 'hidden'
					)
				)
			);
		}
	}

	if( strlen( $arResult["ERROR"] ) <= 0 ){
		if( strlen( $_REQUEST["form_submit"] ) > 0 ){
			if($arParams['USE_CAPTCHA'] === 'IMAGE'){
				if(!isset($_REQUEST['captcha_word']) || empty($_REQUEST['captcha_word']) || !$APPLICATION->CaptchaCheckCode($_REQUEST['captcha_word'], $_REQUEST['captcha_sid'])){
					$arResult['FORM_ERRORS'][] = GetMessage('FORM_CAPTCHA');
					$captcha_error = true;
				}
			}
			elseif($arParams['USE_CAPTCHA'] === 'RECAPTCHA'){
				$proved = false;

				if(isset($_REQUEST['g-recaptcha-response']) && strlen($_REQUEST['g-recaptcha-response'])){
					$result = file_get_contents(
						'https://www.google.com/recaptcha/api/siteverify',
						false,
						stream_context_create(
							array(
							    'http' => array(
							        'method'  => 'POST',
							        'header'  => 'Content-type: application/x-www-form-urlencoded',
							        'timeout' => 5,
							        'content' => http_build_query(
							        	array(
							    			'secret' => RECAPTCHA_SECRET_KEY,
							    			'response' => $_REQUEST['g-recaptcha-response'],
										)
									)
							    )
							)
						)
					);

					if($result){
						if($arResponse = json_decode($result, true)){
							if(is_array($arResponse)){
								$proved = (isset($arResponse['success']) && isset($arResponse['success']) === true);
							}
						}
					}
				}

				if(!$proved){
					$arResult['FORM_ERRORS'][] = GetMessage('FORM_RECAPTCHA');
					$captcha_error = true;
				}
			}
			elseif($arParams['USE_CAPTCHA'] === 'HIDDEN'){
				if(isset($_REQUEST[$antiSpamHiddenFieldCode]) && strlen($_REQUEST[$antiSpamHiddenFieldCode])){
					$arResult['FORM_ERRORS'][] = GetMessage('FORM_CAPTCHA');
					$captcha_error = true;
				}
			}

			if($arParams['DISPLAY_PROCESSING_NOTE'] === 'Y'){
				if(!isset($_REQUEST[$processingApprovalFieldCode]) || !strlen($_REQUEST[$processingApprovalFieldCode])){
					$arResult['FORM_ERRORS'][] = GetMessage('FORM_PROCESSING');
				}
			}

			if(is_array($_REQUEST)){
				foreach($_REQUEST as $code => $value){
					if($arResult["QUESTIONS"][$code]["FIELD_TYPE"] == "html"){
						$_REQUEST[$code] = array( "VALUE" => array ("TEXT" => $value, "TYPE" => $arResult["QUESTIONS"][$code]["FIELD_TYPE"]) );
					}
					elseif($arResult["QUESTIONS"][$code]["FIELD_TYPE"] == "date"){
						if(strlen($value)){
							$objDate = new \Bitrix\Main\Type\Date(str_replace(array('-', '/', ' ', ':'), array('.', '.', '.', '.'), $value));
							$_REQUEST[$code] = array("VALUE" => $objDate->toString());
						}
						else{
							$_REQUEST[$code] = array("VALUE" => $value);
						}
					}
					elseif($arResult["QUESTIONS"][$code]["FIELD_TYPE"] == "datetime"){
						if(strlen($value)){
							$arDateTime = explode(' ', $value);
							$objDateTime = new \Bitrix\Main\Type\DateTime(str_replace(array('-', '/', ' ', ':'), array('.', '.', '.', '.'), $arDateTime[0]).' '.str_replace(array('-', '/', ' ', ':'), array(':', ':', ':', ':'), $arDateTime[1]));
							$_REQUEST[$code] = array("VALUE" => $objDateTime->toString());
						}
						else{
							$_REQUEST[$code] = array("VALUE" => $value);
						}
					}
				}
			}

			$arPropFields = $_REQUEST;

			if(is_array($_FILES)){
				foreach($arResult["QUESTIONS"] as $FIELD_CODE => $arQuestion){
					if($arQuestion["FIELD_TYPE"] === 'file'){
						$bMultiple = $arQuestion["MULTIPLE"] === "Y";
						$arFiles = array();
						if(isset($_FILES[$FIELD_CODE]) && !$bMultiple){
							$arFiles[$FIELD_CODE] = $_FILES[$FIELD_CODE];
						}
						elseif(isset($_FILES[$FIELD_CODE.'_n1']) && $bMultiple){
							foreach($_FILES as $key => $arFile){
								if(is_numeric(str_replace(array($FIELD_CODE, '_', 'n'), '', $key))){
									$arFiles[$key] = $_FILES[$key];
								}
							}
						}

						if($arFiles){
							foreach($arFiles as $key => $arFile){
								if($arFile['name']){
									if($arFile['error']){
										$arResult["FORM_ERRORS"][] = GetMessage('FORM_FILE_UPLOAD_ERROR').$arFile['name'];
									}
									else{
										$code = explode('_', $key);
										$tmp = $code[$cntCode - 1];
										$arPropFields[$FIELD_CODE][($tmp ? $tmp : count($arPropFields[$FIELD_CODE]))] = $arFile;
									}
								}
							}
						}
					}
				}
			}

			if(is_array($arResult["QUESTIONS"])){
				foreach( $arResult["QUESTIONS"] as $FIELD_CODE => $arQuestion ){
					if(empty($arPropFields[$FIELD_CODE]) && $arQuestion["IS_REQUIRED"] == "Y" ){
						$arResult["FORM_ERRORS"][] = GetMessage("FORM_REQUIRED_INPUT").$arQuestion["NAME"];
					}
				}
			}

			if( count( $arResult["FORM_ERRORS"] ) <= 0 ){
				//if( check_bitrix_sessid() ){
					$el = new CIBlockElement;

					$arFields = array(
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						"ACTIVE" => "Y",
						"NAME" => GetMessage("DEFAULT_NAME").ConvertTimeStamp(),
						"PROPERTY_VALUES" => $arPropFields,
					);

					//echo '<pre>';print_r($arFields);echo '</pre>';die();

					foreach( GetModuleEvents("aspro.form", "OnBeforeFormSend", true) as $arEvent )
						ExecuteModuleEventEx($arEvent, array(&$arFields));

					if( $RESULT_ID = $el->Add( $arFields ) ){
						$arResult["FORM_RESULT"] = "ADDOK";
						foreach( GetModuleEvents("aspro.form", "OnAfterFormSend", true) as $arEvent )
							ExecuteModuleEventEx($arEvent, array(&$arFields));

						$arEventFields = array(
							"SITE_NAME" => $arResult["SITE"]["NAME"],
							"FORM_NAME" => $arResult["IBLOCK_TITLE"],
							"ADMIN_RESULT_URL" => (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['SERVER_NAME'].'/bitrix/admin/iblock_element_edit.php?IBLOCK_ID='.$arParams['IBLOCK_ID'].'&type='.$arResult["IBLOCK_TYPE_STRING"]['ID'].'&ID='.$RESULT_ID.'&lang='.$arResult["SITE"]["LANGUAGE_ID"].'&find_section_section=0&WF=Y',
							"PAGE_LINK" => ((CSite::inDir(SITE_DIR.'ajax/') || CSite::inDir(SITE_DIR.'form/')) ? (isset($_SERVER["HTTP_REFERER"]) && $_SERVER["HTTP_REFERER"] ? $_SERVER["HTTP_REFERER"] : $APPLICATION->GetCurPage()) : $APPLICATION->GetCurPage()),
						);

						if(is_array($arResult["QUESTIONS"])){
							foreach( $arResult["QUESTIONS"] as $FIELD_CODE => $arQuestion ){
								if($FIELD_CODE !== $antiSpamHiddenFieldCode){
									if($arQuestion["FIELD_TYPE"] == "list" || $arQuestion["FIELD_TYPE"] == "checkbox"){
										if($arQuestion['MULTIPLE'] == 'Y' && is_array($arQuestion["VALUE"])){
											foreach($arQuestion["VALUE"] as $value){
												$arEventFields[$FIELD_CODE][] = $arQuestion["ENUMS"][$value];
											}
											$arEventFields[$FIELD_CODE] = (count($arEventFields[$FIELD_CODE]) > 1 ? "\n" : '').implode("\n", $arEventFields[$FIELD_CODE]);
										}
										else{
											$arEventFields[$FIELD_CODE] = $arQuestion["ENUMS"][$arQuestion["VALUE"]];
										}
									}
									elseif($arQuestion["FIELD_TYPE"] == "file"){
										$dbRes = CIBlockElement::GetList(array(), array('ID' => $RESULT_ID, 'IBLOCK_ID' => $arParams['IBLOCK_ID']), false, false, array('ID', 'PROPERTY_'.$FIELD_CODE));
										while($arItem = $dbRes->Fetch()){
											if($arItem['PROPERTY_'.strtoupper($FIELD_CODE).'_VALUE']){
												$filePath = CFile::GetPath($arItem['PROPERTY_'.strtoupper($FIELD_CODE).'_VALUE']);
												$fileSize = filesize($_SERVER['DOCUMENT_ROOT'].$filePath);
												$fileLink = (CMain::IsHTTPS() ? 'https' : 'http').'://'.$_SERVER['SERVER_NAME'].$filePath;
												$format = 0;
												$formats = array(GetMessage('FORM_CT_NAME_b'), GetMessage('FORM_CT_NAME_KB'), GetMessage('FORM_CT_NAME_MB'), GetMessage('FORM_CT_NAME_GB'), GetMessage('FORM_CT_NAME_TB'));
												while($fileSize > 1024 && count($formats) != ($format + 1)){
													++$format;
													$fileSize = round($fileSize / 1024, 1);
												}
												$arEventFields[$FIELD_CODE][] = GetMessage('FORM_CT_NAME_SIZE').$fileSize.$formats[$format].'. '.GetMessage('FORM_CT_NAME_LINK').$fileLink;
											}
										}

										$arEventFields[$FIELD_CODE] = (count($arEventFields[$FIELD_CODE]) > 1 ? "\n" : '').implode("\n", $arEventFields[$FIELD_CODE]);
									}
									else{
										$arEventFields[$FIELD_CODE] = $arQuestion["VALUE"];
									}
								}
							}

						}

						if(strlen($arEventFields["EMAIL"])){
							if(isset($arEventFields['ORDER_LIST']) && strpos('aspro_scorp_order_page', $arResult['IBLOCK_CODE']) !== false){
								$arEventFields['ORDER_LIST'] = implode(($arMess['BODY_TYPE'] === 'text' ? "\n" : '<br />'), $arResult["QUESTIONS"]['ORDER_LIST']["VALUE"]);
								if(isset($arEventFields['SESSION_ID'])){
									unset($arEventFields['SESSION_ID']);
								}
							}
							CEvent::SendImmediate( $arMess["EVENT_NAME"], SITE_ID, $arEventFields, "Y", $arMess["ID"] );
						}

						if(isset($arEventFields['ORDER_LIST']) && strpos('aspro_scorp_order_page', $arResult['IBLOCK_CODE']) !== false){
							$arEventFields['ORDER_LIST'] = implode(($arMessAdmin['BODY_TYPE'] === 'text' ? "\n" : '<br />'), $arResult["QUESTIONS"]['ORDER_LIST']["VALUE"]);
						}
						CEvent::SendImmediate( $arMessAdmin["EVENT_NAME"], SITE_ID, $arEventFields, "Y", $arMessAdmin["ID"] );

						if( $arParams["SEF_MODE"] == "Y" ){
							LocalRedirect(
								$APPLICATION->GetCurPageParam(
									"formresult=".urlencode($arResult["FORM_RESULT"]),
									array('formresult', 'strFormNote', 'SEF_APPLICATION_CUR_PAGE_URL')
								)
							);

							die();
						}
						else{
							LocalRedirect(
								$APPLICATION->GetCurPageParam(
									"IBLOCK_ID=".$arParams["IBLOCK_ID"]
									."&RESULT_ID=".$RESULT_ID
									."&formresult=".urlencode($arResult["FORM_RESULT"]),
									array('formresult', 'strFormNote', 'IBLOCK_ID', 'RESULT_ID')
								)
							);

							die();
						}
					}else{
						$arResult["FORM_ERRORS"][] = $el->LAST_ERROR;
					}
				//}
			}
		}

		if( !empty( $_REQUEST["formresult"] ) && strtoupper($_REQUEST["formresult"]) == "ADDOK" ){
			$arResult['FORM_NOTE'] = !empty( $arParams["SUCCESS_MESSAGE"] ) ? $arParams["~SUCCESS_MESSAGE"] : GetMessage('FORM_NOTE_ADDOK');
		}

		$arResult["isFormErrors"] = count( $arResult["FORM_ERRORS"] ) > 0 ? "Y" : "N";

		if($arParams['USE_CAPTCHA'] === 'IMAGE'){
			$arResult['CAPTCHACode'] = $APPLICATION->CaptchaGetCode();
		}

		$arResult = array_merge(
			$arResult,
			array(
				"isFormNote" => strlen( $arResult["FORM_NOTE"] ) ? "Y" : "N",
				"FORM_HEADER" => sprintf(
					"<form name=\"%s\" action=\"%s\" method=\"%s\" enctype=\"multipart/form-data\">",
					$arResult["IBLOCK_CODE"], POST_FORM_ACTION_URI, "POST"
				).$res .= bitrix_sessid_post(),
				"isIblockTitle" => strlen( $arResult["IBLOCK_TITLE"] ) > 0 ? "Y" : "N",
				"isIblockDescription" => strlen( $arResult["IBLOCK_DESCRIPTION"] ) > 0 ? "Y" : "N",
				"DATE_FORMAT" => CLang::GetDateFormat("SHORT"),
				"FORM_FOOTER" => "</form>",
				"SUBMIT_BUTTON" => "<button class=\"".$arParams["SEND_BUTTON_CLASS"]."\" type=\"submit\">".$arParams["SEND_BUTTON_NAME"]."</button><br/><input type=\"hidden\" name=\"form_submit\" value=\"".GetMessage("FORM_ADD")."\">",
				"CLOSE_BUTTON" => "<button class=\"".$arParams["CLOSE_BUTTON_CLASS"]."\" data-url=\"".$APPLICATION->GetCurPageParam('',	array('formresult', 'strFormNote', 'IBLOCK_ID', 'RESULT_ID', 'bxajaxid', 'AJAX_CALL', 'SEF_APPLICATION_CUR_PAGE_URL'))."\">".$arParams["CLOSE_BUTTON_NAME"]."</button>",
			)
		);

		if(($arResult["isUseCaptcha"] = ($arParams['USE_CAPTCHA'] === 'IMAGE' || $arParams['USE_CAPTCHA'] === 'RECAPTCHA') ? 'Y' : 'N') === 'Y'){
			if(($arResult["isUseReCaptcha"] = ($arParams['USE_CAPTCHA'] === 'RECAPTCHA' ? 'Y' : 'N')) === 'Y'){
				$arResult["CAPTCHA_CAPTION"] = "<label>".GetMessage("FORM_RECAPTCHA_FIELD_TITLE")."<span class=\"required-star\">*</span></label>";
			}
			else{
				$arResult["CAPTCHA_CAPTION"] = "<label for=\"captcha_word\">".GetMessage("FORM_CAPTCHA_FIELD_TITLE")."<span class=\"required-star\">*</span></label>";
				$arResult["CAPTCHA_IMAGE"] = "<input type=\"hidden\" name=\"captcha_sid\" class=\"captcha_sid\" value=\"".htmlspecialcharsbx($arResult["CAPTCHACode"])."\" /><img src=\"/bitrix/tools/captcha.php?captcha_sid=".htmlspecialcharsbx($arResult["CAPTCHACode"])."\" class=\"captcha_img\" width=\"180\" height=\"40\" />";
				$captcha_val = !empty( $_REQUEST["captcha_word"] ) ? $_REQUEST["captcha_word"] : "";
				$captcha_val = htmlspecialchars($captcha_val, (ENT_COMPAT | ENT_HTML401), LANG_CHARSET);
				$arResult["CAPTCHA_FIELD"] = "<input type=\"text\" name=\"captcha_word\" value=\"".$captcha_val."\" class=\"form-control captcha required\" autocomplete=\"off\" />".($captcha_error ? "<label for=\"captcha_word\" class=\"error\">".GetMessage("CAPTCHA_ERROR")."</label>" : "");
			}

			$arResult["CAPTCHA_ERROR"] = $captcha_error ? "Y" : "N";
		}


		if( $arResult["isFormErrors"] == "Y" ){
			ob_start();
			ShowError( implode( '<br />', $arResult["FORM_ERRORS"] ) );
			$arResult["FORM_ERRORS_TEXT"] = ob_get_contents();
			ob_end_clean();
		}

		$this->initComponentTemplate();
		if( !empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) || $_REQUEST["formresult"] == "ADDOK" || $arResult["isFormErrors"] == "Y" ){
			//$APPLICATION->SetAdditionalCSS($this->__template->__folder.'/style.css');
			//$APPLICATION->ShowCSS(true);
		}

		if($arParams['USE_CAPTCHA'] === 'RECAPTCHA'){
			// $APPLICATION->AddHeadString('<script src="https://www.google.com/recaptcha/api.js?render=explicit&hl='.LANGUAGE_ID.'" async defer></script>', true); // is not working
			$recaptchaSrc = "https://www.google.com/recaptcha/api.js?render=explicit&hl=".LANGUAGE_ID;
			?>
			<script type="text/javascript">
			if(typeof grecaptcha === 'undefined'){
				var script = document.createElement('script');
				script.src = '<?=$recaptchaSrc?>';
				(document.head || document.documentElement).appendChild(script);
			}
			</script>
			<?
		}

		$this->IncludeComponentTemplate();
	}else{
		ShowError(GetMessage($arResult["ERROR"]));
	}
}else{
	ShowError(GetMessage("FORM_MODULE_NOT_INSTALLED"));
}?>