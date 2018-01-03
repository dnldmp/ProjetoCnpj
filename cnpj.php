<?php 

	$cnpj = "33014556000196";
	$link = "https://www.receitaws.com.br/v1/cnpj/$cnpj";

	$ch = curl_init($link);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	$response = curl_exec($ch);

	curl_close($ch);

	$data = json_decode($response, true);

	$headers = array();

	foreach ($data as $key => $value) {
		array_push($headers, ucfirst($key));
	}

	$file = fopen("empresas.csv", "w+");

	fwrite($file, implode(",", $headers) . "\r\n");

	$dataRow = array();

	foreach ($data as $key => $value) {

		if (!is_array($value)){

			array_push($dataRow, $value);

		} else {

			array_push($dataRow, "Vazio");
		}

	}

	fwrite($file, implode(",", $dataRow) . "\r\n");

	fclose($file);

	echo "Arquivo criado com sucesso!";

 ?>