<?php if ( isset($filtro) ) : echo $filtro; endif; ?>
<?php 
if ( isset($editou) && $editou ) : 
    ?>
<p class="alert alert-success">
    <?php 
    echo $editou ? 'Editado com sucesso' : 'Não foi possivel editar o item';
    ?>
</p>    
    <?php
endif; ?>


<style>
#chartdiv {
	width	: 100%;
	height	: 500px;
}													
</style>
<!-- HTML -->
<?php 
//if ( ENVIRONMENT == 'development' ) :
?>
<div class="espaco_json"><?php echo isset($json) ? $json : '';?></div>
<div id="chartdiv"></div>													
<?php
//endif;
?>
<div class="row">
    <div class="paginacao col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php if (isset($paginacao)) : echo $paginacao; endif; ?>
            </div>
        </div>
    </div>
    <div class="table-scrollable">
        <?php echo $listagem;?>
    </div>
    <div class="paginacao col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php if (isset($paginacao)) : echo $paginacao; endif; ?>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="editavel" id="editavel" class="form-control editavel" value="<?php echo isset($editavel) ? $editavel : 0;?>">
