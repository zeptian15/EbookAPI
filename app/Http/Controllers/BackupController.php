<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Menggunakan kelas FileSystem bawaan Laravel
use Illuminate\Support\Facades\Storage;

// Panggil kelas Book
use App\Book;

class BackupController extends Controller
{
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

    public function create()
    {
        return view('book.form');
    }

    public function store(Request $request)
    {
        // Validasi
        $rule = [
            'judul' => 'required',
            'file' => 'required|mimes:pdf',
            'deskripsi' => 'required'
        ];
        $this->validate($request, $rule);

        // Variabel yang menampung hasil inputan
        $judul = $request->input('judul');
        $file = $request->input('file');
        $deskripsi = $request->input('deskripsi');

        // Membuat objek baru untuk menyimpan
        $book = new Book([
            'judul' => $judul,
            'file' => $file,
            'deskripsi' => $deskripsi
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

    public function edit($id)
    {
        $books['books'] = Book::findOrFail($id);
        
        return view('book.form', $books);
    }

    public function update(Request $request, $id)
    {
        // Validasi
        $rule = [
            'judul_buku' => 'required',
            'file_buku' => 'required'
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
            'judul' => 'required',
            'file' => 'required|mimes:pdf',
            'deskripsi' => 'required'
        ];
        $this->validate($request, $rule);

        // Variabel yang menampung hasil inputan
        $judul = $request->input('judul');
        $file = $request->file('file');
        $deskripsi = $request->input('deskripsi');

        // Pindahkan File
        $directory = 'pdf';
        $file->move($directory, $file->getClientOriginalName());
        $link_buku = $file->getClientOriginalName();

        // Membuat objek baru untuk menyimpan
        $book = new Book([
            'judul' => $judul,
            'file' => $link_buku,
            'deskripsi' => $deskripsi
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
    // Proses Update data Buku
    public function updateBook(Request $request, $id){
        // Validasi
        $rule = [
            'judul' => 'required',
            'file' => 'required|mimes:pdf',
            'deskripsi' => 'required'
        ];
        $this->validate($request, $rule);

        // Variabel yang menampung hasil inputan
        $judul = $request->input('judul');
        $file = $request->file('file');
        $deskripsi = $request->input('deskripsi');

        // Pindahkan File
        $directory = 'pdf';
        $file->move($directory, $file->getClientOriginalName());
        $link_buku = $file->getClientOriginalName();

        // Cari buku dengan id tertentu
        $book = Book::findOrFail($id);
        $book->judul = $judul;
        $book->file = $link_buku;
        $book->deskripsi = $deskripsi;

        // Save Ke Database 
        if($book->update()){
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
