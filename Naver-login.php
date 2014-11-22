<meta charset="UTF-8">
<?php
	
	$id = "NAVER_ID";
	$pw = "NAVER_PW";

	$random =  rand(5, 15);
	$usrsetting = "$randomx$random";
	$cap = $_GET["cap"];

	function winhttp($method, $url, $data = '', $referer = null, $type = null) { // Gogoomas naver cafe Winhttp Source, Thanks.
																				 // IF Have Problem, contact to support@rainclab.net 
		
	}
	function json_encode2($data) {
		switch (gettype($data)) {
			case 'boolean':
		    	return $data?'true':'false';
			case 'integer':
			case 'double':
		    	return $data;
			case 'string':
		    	return '"'.strtr($data, array('\\'=>'\\\\','"'=>'\\"')).'"';
			case 'array':
			$rel = false; // relative array?
			$key = array_keys($data);
		    	foreach ($key as $v) {
	        		if (!is_int($v)) {
	            		$rel = true;
		            	break;
		        	}
		    	}
			$arr = array();
			foreach ($data as $k=>$v) {
			$arr[] = ($rel?'"'.strtr($k, array('\\'=>'\\\\','"'=>'\\"')).'":':'').json_encode2($v);
			}
			return $rel?'{'.join(',', $arr).'}':'['.join(',', $arr).']';
			default:
			return '""';
			}
	}

	


	function getKey($html) {
		$doc = new DOMDocument;
		@$doc->loadHTML($html);

		$items = $doc->getElementsByTagName('input');
		foreach ($items as $tag) { // FIND VALUE AS FOREACH FUNC()
			$name = $tag->getAttribute('name');
			if ($name == "key") {
				$value = $tag->getAttribute('value');
			}
		}

		return $value;
	}

	function getRSA($id, $pw, $sessionkey,$keyname, $evalue, $nvalue) {
		$result = shell_exec("node rsa.js $id $pw $sessionkey $keyname $evalue $nvalue");
		return $result;
	}
	function Accept($cookies, $authkey){
		$ch = curl_init();
		$content = "regyn=N&nvlong=&mode=device&key=$authkey&enctp=2&encpw=&encnm=&svctype=0&svc=&viewtype=&locale=ko_KR&postDataKey=&smart_LEVEL=1&logintp=&url=http%3A%2F%2Fwww.naver.com%2F&mode=&secret_yn=&pre_id=&resp=&exp=&ru=";
		curl_setopt($ch, CURLOPT_URL, "https://nid.naver.com/nidlogin.login?svctype=0");
		curl_setopt ($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Cookie : $cookies',
			'User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.111 Safari/537.36'
		));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		$response = curl_exec($ch);
		return $response;
	}

	function getNNB($usrsetting){
		$result = winhttp('GET','https://lcs.naver.com/m','u=https%3A%2F%2Fnid.naver.com%2Fnidlogin.login&e=&i=&os=Win32&ln=ko&sr=$usrsetting&bw=1349&bh=343&c=24&j=Y&jv=1.8&k=Y&fv=15.0&sl=5.1&ct=&p=Widevine%20Content%20Decryption%20Module%3BShockwave%20Flash%3BChrome%20Remote%20Desktop%20Viewer%3BNative%20Client%3BChrome%20PDF%20Viewer%3BnpINISAFEWeb60%20Dynamic%20Link%20Library%3BQuickTime%20Plug-in%207.7.5%3BGoogle%20Update%3BINICIS%20INIpay%20Plugin%3BInnoGMP%3BJava%20Deployment%20Toolkit%208.0.200.26%3BJava(TM)%20Platform%20SE%208%20U20%3BKCPHUB%3BKCP%3BKeySharpBiz%3BPando%20Web%20Plugin%3BLG%20Uplus%20XPay%20Plugin%20(npRuntime)%201.0.5.1%3Brexpert%203.0%20plugin%20viewer%3BiTunes%20Application%20Detector%3BNexon%20Game%20Controller%3BINISAFE%20CrossWeb%20NP%20Plugin%3BNDownloaderObj%3BNaverMultiTrackPlugin%3BTouchEn%20Key%20for%20Multi-Browser%3BSilverlight%20Plug-In&EOU');
		$copy = $result["header"];
		$copy2 = explode('NNB=', $copy);
		$copy3 = explode(';', $copy2[1]);
		$copy4 = $copy3[0];
		return $copy4;
	}

	// function PostData($text, $NID_SES, $NID_AUT) {
	// 	$curlHandle = curl_init();
	// 	curl_setopt($curlHandle, CURLOPT_URL, "http://cafe.naver.com/MemoPost.nhn");
	// 	curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array(
	// 		'User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.111 Safari/537.36',
	// 		'Cookie ''
	// 	$content = "clubid=4&menuid=11&emotion=1012609&stickerId=&contents=$text";
	// 	curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $content);
	// 	curl_setopt($curlHandle, CURLOPT_HEADER, true);
	// 	curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
	// 	$response = curl_exec($curlHandle);
	// 	return $response . "Cookie : ";
	// }


	// //$keys = NaverKey();
	// $responses = winhttp ( 'GET', 'http://static.nid.naver.com/enclogin/keys.nhn' );
	// $SendKey = explode ( ",", $responses ['body'] );
	
	// $sessionkey = $keys[0]; //defaults 4
	// $keyname = $keys[1];
	// $evalue = $keys[2];
	// $nvalue = $keys[3];
	
	//CUSTOM RSA AS WINHTTP
	$responses =winhttp ( 'GET', 'http://static.nid.naver.com/enclogin/keys.nhn' );

	$SendKey = explode ( ",", $responses ['body'] );
	// Parse-MiddleKey
	$f = explode ( "\r\n", $SendKey [0] );
	$e = explode ( "\r\n", $SendKey [3] );

	$NNB = getNNB($usrsetting); // Checked
	$encpw = getRSA($id, $pw, $f[1], $SendKey[1], $SendKey[2], $e[0]);
	echo $encpw;
	// echo "enc : " . $encpw;
	$keyname = $SendKey[1];
	$ch = curl_init();
	$ch2 = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://nid.naver.com/nidlogin.login");
	$fields="enctp=1&encpw=$encpw&encnm=$keyname&svctype=0&id=&pw=&x=35&y=14";
	$fields = preg_replace("/\s+/", "", $fields);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)'); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Referer: http://static.nid.naver.com/login.nhn?svc=wme&amp;url=http%3A%2F%2Fwww.naver.com&amp;t=20120425',
		'Content-Type: application/x-www-form-urlencoded'
	));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	curl_close($ch);
	
	// echo $response;
	echo "!cookie = " . $responses['header']. "EOT";
	// print_r($responses['header']);
	if (strpos($response, "새로운")) { // NEW DEVICE CHECK
		$key = getKey($response); // GET KEY FROM HTML CODE (<input name="key" value="TARGET">)
		// echo "YOUR AUTH KEY : " . $key . " EOT";
		print_r($tmp);
		$result = Accept($cookie2, $key);
		$exp = explode('Set-Cookie: ', $result);
		$NID_SES3 = explode('Set-Cookie: NID_AUT=',$result);
		$work = $NID_SES3[1];
		$NID_SES2 = explode(';', $work);
		$NID_SES = $NID_SES2[0];

		$NID_AUT3 = explode('Set-Cookie: NID_AUT=', $result);
		$work2 = $NID_AUT3[1];
		$NID_AUT2 = explode(';', $work2);
		$NID_AUT = $NID_AUT2[0];

		echo "NID_SES : $NID_SES , NID_AUT : $NID_AUT";
		// $result2 = PostData("hahaha", $NID_SES, $NID_AUT);


		// echo "AUTHED_COOKIE : " . $auth_cook . "/EOT";
		echo "PREVIOUS HEADER : " . $result . "/EOT";
		echo "CAFEPOST : " . $result2 . "/eot";
		exit();
	} elseif (strpos($response,"않습니다")) {
		echo "login failed";
		echo $response;
		exit();
	}
?>
