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
		$arrBook = $b->objectToArray(json_decode($request->input('book')), $request->file('file'));

		$validator = Validator::make($arrBook,[
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

			if (!empty($arrBook['image']) && $arrBook['image']->isValid()) {
				$fileName = time().'.'.$arrBook['image']->getClientOriginalExtension();
				Storage::disk("book")->put($fileName, file_get_contents($arrBook['image']));
			}else{
				$fileName = null;
			}

	        $book = book::create([
	            'title' => $arrBook['title'],
	            'description' => $arrBook['description'],
	            'image' =>$fileName
	        ]);

	        $authors = $arrBook['authors'];
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