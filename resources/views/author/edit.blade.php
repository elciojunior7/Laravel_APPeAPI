@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
            	<ol class="breadcrumb panel-heading" style="background: #efc050">
                	<li><a href="{{route('author.index')}}" style="color:#616161; font-weight:bold">Autores</a></li>
                	<li class="active" style="color:#616161;">Editar</li>
                </ol>
                <div class="panel-body">
	                <form action="{{ route('author.update', $author->id) }}" method="POST" enctype="multipart/form-data">
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
						  	<label for="name">Nome*</label>
						    <input type="text" class="form-control" name="name" id="name" placeholder="Nome" value="{{ $author->name }}">
						</div>
                        <div class="form-group">
                            <label for="surname">Sobrenome</label>
                            <input type="text" class="form-control" name="surname" id="surname" placeholder="Sobrenome" value="{{ $author->surname }}" data-parsley-pattern="[a-zA-Z]+$" data-parsley-trigger="keyup">
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
