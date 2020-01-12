<!DOCTYPE html>
<html lang=pt-br>
<head>
	<meta charset="UTF-8" >
	<meta name="description" content="<?php if ( isset( $description ) ) : echo $description; endif; ?>" />
	<meta name="keywords" content="<?php if ( isset( $keywords ) ) : echo $keywords; endif;?>" />
        <meta name="author" content="POWImoveis - <comercial@pow.com.br>" />
	<title><?php if ( isset( $titulo ) ) : echo $titulo; endif; ?></title>
</head>
<body class="login">
    <?php 
    echo $conteudo;
    ?>
<?php 
	echo ( isset($includes_separado) ? $includes_separado : (isset($includes) ? $includes : '') );
?>
</body>
</html>