<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/autores', ['uses'=>'AuthorController@index','as'=>'author.index']);
Route::get('/livros', ['uses'=>'BookController@index','as'=>'book.index']);
Route::get('/emprestimos', ['uses'=>'LendingController@index','as'=>'lending.index']);
Route::get('/emprestimoAdmin', ['uses'=>'LendingAdminController@index','as'=>'lendingAdmin.index']);

Route::get('/autores/add', ['uses'=>'AuthorController@add','as'=>'author.add']);
Route::post('/autores/save', ['uses'=>'AuthorController@save','as'=>'author.save']);
Route::get('/autores/edit/{id}', ['uses'=>'AuthorController@edit','as'=>'author.edit']);
Route::post('/autores/update/{id}', ['uses'=>'AuthorController@update','as'=>'author.update']);
Route::post('/autores/delete', ['uses'=>'AuthorController@delete','as'=>'author.delete']);
Route::put('/autores/search', ['uses'=>'AuthorController@search','as'=>'author.search']);

Route::get('/livros/add', ['uses'=>'BookController@add','as'=>'book.add']);
Route::post('/livros/save', ['uses'=>'BookController@save','as'=>'book.save']);
Route::get('/livros/edit/{id}', ['uses'=>'BookController@edit','as'=>'book.edit']);
Route::post('/livros/update/{id}', ['uses'=>'BookController@update','as'=>'book.update']);
Route::post('/livros/delete', ['uses'=>'BookController@delete','as'=>'book.delete']);
Route::put('/livros/search', ['uses'=>'BookController@search','as'=>'book.search']);

Route::get('/emprestimo/add', ['uses'=>'LendingController@add','as'=>'lending.add']);
Route::get('/emprestimo/lend', ['uses'=>'LendingAdminController@add','as'=>'lendingAdmin.lend']);
Route::post('/emprestimo/save', ['uses'=>'LendingController@save','as'=>'lending.save']);
Route::post('/emprestimo/confirm', ['uses'=>'LendingAdminController@save','as'=>'lendingAdmin.save']);
Route::get('/emprestimo/edit/{id}', ['uses'=>'LendingAdminController@edit','as'=>'lendingAdmin.edit']);
Route::post('/emprestimo/update/{id}', ['uses'=>'LendingAdminController@update','as'=>'lendingAdmin.update']);
Route::post('/emprestimo/giveback', ['uses'=>'LendingController@giveback','as'=>'lending.giveback']);
Route::post('/emprestimo/takeBookBack', ['uses'=>'LendingAdminController@takeBookBack','as'=>'lendingAdmin.takeBookBack']);
Route::post('/emprestimo/delete', ['uses'=>'LendingAdminController@delete','as'=>'lendingAdmin.delete']);
Route::put('/emprestimo/search', ['uses'=>'LendingController@search','as'=>'lending.search']);
Route::put('/emprestimo/find', ['uses'=>'LendingAdminController@search','as'=>'lendingAdmin.search']);