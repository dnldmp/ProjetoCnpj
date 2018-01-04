<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<form class="form-group" method="POST" enctype="multipart/form-data">
	<label for="nome">Arquivo:</label>
	<input type="file" name="fileUpload" class="form-control-file"><br>
	<button class="btn btn-success" type="submit">Enviar</button>
</form>

<?php 
//FEITO COM AMOR PELO DANILO DOMINONI

	$dados = array();
	$nomeArquivo = "empresas.txt";

	if (file_exists($nomeArquivo)) {

		$arquivo = fopen($nomeArquivo, "r");

		while ($cnpj = fgets($arquivo)) {

			array_push($dados, $cnpj);

		}	

		fclose($arquivo);

	}

	//foreach ($dados as $value) {

		consultaCNPJ("19861350000170");

	//}

//FUNÇÃO DE CONSULTA CNPJ
function consultaCNPJ($cod){
	//CRIANDO O LINK PARA CONSULTA A PARTIR DO CNPJ 
	$link = "https://www.receitaws.com.br/v1/cnpj/$cod";

	//CONSULTA COM O WEB SERVICE
	$ch = curl_init($link);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	//JSON RETORNADO
	$response = curl_exec($ch);

	//FECHANDO CONSULTA
	curl_close($ch);

	//TRANSFORMANDO O JSON RETORNADO EM ARRAY
	$data = json_decode($response, true);

	//ARRAY PARA CRIAR O HEADER
	$headers = array();

	//VERIFICA SE O ARQUIVO CONSEGUIU SER CRIADO
	if($file = fopen("empresas.csv", "w+")){

		//CRIAÇÃO DO HEADER
		foreach ($data as $key => $value) {
			array_push($headers, ucfirst($key));
		}		

		fwrite($file, implode(",", $headers) . "\r\n");

		//ARRAY PARA CRIAR AS COLUNAS
		$dataRow = array();

		//CRIAÇÃO DAS COLUNAS
		foreach ($data as $value) {

			if (!is_array($value)){

				array_push($dataRow, $value);

			} else {

				array_push($dataRow, "Vazio");
			}

		}

		//ADICIONANDO E FECHADO O ARQUIVO
		echo (!fwrite($file, implode(",", $dataRow) . "\r\n"));
		fclose($file);

		echo "Arquivo criado com sucesso!";

	} else {

		echo "Problema na criação do arquivo, verifique se o arquivo se encontra fechado";

	}

}

 ?>