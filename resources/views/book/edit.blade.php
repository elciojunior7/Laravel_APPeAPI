@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <ol class="breadcrumb panel-heading" style="background: #efc050">
                    <li><a href="{{route('book.index')}}" style="color:#616161; font-weight:bold">Livros</a></li>
                    <li class="active" style="color:#616161;">Editar</li>
                </ol>
                <div class="panel-body">
                    <form action="{{ route('book.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="title">Título</label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="Título" value="{{ $book->title }}">
                        </div>
                        <div class="form-group">
                            <label for="description">Descrição</label>
                            <textarea class="form-control" rows="3" name="description" id="description" >{{ $book->description }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="name">Autores</label>
                            <select name="authors[]" title="Autores" multiple style="width:100%">
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}" <?= in_array($author->id, $selecteds_author) ? "selected" : NULL ; ?>>{{ $author->name ." ". $author->surname}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <img src="/images/book/{{ $book->image }}"  width="10%" />
                            <input type="hidden" name="deleteimage" value="{{ $book->image }}">
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <input name="image" type="file">
                            </div>
                        </div>
                        <br />
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
