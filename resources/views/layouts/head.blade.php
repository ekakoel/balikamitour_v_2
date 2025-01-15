<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<title>{{ config('app.name', 'Bali Kami Tour') }} | @yield('title')</title>
	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/balikami/apple-touch-icon.png') }}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/balikami/favicon-32x32.png') }}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/balikami/favicon-16x16.png') }}">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/panel/styles/core.css">
	<link rel="stylesheet" type="text/css" href="/panel/styles/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="/panel/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="/panel/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="{{ asset("/panel/styles/style.css") }}">
	<link rel="stylesheet" type="text/css" href="{{ asset("/css/style.css") }}">
	<link rel="stylesheet" type="text/css" href="/panel/fullcalendar/fullcalendar.css">
	<link rel="stylesheet" type="text/css" href="/panel/dropzone/dropzone.css">
	<link rel="stylesheet" type="text/css" href="/panel/slick/slick.css">
	<link rel="stylesheet" type="text/css" href="/panel/datatables/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="/panel/datatables/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="/panel/bootstrap-touchspin/jquery.bootstrap-touchspin.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js "></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
	<link rel="stylesheet" href="/assets/owlcarousel/owl.carousel.min.css">
	<link rel="stylesheet" href="/assets/owlcarousel/owl.theme.default.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script src="{{ asset('panel/ckeditor/ckeditor.js') }}"></script>
	{{-- <script src="https://cdn.ckeditor.com/4.25.0-lts/standard/ckeditor.js"></script> --}}
	{{-- <script src="https://cdn.ckeditor.com/ckeditor4/4.17.1/standard/ckeditor.js"></script> --}}
	{{-- <script src="https://cdn.tiny.cloud/1/0noybrzl9hm927mh3vrm82ywkgnzgak4ags4e2a8ctwi34gi/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> --}}

	{{-- <script src="https://cdn.ckeditor.com/ckeditor4/4.17.1/standard/ckeditor.js"></script> --}}
	<!-- Menambahkan CSS Quill -->
	{{-- <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet"> --}}

	<!-- Menambahkan Quill JS -->
	{{-- <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script> --}}


	<script>  
		if(performance.navigation.type == 2){
		   location.reload(true);
		}
		document.addEventListener('contextmenu', function(e) {
			e.preventDefault();
		});
	</script>
	@livewireStyles
    <link href="{{ asset('multiform.css') }}" rel="stylesheet" id="bootstrap">
	</head>
	<body class="sidebar-light anim-feed-up">
		@include('component.menu')
		@include('layouts.left-navbar')
		@yield('content')
		@include('layouts.footjs')
	</body>
	@livewireScripts
</html>