<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Author;
use App\Models\Lending;
use Illuminate\Http\Request;
use Validator;

class ApiController extends Controller{

	public function books(){

		$books = Book::with(['authors','lendings'])->get();
		return response()->json($books);
	}

	public function saveBooks(Request $request){

		$validator = Validator::make($request->all(),[
            'title' => 'required',
            'authors' => 'required',
        ],[
            'required' => 'Campo :attribute é obrigatório'
        ],[
            'title' => 'Título',
            'authors' => 'Autores',
        ]);

        if($validator->passes()){
	        /*if (!empty($request->file('image')) && $request->file('image')->isValid()) {
	            $fileName = time().'.'.$request->file('image')->getClientOriginalExtension();
	            $request ->file('image')->move($this->path,$fileName);
	        }else{
	            $fileName = null;
	        }*/
	        $contents = file_get_contents($request->input('image'));
			$fileName = time() . '.' . substr($url, strrpos($url, ".") + 1);
			Storage::disk("public")->put($fileName, $contents);

	        $book = book::create([
	            'title' => $request->input('title'),
	            'description' => $request->input('description'),
	            'image' =>$fileName
	        ]);

	        $authors = $request->input('authors');
	        if (!empty($authors))
	        {
	            $book->authors()->sync($authors);
	        }
	        if(!empty($book)){
				return response()->json($book);
			}
		}

	    return response()->json(["message" => "Erro ao Inserir Livro", "erros" => $validator->errors()], 500);

	}

}

?>