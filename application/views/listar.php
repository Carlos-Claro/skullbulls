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
<div class="modal fade" tabindex="-1" role="dialog" id="modal-listagem">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title"></h4>
</div>
<div class="modal-body">

</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->