@extends('app')

<script>
    function getUp(sel)
	{	    
	    var prod_id = sel.value;

        $.get('{{ url('information') }}/create/ajax-state-prod_um?prod_id=' + prod_id, function(data) {
                $('#um_id').val(data.um_id);
                $('#um_desc').val(data.um_desc);
        });

	}
</script>

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Nueva Registro de Inventario</div>
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

					<form class="form-horizontal" role="form" method="POST" action="/validado/inventario/crear">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="form-group">
							<label class="col-md-4 control-label">Producto</label>
							<div class="col-md-6">
								<select name="prod_id" class="form-control text-uppercase" onchange="getUp(this)">
									<option  value='0'>Elija Producto</option>
									@foreach ($productos as $producto)
									   <option  value='{{$producto->prod_id}}'>{{$producto->prod_desc}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Cantidad</label>
							<div class="col-md-6">
								<input type="text" class="form-control text-uppercase" id="inv_cant" name="inv_cant">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Unidad de Medida</label>
							<div class="col-md-6">
								<input type="text" disabled class="form-control text-uppercase" id="um_desc" name="um_desc">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Crear
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
