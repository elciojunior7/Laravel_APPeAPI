<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\User;
use App\Models\Lending;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Validator;

class LendingController extends Controller
{    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $myLendings = Lending::where('user_id', '=', $user->id)->get();
        $lendings = array();
        foreach($myLendings as $lending){
            $bookNames = "";
            foreach($lending->books as $book){
                $bookNames .= $book->title." - ";
            }
            $lending["bookNames"] = $bookNames;
            $lendings[] = $lending;
        }
        
        return view('lending.index', compact('lendings', 'user'));
    }

    public function add()
    {
        $books = Book::get();
        return view('lending.add',compact('books'));
    }

    public function save(Request $request)
    {
        $date_end = $request->input('date_end');
        $date_start = $request->input('date_start');
        $id_user = $user = Auth::user()->id;
        $books = $request->input('chosen_books');

        $validator = Validator::make($request->all(),[
            'date_start' => 'required',
            'chosen_books' => 'required'
        ],[
            'required' => 'Campo :attribute é obrigatório'
        ],[
            'date_start' => 'Data da Retirada',
            'chosen_books' => "Livros Escolhidos"
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'msg' => $validator->errors()->all(),
            ], 400); 
        }

        $lending = Lending::create([
                        'user_id' => $id_user,
                        'date_start' => $date_start,
                        'date_end' => $date_end
                    ]);

        $lending->books()->sync($books);               

        $returnHtml = '../emprestimos';
        return response()->json([
            'success' => true,
            'html' => $returnHtml,
        ], 200);
    } 

    public function giveback ( Request $request )
    {
        $id = $request->input('id');
        $validator = Validator::make($request->all(),[
            'date_finish' => 'required',
        ],[
            'required' => 'Campo :attribute é obrigatório para encerrar empréstimo'
        ],[
            'date_finish' => 'Data de Devolução'
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'msg' => $validator->errors()->all(),
            ], 400); 
        }

        $lending = Lending::find( $id );
        $date_finish = $request->input('date_finish');

        if (!empty( $lending ))
        {
            $lending->date_finish = $date_finish;
            $lending->update();
        }

        $returnHtml = '../emprestimos';
        return response()->json([
            'success' => true,
            'html' => $returnHtml,
        ], 200);
    }

    public function search( Request $request )
    {
        $title = $request->input('title');
        $user = Auth::user();

        if(($title) && (!empty($title))){
            $books = Book::where('title','like','%'.$title.'%')->get();
            $myLendings = Lending::where('user_id','=',$user->id)->get();
            $lendings = array();
            foreach($books as $book){
                foreach($book->lendings as $lend){
                    foreach($myLendings as $key => $lending){
                        if($lending->id == $lend->id){
                            $bookNames = "";
                            foreach($lending->books as $book){
                                $bookNames .= $book->title." - ";
                            }
                            $lending["bookNames"] = $bookNames;
                            $lendings[] = $lending;
                            unset($myLendings[$key]);
                            break;
                        }
                    }
                }
            }
        }else{
            $myLendings = Lending::where('user_id', '=', $user->id)->get();
            $lendings = array();
            foreach($myLendings as $lending){
                $bookNames = "";
                foreach($lending->books as $book){
                    $bookNames .= $book->title." - ";
                }
                $lending["bookNames"] = $bookNames;
                $lendings[] = $lending;
            }
        }

        return view('lending.index', compact('lendings', 'user'));
    }

}
