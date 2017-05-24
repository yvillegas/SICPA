@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Editar Unidad de Medida</div>
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

					<form class="form-horizontal" role="form" method="POST" action="/validado/unidadproducto/editar">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="um_id" value="{{$unidadproducto->um_id}}" >
						<div class="form-group">
							<label class="col-md-4 control-label">Descripción</label>
							<div class="col-md-6">
								<input type="text" class="form-control text-uppercase" name="um_desc" value="{{$unidadproducto->um_desc}}" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Abreviatura</label>
							<div class="col-md-6">
								<input type="text" class="form-control text-uppercase" name="um_abrev" value="{{$unidadproducto->um_abrev}}" >
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">Editar</button>
								<a href="/validado/unidadproducto" class="btn btn-danger" role="button">Cancelar</a>
							</div>
						</div>
					</form>
					
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
