<meta charset="utf-8" />
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<form class="form-group" method="POST" enctype="multipart/form-data">
	<label for="nome">Arquivo:</label>
	<input type="file" name="fileUpload" class="form-control-file"><br>
	<button class="btn btn-success" type="submit">Enviar</button>
</form>

<?php 
//Feito com amor pelo Danilo Dominoni

//CONFIGURAÇÕES INICIAIS
ini_set('max_execution_time', 3000000);

//CRIANDO ARRAY PARA SALVAR OS CNPJ
$dados = array();

//NOME DO ARQUIVO DE BUSCA
$nomeArquivo = "cnpj.txt";

//VERIFICAR SE O ARQUIVO NÃO ESTÁ VAZIO
if (file_exists($nomeArquivo)) { //begin if

	//ABRINDO ARQUIVO
	$arquivo = fopen($nomeArquivo, "r");

	while ($cnpj = fgets($arquivo)) { //begin while

		//SALVANDO CNPJ
		array_push($dados, $cnpj);

	}//end while

	//FECHANDO ARQUIVO
	fclose($arquivo);

}//end if

foreach ($dados as $key => $value) {//begin foreach

	//CHAMANDO A FUNÇÃO DE CONSULTA
	$cnpj = $dados[$key];
	consultaCNPJ(trim($cnpj));

}//end foreach

//FUNÇÃO DE CONSULTA CNPJ
function consultaCNPJ($cod){//begin function

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
	if($file = fopen("empresas.txt", "a+")){ //begin first if

		//CRIAÇÃO DO HEADER
		foreach ($data as $key => $value) { //begin header foreach
			array_push($headers, ucfirst($key));
		}//end header foreach		

		fwrite($file, implode(",", $headers) . "\r\n");

		//ARRAY PARA CRIAR AS LINHAS
		$dataRow = array();

		//CRIAÇÃO DAS COLUNAS
		foreach ($data as $key => $value) {//begin row foreach

			if (!is_array($value)){//begin if

				array_push($dataRow, $value);

			} else {//end if and begin else

				//ARMAZENA VALOR DO FOREACH PARA SABER O NOME DO ARRAY
				$nomeArray = $value;

				//CRAÇÃO DE UMA VARIÁVEL PARA ADICIONAR AO ARRAY DE LINHA
				$resultArray = "";

				foreach ($nomeArray as $chave => $rowArray) {//begin conteudo foreach

					foreach ($rowArray as $chave => $valorCampo) {//begin get foreach

						//CRIANDO A STRING PARA JOGAR NO ARRAY DE LINHA
						$resultArray .= str_replace(",", "", $valorCampo) . " - ";
							
					}//end get foreach

				}//end conteudo foreach

				//ADICIONANDO O CONTEUDO NO ARRAY DE LINHA
				array_push($dataRow, $resultArray);

			}//end else

		}//end row foreach

		//ADICIONANDO E FECHADO O ARQUIVO
		fwrite($file, implode(",", $dataRow) . "\r\n");
		fclose($file);

		echo "Arquivo criado com sucesso!";

	} else { //end first if and begin else

		echo "Problema na criação do arquivo, verifique se o arquivo se encontra fechado";

	}//end else

}//end function

 ?>