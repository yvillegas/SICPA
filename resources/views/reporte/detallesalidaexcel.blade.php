<?php
	ob_start();
?>

<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	</head>
	<table border=\"1\" align=\"center\">
		<font size='6' color='#084B8A'><center>REPORTE</center><font size='5' color=\"#ffffff\">
					<tr bgcolor=\"#ffffff\"  align=\"center\"  height='40'>
						<th bgcolor='#ADB8C2' width="3000px" ><font color=\"#ffffff\"><strong>Nro.</strong></font></th>
						<th bgcolor='#ADB8C2' ><font color=\"#ffffff\"><strong>Tipo</strong></font></th>
						<th bgcolor='#ADB8C2' ><font color=\"#ffffff\"><strong>RUC o DNI</strong></font></th>
						<th bgcolor='#ADB8C2' ><font color=\"#ffffff\"><strong>Razón Social</strong></font></th>
						<th bgcolor='#ADB8C2' ><font color=\"#ffffff\"><strong>Fecha</strong></font></th>
						<th bgcolor='#ADB8C2' ><font color=\"#ffffff\"><strong>Subtotal</strong></font></th>
						<th bgcolor='#ADB8C2' ><font color=\"#ffffff\"><strong>IGV</strong></font></th>
						<th bgcolor='#ADB8C2' ><font color=\"#ffffff\"><strong>Total</strong></font></th>
						<th bgcolor='#ADB8C2' ><font color=\"#ffffff\"><strong>Saldo</strong></font></th>
						<th bgcolor='#ADB8C2' ><font color=\"#ffffff\"><strong>Moneda</strong></font></th>
						<th bgcolor='#ADB8C2' ><font color=\"#ffffff\"><strong>Tipo de Cambio</strong></font></th>
						<th bgcolor='#ADB8C2' ><font color=\"#ffffff\"><strong>Cantidad</strong></font></th>
						<th bgcolor='#ADB8C2' ><font color=\"#ffffff\"><strong>Unidad</strong></font></th>
						<th bgcolor='#ADB8C2' ><font color=\"#ffffff\"><strong>Producto</strong></font></th>
						<th bgcolor='#ADB8C2' ><font color=\"#ffffff\"><strong>Precio Unitario</strong></font></th>
						<th bgcolor='#ADB8C2' ><font color=\"#ffffff\"><strong>Precio Total</strong></font></th>
					</tr>

			@if(sizeof($detallecomprobantes)>0)
				

				@foreach ($detallecomprobantes as $detallecomprobante)
					<tr>
						<td><strong>{{$detallecomprobante->comprobante->comp_nro}}</strong></td>
						<td><strong>{{$detallecomprobante->comprobante->tipocomprobante->tcomp_desc}}</strong></td>
						<td><strong>{{$detallecomprobante->comprobante->entidad->ent_ruc}}</strong></td>
						<td><strong>{{$detallecomprobante->comprobante->entidad->ent_rz}}</strong></td>
						<td><strong>{{date('d/m/Y', strtotime($detallecomprobante->comprobante->comp_fecha))}}</strong></td>
						<td><strong>{{number_format($detallecomprobante->comprobante->comp_subt,2,'.',',')}}</strong></td>
						<td><strong>{{number_format($detallecomprobante->comprobante->comp_igv,2,'.',',')}}</strong></td>
						<td><strong>{{number_format($detallecomprobante->comprobante->comp_tot,2,'.',',')}}</strong></td>
						<td><strong>{{number_format($detallecomprobante->comprobante->comp_saldo,2,'.',',')}}</strong></td>
						<td><strong>{{$detallecomprobante->comprobante->comp_moneda}}</strong></td>
						<td><strong>{{$detallecomprobante->comprobante->comp_tipcambio}}</strong></td>

						<?php
							$moneda=0;
							if($detallecomprobante->comprobante->comp_moneda=='SOLES')
								$moneda='S/. ';
							else
								$moneda='$. ';
						?>

						<td><strong>{{rtrim($detallecomprobante->dcomp_cant,'.0')}}</strong></td>
						<td><strong>{{$detallecomprobante->unidadproducto->unidadmedida->um_desc}}</strong></td>
						<td><strong>{{$detallecomprobante->unidadproducto->producto->prod_desc}}</strong></td>
						<td><strong>{{$moneda}}{{number_format($detallecomprobante->dcomp_prec,2,'.',',')}}</strong></td>
						<td><strong>{{$moneda}}{{number_format($detallecomprobante->dcomp_cant*$detallecomprobante->dcomp_prec,2,'.',',')}}</strong></td>
					</tr>
				@endforeach

			@else
				<div class="alert alert-danger">
					<p>Al parecer no tiene detallecomprobantes</p>
				</div>
			@endif

			</table>
</html>


<?php
	$reporte = ob_get_clean();
	header("Content-type: application/vnd.ms-excel");  
	header("Content-Disposition: attachment; filename=Reporte Compras.xls");  
	header("Pragma: no-cache");  
	header("Expires: 0");   

	echo $reporte;  
?>