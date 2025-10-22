<html>
<head>
	<title>Teste do site do WebSocket</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<h4>🌡️ Temperatura: <span id="temperature">0 °C</span></h4>
	<h4>💧 Umidade do Ar: <span id="humidity">0%</span></h4>
	<h4>🌱 Umidade do Solo: <span id="soil">0%</span></h4>
	<h4>💡 Iluminação: <span id="ldr">0%</span></h4>

	<hr>

	<!-- Modo Manual/Automático -->
	<h4>Modo de Operação</h4>
	<button onclick="setMode('manual')">Manual</button>
	<button onclick="setMode('auto')">Automático</button>

	<hr>

	<!-- Botões dos Relés -->
	<div id="reles">
		<h4>Relés</h4>
		<script>
			for (let i = 1; i <= 4; i++) {
				document.write(`<button id="rele${i}" onclick="toggleRele(${i})">Relé ${i} OFF</button><br><br>`);
			}
		</script>
	</div>

	<script>
		var socket = new WebSocket('ws://192.168.137.1:81');

		socket.onmessage = function(event) {
			console.log('Mensagem recebida: ' + event.data);
			const data = event.data.split(':');
			const msg = data[0] || "";
			const sensor = data[1] || "";

			if(sensor == "dht"){
				var parts = msg.split(",");
				document.getElementById("temperature").innerHTML = parts[0] + " °C";
				document.getElementById("humidity").innerHTML = parts[1] + "%";
			}
			else if(sensor == "soil"){
				document.getElementById("soil").innerHTML = msg + "%";
			}
			else if(sensor == "ldr"){
				document.getElementById("ldr").innerHTML = msg + "%";
			}
			else if(sensor.startsWith("rele")) {
				let index = sensor.replace("rele", "");
				let button = document.getElementById("rele" + index);
				button.innerHTML = `Relé ${index} ${msg == "1" ? "ON" : "OFF"}`;
			}
		}; 

		// Função para enviar comando de relé (modo manual)
		function toggleRele(id) {
			var button = document.getElementById("rele" + id);
			var status = button.innerHTML.includes("OFF") ? "1" : "0";
			socket.send(status + `:rele${id}:esp:localhost`);
		}

		// Função para trocar de modo
		function setMode(mode) {
			socket.send("mode:" + mode);
			alert("Modo alterado para " + mode.toUpperCase());
		}
	</script>
</body>
</html>
<?php
	echo "🌍 Interface WebSocket - Projeto IoT @gabc.lima";
?>
