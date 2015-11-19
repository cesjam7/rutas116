<?php
 include('simple_html_dom.php');

 $html = file_get_html('http://www.bomberosperu.gob.pe/po_diario.asp');

 $contador = 0;
 $cron = array();
 $emergencia = array();
echo 'aaaaaaa';
 foreach($html->find('tr') as $div) {
 	$contador++;
    echo 'bbbbbb';
 	if($contador>10):
echo 'cccccc';
        $emergencia['tipo'] = str_replace("&nbsp;","",$div->find('td.lineaizq', 4)->plaintext);
        $emergencia['direccion'] = str_replace("&nbsp;","",$div->find('td.lineaizq', 3)->plaintext);
	 	$mapa = $div->find('img', 0);
	 	$valores_map = str_replace("javascript:mapa('","", $mapa->onclick);
 		$valor_map = explode("','", $valores_map);
        $emergencia['lat'] = str_replace("&nbsp;","",$valor_map[0]);
 		$emergencia['lng'] = str_replace("&nbsp;","",$valor_map[1]);

        array_push($cron, $emergencia);

 	endif;

 }
echo 'dddddd';
print_r(json_encode($cron));

    // clean up memory
 	$html->clear();
 	unset($html);

 ?>
