@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                   
                <ol class="breadcrumb panel-heading" style="background: #efc050">
                    <li><a href="{{route('lendingAdmin.index')}}" style="color:#616161; font-weight:bold">Empréstimos</a></li>
                    <li class="active" style="color:#616161;">Editar Empréstimo</li>
                </ol>
                <div class="panel-body">
                        <div class="alert alert-danger errorMsg" style="display:none">
                            <ul></ul>
                        </div>
                        <form action="{{ route('lendingAdmin.update' , $lending->id) }}" method="POST" enctype="multipart/form-data">
	                	{{ csrf_field() }}	
                        
                        <div class="form-group">
                            <label for="name">Data da Retirada: </label>                            
                            <input type="date" id="date_start" name="date_start" value="{{ date('Y-m-d', strtotime($lending->date_start)) }}" >
                        </div>
                        <div class="form-group">
                            <label for="name">Data Limite para Devolução:</label>                 
                            <input type="date" id="date_end" name="date_end" value="{{ date('Y-m-d', strtotime($lending->date_end)) }}" disabled>
                        </div>
                        <div class="form-group">
                            @if(isset($lending->date_finish)) 
                                <label for="name">Data que o livro foi devolvido:</label>
                                <input type="date" id="date_finish" name="date_finish" value="{{ date('Y-m-d', strtotime($lending->date_finish)) }}" >
                            @else
                                <span {{$classFinish}}>{{$msgFinish}}</span>
                                
                            @endif    
                        </div>

                        <div class="form-group">
                            <label for="name">Aluno</label>
                            <select id="user" name="user" title="Alunos" style="width:100%">
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ ($user->id == $lending->user_id)  ? "selected" : "" }} > {{ $user->name }} </option>
                                @endforeach
                            </select>                           
                        </div>

                        <div class="form-group">
                            <label for="name">Livros</label>
                            <select name="chosen_books[]" title="Livros" multiple style="width:100%">
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}" {{ in_array($book->id, $selecteds_books) ? "selected" : null}} >{{ $book->title }}</option>
                                @endforeach
                            </select>
                        </div>
                      
                        <br />
                        <button onclick="confirm()" type="button" class="btn btn-primary">Alterar</button>
	                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
  <div class="modal-dialog" style="width: 400px; height: 400px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModal">Confirmando...</h4>
      </div>
      <div class="modal-body">
        <div>Data da Retirada do livro é: <span id="startDateModal"></span></div>
        <div>Então a data limite de devolução será: <span id="endDateModal"></span></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button onclick="gravar()" type="button" class="btn btn-success" data-dismiss="modal">Gravar</button>
      </div>
    </div>
  </div>
</div>
<script>
    function confirm(){ 
        if($("#date_finish").length > 0){
            alert("Este empréstimo já foi encerrado. Se não for sua intenção alterá-lo, clique em 'Cancelar' na próxima tela");
        }
        let startDateStr = $("#date_start").val();
        let startDate = new Date(startDateStr+"T23:59:59");
        let endDate = new Date();
        let idUser = $("#user").val();
        $(".modal-body").data("id", idUser);
        endDate.setDate(startDate.getDate()+ 7);
        $("#startDateModal").html(startDate.getDate()+" / "+(startDate.getMonth()+1)+" / "+startDate.getFullYear());
        $("#endDateModal").data("end", endDate.getFullYear()+"-"+(endDate.getMonth()+1)+"-"+endDate.getDate());
        $("#endDateModal").html(endDate.getDate()+" / "+(endDate.getMonth()+1)+" / "+endDate.getFullYear());
        $('#confirmModal').modal('show'); 
    };
    function gravar(){ 

        if($("#date_finish").length > 0){
            var dataFinish = $("#date_finish").val();
        }else{
            var dataFinish = undefined;
        }
        $.ajax({
            type: "post",
            url: "{{ route('lendingAdmin.update', $lending->id) }}",
            async: false,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType: 'json',
            data : {date_finish: dataFinish,
                    date_end: $("#endDateModal").data("end"), 
                    date_start:$("#date_start").val(), 
                    id_user:$(".modal-body").data("id"), 
                    chosen_books: $("select[name='chosen_books[]']").val() },
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
    function showErrorMsg(msg){
        $(".errorMsg").children("ul").html('');
        $(".errorMsg").css('display','block');
        $.each( msg, function( key, value ) {
            $(".errorMsg").children("ul").append('<li>'+value+'</li>');
        });
    }
</script>
@endsection
