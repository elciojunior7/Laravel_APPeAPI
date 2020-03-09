@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">            	
                <ol class="breadcrumb panel-heading" style="background: #efc050">
                    <li><a href="{{route('lending.index')}}" style="color:#616161; font-weight:bold">Meus Empréstimos</a></li>
                    <li class="active" style="color:#616161;">Novo</li>
                </ol>         
                <div class="panel-body">
                    <div class="alert alert-danger errorMsg" style="display:none">
                        <ul></ul>
                    </div>
	                <form >
	                	{{ csrf_field() }}						

                        <div class="form-group">
                            <label for="name">Data da Retirada: </label>                            
                            <input type="date" id="date_start" name="date_start">
                        </div>

                        <div class="form-group">
                            <label for="name">Livros Escolhidos</label>
                            <select name="chosen_books[]" title="Livros" multiple style="width:100%">
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->title }}</option>
                                @endforeach
                            </select>
                        </div>

						<button onclick="confirm()" type="button" class="btn btn-primary">Emprestar</button>
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
        let startDateStr = $("#date_start").val();
        let startDate = new Date(startDateStr+"T23:59:59");
        let endDate = new Date();
        endDate.setDate(startDate.getDate()+ 7);
        $("#startDateModal").html(startDate.getDate()+" / "+(startDate.getMonth()+1)+" / "+startDate.getFullYear());
        $("#endDateModal").data("end", endDate.getFullYear()+"-"+(endDate.getMonth()+1)+"-"+endDate.getDate());
        $("#endDateModal").html(endDate.getDate()+" / "+(endDate.getMonth()+1)+" / "+endDate.getFullYear());
        $('#confirmModal').modal('show'); 
    }
    function gravar(){ 
        $.ajax({
            type: "post",
            url: "{{ route('lending.save') }}",
            async: false,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType: 'json',
            data : {date_end: $("#endDateModal").data("end"), 
                    date_start:$("#date_start").val(), 
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
