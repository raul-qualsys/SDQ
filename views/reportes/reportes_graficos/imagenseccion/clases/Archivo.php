<?php 

class Archivo {
	public function subeimagen64temp($img, $nombre) {

		$carpetaDestino = "../archivos/";
       if(!file_exists($carpetaDestino))
        mkdir($carpetaDestino);
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		$file = $carpetaDestino . $nombre . '.png';
		$success = file_put_contents($file, $data);
		return $success;
	}
}

?>