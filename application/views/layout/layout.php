<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--><html lang=pt-br><!--<![endif]-->
<head>
    <meta charset="UTF-8" >
    <meta name="description" content="<?php if ( isset( $description ) ) : echo $description; endif; ?>" />
    <meta name="keywords" content="<?php if ( isset( $keywords ) ) : echo $keywords; endif;?>" />
    <meta name="author" content="Carlos Claro - http://www.carlosclaro.com.br" />
    <?php // if ( ! LOCALHOST ) : echo '<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">'; endif; ?>
    

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <title><?php if ( isset( $titulo ) ) : echo $titulo; endif; ?></title>
<?php
if ( ! HOTSITE_MANAGER ):
    echo ( isset($includes) ? $includes : '' );
endif;
?>

</head>
<body class="page-sidebar-closed-hide-logo">
    <?php
    if ( LOCALHOST ) : echo '<div class="alert alert-info text-center">Ambiente de testes</div>'; endif;
    ?>
    <!-- BEGIN HEADER -->
    <div class="page-header navbar">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="<?php echo base_url().'painel';?>">
                    <!--<img src="<?php echo base_url();?>images/logo_pow.png" class="img-responsive " style="width:200px">-->
                </a>
                <div class="menu-toggler sidebar-toggler">
                    <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
                </div>
            </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
        </a>
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="page-top">
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <li class="trabalhando"></li>
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                        
                        <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="icon-bell"></i>
                            <span class="badge badge-info">Ajuda</span>
                            <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <p class="text-center">
                                        Ajuda sobre nosso sistema
                                    </p>
                                </li>
                                <li>
                                    <ul class="dropdown-menu-list scroller" style="height: 250px;">
                                        <li>
                                            <a href="#">
                                            <span class="label label-sm label-icon label-success">
                                            <i class="fa fa-plus"></i>
                                            </span>
                                            Saiba mais
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown dropdown-user">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <span class="username">
                                    Admin
                                </span>
                                <i class="fa fa-angle-down"></i>
                                <input type="hidden" class="id_usuario" value="<?php echo (isset($usuario['id']) ? $usuario['id'] : 0);?>">
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#">
                                        <i class="icon-user"></i> 
                                        Meu perfil
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url();?>/login/logout">
                                        <i class="fa fa-key"></i> 
                                        Log Out 
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- <li class="dropdown dropdown-extended quick-sidebar-toggler">
                                <span class="sr-only">Toggle Quick Sidebar</span>
                                <i class="icon-logout"></i>
                        </li> -->
                        <!-- END USER LOGIN DROPDOWN -->
                        <!-- END USER LOGIN DROPDOWN -->
                    </ul>
                </div>
                <!-- END TOP NAVIGATION MENU -->
            <!-- END RESPONSIVE MENU TOGGLER -->
            </div>
        </div>
    </div>
    
    <div class="clearfix"> </div>
    <div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <div class="page-sidebar navbar-collapse ">
            <!-- BEGIN SIDEBAR MENU -->         
            <!-- Collect the nav links, forms, and other content for toggling -->
            <ul class="nav navbar-nav">
                <li class="dropdown <?php echo ( $classe == 'caes' ) ? 'active': ''; ?> ">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Cães <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo base_url();?>caes/listar">Lista</a></li>
                    </ul>
                </li>
                <li class="dropdown <?php echo ( $classe == 'canis' ) ? 'active': ''; ?> ">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Canis <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo base_url();?>canis/listar">Lista</a></li>
                    </ul>
                </li>
                <li class="dropdown <?php echo ( $classe == 'cores' ) ? 'active': ''; ?> ">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Cores <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo base_url();?>cores/listar">Lista</a></li>
                    </ul>
                </li>
                <li class="dropdown <?php echo ( $classe == 'raca' ) ? 'active': ''; ?> ">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Raças <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo base_url();?>racas/listar">Lista</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div>
    <div class="page-content-wrapper">
       <div class="page-content">
            <div class="page-bar">
                <?php echo isset($breadscrumbs) ? $breadscrumbs : ''; ?>
            </div>

           <div class="portlet <?php echo ($conteudo_transparente ? '' : 'light');?>">
                <?php 
                echo $conteudo;
                ?>
           </div>
        </div>
    </div>
</div>
<?php
if (isset($usuario_origem)):
    ?>
    <div style="position:fixed;bottom:0px; right: 20px;">
        <?php echo $usuario_origem; ?>
    </div>
    <?php
endif;

if ( HOTSITE_MANAGER ):
    echo ( isset($includes_separado) ? $includes_separado : '' );
endif;
?>
</body>
</html>