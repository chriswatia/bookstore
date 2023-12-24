<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BookLoan;
use App\Models\Book;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookLoanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Check if the user has the role 'User'
        if ($user->role->name === 'User') {
            // If the user is a 'User', only retrieve their own book loans
            $bookLoans = BookLoan::where('user_id', $user->id)->get();
        } else {
            // If the user is an 'Admin', retrieve all book loans
            $bookLoans = BookLoan::all();
        }
    
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

        $book = Book::find($request->book_id);

        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        $dueDate = now()->addDays($book->book_loan_days);

        $bookLoan = BookLoan::create([
            'user_id' => $request->user_id ? null : Auth::id(),
            'book_id' => $request->book_id,
            'loan_date' => $request->loan_date,
            'return_date' => $request->return_date,
            'due_date' => $dueDate,
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

    public function borrowBook(Request $request)
    {
        // Validation
        $rules = array(
            'book_id'   => 'required|exists:books,id',
			'loan_date'   => 'required|date'
		);

        $validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			return response()->json(['errors' => $validator->errors()], 422);
		}

        $user = Auth::user();
        $book = Book::find($request->book_id);

        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        if ($book->isAvailable()) {
            $dueDate = now()->addDays($book->book_loan_days);

            $bookLoan = BookLoan::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'loan_date' => $request->loan_date,
                'return_date' => $dueDate,
                'due_date' => $dueDate,
                'status' => 'pending',
                'added_by' => $user->id,
            ]);

            $book->update(['status' => 'unavailable']);

            return response()->json($bookLoan, 201);
        } else {
            return response()->json(['error' => 'Book is not available for loan'], 400);
        }
    }

    public function approveBookLoan($id)
    {
        // Find the book loan
        $bookLoan = BookLoan::findOrFail($id);

        // Update the book loan status to 'approved'
        $bookLoan->update(['status' => 'approved']);

        return response()->json(['message' => 'Book loan approved successfully']);
    }

    public function issueBook($id)
    {
        // Find the book loan
        $bookLoan = BookLoan::findOrFail($id);

        // Update the book loan status to 'issued'
        $bookLoan->update(['status' => 'issued']);

        return response()->json(['message' => 'Book issued successfully']);
    }

    public function extendBookLoan(Request $request, $id)
    {
        // Find the book loan
        $bookLoan = BookLoan::findOrFail($id);

        // Check if the book loan can be extended
        if (!$bookLoan->canBeExtended()) {
            return response()->json(['error' => 'Book loan cannot be extended'], 400);
        }

        $bookLoan->update(['return_date' => $request->return_date, 'extended' => true, 'extension_date' => now()]);

        return response()->json(['message' => 'Book loan extended successfully']);
    }

    public function receiveBook($id)
    {
        // Find the book loan
        $bookLoan = BookLoan::findOrFail($id);

        // Check if the book loan is already returned
        if ($bookLoan->status === 'returned') {
            return response()->json(['error' => 'Book loan is already returned'], 400);
        }

        // Update the book loan status to 'returned'
        $bookLoan->update(['status' => 'returned', 'return_date' => now()]);

        // Update the corresponding book status to 'available'
        $bookLoan->book->update(['status' => 'available']);

        return response()->json(['message' => 'Book received back successfully']);
    }
}
