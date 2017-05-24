@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Nuevo Detalle</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> Al parecer algo está mal.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="/validado/detallesalidaexterno/crear">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" class="form-control text-uppercase" name="ie_id" value='{{$ie_id}}'>
						<div class="form-group">
							<label class="col-md-4 control-label">Cantidad</label>
							<div class="col-md-6">
								<input type="text" class="form-control text-uppercase" name="die_cant">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Descripción</label>
							<div class="col-md-6">
								<input type="text" class="form-control text-uppercase" name="die_desc">
							</div>
						</div>		
						<div class="form-group">
							<label class="col-md-4 control-label">Precio Unitario</label>
							<div class="col-md-6">
								<input type="text" class="form-control text-uppercase" name="die_prec">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Crear
								</button>
							</div>
							<a href="/validado/detallesalidaexterno?ie_id={{$ie_id}}" class="btn btn-danger" role="button">Cancelar</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
