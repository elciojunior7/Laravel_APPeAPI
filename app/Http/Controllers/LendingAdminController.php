<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\User;
use App\Models\Lending;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Validator;

class LendingAdminController extends Controller
{    
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    public function index()
    {
        $user = Auth::user();
        $lendings = Lending::get();
        
        return view('lendingAdmin.index', compact('lendings'));
    }

    public function add()
    {
        $users = User::where('role','!=','100')->get();
        $books = Book::get();
        return view('lendingAdmin.add',compact('users','books'));
    }

    public function save(Request $request)
    {
        $date_end = $request->input('date_end');
        $date_start = $request->input('date_start');
        $id_user = $request->input('id_user');
        $books = $request->input('chosen_books');

        $validator = Validator::make($request->all(),[
            'date_start' => 'required',
            'chosen_books' => 'required',
            'id_user' => 'required'
        ],[
            'required' => 'Campo :attribute é obrigatório'
        ],[
            'date_start' => 'Data de Retirada',
            'id_user' => 'Aluno',
            'chosen_books' => 'Livros Escolhidos'
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

        $returnHtml = '../emprestimoAdmin';
        return response()->json([
            'success' => true,
            'html' => $returnHtml,
        ], 200);
    } 

    public function edit($id)
    {
        $lending = Lending::find( $id );
        $users = User::where('role','!=','100')->get();
        $books = Book::get();
        $selecteds_books = array();

        if (!empty($lending))
        {  
            $msgFinish = "Devolução não realizada. Está dentro do prazo"; 
            $classFinish = "style=color:green";
            if(!isset($lending->date_finish) && (time() > strtotime($lending->date_end))) {
                $msgFinish = "Devolução está atrasada";
                $classFinish = "style=color:red";
            }
            foreach($lending->books as $book)
            {
                $selecteds_books[] = $book->lent->book_id;
            }

            return view('lendingAdmin.edit', compact('msgFinish','classFinish','lending','users','books','selecteds_books'));
        }
    }

    public function update(Request $request, $id)
    {
        $date_finish = $request->input('date_finish');
        $date_end = $request->input('date_end');
        $date_start = $request->input('date_start');
        $id_user = $request->input('id_user');
        $books = $request->input('chosen_books');

        $validator = Validator::make($request->all(),[
            'date_start' => 'required',
            'chosen_books' => 'required',
            'id_user' => 'required'
        ],[
            'required' => 'Campo :attribute é obrigatório'
        ],[
            'date_start' => 'Data de Retirada',
            'id_user' => 'Aluno',
            'chosen_books' => 'Livros Escolhidos'
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'msg' => $validator->errors()->all(),
            ], 400); 
        }

        $lending = Lending::find( $id );

        if (!empty( $lending ))
        {  
            if($date_finish){
               $lending->date_finish = $date_finish;
            }         
            $lending->user_id = $id_user;
            $lending->date_start = $date_start;
            $lending->date_end = $date_end;
            
            if (!empty($books))
            {
                $lending->books()->sync( $books );
            }

            $lending->update();
        }

        $returnHtml = '../../emprestimoAdmin';
        return response()->json([
            'success' => true,
            'html' => $returnHtml,
        ], 200);
    }

    public function takeBookBack ( Request $request )
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

        $returnHtml = '../emprestimoAdmin';
        return response()->json([
            'success' => true,
            'html' => $returnHtml,
        ], 200);
    }

    public function search( Request $request )
    {
        $name = $request->input('name');

        if(($name) && (!empty($name))){
            $users = User::where('name','like','%'.$name.'%')->get();
            $lendings = array();
            foreach($users as $user){
                $lendingsByUser = Lending::where('user_id','=',$user->id)->get();
                if(count($lendingsByUser) > 0){
                    foreach($lendingsByUser as $lending){
                        $lendings[] = $lending;
                    }
                }
            }
        }else{
            $lendings = Lending::get();
        }

        return view('lendingAdmin.index', compact('lendings'));
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        $lending = Lending::find( $id );

        if (!empty( $lending ))
        {
            $lending->books()->detach();
            $lending->delete();
        }

        $returnHtml = '../emprestimoAdmin';
        return response()->json([
            'success' => true,
            'html' => $returnHtml,
        ], 200);
    }
}
