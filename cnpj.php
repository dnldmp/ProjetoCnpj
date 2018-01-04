<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<form class="form-group" method="POST" enctype="multipart/form-data">
	<label for="nome">Arquivo:</label>
	<input type="file" name="fileUpload" class="form-control-file"><br>
	<button class="btn btn-success" type="submit">Enviar</button>
</form>

<?php 
//FEITO COM AMOR PELO DANILO DOMINONI

	consultaCNPJ("19861350000413");
	function consultaCNPJ($cod){
		$cnpj = $cod;
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

	}

 ?>