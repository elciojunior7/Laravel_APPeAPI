<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Author;
use App\Models\Lending;
use Illuminate\Http\Request;
use Validator;
use Storage;

class ApiController extends Controller{

	public function books(){

		$books = Book::with(['authors','lendings'])->get();
		return response()->json($books);
	}
	
	public function authors()
    {
        $authors= Author::get();
        return response()->json($authors);
    }

	public function saveBooks(Request $request){

		//$request->file('file')->getClientOriginalName());
		//return response()->json($request->input('book'));

		$b = new Book();
		$b = json_decode($request->input('book'));
		return response()->json($b->toArray());

		$validator = Validator::make(json_decode($request->input('book')),[
            'title' => 'required',
            'authors' => 'required',
        ],[
            'required' => 'Campo :attribute é obrigatório'
        ],[
            'title' => 'Título',
            'authors' => 'Autores',
		]);

        if($validator->passes()){
			return response()->json($request->input('title'));
	        /*if (!empty($request->file('image')) && $request->file('image')->isValid()) {
	            $fileName = time().'.'.$request->file('image')->getClientOriginalExtension();
	            $request ->file('image')->move($this->path,$fileName);
	        }else{
	            $fileName = null;
			}*/
			$url = $request->input('title');
	        $contents = file_get_contents($url);
			$fileName = time() . '.' . substr($url, strrpos($url, ".") + 1);
			Storage::disk("book")->put($fileName, $contents);

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