<!DOCTYPE html>
<?php
	include("procesarLiquidacion.php");
?>
<html>
	<head>
		<style>
			@import url("css/liquidacion.css");
		</style>
		
		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/autoNumeric.js"></script>
		
		<script>
			$(document).ready(function() {
				// formateo numerico con signo peso, separador de miles y decimales (sin decimales)
				$(".monto").autoNumeric('init',{aSign:'$ ', aSep:'.', aDec:',', mDec:0, vMin:0, lZero:'deny'});
				
				// formateo numerico sin signo peso, separador de miles y decimales (2 digitos)
				$("#aporteExtraSalud").autoNumeric('init',{aSign:'', aSep:'.', aDec:',', mDec:2, vMin:0, lZero:'deny'});
				$("#valorUF").autoNumeric('init',{aSign:'$', aSep:'.', aDec:',', mDec:2, vMin:0, lZero:'deny'});
				$("#topeImponible").autoNumeric('init',{aSign:' UF', pSign:'s', aSep:'.', aDec:',', mDec:2, vMin:0, lZero:'deny'});
				
				$("input[type='submit']").click(function() {					
					$(".monto").autoNumeric('destroy');
					$("#aporteExtraSalud").autoNumeric('destroy');
					
					$(".monto").autoNumeric('init',{aSign:'', aSep:'', aDec:',', mDec:0, vMin:0, lZero:'deny'});
					$("#aporteExtraSalud").autoNumeric('init',{aSign:'', aSep:'', aDec:',', mDec:2, vMin:0, lZero:'deny'});
				});
			});			
		</script>
	</head>
	
	<body>
	
		<form name="liquidacion" action="index.php" method="POST" >
			<fieldset>				
				<table class="liquidacionEncabezado">	
					<tr>
						<td class="etiquetaCampoEncabezado">Nombre:</td>
						<td class="valorCampoEncabezado">
							<input type="text" name="nombreTrabajador" value="Juan Antonio P&eacute;rez Garc&iacute;a" />
						</td>
						<td class="etiquetaCampoEncabezado">RUT:</td>
						<td class="valorCampoEncabezado">
							<input type="text" name="rutTrabajador" value="22.222.222-2" />
						</td>
					</tr>
					<tr>
						<td class="etiquetaCampoEncabezado">Cargo:</td>
						<td class="valorCampoEncabezado">
							<select name="cargo" >
								<option value="">-- Seleccione Cargo --</option>
								<option value="DG">Dise&ntilde;ador Gr&aacute;fico</option>
								<option value="AP">Analista Programador.</option>
								<option value="APS">Analista Programador Sr.</option>
								<option value="IC" selected>Ingeniero de Calidad</option>
								<option value="ICS">Ingeniero de Calidad Sr.</option>
								<option value="IS">Ingeniero de Software</option>
								<option value="ISS">Ingeniero de Software Sr.</option>
								<option value="JP">Jefe de Proyectos</option>
								<option value="JPS">Jefe de Proyectos Sr.</option>
								<option value="GC">Gerente Cuenta</option>
							</select>
						</td>		
						<td class="etiquetaCampoEncabezado">&Aacute;rea:</td>
						<td class="valorCampoEncabezado">
							<select name="area" >			
								<option value="desarrollo">Gerencia Desarrollo</option>
							</select>
						</td>		
					</tr>
					<tr>
						<td class="etiquetaCampoEncabezado">Per&iacute;odo:</td>
						<td class="valorCampoEncabezado">
							<input type="text" name="periodo" value="Mayo 2014" readonly />							
						</td>		
						<td class="valorCampoEncabezado" colspan="2">						
							<b>Tope Imponible:</b> <span id="topeImponible"><?= $topeImponible ?></span><br />
							<b>Valor U.F:</b> <span id="valorUF"><?= $valorUF ?></span>
						</td>		
					</tr>					
				</table>
			</fieldset>
			
			<fieldset>
				<table class="liquidacionDetalle">	
					<tr>
						<th>Item</th>
						<th>Haberes</th>
						<th>Descuentos</th>
					</tr>
					<tr>
						<td class="item">Sueldo Base</td>
						<td class="haber">
							<input class="monto campoEntrada" type="text" name="sueldoBase" value="<?= $sueldoBase ?>" />
						</td>
						<td class="descuento"></td>
					</tr>
					<tr>
						<td class="item">Gratificaci&oacute;n</td>
						<td class="haber">
							<input class="monto" type="text" name="gratificacion" value="83125" readonly />
						</td>
						<td class="descuento"></td>
					</tr>
					<tr>
						<td class="item">7% Salud +
							<input id="aporteExtraSalud" type="text" name="extra_salud" value="<?= $aporteSalud ?>" class="campoEntrada" />
							<select name="extra_salud_tipo">
								<option value="UF" <?= ($tipoAporteSalud=="UF")?"selected":"" ?> >U.F.</option>
								<option value="PESO" <?= ($tipoAporteSalud=="PESO")?"selected":"" ?> >Pesos</option>
								<option value="PORCENTAJE" <?= ($tipoAporteSalud=="PORCENTAJE")?"selected":"" ?> >Porciento</option>
							</select>
							extra
						</td>
						<td class="haber"></td>
						<td class="descuento">
							<span class="monto"><?=$descuentoSalud ?></span>
						</td>
					</tr>
					<tr>
						<td class="item">10% AFP +
							<select name="afp" class="campoEntrada">
								<option value="0.1144" <?= ($porcentajeAFP=="0.1144")?"selected":"" ?> >Capital (1.44%)</option>
								<option value="0.1148" <?= ($porcentajeAFP=="0.1148")?"selected":"" ?> >Cuprum (1.48%)</option>
								<option value="0.1127" <?= ($porcentajeAFP=="0.1127")?"selected":"" ?> >Habitat (1.27%)</option>
								<option value="0.1077" <?= ($porcentajeAFP=="0.1077")?"selected":"" ?> >Modelo (0.77%)</option>
								<option value="0.1236" <?= ($porcentajeAFP=="0.1236")?"selected":"" ?> >Planvital (2.36%)</option>
								<option value="0.1154" <?= ($porcentajeAFP=="0.1154")?"selected":"" ?> >Provida (1.54%)</option>
							</select>
							sobre <input class="monto" type="text" name="sueldoImponible" value="<?= $sueldoImponible ?>" disabled />
						</td>
						<td class="haber"></td>
						<td class="descuento">
							<span class="monto"><?= $descuentoAFP ?></span>
						</td>
					</tr>
					<tr>
						<td class="item">
							0,6% Seguro de Cesant&iacute;a sobre <span class="monto"><?= $imponibleAFC ?></span>
						</td>
						<td class="haber"></td>
						<td class="descuento">
							<span class="monto"><?= $descuentoCesantia ?></span>
						</td>
					</tr>
					<tr>
						<td class="item">Impuesto <?= $impuestoRentaFactor*100 ?>% de
							<span class="monto"><?= $sueldoTributable ?></span> -rebaja
							<span class="monto"><?= $impuestoRentaRebaja ?></span>
						</td>
						<td class="haber"></td>
						<td class="descuento">
							<span class="monto"><?= $impuestoRenta ?></span>
						</td>
					</tr>
					<tr>
						<td class="item">Totales</td>
						<td class="haber">
							<span class="monto"><?= $totalHaberes ?></span>
						</td>
						<td class="descuento">
							<span class="monto"><?= $totalDescuentos ?></span>
						</td>
					</tr>
					<tr>
						<td class="itemFinal">L&iacute;quido a Pagar</td>
						<td class="sueldoLiquido" colspan="2">
							<span class="monto"><?= $sueldoLiquido ?></span>
						</td>
					</tr>
				</table>
			</fieldset>
			
			<input type="submit" value="Calcular" name="calcular" />
		</form>
	</body>
</html>