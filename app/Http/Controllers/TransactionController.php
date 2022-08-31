<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        //return keterlambatan()
        $transactions = Transaction::with('members')->get();
                //return $transactions;

        return view('admin.transaction.index', compact('transactions'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function api(Request $request)
    {
        $transactions = Transaction::select('transactions.*', DB::raw('DATEDIFF(date_end, date_start) as lama_pinjam'))->with('members', 'books');

        if ($request->status) {
                $transaction = Transaction::with('books', 'members')->where('status', '=', $request->status + 1)->get();
            } else if ($request->dateSearch) {
                $transaction = Transaction::with('books', 'members')->whereDate('date_start', '=', $request->date)->get();
            } else {
                $transaction = Transaction::with('books', 'members')->get();
            }

        $transactions = $transactions->get();

        foreach ($transactions as $key => $value) {
            $value->date_start = date('d M Y', strtotime($value->date_start));
            $value->date_end = date('d M Y', strtotime($value->date_end));
            $value->book_total = count($value->books);

            $payment = 0;

            foreach ($value->books as $book) {
                $payment = $payment+$book->price;
            }

            $value->total_bayar = 'Rp '.number_format($payment);

        }

        $datatables = datatables()->of($transactions)->addIndexColumn();

        return $datatables
                ->addColumn('action', function($transaction){      
                           $btn = '<a href="' . route('transactions.show', $transaction->id) . '" class="edit btn btn-info btn-sm" method="POST">View</a>';
                           $btn = $btn.'<a href="' . route('transactions.edit', $transaction->id) . '" class="edit btn btn-primary btn-sm" method="POST">Edit</a>';
                           $btn = $btn.'<form action="' . route('transactions.destroy', $transaction->id) . '" method="POST">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="submit" value="Delete" class="btn btn-danger btn-sm" onclick="return confirm(`Are you sure for delete this one?`)">
                            ' . csrf_field() . '
                            </form>';
         
                            return $btn;
                    })
                ->rawColumns(['action'])
                ->make(true);   
    }
    public function create()
    {
        $books = Book::where('qty', '!=', '0')->get();
        $members = Member::all();
        return view('admin.transaction.create', compact('members','books'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'member_id' => ['required'],
            'date_start' => ['required'],
            'date_end' => ['required'],
            'books' => ['required'],
            'status' => ['required']
        ]);
        // ke transaction
        $transactions = Transaction::create([
            'member_id' => request('member_id'),
            'date_start' => request('date_start'),
            'date_end' => request('date_end'),
            'status' => 2,
        ]);
        // ke transactionDetails
        $books = request('books');
        foreach ($books as $book => $value) {
            TransactionDetail::create([
                'transaction_id' => $transactions->id,
                'book_id' => $value,
                'qty' => 1
            ]);
            // buku berkurang
            $update = Book::where('id', $value)->first();
            $update->update([
                'qty' => $update->qty - 1
            ]);
        }
        return redirect('transactions');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        $transaction = Transaction::with('members','books')->find($transaction->id);
        $books = Book::all();
        return view('admin.transaction.show', compact('transaction','books'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        $transaction = Transaction::with('members','books')->find($transaction->id);
        $members = Member::all();
        $books = Book::where('qty','!=','0')->get();
        return view('admin.transaction.edit', compact('transaction','books','members'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        $this->validate($request, [
            'member_id' => ['required'],
            'date_start' => ['required'],
            'date_end' => ['required'],
            'books' => ['required'],
            'status' => ['required','boolean']
        ]);
        //update ke transaction
        $transaction->update([
            'member_id' => request('member_id'),
            'date_start' => request('date_start'),
            'date_end' => request('date_end'),
            'status' => request('status'),
        ]);

        TransactionDetail::where('transaction_id',$transaction->id)->delete();

        $books = request('books');
        foreach ($books as $book => $value) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'book_id' => $value,
                'qty' => 1
            ]);
            //jika buku sdh kembali qty tambah 1
            if(request('status') == 1) {
                $update = Book::where('id',$value)->first();
                $update->update([
                    'qty' => $update->qty + 1
                ]);
                //juka buku sdh kembali qty di transaksi detail jadi 0
                $transaction_details = TransactionDetail::where('transaction_id',$transaction->id)->get();
                foreach ($transaction_details as $td) {
                    $td->update([
                        'qty' => 0
                    ]);
                }
            }
        }

        return redirect('transactions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        TransactionDetail::where('transaction_id',$transaction->id)->delete();

        $transaction->delete();
        //return $transaction;
        return redirect('transactions');
    }
}
