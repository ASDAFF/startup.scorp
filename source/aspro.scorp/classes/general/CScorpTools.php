<?
class CScorpTools{
	static $moduleClass = 'CScorp';

	function ___1595018847(){

	}

	function ___1596018847($data, $signKey = ''){
		$strData = serialize(self::___1590018847($data));
		$tmp = base64_encode($strData);

		if(strlen($signKey)){
			$signer = new \Bitrix\Main\Security\Sign\Signer;
			$signer->setKey(hash('sha512', $signKey));
			$tmp .= '.'.$signer->getSignature($strData);
		}

		return urlencode($tmp);
	}

	function ___1598018847($hash, $signKey = ''){
		$data = false;

		if(is_string($hash) && strlen($hash)){
			$tmp = urldecode($hash);


			if(($dotPos = strpos($tmp, '.')) === strrpos($tmp, '.')){
				if($bSigned = ($dotPos !== false)){
					$signature = substr($tmp, $dotPos + 1);
					$tmp = substr($tmp, 0, $dotPos);
				}
				$strData = base64_decode($tmp);

				if($bSigned && strlen($signKey)){
					try{
						$signer = new \Bitrix\Main\Security\Sign\Signer;
						$signer->setKey(hash('sha512', $signKey));
						if($signer->validate($strData, $signature)){
							$data = self::___1593018847(@unserialize($strData));
						}
					}
					catch(Exception $e){
						echo $e->getMessage();
					}
				}
				elseif(!strlen($signKey)){
					$data = self::___1593018847(@unserialize($strData));
				}
			}
		}

		return $data;
	}

	function ___1590018847($arData){
		if(is_array($arData)){
			$arResult = array();
			foreach($arData as $key => $value){
				$arResult[iconv(LANG_CHARSET, 'UTF-8', $key)] = self::___1590018847($value);
			}
		}
		else{
			$arResult = iconv(LANG_CHARSET, 'UTF-8', $arData);
		}

		return $arResult;
	}

	function ___1593018847($arData){
		if(is_array($arData)){
			$arResult = array();
			foreach($arData as $key => $value){
				$arResult[iconv('UTF-8', LANG_CHARSET, $key)] = self::___1593018847($value);
			}
		}
		else{
			$arResult = iconv('UTF-8', LANG_CHARSET, $arData);
		}

		return $arResult;
	}
}