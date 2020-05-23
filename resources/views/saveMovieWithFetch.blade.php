<!DOCTYPE html>
<html lang="es" dir="ltr">
	<head>
		<meta charset="utf-8">
		{{-- Necesario para poder enviar DATA vía fetch --}}
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>Fetch Laravel</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
		<script src="{{ asset('js/app.js') }}" defer></script>
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-8">
					<h2>Total de películas: <span></span></h2>
					<!-- En este listado se cargarán las películas que vengan de la consulta asíncrona -->
					<ul class="list-group"></ul>
				</div>
				<div class="col-4">
					<h2>Dar de alta una película</h2>

				<form method="post" enctype="multipart/form-data" action="guardado">
						@csrf
						<div class="form-group">
							<label>Title:</label>
							<input type="text" name="title" class="form-control">
						</div>
						<div class="form-group">
							<label>Rating:</label>
							<input type="text" name="rating" class="form-control">
						</div>
						<div class="form-group">
							<label>Awards:</label>
							<input type="text" name="awards" class="form-control">
						</div>
						<div class="form-group">
							<label>Release date:</label>
							<input type="date" name="release_date" class="form-control">
						</div>
						<button type="submit" class="btn btn-success">GUARDAR</button>
					</form>
				</div>
			</div>
		</div>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
		<script>
			function cargarPeliculas(){
			// Lista HTML donde se cargarán las películas que vienen de la DB
			let ul = document.querySelector('ul');

			fetch('api/index')
				.then(response => {return response.json()})
				.then(data => {
					let array = [];
					for (let i = 0; i < data.length; i++) {
						let li = document.createElement('li');
					 	let titulo = document.createTextNode(data[i].title);
					 	li.append(titulo);
					 	ul.append(li);		 
					}
				let cant = data.length;
				let span = document.querySelector('h2 span').innerHTML = cant;
				})
				.catch(error => {console.error(`Error: `, error)});

			};
			window.onload = () => {
				toastr.info('asdasd');
				toastr.warning('asdasd');

			// Formulario con el que estamos guardando una película
			let form = document.querySelector('form');
			cargarPeliculas();
			// Array de los campos del Formulario, sacamos el último pues es el botón de enviar
			let campos = Array.from(form.elements);
			campos.shift();
			campos.pop();
			campos.forEach( function(element) {
                    element.onblur = function()  {
                        this.value == "" ? this.classList.add("error") : this.classList.remove("error")
                    }; 
                })
			// Cabecera CSRF para que Laravel recibe el $request y guarde la película
			let header = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

				form.onsubmit = e => {
                    e.preventDefault();
                    let errores = 0
             
                    campos.forEach( function(element) {
                        if(element.value == ""){
                            errores++
                        } 
                    
                    })
            
			 if(!errores){
			 fetch('/formulario', {
			 	method: 'POST',
			 	body: new FormData (document.querySelector('form')), // data del formulario
			 	headers: {'X-CSRF-TOKEN': header} // Para enviar data via fetch

			 })
			 	.then(response => response.text())
			 	.then(data => {
					swal({
                                icon: 'success',
                                title: data.mensaje,
                                showConfirmButton: false,
                                timer: 1500
                            });
							cargarPeliculas();
				 })
			 	.catch(error => {
					swal({
                                icon: 'error',
                                title: 'Oops...' + error,
                                text: 'Llena bien el form!',
                            })
				 });
					}
					else{swal({
                                icon: 'error',
                                title: 'Oops...' + error,
                                text: 'Llena bien el form!',
                            })

					}}
			};

		</script>
	</body>
</html>
