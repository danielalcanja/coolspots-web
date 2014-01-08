<?php
//$body = array(
//	'page' => 2,
//	'city' => 1
//);
//$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, "http://beta.coolspots.com.br/app_dev.php/json/location");
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
//curl_setopt($ch, CURLOPT_POST, true);
//curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
//curl_setopt($ch, CURLOPT_VERBOSE, true);
//curl_setopt($ch, CURLOPT_TIMEOUT_MS, 20000);
//$result = curl_exec($ch);
//if($result === false) echo "Erro: " . curl_error($ch);
//else echo $result;
//die();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<title>My jQuery JSON Web Page</title>
<head>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="/bundles/site/js/util.js"></script>
<script type="text/javascript">
// client_id: 028ea9a1bd024485bae1780ecb871f6d
// client_secret: 38443f441eae4a4fa17efb8363fb106b

// client_id: 98c17dd506c647df9d0042d51a91be6a
// client_secret: 08021a8889ac45bba963ef63396315ca
// subscription: 4036423

// api keys para remover subscricoes: 1, 4
function getData() {
	var url = "/app_dev.php/json/addlocation";
	var parameters = {
		id_instagram: 665104,
		id_foursquare: '4b997e23f964a520d17e35e3',
		geo: {
			countryName: 'Brazil',
			countryCode: 'BR',
			stateName: 'Mato Grosso',
			stateAbbr: 'MT',
			cityName: 'Cuiabá'
		},
		category: {
			id: 3,
			exid: '4bf58dd8d48988d116941735',
			name: 'Bar'
		},
		name: 'Ditado Popular'
	};
	obj = jsonCall(url, parameters);
	if(obj.error) {
		console.log("Deu erro: " + obj.error_message);
	} else {
		console.log(obj);
	}
}
</script>
</head>
<body>

<h1>My jQuery JSON Web Page</h1>

<div id="resultDivContainer"></div>

<button type="button" onclick="getData()">JSON</button>

</body>
</html> 