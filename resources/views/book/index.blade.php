@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <ol class="breadcrumb panel-heading" style="background: #efc050">
                    <li class="active" style="color:#616161; font-weight:bold">LIVROS</li>
                </ol>
                <div class="panel-body">
                    <form class="form-inline" action="{{ route('book.search') }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="put">
                        <div class="form-group" style="float: right;">
                            <p><a href="{{route('book.add')}}" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-plus"></i> Adicionar</a></p>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="name" name="title" placeholder="Livros">
                        </div>
                        <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i> Buscar</button>
                    </form>
                    <br />
                    <p>Clique sobre a capa do livro para expandi-la</p>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th width="20">Capa</th>
                                <th>Título</th>
                                <th>Descrição</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($books as $book)
                                <tr>
                                    <th id="code" scope="row" class="text-center">{{ $book->id }}</th>
                                    <td class="center ">
                                        <img onclick="enlargeCover(this)" src="/images/book/{{ $book->image }}" width="100%" style="cursor:pointer" />
                                    </td>
                                    <td id="booktitle">{{ $book->title }}</td>
                                    <td>{{ $book->description }}</td>
                                    <td width="155" class="text-center">
                                        <a href="{{route('book.edit', $book->id)}}" class="btn btn-default">Editar</a>
                                        <button onclick="action(this)" type="button" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i>Excluir</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if(!isset($search))
                    <div align="center">
                        {!! $books->links() !!}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
  <div class="modal-dialog" style="width: 400px; height: 400px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModal">Capa do Livro</h4>
      </div>
      <div class="modal-body" >
        <img src="" id="imagepreview" style="width: 350px; height: 400px;" >
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
        <div>Deseja realmente remover o livro <span id="book" style="font-weight:bold"></span></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button id="btnOk" onclick="" type="button" class="btn btn-success" data-dismiss="modal">Confirmar</button>
      </div>
    </div>
  </div>
</div>
<script>
    function action(e){ 
        let id = $(e).parent().siblings("#code").html();
        $("#btnOk").attr("onclick", "remove("+id+")");
        let book = $(e).parent().siblings("#booktitle").html();
        $("#book").html(book);
        $('#indexModal').modal('show');
    }
    function remove(id){ 
        $.ajax({
            type: "post",
            url: "{{route('book.delete')}}",
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
    function enlargeCover(e){ 
        $('#imagepreview').attr('src', $(e).attr('src'));
        $('#myModal').html($(e).parent().siblings("#booktitle").html());
        $('#imagemodal').modal('show'); 
    };
</script>
@endsection