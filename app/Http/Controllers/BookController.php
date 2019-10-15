<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Menggunakan kelas FileSystem bawaan Laravel
use Illuminate\Support\Facades\Storage;

// Panggil kelas Book
use App\Book;

class BookController extends Controller
{
    public function index()
    {
        // Menampilkan data semua buku yang ada
        $books = Book::all();
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
        // Panggil Form
        return view('book.form');
    }

    // Proses Upload File ke Folder Public
    public function upload(Request $request){
        // Validasi
        $rule = [
            'judul' => 'required',
            'file' => 'required|mimes:pdf',
            'deskripsi' => 'required',
            'gambar' => 'required|mimes:jpg,png,jpeg'
        ];
        $this->validate($request, $rule);

        // Variabel yang menampung hasil inputan
        $judul = $request->input('judul');
        $file = $request->file('file');
        $deskripsi = $request->input('deskripsi');
        $gambar = $request->file('gambar');

        // Pindahkan File
        $directory = 'pdf';
        $filePDF = time() . "." . $file->getClientOriginalExtension();
        $file->move($directory, $filePDF);

        // Pindahkan File Gambar
        $lokasi = 'images';
        $fileGambar = time() . "." . $gambar->getClientOriginalExtension();
        $gambar->move($lokasi, $fileGambar);
        // Membuat objek baru untuk menyimpan
        $book = new Book([
            'judul' => $judul,
            'file' => $filePDF,
            'deskripsi' => $deskripsi,
            'gambar' => $fileGambar,
            'isFavourite' => false
        ]);

        // // Cek apabila Buku berhasil ditambahkan atau tidak
        if($book->save()){
            return redirect('/book')->with('success', 'Data Buku berhasil ditambahkan!');
        } else {
            return redirect('/book/create')->with('error', 'Data Buku gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        $books['books'] = Book::findOrFail($id);
        
        return view('book.form', $books);
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

        // Cek apabila Buku berhasil diubah atau tidak
        if($book->update()){
            return redirect('/book')->with('success', 'Data Buku berhasil diubah!');
        } else {
            return redirect('/book/create')->with('error', 'Data Buku gagal diubah!');
        }
    }

    public function destroy($id)
    {
        // Temukan Buku berdasarkan Id yang dikirim
        $book = Book::findOrFail($id);
        // Hapus data dari Database
        if($book->delete()){
            return redirect('/book')->with('success', 'Data Buku berhasil dihapus!');
        } else {
            return redirect('/book/create')->with('error', 'Data Buku gagal dihapus!');
        }
    }
}
