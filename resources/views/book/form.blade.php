<html>
<head>
	<title>Manage Buku</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
</head>
<body>
	<div class="row">
		<div class="zep">
			<h2 class="text-center my-5">Upload Buku ke Server</h2>
			
			<div class="col-lg-85">	
 
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
						<input class="form-control" name="judul" value="{{old('judul', @$books->judul)}}"></input>
					</div>

					<div class="form-group">
						<b>Deskripsi</b>
						<textarea class="form-control" rows="5" name="deskripsi">{{old('deskripsi', @$books->deskripsi)}}</textarea>
					</div>

                    <div class="form-group">
						<b>File PDF</b><br/>
						<input type="file" name="file" class="form-control-file">
					</div>

					<div class="form-group">
						<b>Cover PDF</b><br/>
						<input type="file" name="gambar" class="form-control-file">
					</div>
 
					<input type="submit" value="Simpan" class="btn btn-primary">
				</form>
			</div>
		</div>
	</div>
</body>
</html>