<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$authors = Author::all();

        return view('admin.author');
        //return $authors;
        //return view('admin.author',compact('authors'));
    }
    public function api()
    {
        $authors = Author::all();

        /*foreach ($authors as $key => $author) {
            $author->date = convert_date($author->created_at);
        }*/

        $datatables = datatables()->of($authors)
                        ->addColumn('date', function($author){
                            return convert_date($author->created_at);
                        })
                        ->addIndexColumn();

        return $datatables->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|min:5|max:100',
            'email'=>'required|email',
            'phone_number'=>'required|numeric|min:10|starts_with:08',
            'address'=>'required',
        ]);

        //$catalog = new Catalog;
        //$catalog->name = $request->name;
        //$catalog->save();

        Author::create($request->all());

        return redirect('authors');
        //return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function edit(Author $author)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Author $author)
    {
        $request->validate([
            'name'=>'required|min:5|max:100',
            'email'=>'required|email',
            'phone_number'=>'required|numeric|min:10|starts_with:08',
            'address'=>'required',
        ]);

        //$catalog = new Catalog;
        //$catalog->name = $request->name;
        //$catalog->save();

        $author->update($request->all());

        return redirect('authors');
        //return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        $author->delete();
    }
}
