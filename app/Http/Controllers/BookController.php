<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\BookStore;
use App\Model\Book;
use App\Model\BookCopy;
use App\Model\BookLoan;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::all();

        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookStore $request)
    {
        $validatedData = $request->validated();
        $show = Book::create($validatedData);
   
        return redirect('/books')->with('success', 'Book is successfully saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $book = Book::firstWhere('book_id', $id);

        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookStore $request, int $id)
    {
        $validatedData = $request->validated();
        Book::where('book_id',$id)->update($validatedData);

        return redirect('/books')->with('success', 'Book Data is successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $book = Book::firstWhere('book_id', $id);
        $book->delete();

        return redirect('/books')->with('success', 'Book Data is successfully deleted');
    }
    
    /**
     * search Book using book_id, title, author_name, publisher
     *
     * @param  mixed $request
     * @return void
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $books = BookCopy::getBooks($query);

        return view('books.search', compact('books'));
    }
    
    /**
     * Book checkout page
     *
     * @param  mixed $request
     * @return void
     */
    public function checkout(Request $request) 
    {
        $bookId = $request->input('book_id', '');
        $branchId = $request->input('branch_id', '');
        return view('books.checkout')
            ->with('book_id', $bookId)
            ->with('branch_id', $branchId);
    }
    
    /**
     * Add Book Loan Entry
     *
     * @param  mixed $request
     * @return void
     */
    public function addLoan(BookStore $request)
    {
        $validated = $request->validated();
        $currentDate = Carbon::now();
        $bookId = $validated['book_id'];
        $branchId = $validated['branch_id'];
        $validated['date_out'] = $currentDate->format('Y-m-d');
        $validated['due_date'] = $currentDate->addDays(14)->format('Y-m-d');
        $checkBookCount = BookLoan::getBookCount($bookId, $branchId);
        $checkBorrowCount = BookLoan::getBorrowCount($validated['card_no']);

        $copiesAvaiable = BookCopy::where('book_id', $bookId)
            ->where('branch_id', $branchId)
            ->first('no_of_copies');
            
        if($checkBorrowCount >= 3) {
            return redirect('checkout')
                ->withErrors('Sorry, You already have 3 active book loans')
                ->withInput();
        }

        if($checkBookCount > $copiesAvaiable->no_of_copies) {
            return redirect('checkout')
                ->withErrors(sprintf('Sorry, BookId %s is not available in BranchId-%s', $bookId, $branchId))
                ->withInput();
        }

        $bookLoanId = BookLoan::create($validated);

        return redirect('/book/loans/')
            ->with('success', sprintf('Thank you, You have checked out BookId %s from BranchId-%s on %s', 
            $bookId, $branchId, $validated['date_out']));
    }

    /**
     * Book Loans page
     *
     * @param  mixed $request
     * @return void
     */
    public function loans(Request $request) 
    {
        $query = $request->input('query');
        $loans = BookLoan::select(
            'book_loans.card_no',
            'borrower.first_name as name',
            'book_loans.book_id',
            'book_loans.branch_id',
            'book_loans.date_out',
            'book_loans.due_date',
            'book_loans.date_in',
        )
        ->leftJoin('borrower', 'borrower.card_no', '=', 'book_loans.card_no')
        ->when($request->has('query'), static function ($q) use ($query) {
            $q->orWhere('book_loans.book_id', 'like', '%' . $query . '%')
              ->orWhere('book_loans.card_no', 'like', '%' . $query . '%')
              ->orWhere('borrower.first_name', 'like', '%' . $query . '%')
              ->orWhere('borrower.last_name', 'like', '%' . $query . '%');
        })
        ->get();

        return view('books.loans')
            ->with('loans', $loans);
    }

     /**
     * Book checkIn page
     *
     * @param  mixed $request
     * @return void
     */
    public function checkIn(Request $request) 
    {
        $bookId = $request->input('book_id', '');
        $branchId = $request->input('branch_id', '');
        $cardNo = $request->input('card_no', '');
        return view('books.check-in')
            ->with('book_id', $bookId)
            ->with('branch_id', $branchId)
            ->with('card_no', $cardNo);
    }
    
    /**
     * Update Book Loan
     *
     * @param  mixed $request
     * @return void
     */
    public function updateLoan(BookStore $request) 
    {
        $validated = $request->validated();
        $currentDate = Carbon::now()->format('Y-m-d');
        $loan = BookLoan::where('book_id', $validated['book_id'])
            ->where('branch_id', $validated['branch_id'])
            ->where('card_no', $validated['card_no'])
            ->update([
                'date_in' => $currentDate
            ]);

        return redirect('/book/loans/')
            ->with('success', sprintf('Thank you, You have checked-in BookId %s from BranchId-%s on %s', 
            $validated['book_id'], $validated['branch_id'], $currentDate));
    }
    
}
