<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;    // 追加
use App\Start;    // 追加
use App\Stop;    // 追加
use App\User;
use App\Category;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = new Category;

        return view('categories.create', [
            'category' => $category,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Auth::check()) {

        $this->validate($request, [
            'name' => 'required|max:191',
        ]);

        $message = '';
        $user = \Auth::user();
        
        $category_store_limit = 128;
        $category_store_count = $user->categories()->count();
        if ($category_store_limit>$category_store_count){

        $request->user()->categories()->create([
        'category' => $request->name,
            ]);
        $message = 'カテゴリー'.$request->name.'を追加しました。';
        }else{
        $message = 'カテゴリーが128以上あります。追加できません。';
        }


        }
        return redirect('/')->with('flash_message', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        if (\Auth::check()) {
            $user = \Auth::user();
            $categories = $user->categories()->get();

        return view('categories.edit', [
            'categories' => $categories,
        ]);


        }

        return redirect('/');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)//削除する
    {

        if (\Auth::check()) {
        $message = '';
            $user = \Auth::user();
            $id = $request->category;
            $category = Category::find($id);
            
        if ($category['user_id']==$user['id']){
        $message = "カテゴリー".$category->category.'を削除しました。';
        $category->delete();

        }else{
        $message = "カテゴリー".$category->category.'を削除できません。';
        }

    }
        return redirect('/')->with('flash_message', $message);
    }
}
