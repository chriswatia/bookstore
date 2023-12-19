<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return response()->json($books);
    }

    public function show($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }
        return response()->json($book);
    }

    public function store(Request $request)
    {
        // Validation logic
        $rules = array(
            'name'   => 'required',
			'publisher'   => 'required',
            'isbn'   => 'required|unique:books,isbn',
			'pages' => 'required',
            'sub_category' => 'required',
            'description' => 'required'
		);

        $validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			return response()->json(['errors' => $validator->errors()], 422);
		}

        //Create Book
        $book = Book::create([
            'name' => $request->name,
            'publisher' => $request->publisher,
            'isbn' => $request->isbn,
            'category' => $request->category,
            'sub_category' => $request->sub_category,
            'description' => $request->description,
            'pages' => $request->pages,
            'image' => $request->image,
            'added_by' => Auth::id(),
        ]);

        return response()->json($book, 201);
    }

    public function update(Request $request, $id)
    {
        // Validation logic
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        //Update Book
        $book->update($request->all());

        return response()->json($book, 200);
    }

    public function destroy($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        $book->delete();

        return response()->json(['message' => 'Book deleted Successfully'], 200);
    }
}
