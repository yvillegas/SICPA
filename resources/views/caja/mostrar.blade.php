@extends('app')

@section('content')
@if (Session::has('creado'))
	<div class="alert alert-success">
		{{Session::get('creado')}}
	</div>
@endif
@if (Session::has('actualizado'))
	<div class="alert alert-success">
		{{Session::get('actualizado')}}
	</div>
@endif
@if (Session::has('eliminado'))
	<div class="alert alert-success">
		{{Session::get('eliminado')}}
	</div>
@endif
<div class="container-fluid">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-default">
			<div class="panel-heading">Caja</div>
			
			<div class="panel-body">
				<label>Compras</label>
				<table class="table">
						<tr>
							<th>Total Soles</th>
							<th>Total Doláres</th>
							<th>Total</th>
						</tr>
						<tr>
							<td>S/. {{number_format($tot_compras_soles,2,'.',',')}}</td>
							<td>$. {{number_format($tot_compras_dolar,2,'.',',')}}</td>
							<td>S/. {{number_format($tot_compras,2,'.',',')}}</td>
						</tr>
					
				</table>
			</div>

			<div class="panel-body">
				<label>Ventas</label>
				<table class="table">
						<tr>
							<th>Total Soles</th>
							<th>Total Doláres</th>
							<th>Total</th>
						</tr>
						<tr>
							<td>S/. {{number_format($tot_ventas_soles,2,'.',',')}}</td>
							<td>$. {{number_format($tot_ventas_dolar,2,'.',',')}}</td>
							<td>S/. {{number_format($tot_ventas,2,'.',',')}}</td>
						</tr>
					
				</table>
			</div>

			<div class="panel-body">
				<label>Gastos</label>
				<table class="table">
						<tr>
							<th>Total Soles</th>
							<th>Total Doláres</th>
							<th>Total</th>
						</tr>
						<tr>
							<td>S/. {{number_format($tot_egresos_soles,2,'.',',')}}</td>
							<td>$. {{number_format($tot_egresos_dolar,2,'.',',')}}</td>
							<td>S/. {{number_format($tot_egresos,2,'.',',')}}</td>
						</tr>
					
				</table>
			</div>

			<div class="panel-body">
				<table class="table">
						<tr>
							<th>TOTAL BRUTO</th>
							<th>S/. {{number_format($total,2,'.',',')}}</th>
						</tr>					
				</table>
			</div>
		</div>
	</div>
</div>
@endsection
