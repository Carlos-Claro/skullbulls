$(function () {
//    if( ! ($('.edita').val() > 0))
//    {
//        $('input,textarea,select').prop('disabled','disabled')
//    }
    $(document).on('click', '.passo_1 .salvar_1', function () {
        steps.atual = 1;
        steps.proximo = 2;
        var nome = $('#nome').val(),
                email = $('#email').val(),
                telefone = $('#telefone').val(),
                empresa = $('#empresa').val(),
                ativo = $('#ativo').prop('checked') ? 1 : 0,
                principal = $('#principal').prop('checked') ? 1 : 0,
                erro = false;
        $.each($('.passo_1 .obrigatorio'), function (k, v) {
            if ($(this).find('input').val() == "")
            {
                !erro ? $(this).find('input').focus() : '';
                $(this).addClass('has-error').removeClass('has-warning');
                erro = true;
            } else
            {
                $(this).removeClass('has-error').removeClass('has-warning').addClass('has-success');
            }
        });
        if (erro)
        {
//            swal('Erro!',"Os campos 'Nome', 'Email', 'Telefone' são obrigatórios",'error');
        } else if (!(email.indexOf('@') > 1 && email.indexOf('.') > 1))
        {
            swal('Erro!', 'Insira um e-mail válido', 'error');
            $('#email').parent().parent().addClass('has-error').removeClass('has-warning').removeClass('has-success');
            $('#email').focus();
        } else
        {
            var data = {
                nome: nome,
                email: email,
                telefone: telefone,
                id_empresa: empresa,
                ativo: ativo,
                principal: principal
            }
            $.post(URI + 'usuario/adicionar', data, function (data) {
                if (data.status)
                {
                    $('.id').val(data.mensagem);
                    var url = URI + 'usuario/editar/' + data.mensagem;
                    $('.fim').attr('href', url);
                    window.history.pushState('Salvo', 'Documento Salvo', url);
                    steps.passa();
                    var id_usuario = data.mensagem;
                    $.post(URI + 'usuario/get_setores', {id: id_usuario}, function (data) {
                        if (data)
                        {
                            var html = '';
                            $.each(data, function (k, v) {
                                html += '<option data-item="' + v.id + '" data-nome="" value="' + v.id + '" class="setores">' + v.descricao + '</option>';
                            });
                            $('#setores_select').html(html).multiSelect('refresh');
                        }
                    }, 'json');
                } else
                {
                    swal('Erro', data.mensagem, 'error');
                }
            }, 'json');
        }
    });
    $(document).on('click', '.passo_2 .salvar_2', function () {
        var cargo = $('.md-checkbox-list input:checked');
        if (cargo.length == 0)
        {
            swal('Erro!', "É ogrigatório atribuir um cargo!", 'error');
        } else
        {
            steps.passa();
        }
    });
    $('.busca_empresas').select2({
        ajax: {
            url: URI + "empresas/pesquisa_empresa",
            dataType: 'json',
            type: 'post',
            delay: 250,
            data: function (params) {
                return {
                    busca: params.term, // search term
                };
            },
            processResults: function (data, params) {
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                return {
                    results: data,
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 1,
        templateResult: layout, // omitted for brevity, see the source of this page
        templateSelection: select // omitted for brevity, see the source of this page
    });
    $(document).on('click', '.voltar', function () {
        steps.volta();
    });
    $(document).on('click', '.cargos', function () {
        var id_cargo = $(this).val(),
                id_usuario = $('.id').val(),
                add = $(this).prop('checked');

        $.post(URI + 'usuario/has_cargos', {id_usuario: id_usuario, id_pow_cargos: id_cargo, add: add}, function (data) {
            toastr.success(data.mensagem);
        }, 'json');
    });

    $('#setores_select').multiSelect({
        selectableHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Procurar setor'>",
        selectionHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Procurar setor'>",
        selectableOptgroup: true,
        cssClass: 'test_css',
        enableFiltering: true,
        afterInit: function (ms) {
            var that = this,
                    $selectableSearch = that.$selectableUl.prev(),
                    $selectionSearch = that.$selectionUl.prev(),
                    selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function (e) {
                        if (e.which == 40) {
                            that.$selectionUl.focus();
                            return false;
                        }
                    });

            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function (e) {
                        if (e.which == 40) {
                            that.$selectionUl.focus();
                            return false;
                        }
                    });
        },
        afterSelect: function (ele) {
            console.log(ele[0]);
            var setor = ele[0];
            var id_usuario = $('.id').val();
            var data = {
                id_setor: setor,
                id_usuario: id_usuario
            };
            swal({
                title: 'Atenção!',
                text: 'Este usuário poderá editar algo neste setor ?',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim!",
                cancelButtonText: "Não!",
                closeOnConfirm: true,
                closeOnCancel: true
            },
                    function (isConfirm) {
                        if (isConfirm)
                        {
                            data.edita = 1;
                            $.post(URI + 'usuario/has_setores', data, function (data) {
                                if (data.erro.status)
                                {
                                    toastr.success(data.erro.mensage);
                                } else
                                {
                                    toastr.error(data.erro.mensage);
                                }
                            }, 'json');
                        } else
                        {
                            data.edita = 0;
                        }
                    });
        },
    });

});

var steps = {
    atual: 1,
    proximo: 2,
    passa: function ()
    {
        var atual = steps.atual;
        var proximo = steps.proximo;
        console.log(atual, proximo);
        if (proximo <= 3)
        {
            $('.passo_' + atual).fadeOut(function () {
                $('.step_' + atual).addClass('done').removeClass('active');
                $('.step_' + proximo).addClass('active');
                $('.passo_' + proximo).removeClass('hide').fadeIn();
            });
            steps.atual++;
            steps.proximo++;
        }
    },
    volta: function ()
    {
        if (steps.atual > 1)
        {
            var atual = steps.atual;
            var proximo = steps.atual - 1;
            $('.passo_' + steps.atual).fadeOut(function () {
                $('.step_' + atual).removeClass('done').removeClass('active');
                $('.step_' + proximo).addClass('active').removeClass('done');
                $('.passo_' + proximo).removeClass('hide').fadeIn();
            });
            steps.atual = proximo;
            steps.proximo = proximo + 1;
        }
    }
}


function layout(data) {
    if (data.loading)
    {
        return data.text;
    }
    var html = data.descricao;

    return html;
}

function select(data) {
    return data.descricao;
}