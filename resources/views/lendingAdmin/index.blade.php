@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <ol class="breadcrumb panel-heading" style="background: #efc050">
                    <li class="active" style="color:#616161; font-weight:bold">Empréstimos</li>
                </ol>
                <br/><br/>
                
                <div class="panel-body">
                    <div class="alert alert-danger errorMsg" style="display:none">
                        <ul></ul>
                    </div>
                    <form class="form-inline" action="{{ route('lendingAdmin.search') }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">
                        <div class="form-group" style="float: right;">
                            <p><a href="{{route('lendingAdmin.lend')}}" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-plus"></i> Novo Empréstimo</a></p>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nomes de Alunos">
                        </div>
                        <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i> Buscar</button>
                    </form>
                    <br/>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Código</th>
                                <th class="text-center">Aluno</th>
                                <th class="text-center">Início Empréstimo</th>
                                <th class="text-center">Data Limite</th>
                                <th class="text-center">Devolvido em</th>
                                <th class="text-center">Ação</th>
                            </tr>
                        </thead>
                        <tbody>   
                        @foreach($lendings as $lending)
                            <?php
                                $data_devolucao = null;
                                $data_inicio = date('d/m/Y', strtotime($lending->date_start)); 
                                $data_fim = date('d/m/Y', strtotime($lending->date_end)); 
                                if (!empty($lending->date_finish))
                                {
                                    $data_devolucao = date('d/m/Y', strtotime($lending->date_finish)); 
                                }                                
                            ?>
                                <tr>                                
                                    <th id="code" scope="row" class="text-center"> <?= $lending->id ?></th>
                                    <td id="studentName">{{ $lending->user->name }}</td>
                                    <td class="text-center"> {{ $data_inicio }}</td>
                                    <td class="text-center"> {{ $data_fim }}</td>
                                    <td class="text-center"> {{ $data_devolucao }}</td>
                                    <td width="250" class="text-center">
                                        @if(!isset($lending->date_finish))
                                            <button onclick="action(this, 'confirm')" type="button" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-book"></i>Encerrar</button>
                                        @endif
                                        <a href="{{route('lendingAdmin.edit', $lending->id)}}" class="btn btn-warning btn-sm"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
                                        <button onclick="action(this, 'remove')" type="button" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i>Excluir</button>
                                    </td>
                                </tr>
                        @endforeach    
                        </tbody>
                    </table>                  
                  
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="indexModal" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
  <div class="modal-dialog" style="width: 400px; height: 400px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModal">Empréstimo para <span style="font-weight:bold" id="student"></span></h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button id="btnOk" onclick="endingLending()" type="button" class="btn btn-success" data-dismiss="modal">Confirmar</button>
      </div>
    </div>
  </div>
</div>
<script>
    function action(e, whatToDo){ 
        let id = $(e).parent().siblings("#code").html();
        if(whatToDo == "remove"){
            $(".modal-body").html("<div>Deseja realmente excluir o empréstimo de código "+ id +"?</div>");
            $("#btnOk").attr("onclick", "remove("+id+")");
        }else{
            $(".modal-body").html("<div>Data de Devolução em: <input type='date' id='date_finish' name='date_finish'></div>");
            $("#btnOk").attr("onclick", "endingLending("+id+")");
        }
        let name = $(e).parent().siblings("#studentName").html();
        $("#student").html(name);
        $('#indexModal').modal('show');
    }
    function endingLending(id){ 
        $.ajax({
            type: "post",
            url: "{{route('lendingAdmin.takeBookBack') }}",
            async: false,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType: 'json',
            data : { date_finish: $("#date_finish").val(), id: id },
            success: function (data) {
                console.log(data);
                window.location.href = data.html;
            },
            error: function(data) {
                console.log(data);
                if(!$.isEmptyObject(data.responseJSON.msg))
                    showErrorMsg(data.responseJSON.msg);
            }
        });
    }
    function remove(id){ 
        $.ajax({
            type: "post",
            url: "{{route('lendingAdmin.delete')}}",
            async: false,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType: 'json',
            data : { id: id },
            success: function (data) {
                console.log(data);
                window.location.href = data.html;
            },
            error: function(data) {
                console.log(data);
            }
        });
    }
    function showErrorMsg(msg){
        $(".errorMsg").children("ul").html('');
        $(".errorMsg").css('display','block');
        $.each( msg, function( key, value ) {
            $(".errorMsg").children("ul").append('<li>'+value+'</li>');
        });
    }
</script>

@endsection