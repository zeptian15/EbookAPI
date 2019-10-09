<html>
<head>
	<title>Tutorial Laravel #30 : Membuat Upload File Dengan Laravel</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
</head>
<body>
	<div class="row">
		<div class="container">
			<h2 class="text-center my-5">Upload Buku ke Server</h2>
			
			<div class="col-lg-8 mx-auto my-5">	
 
				@if(count($errors) > 0)
				<div class="alert alert-danger">
					@foreach ($errors->all() as $error)
					{{ $error }} <br/>
					@endforeach
				</div>
				@endif
 
				<form action="{{url('/book/' . @$books->id)}}" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}

                    @if(!empty($books))
                        @method('PATCH')
                    @endif
 
					<div class="form-group">
						<b>Judul Buku</b>
						<input class="form-control" name="judul_buku" value="{{old('judul_buku', @$books->judul_buku)}}"></input>
					</div>

                    @if(!empty($books))
                    <div class="form-group">
						<b>File PDF sebelumnya</b>
						<input class="form-control" name="link_buku" value="{{old('link_buku', @$books->link_buku)}}" readonly></input>
					</div>
                    @endif

                    <div class="form-group">
						<b>File PDF</b><br/>
						<input type="file" name="link_buku" class="form-control-file" value="{{old('link_buku', @$books->link_buku)}}">
					</div>
 
					<input type="submit" value="Simpan" class="btn btn-primary">
				</form>
			</div>
		</div>
	</div>
</body>
</html>