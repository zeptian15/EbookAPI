<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Menggunakan kelas FileSystem bawaan Laravel
use Illuminate\Support\Facades\Storage;

// Panggil kelas Book
use App\Book;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Menampilkan data semua buku yang ada
        $books = Book::all();
        foreach($books as $book){
            $book->view_book = [
                'href' => 'api/v1/buku' . $book->id,
                'method' => 'GET'
            ];
        }
        // // Kirimkan respon
        return response()->json($books, 200);

    }

    // Method khusus untuk API
    public function getAll()
    {
        // Menampilkan data semua buku yang ada
        $books['books'] = Book::paginate(5);
        // Masukan ke dalam Form
        return view('book', $books);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('book.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi
        $rule = [
            'judul_buku' => 'required',
            'link_buku' => 'required|mimes:pdf'
        ];
        $this->validate($request, $rule);

        // Variabel yang menampung hasil inputan
        $judul = $request->input('judul_buku');
        $link = $request->input('link_buku');

        // Membuat objek baru untuk menyimpan
        $book = new Book([
            'judul_buku' => $judul,
            'link_buku' => $link
        ]);

        // Cek apabila Buku berhasil ditambahkan atau tidak
        if($book->save()){
            $book->view_book = [
                'href' => 'api/v1/buku/' . $book->id,
                'method' => 'GET'
            ];
            // Buat pesan berhasil
            $message = [
                'msg' => 'Buku berhasil ditambahkan!',
                'buku' => $book
            ];
            // Berikan pesan berhasil
            return response()->json($message, 201);
        }
        // Buat pesan gagal
        $response = [
            'msg' => 'Buku gagal ditambahkan!'
        ];
        // Berikan pesan gagal
        return response()->json($response, 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Menampilkan detail buku
        $book = Book::where('id', $id)->firstOrFail();
        $book->view_book = [
            'href' => 'api/v1/buku',
            'method' => 'GET'
        ];
        // Buat pesan
        $response = [
            'msg' => 'Informasi Buku',
            'buku' => $book
        ];
        // Berikan pesan
        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $books['books'] = Book::findOrFail($id);
        
        return view('book.form', $books);
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
        // Validasi
        $rule = [
            'judul_buku' => 'required',
            'link_buku' => 'required'
        ];
        $this->validate($request, $rule);

        // Variabel yang menampung hasil inputan
        $judul = $request->input('judul');
        $link = $request->input('link');

        // Cari buku dengan id tertentu
        $book = Book::findOrFail($id);
        // Passing data
        $book->judul_buku = $judul;
        $book->link_buku = $link;

        // Update Buku
        if($book->update()){
            // Buat pesan jika berhasil di Update
            $message = [
                'msg' => 'Data buku berhasil di Update!',
                'buku' => $book
            ];
            // Berikan pesan jika berhasil
            return response()->json($message, 200); 
        }
        // Buat pesan jika gagal
        $response = [
            'msg' => 'Data buku gagal di Update!'
        ];
        // Berikan pesan jika gagal
        return response()->json($response, 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Temukan Buku berdasarkan Id yang dikirim
        $book = Book::findOrFail($id);
        // Hapus data dari Database
        if($book->delete()){
            // Buat pesan jika berhasil
            $message = [
                'msg' => 'Data buku berhasil dihapus!',
                'create' => [
                    'href' => 'api/v1/buku',
                    'method' => 'POST',
                    'params' => 'judul_buku, link_buku'
                ]
            ];
            // Berikan pesan jika berhasil
            return response()->json($message, 200);
        }
        // Buat pesan jika gagal
        $response = [
            'msg' => 'Data buku gagal dihapus!'
        ];
        // Berikan respon jika gagal
        return response()->json($response, 404);
    }

    // Proses Upload File ke Folder Public
    public function upload(Request $request){
        // Validasi
        $rule = [
            'judul_buku' => 'required',
            'link_buku' => 'required'
        ];
        $this->validate($request, $rule);

        $judul = $request->input('judul_buku');
        $file = $request->file('link_buku');

        // Lokasi penyimpanan File
        $directory = 'images';
        $file->move($directory, $file->getClientOriginalName());
        $link_buku = $file->getClientOriginalName();

        // Buat objek buku baru
        $book = new Book([
            'judul_buku' => $judul,
            'link_buku' => $link_buku
        ]);

        // Save Ke Database 
        if($book->save()){
            // Jika berhasil buat pesan berhasil
            $message = [
                'msg' => 'Buku berhasil ditambahkan!',
                'book' => $book
            ];
            // Berikan pesan
            return response()->json($message, 201);
        }
        // Buat pesan jika gagal
        $response = [
            'msg' => 'Buku gagal ditambahkan!'
        ];
        // Berikan pesan jika gagal
        return response()->json($response, 404);
    }
    // Proses Update data Buku
    public function updateBook(Request $request){
        // Validasi
        $rule = [
            'judul_buku' => 'required',
            'link_buku' => 'mimes:pdf'
        ];
        $this->validate($request, $rule);

        $judul = $request->input('judul_buku');
        $file = $request->file('link_buku');

        // Lokasi penyimpanan File
        $directory = 'images';
        $file->move($directory, $file->getClientOriginalName());
        $link_buku = $file->getClientOriginalName();

        // Buat objek buku baru
        $book = new Book([
            'judul_buku' => $judul,
            'link_buku' => $link_buku
        ]);

        // Save Ke Database 
        if($book->save()){
            // Jika berhasil buat pesan berhasil
            $message = [
                'msg' => 'Buku berhasil ditambahkan!',
                'book' => $book
            ];
            // Berikan pesan
            return response()->json($message, 201);
        }
        // Buat pesan jika gagal
        $response = [
            'msg' => 'Buku gagal ditambahkan!'
        ];
        // Berikan pesan jika gagal
        return response()->json($response, 404);
    }
}
