<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Publisher;
use App\Models\Author;
use App\Models\Catalog;
use App\Models\Book;
use App\Models\Member;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class HomeController extends Controller
{      
        public function dashboard()
    {
        $total_member = Member::count();
        $total_book = Book::count();
        $total_author = Author::count();
        $total_publisher = Publisher::count();

        $data_donut = Book::select(DB::raw("count(publishers_id) as total"))->groupBy('publishers_id')->orderBy('publishers_id', 'asc')->pluck('total');
        $label_donut = Publisher::orderBy('publishers.id', 'asc')->join('books', 'books.publishers_id', '=', 'publishers.id')->groupBy('publishers.name')->pluck('publishers.name');

        $label_bar = ['Peminjaman','Pengembalian'];
        $data_bar = [];

        foreach ($label_bar as $key => $value) {
                $data_bar[$key]['label'] = $label_bar[$key];
                $data_bar[$key]['backgroundColor'] = $key == 0 ? 'rgba(60,141,188,0,9' : 'rgba(255,193,7,1)';
                $data_month = [];

                foreach (range(1, 12) as $month) {
                if ($key == 0) {
                        $data_month[] = Transaction::select(DB::raw("count(*) as total"))->whereMonth('date_start', $month)->first()->total;
                } else {
                        $data_month[] = Transaction::select(DB::raw("count(*) as total"))->whereMonth('date_end', $month)->first()->total;                     
                }

                $data_bar[$key]['data'] = $data_month;
        }

        $data_pie = Book::select(DB::raw("count(author_id) as total"))->groupBy('author_id')->orderBy('author_id', 'asc')->pluck('total');
        $label_pie = Author::orderBy('authors.id', 'asc')->join('books', 'books.author_id', '=', 'authors.id')->groupBy('authors.name')->pluck('authors.name');
        
        }
        return view('admin.dashboard', compact('total_member','total_book','total_author','total_publisher','data_donut','label_donut','data_bar','data_pie','label_pie'));

    }       
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {  
        //$member = Member::with('user')->get();
        //$books = Book::with('publisher')->get();
        //$books = Book::with('author')->get();
        //$books = Book::with('catalog')->get();
        //$publisher = Publisher::with('books')->get();
        //$author = Author::with('books')->get();
        //$catalog = Catalog::with('books')->get();
        //no 1
        $data1 = Member::select('*')
                ->join('users','users.member_id','=','members.id')
                ->get();

        //no 2
        $data2 = Member::select('*')
                ->leftJoin('users','users.member_id','=','members.id')
                ->where('users.id',NULL)
                ->get();

        //no 3
        $data3 = Member::select('members.id','members.name')
                ->rightJoin('transactions','transactions.member_id','=','members.id')
                ->where('transactions.member_id',NULL)
                ->get();

        //no 4
        $data4 = Member::select('members.id','members.phone_number','members.name')
                ->join('transactions','transactions.member_id','=','members.id')
                ->get();

        //no 5
        $data5 = Member::select('members.id','members.phone_number','members.name')
                ->join('transactions','transactions.member_id','=','members.id')
                ->groupBy('members.name','members.id','members.phone_number')
                ->havingRaw('count(members.name) > ?',[1])
                ->get();
       

        //no 6
        $data6 = Member::select('members.name','members.phone_number','members.address','transactions.date_start','transactions.date_end')
                ->leftJoin('transactions','transactions.member_id','=','members.id')
                ->get();

        //no 7
        $data7 = Member::select('members.name','members.phone_number','members.address','transactions.date_start','transactions.date_end')
                ->leftJoin('transactions','transactions.member_id','=','members.id')
                ->whereMonth('date_end',date('6'))
                ->get();

        //no 8
        $data8 = Member::select('members.name','members.phone_number','members.address','transactions.date_start','transactions.date_end')
                ->leftJoin('transactions','transactions.member_id','=','members.id')
                ->whereMonth('date_start',date('5'))
                ->get();

        //no 9
        $data9 = Member::select('members.name','members.phone_number','members.address','transactions.date_start','transactions.date_end')
                ->leftJoin('transactions','transactions.member_id','=','members.id')
                ->whereMonth('date_start',date('6'))
                ->whereMonth('date_end',date('6'))
                ->get();

        //no 10
        $data10 = Member::select('members.name','members.phone_number','members.address','transactions.date_start','transactions.date_end')
                ->join('transactions','transactions.member_id','=','members.id')
                ->where('members.address','LIKE',"%{Bandung}%")
                ->get();

        //no 11
        $data11 = Member::select('members.name','members.phone_number','members.address','transactions.date_start','transactions.date_end')
                ->leftJoin('transactions','transactions.member_id','=','members.id')
                ->where('members.address','LIKE',"%{Bandung}%")
                ->where('members.gender','LIKE',"%{f}%")
                ->get();

        //no 12
        $data12 = Member::select('members.name','members.phone_number','members.address','transactions.date_start','transactions.date_end','books.isbn','books.qty')
                ->join('transactions','transactions.member_id','=','members.id')
                ->join('transaction_details','transaction_details.transaction_id','=','transactions.id')
                ->join('books','books.id','=','transaction_details.book_id')
                ->where('books.qty','>',1)
                ->get();

        //no 13
        $data13 = Member::select('members.name','members.phone_number','members.address','transactions.date_start','transactions.date_end','books.isbn','books.qty','books.title','books.price',Book::raw('(books.qty * books.price) as total'))
                ->join('transactions','transactions.member_id','=','members.id')
                ->join('transaction_details','transaction_details.transaction_id','=','transactions.id')
                ->join('books','books.id','=','transaction_details.book_id')
                ->get();
        

        //no 14
        $data14 = Member::select('members.name','members.phone_number','members.address','transactions.date_start','transactions.date_end','books.isbn','books.qty','books.title','publishers.name','authors.name','catalogs.name')
                ->join('transactions','transactions.member_id','=','members.id')
                ->join('transaction_details','transaction_details.transaction_id','=','transactions.id')
                ->join('books','books.id','=','transaction_details.book_id')
                ->join('publishers','publishers.id','=','books.publishers_id')
                ->join('authors','authors.id','=','books.author_id')
                ->join('catalogs','catalogs.id','=','books.catalog_id')
                ->get();

        //no 15
        $data15 = Catalog::select('*')
                ->join('books','books.catalog_id','=','catalogs.id')
                ->get();

        //no 16
        $data16 = Catalog::select('catalogs.id','catalogs.name','books.title')
                ->rightJoin('books','books.catalog_id','=','catalogs.id')
                ->get();

        //no 17
        $data17 = Book::select('books.author_id',Book::raw('SUM(books.qty) as total'))
                ->where('books.author_id','LIKE',"%{PG05}%")
                ->groupBy('books.author_id')
                ->get();

        //no 18
        $data18 = Book::select('*')
                ->where('books.price','>',10000)
                ->get();

        //no 19
        $data19 = Book::select('*')
                ->join('publishers','publishers.id','=','books.publishers_id')
                ->where('publishers.name','LIKE',"%{01}%")
                ->where('books.qty','>',10)
                ->get();

        //no 20
        $data20 = Member::select('*')
                ->whereMonth('created_at',date('6'))
                ->get();
        

        //return $data5;

        $transactions = DB::table('transactions')->select('*')->join('members', 'members.id', '=', 'transactions.member_id')->get();

        return $transactions;
        return view('home');
    }
}
