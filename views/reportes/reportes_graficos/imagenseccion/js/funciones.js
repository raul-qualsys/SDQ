function tomarImagenPorSeccion(div,nombre) {

	html2canvas(document.querySelector("#" + div)).then(canvas => {
		var img = canvas.toDataURL();
		console.log(img);
		base = "img=" + img + "&nombre=" + nombre;

		$.ajax({
			type:"POST",
			url:"imagenseccion/procesos/crearImagenes.php",
			data:base
			// success:function(respuesta) {	
			// 	respuesta = parseInt(respuesta);
			// 	if (respuesta > 0) {
			// 		alert("Imagen creada con exito!");
			// 	} else {
			// 		alert("No se pudo crear la imagen :(");
			// 	}
			// }
		});
	});	
}