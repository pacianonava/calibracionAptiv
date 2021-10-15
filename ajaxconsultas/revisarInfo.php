<?php

include("../datos.php");
include("../funciones.php");
$mysqli = conectarBase($host,$usuario,$clave,$base);


if($mysqli){
	if(!empty($_POST["SN"])) {
		$consulta = "SELECT * FROM `calibration_stress`.`calib-master_calibration` WHERE SN='" . $_POST["SN"] . "'";
		$datos = $mysqli->query($consulta);
		$row=$datos->fetch_assoc();
		$user_count = $datos->num_rows;
		if($user_count>0) {
		  //echo "<div class='invalid-feedback'> Usuario no Disponible.</div>";
			echo $row["ID_MC"];
		}else{
		  //echo "<div class='valid-feedback'> Usuario Disponible.</div>";
		  echo 'true';
		}
	}

	if(!empty($_POST["Coord"])) {
		$DEP = 0;
		$consulta = "SELECT * FROM `calibration_stress`.`calib-coordinadores` WHERE COORD='" . $_POST["Coord"] . "'";
		$datos = $mysqli->query($consulta);
		while ($row=$datos->fetch_assoc()){
			$DEP = $row["DEP"];
		}

		echo $DEP;
	}
	if(!empty($_POST["codigoP"])) {
	  $consulta = "SELECT * FROM `calibration_stress`.`admin-numerorandom` WHERE NumeroR='" . $_POST["codigoP"] . "'";
	  $datos = $mysqli->query($consulta);
	  $user_count = $datos->num_rows;
	  if($user_count>0) {
		  //echo "<div class='invalid-feedback'> Usuario no Disponible.</div>";
		  echo 'false';
	  }else{
		  //echo "<div class='valid-feedback'> Usuario Disponible.</div>";
		  echo 'true';
	  }
	}
	
	if(!empty($_POST["calibTipo"])) {
		$dato = '';
		$consulta = "SELECT * FROM `calibration_stress`.`calib-listprocedure` WHERE calibTipo='".$_POST["calibTipo"]."'";
		$datos = $mysqli->query($consulta);
		while ($row=$datos->fetch_assoc()){
			$nombreProcedure = $row["nombreProcedure"];
			$nombreDocumento = $row["nombreDocumento"];
			$dato .= '<option value="'.$nombreProcedure.'">'.$nombreDocumento.'</option>';
		}

		echo $dato;
	}
	
	if(!empty($_POST["listaequipos"])) {
		$dato = '';
		$consulta = "SELECT ID_MC, Status, maxExpDate, SN FROM `calibration_stress`.`calib-master_calibration` INNER JOIN (SELECT MAX(duedate)maxExpDate, `fk_idMC` FROM `calib-calibhist` GROUP BY fk_idMC) AS DateCalib ON DateCalib.fk_idMC=ID_MC WHERE ID_MC LIKE '%".$_POST["listaequipos"]."%' OR `SN` LIKE '%".$_POST["listaequipos"]."%'  ORDER BY Status, ID_MC;";
		//$consulta = "SELECT * FROM `calibration_stress`.`calib-listprocedure` WHERE calibTipo='".$_POST["listaequipos"]."'";
		$datos = $mysqli->query($consulta);
		while ($row=$datos->fetch_assoc()){
			$ID_MC = $row["ID_MC"];
			$dato .= '<option value="'.$ID_MC.'">'.utf8_encode($row["SN"]).' '.utf8_encode($row["Status"]).(($row["Status"]!='BAJA')? ' Vence:'.date("d-M-Y", strtotime($row["maxExpDate"])).'':'').'</option>';
		}

		echo $dato;
	}

}

?>