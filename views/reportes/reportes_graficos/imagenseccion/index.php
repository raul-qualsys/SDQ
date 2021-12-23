<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" type="text/css" href="librerias/bootstrap4/bootstrap.min.css">

	<title>Capturar una seccion de  mi web</title>
</head>
<body style="background-color: gray">
	<div class="container">
		<br>
		<br>
		<div class="row" style="background-color: white">
			<div class="col-sm-6" style="background-color: #CEF6EC">
				<div id="graficoBarras"></div>
			</div>
			<div class="col-sm-6">
				<div id="graficoPastel"></div>
			</div>
		</div>
		<div class="row" style="background-color: #FBFBEF">
			<div class="col-sm-4">
				
				<button class="btn btn-success" id="btnimgGraficaBarras">
					Guardar img grafica barras
				</button>
				<button class="btn btn-primary" id="btnImgGraficaPastel">
					Guardar img grafica pastel
				</button>
			</div>
		</div>
	</div>

	<!-- Optional JavaScript -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="librerias/jquery-3.4.1.min.js"></script>
	<script src="librerias/bootstrap4/popper.min.js"></script>
	<script src="librerias/bootstrap4/bootstrap.min.js"></script>
	<script src="librerias/plotly-latest.min.js" charset="utf-8"></script>
	<script src="librerias/htmlToCanvas.js"></script>
	<script src="js/funciones.js"></script>


	<script type="text/javascript">
		$(document).ready(function(){
			$('#btnimgGraficaBarras').click(function(){
				tomarImagenPorSeccion('graficoBarras','graficoBarras');
			});

			$('#btnImgGraficaPastel').click(function(){
				tomarImagenPorSeccion('graficoPastel','graficoPastel');
			});
		});
	</script>


	<script type="text/javascript">
		
		var data = [{
			values: [19, 26, 55],
			labels: ['Residential', 'Non-Residential', 'Utility'],
			type: 'pie'
		}];

		var layout = {
			height: 400,
			width: 500
		};

		Plotly.newPlot('graficoPastel', data, layout);




		var trace1 = {
			  type: 'bar',
			  x: [1, 2, 3, 4],
			  y: [5, 10, 2, 8],
			  marker: {
			      color: '#C8A2C8',
			      line: {
			          width: 2.5
			      }
			  }
		};

		var data = [ trace1 ];

		var layout = { 
		  title: 'Responsive to window\'s size!',
		  font: {size: 18}
		};

		var config = {responsive: true}

		Plotly.newPlot('graficoBarras', data, layout, config );
	</script>
</body>
</html>