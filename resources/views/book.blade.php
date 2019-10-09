<html>
<head>
	<title>Manage Buku</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="{{asset('/css/style.css')}}">
</head>
<body>
		<div class="zep">
			<h2 class="text-center my-2"><b>Daftar buku yang tersedia</b></h2>
			<div class="col-lg-12">	
			<a href="/book/create" class="btn btn-info mb-3">Tambah data Buku</a>
				@if(count($errors) > 0)
				<div class="alert alert-danger">
					@foreach ($errors->all() as $error)
					{{ $error }} <br/>
					@endforeach
				</div>
				@endif

				<table class="table table-bordered">
				<thead class="text-center">
					<tr>
						<th>ID</th>
						<th>Judul Buku</th>
						<th>Link Buku</th>
						<th colspan="2">Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach($books as $book)
					<tr>
						<td class="text-center">{{$book->id}}</td>
						<td>{{$book->judul_buku}}</td>
						<td>{{$book->link_buku}}</td>
						<td class="text-center">
							<a href="{{url('/book/update/' . $book->id)}}" class="btn btn-primary"> Edit</a>
						</td>
						<td class="text-center">
							<form action="{{url('/book/' . $book->id)}}" methdo="post">
								@csrf
								@method('DELETE')
								<button type="submit" class="btn btn-danger">Delete</button>
							</form>
						</td>
					</tr>
					@endforeach
				</tbody>
				</table>
				{{$books->links()}}
			</div>
		</div>
</body>
</html>