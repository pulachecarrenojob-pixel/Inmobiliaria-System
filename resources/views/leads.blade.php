<!DOCTYPE html>
<html>

<head>
    <title>CRM Inmobiliario</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: white;
            padding: 30px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        form {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }

        .chart-box {
            width: 100%;
            max-width: 500px;
            margin: auto;
        }

        input,
        select {
            padding: 12px;
            border-radius: 10px;
            border: none;
            outline: none;
            background: #1e293b;
            color: white;
        }

        button {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        button:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px #22c55e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
        }

        th {
            background: #1e293b;
            padding: 12px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #334155;
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .badge {
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 12px;
        }

        .nuevo {
            background: #3b82f6;
        }

        .prioridad_alta {
            background: #ef4444;
        }

        .delete-btn {
            background: #ef4444;
            padding: 6px 10px;
        }

        .loading {
            text-align: center;
            margin-top: 20px;
            opacity: 0.7;
        }

        .charts-container {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .chart-box {
            background: rgba(255, 255, 255, 0.03);
            padding: 15px;
            border-radius: 12px;
            width: 48%;
            height: 300px;
            /* 🔥 tamaño controlado */
        }

        .chart-box canvas {
            width: 100% !important;
            height: 100% !important;
        }
    </style>
</head>

<body>

    <div class="container">

        <h1>🏡 CRM Inmobiliario</h1>

        <div class="card">
            <form id="form">
                <input type="text" id="nombre" placeholder="Nombre" required>
                <input type="email" id="email" placeholder="Email" required>
                <input type="text" id="telefono" placeholder="Teléfono" required>

                <select id="interes">
                    <option value="casa">Casa</option>
                    <option value="departamento">Departamento</option>
                </select>

                <button type="submit">Guardar</button>
            </form>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Interés</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla"></tbody>
            </table>

            <div class="loading" id="loading">Cargando datos...</div>
        </div>

    </div>
    <div class="card">
        <h2>📊 Dashboard</h2>

        <div class="charts-container">
            <div class="chart-box">
                <canvas id="estadoChart"></canvas>
            </div>

            <div class="chart-box">
                <canvas id="interesChart"></canvas>
            </div>
        </div>

        <div style="margin-top:20px;">
            <h3>Total Leads: <span id="totalLeads">0</span></h3>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const API = "http://127.0.0.1:8000/api/leads";
        const STATS = "http://127.0.0.1:8000/api/leads/stats";

        let estadoChart, interesChart;

        async function cargarStats() {
            const res = await fetch(STATS);
            const data = await res.json();

            document.getElementById("totalLeads").innerText = data.total;

            const estados = data.por_estado.map(e => e.estado);
            const estadoValores = data.por_estado.map(e => e.total);

            const intereses = data.por_interes.map(i => i.interes);
            const interesValores = data.por_interes.map(i => i.total);

            if (estadoChart) estadoChart.destroy();
            if (interesChart) interesChart.destroy();

            estadoChart = new Chart(document.getElementById("estadoChart"), {
                type: "bar",
                data: {
                    labels: estados,
                    datasets: [{
                        label: "Leads por Estado",
                        data: estadoValores
                    }]
                }
            });

            interesChart = new Chart(document.getElementById("interesChart"), {
                type: "pie",
                data: {
                    labels: intereses,
                    datasets: [{
                        data: interesValores
                    }]
                }
            });
            estadoChart = new Chart(document.getElementById("estadoChart"), {
                type: "bar",
                data: {
                    labels: estados,
                    datasets: [{
                        label: "Leads por Estado",
                        data: estadoValores
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false // 🔥 clave
                }
            });
            interesChart = new Chart(document.getElementById("interesChart"), {
                type: "pie",
                data: {
                    labels: intereses,
                    datasets: [{
                        data: interesValores
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        // CRUD
        document.getElementById("form").addEventListener("submit", async (e) => {
            e.preventDefault();

            const data = {
                nombre: nombre.value,
                email: email.value,
                telefono: telefono.value,
                interes: interes.value
            };

            await fetch(API, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            });

            form.reset();
            cargar();
        });

        async function cargar() {
            const res = await fetch(API);
            const data = await res.json();

            tabla.innerHTML = "";

            data.forEach(lead => {
                tabla.innerHTML += `
            <tr>
                <td>${lead.nombre}</td>
                <td>${lead.email}</td>
                <td>${lead.interes}</td>
                <td>${lead.estado}</td>
                <td>
                    <button onclick="eliminar(${lead.id})">❌</button>
                </td>
            </tr>
        `;
            });

            cargarStats();
        }

        async function eliminar(id) {
            await fetch(API + "/" + id, { method: "DELETE" });
            cargar();
        }

        // 🔥 AUTO REFRESH (TIEMPO REAL)
        setInterval(() => {
            cargar();
        }, 5000);


        cargar();
    </script>

</body>

</html>