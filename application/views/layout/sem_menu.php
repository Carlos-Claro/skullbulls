<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8" >
	<meta name="description" content="<?php if ( isset( $description ) ) : echo $description; endif; ?>" />
	<meta name="keywords" content="<?php if ( isset( $keywords ) ) : echo $keywords; endif;?>" />
        <meta name="author" content="POWImoveis - <comercial@pow.com.br>" />
        <?php
        if ( isset($favicon) && ! empty($favicon) ) :
            ?>
            <link rel="shortcut icon" href="<?php echo $favicon;?>" type="image/x-icon">
            <link rel="icon" href="<?php echo $favicon;?>" type="image/x-icon">
            <?php
        endif;
        ?>
	<title><?php if ( isset( $titulo ) ) : echo $titulo; endif; ?></title>
<?php 
	echo ( isset($meta) ? $meta : '' );
//	echo ( isset($includes_separado['css']) ? $includes_separado['css'] : '' );
	echo ( isset($header) ? str_replace(array('<br>','<br />'),'',$header) : '' );
?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
</head>
<body class="<?php echo isset($body) ? $body : '';?>">
    <?php 
    echo $conteudo;
    echo ( isset($includes_separado) ? $includes_separado : (isset($includes) ? $includes : '') );
    ?>
</body>
</html>