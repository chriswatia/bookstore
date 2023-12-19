<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BookLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookLoanController extends Controller
{
    public function index()
    {
        $bookLoans = BookLoan::all();
        return response()->json($bookLoans);
    }

    public function show($id)
    {
        $bookLoan = BookLoan::find($id);
        if (!$bookLoan) {
            return response()->json(['error' => 'Book Loan not found'], 404);
        }
        return response()->json($bookLoan);
    }

    public function store(Request $request)
    {
        // Validation logic
        $rules = array(
            // 'user_id' => 'required',
            'book_id'   => 'required|exists:books,id',
			'loan_date'   => 'required|date',
            'return_date'   => 'required|date'
		);

        $validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			return response()->json(['errors' => $validator->errors()], 422);
		}

        $bookLoan = BookLoan::create([
            'user_id' => $request->user_id ? null : Auth::id(),
            'book_id' => $request->book_id,
            'loan_date' => $request->loan_date,
            'return_date' => $request->return_date,
            'due_date' => $request->return_date,
            'extended' => false, // Set default to false
            'status' => 'pending', // Set default status
            'added_by' => Auth::id(),
        ]);

        $bookLoan = BookLoan::find($bookLoan->id);

        return response()->json($bookLoan, 201);
    }

    public function update(Request $request, $id)
    {
        // Validation logic
        $rules = array(
			'loan_date'   => 'required|date',
            'return_date'   => 'required|date'
		);

        $validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			return response()->json(['errors' => $validator->errors()], 422);
		}

        $bookLoan = BookLoan::find($id);
        if (!$bookLoan) {
            return response()->json(['error' => 'Book Loan not found'], 404);
        }

        $bookLoan->update($request->all());

        return response()->json($bookLoan, 200);
    }

    public function destroy($id)
    {
        $bookLoan = BookLoan::find($id);
        if (!$bookLoan) {
            return response()->json(['error' => 'Book Loan not found'], 404);
        }

        $bookLoan->delete();

        return response()->json(['message' => 'Book Loan deleted'], 200);
    }
}

