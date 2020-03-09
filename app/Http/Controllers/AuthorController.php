<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use Validator;

class AuthorController extends Controller
{
     public function __construct()
    {
        //manda para o middleware para saber se estou autenticado
        $this->middleware('auth.admin');
    }


    public function index()
    {
        $authors= Author::paginate(10);
        return view('author.index', compact('authors'));
    }

    //cria a view para visualizar
    public function add()
    {
        return view('author.add');
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
        ],[
            'required' => 'Campo :attribute é obrigatório'
        ],[
            'name' => 'Nome'
        ]);

        if($validator->fails()){
             return redirect()->back()->withErrors($validator)->withInput();
        }

        $author = Author::create([
            'name' => $request->input('name'),
            'surname' => $request->input('surname')
        ]);
        
        return redirect()->route('author.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $author = author::find($id);

        if(!$author){
            return redirect()->route('author.index');
        }
        return view('author.edit', compact('author'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
        ],[
            'required' => 'Campo :attribute é obrigatório'
        ],[
            'name' => 'Nome'
        ]);

        if($validator->fails()){
             return redirect()->back()->withErrors($validator)->withInput();
        }

        $update = [
            'name' => $request->input('name'),
            'surname' => $request->input('surname')
        ];

        $result = Author::find($id)->update($update);

        return redirect()->route('author.index');
    }


    public function search(Request $request)
    {
        $name = $request->input('name');
        $search = TRUE;

        if(($name) && (!empty($name))){
            $authors = author::where('name','like','%'.$name.'%')->orWhere('surname','like','%'.$name.'%')->get();
            return view('author.index', compact('authors', 'search'));
        }else{
            return redirect()->route('author.index');
        }
    }

    public function delete( Request $request )
    {
        $id = $request->input('id');
        $author = author::find($id);

        if($author){
            $author->books()->detach();
            $result = $author->delete();
        }
        $returnHtml = '../autores';
        return response()->json([
            'success' => true,
            'html' => $returnHtml,
        ], 200);
    }

}
