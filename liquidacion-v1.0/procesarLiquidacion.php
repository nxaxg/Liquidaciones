<?php

	//inicializar variables
	$topeImponible = 72.3;
	$topeImponibleAFC = 108.5;
	$valorUF = 23792.36;
	$sueldoBase = "";
	$imponibleAFC = "";
	$sueldoImponible = "";
	$aporteSalud = "0";
	$tipoAporteSalud = "";
	$descuentoSalud = "";
	$porcentajeAFP = "";
	$descuentoAFP = "";
	$descuentoCesantia = "";
	$sueldoTributable="";
	$impuestoRenta = "";
	$impuestoRentaFactor = "";
	$impuestoRentaRebaja = "";
	$totalHaberes="";
	$totalDescuentos="";
	$sueldoLiquido="";	
	
	// codigo a ejecutar cuando se envia el formulario
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$aporteExtraSalud = 0;
		$sueldoBase = $_POST["sueldoBase"] ;
		$sueldoImponible = $_POST["gratificacion"] + $sueldoBase;
		$totalHaberes = $sueldoImponible;
		$porcentajeAFP = $_POST["afp"];
		$aporteSalud = $_POST["extra_salud"];
		$tipoAporteSalud = $_POST["extra_salud_tipo"];
		
		// si el sueldo imponible excede el tope maximo
		if($sueldoImponible > ($topeImponible * $valorUF)) {
			// se utiliza el tope para los calculos de AFP e ISAPRE
			$sueldoImponible  = ($topeImponible * $valorUF);
		}

		// si se ingresa algun aporte adicional de salud por parte del trabajador
		if($aporteSalud > 0) {
			// calcular el monto del aporte extra
			if($tipoAporteSalud == "UF") {
				$aporteExtraSalud = $aporteSalud * $valorUF;				
			} else if($tipoAporteSalud == "PESO") {
				$aporteExtraSalud = $aporteSalud;
			} else if($tipoAporteSalud == "PORCENTAJE") {
				$aporteExtraSalud = $sueldoImponible * ($aporteSalud/100);
			}
		}
			
		// calculo descuento ISAPRE
		$descuentoSalud = $sueldoImponible * 0.07 + $aporteExtraSalud;
		
		// calculo descuento AFP
		$descuentoAFP = $sueldoImponible * $porcentajeAFP;
		
		// calculo seguro de cesantia
		if($totalHaberes > ($topeImponibleAFC * $valorUF)) {
			$imponibleAFC = ($topeImponibleAFC * $valorUF);
			$descuentoCesantia = $imponibleAFC  * 0.006;			
		} else {
			$imponibleAFC = $totalHaberes;
			$descuentoCesantia = $imponibleAFC * 0.006;			
		}
		
		
		// calculo de sueldo tributable (para calcular impuesto a la renta)
		$sueldoTributable = $sueldoImponible - $descuentoSalud - $descuentoAFP - $descuentoCesantia;
			
		calcularImpuestoRenta();		
		
		// total de descuentos
		$totalDescuentos = $descuentoSalud + $descuentoAFP + $descuentoCesantia +  $impuestoRenta;
		
		// sueldo liquido del trabajador
		$sueldoLiquido = $totalHaberes - $totalDescuentos;
		
		// redondear montos
		$descuentoSalud = round($descuentoSalud);
		$descuentoAFP = round($descuentoAFP);
		$descuentoCesantia = round($descuentoCesantia);
		$sueldoTributable = round($sueldoTributable);
		$sueldoImponible = round($sueldoImponible);
		$impuestoRenta = round($impuestoRenta);
		$totalHaberes = round($totalHaberes);
		$totalDescuentos = round($totalDescuentos);
		$sueldoLiquido = round($sueldoLiquido);		
	}
	
	
	function calcularImpuestoRenta() {	
		// indicar que las variables a utilizar son globales! (ya inicializadas mas arriba)
		global $sueldoTributable,$impuestoRentaFactor, $impuestoRentaRebaja, $impuestoRenta;
		
		// tabla de servicios impuestos correspondiente al mes de mayo 2014
		// http://www.sii.cl/pagina/valores/segundacategoria/imp_2da_mayo2014.htm
		if($sueldoTributable > 0 && $sueldoTributable <= 564313.50) {
			$impuestoRentaFactor = 0;
			$impuestoRentaRebaja = 0;
			$impuestoRenta = 0;
		} else if($sueldoTributable > 564313.50 && $sueldoTributable <= 1254030) {
			$impuestoRentaFactor = 0.04;
			$impuestoRentaRebaja = 22572.54;
			$impuestoRenta = ($sueldoTributable * $impuestoRentaFactor) - $impuestoRentaRebaja;
		} else if($sueldoTributable > 1254030 && $sueldoTributable <= 2090050) {
			$impuestoRentaFactor = 0.08;
			$impuestoRentaRebaja = 72733.74;
			$impuestoRenta = ($sueldoTributable * $impuestoRentaFactor) - $impuestoRentaRebaja;
		} else if($sueldoTributable >  2090050 && $sueldoTributable <= 2926070) {
			$impuestoRentaFactor = 0.135;
			$impuestoRentaRebaja = 187686.49;
			$impuestoRenta = ($sueldoTributable * $impuestoRentaFactor) - $impuestoRentaRebaja;
		} else if($sueldoTributable >  2926070 && $sueldoTributable <=  3762090) {
			$impuestoRentaFactor = 0.23;
			$impuestoRentaRebaja = 465663.14;
			$impuestoRenta = ($sueldoTributable * $impuestoRentaFactor) - $impuestoRentaRebaja;
		} else if($sueldoTributable > 3762090 && $sueldoTributable <= 5016120) {
			$impuestoRentaFactor = 0.304 ;
			$impuestoRentaRebaja =  744057.8;
			$impuestoRenta = ($sueldoTributable * $impuestoRentaFactor) - $impuestoRentaRebaja;
		} else if($sueldoTributable > 5016120 && $sueldoTributable <=  6270150) {
			$impuestoRentaFactor = 0.355;
			$impuestoRentaRebaja = 999879.92;
			$impuestoRenta = ($sueldoTributable * $impuestoRentaFactor) - $impuestoRentaRebaja;
		} else if($sueldoTributable > 6270150) {
			$impuestoRentaFactor = 0.4;
			$impuestoRentaRebaja =  1282036.67;
			$impuestoRenta = ($sueldoTributable * $impuestoRentaFactor) - $impuestoRentaRebaja;
		}	
	}