<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>JSON TEST</title>
	<meta charset="UTF-8" />
	<script type="text/javascript" src="/bundles/site/js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="/bundles/site/js/util.js"></script>
	<style type="text/css">
		body {
			font-family: "Arial";
			font-size: 12px;
		}
		#resultado {
			margin: 10px;
			padding: 10px;
			border: 1px solid #cecece;
			background-color: #fffff0;
			display: none;
		}
		h1 {
			margin: 5px;
		}
	</style>
	<script type="text/javascript">
	function getData() {
		var url = $('#url').val();
		if(url === "0") {
			alert("Selecione uma das URLs da API");
			return false;
		}
		var params = JSON.parse($('#parameters').val());
		jsonCall(url, params, processa);
	}
	
	function processa(obj) {
		if(!obj) {
			alert('Erro na execução da API');
			return false;
		}
		result = JSON.stringify(obj, null, 2);
		$("#resultDivContainer").html(result);
		$("#resultado").show();
	}
	</script>
</head>
<body>

<h1>COOLSPOT JSON API</h1>

<table width="10%" border="0" cellpadding="3" cellspacing="1">
	<tr>
		<td nowrap="nowrap">URL da chamada:</td>
		<td>
			<select name="url" id="url" onchange="$('#resultado').hide();">
				<option value="0">[SELECIONE]</option>
				<option value="/json/events">/json/events</option>
				<option value="/json/events/add">/json/events/add</option>
				<option value="/json/events/photos">/json/events/photos</option>
				<option value="/json/events/private">/json/events/private</option>
				<option value="/json/events/private/search">/json/events/private/search</option>
				<option value="/json/events/remove">/json/events/remove</option>
				<option value="/json/events/search">/json/events/search</option>
				<option value="/json/events/self">/json/events/self</option>
				<option value="/json/events/status">/json/events/status</option>
				<option value="/json/favorites">/json/favorites</option>
				<option value="/json/favorites/add">/json/favorites/add</option>
				<option value="/json/favorites/remove">/json/favorites/remove</option>
				<option value="/json/friends">/json/friends</option>
				<option value="/json/friends/request/status">/json/friends/request/status</option>
				<option value="/json/friends/request">/json/friends/request</option>
				<option value="/json/locations">/json/locations</option>
				<option value="/json/locations/add">/json/locations/add</option>
				<option value="/json/locations/comments">/json/locations/comments</option>
				<option value="/json/locations/comments/add">/json/locations/comments/add</option>
				<option value="/json/locations/info">/json/locations/info</option>
				<option value="/json/locations/likes">/json/locations/likes</option>
				<option value="/json/locations/likes/add">/json/locations/likes/add</option>
				<option value="/json/locations/likes/remove">/json/locations/likes/remove</option>
				<option value="/json/locations/photos">/json/locations/photos</option>
				<option value="/json/locations/search">/json/locations/search</option>
				<option value="/json/photos/likes">/json/photos/likes</option>
				<option value="/json/photos/likes/add">/json/photos/likes/add</option>
				<option value="/json/photos/likes/remove">/json/photos/likes/remove</option>
				<option value="/json/users">/json/users</option>
				<option value="/json/users/add">/json/users/add</option>
				<option value="/json/users/search">/json/users/search</option>
			</select>
		</td>
	</tr>
	<tr valign="top">
		<td nowrap="nowrap">Parâmetros (JSON):</td>
		<td>
			<textarea name="parameters" id="parameters" cols="80" rows="15">{
  "param": "value"
}</textarea>
			<br />*Utilize "ASPAS" para delimitar os parâmetros<br /><br />
			<button type="button" onclick="getData()">EXECUTAR</button>

		</td>
	</tr>
</table>




<div id="resultado">
	<h1>Resultado:</h1>
	<pre id="resultDivContainer">

	</pre>
</div>
</body>
</html> 