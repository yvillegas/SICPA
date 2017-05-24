<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Laravel</title>

	<link href="/css/app.css" rel="stylesheet">

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/"><img src="/images/logo.jpg"/></a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				
				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="/validacion/inicio">Iniciar Sesión</a></li>
						<li><a href="/validacion/registro">Registrarse</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Mantenimiento <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								
								<li><a href="/validado/almacen">Almacen</a></li>
								<li><a href="/validado/familia">Familias de Producto</a></li>
								<li><a href="/validado/categoria">Categorías de Producto</a></li>
								<li><a href="/validado/unidadmedida">Unidades de Medida</a></li>
								<li><a href="/validado/conversion">Conversiones</a></li>
								<li><a href="/validado/producto">Productos</a></li>
								<li><a href="/validado/unidadproducto">Unidades de Medida por Producto</a></li>		
								<li><a href="/validado/cliente">Clientes</a></li>
								<li><a href="/validado/proveedor">Proveedores</a></li>
								<li><a href="/validado/vendedor">Vendedores</a></li>		
								<li><a href="/validado/inventario">Inventario</a></li>
							</ul>							
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Operaciones <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="/validado/ingreso">Compras</a></li>
								<li><a href="/validado/salida">Ventas</a></li>
								<li><a href="/validado/notacreditoemitida">Notas de Credito Emitidas</a></li>
								<li><a href="/validado/npedido">Nota de Pedido</a></li>
								<li><a href="/validado/salidaexterno">Gastos</a></li>
								<li><a href="/validado/notacredito">Notas de Credito</a></li>
								<li><a href="/validado/caja">Caja</a></li>
							</ul>							
						</li>
						<li class="dropdown">
							<a href="/validado/reporte" role="button" aria-expanded="false">Reportes</a>					
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->usu_nom }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="/validacion/salida">Salir</a></li>
							</ul>							
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>
	</br>
	@yield('content')

	<!-- Scripts -->

	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>-->
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/js/zelect.js"></script>
</body>
</html>
