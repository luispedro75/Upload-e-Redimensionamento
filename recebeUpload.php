<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload de arquivos e redimensionamento</title>
</head>

<body>
<?php
// verifica se foi enviado um arquivo 
if(isset($_FILES['arquivo']['name']) && $_FILES["arquivo"]["error"] == 0)
{

//	echo "Você enviou o arquivo: <strong>" . $_FILES['arquivo']['name'] . "</strong><br />";
//	echo "Este arquivo é do tipo: <strong>" . $_FILES['arquivo']['type'] . "</strong><br />";
//	echo "Temporáriamente foi salvo em: <strong>" . $_FILES['arquivo']['tmp_name'] . "</strong><br />";
//	echo "Seu tamanho é: <strong>" . $_FILES['arquivo']['size'] . "</strong> Bytes<br /><br />";

	$arquivo_tmp = $_FILES['arquivo']['tmp_name'];
	$nome = $_FILES['arquivo']['name'];
	

	// Pega a extensao
	$extensao = strrchr($nome, '.');

	// Converte a extensao para mimusculo
	$extensao = strtolower($extensao);

	// Somente imagens, .jpg;.jpeg;.gif;.png
	// Aqui eu enfilero as extesões permitidas e separo por ';'
	// Isso server apenas para eu poder pesquisar dentro desta String
	if(strstr('.jpg;.jpeg;.gif;.png', $extensao))
	{
		// Cria um nome único para esta imagem
		// Evita que duplique as imagens no servidor.
		$novoNome = md5(microtime()) . $extensao;
		
		// Concatena a pasta com o nome
		$destino = 'imagens/' . $novoNome; 
		
		// tenta mover o arquivo para o destino
		if( @move_uploaded_file( $arquivo_tmp, $destino  ))
		{
			echo "Arquivo salvo com sucesso em : <strong>" . $destino . "</strong><br />";
//			echo "<img src=\"" . $destino . "\" />";
		}
		else
			echo "Erro ao salvar o arquivo. Aparentemente você não tem permissão de escrita.<br />";
	}
	else
		echo "Você poderá enviar apenas arquivos \"*.jpg;*.jpeg;*.gif;*.png\"<br />";
}
else
{
	echo "Você não enviou nenhum arquivo!";
}

// Nova largura
$largura_nova = 600;
// echo "$largura_nova<br />";

// Somente exista numeros muito pequenos. Para este exemplo nao quero
if($largura_nova < 20)
	$largura_nova = 20;

// Carregar imagem ja existente no servidor
 $imagem = imagecreatefromjpeg( $destino );
 // echo "<img src=\"" . $imagem . "\" /><br />";
/* @Parametros
 * "foto.jpg" - Caminho relativo ou absoluto da imagem a ser carregada.
 */

// Obtem a largura_nova da imagem
$largura_original = imagesx( $imagem );
// echo "$largura_original<br />";
/* @Parametros
 * $imagem - Imagem previamente criada Usei imagecreatefromjpeg
 */

// ObtÈm a altura da imagem
$altura_original = imagesy( $imagem );
// echo "$altura_original<br />";
/* @Parametros
 * $imagem - Imagem previamente criada Usei imagecreatefromjpeg
 */

// Calcula a nova altura da imagem 
$altura_nova = intval( ( $altura_original * $largura_nova ) / $largura_original );
// echo "$altura_nova<br />";

// Cria a nova imagem com os tamanhos novos
$nova_imagem = imagecreatetruecolor( $largura_nova, $altura_nova );
// echo "<img src=\"" . $nova_imagem . "\" /><br />";
/* @Parametros
 * $largura_nova - Largura da nova imagem
 * $altura_nova - Altura da nova imagem
 */
    
// Cria uma copia da imagem com os novos tamanhos e 
// passa para a imagem criada com imagecreatetruecolor
imagecopyresampled( $nova_imagem, $imagem, 0, 0, 0, 0, $largura_nova, $altura_nova, $largura_original, $altura_original );
/* @Parametros
 * $nova_imagem - Nova imagem criada com imagecreatetruecolor
 * $imagem - Imagem a ser redimensionada.
 * 0 - Valor X de destino. Usado quando recortar
 * 0 - Valor Y de destino. Usado quando recortar
 * 0 - Valor X da imagem original. Usado quando recortar
 * 0 - Valor Y da imagem original. Usado quando recortar
 * $largura_nova - Nova largura
 * $altura_nova - Nova altura
 * $largura_original - Altura da imagem original
 * $altura_original - Largura da imagem original
 */

// Header informando que È uma imagem JPEG
// header( 'Content-type: image/jpeg' );

// e envia a imagem para o browser ou arquivo
 imagejpeg( $nova_imagem, NULL, 80 );
/* @Parametros
 * $imagem - Imagem previamente criada Usei imagecreatefromjpeg
 * NULL - O caminho para salvar o arquivo. 
          Se nao definido ou NULL, o stream da imagem ser mostrado diretamente. 
 * 80 - Qualidade da compresao da imagem.
 */
?>
</body>
</html>