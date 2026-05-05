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
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        form {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }

        input, select {
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
            background: rgba(255,255,255,0.05);
        }

        .badge {
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 12px;
        }

        .nuevo { background: #3b82f6; }
        .prioridad_alta { background: #ef4444; }

        .delete-btn {
            background: #ef4444;
            padding: 6px 10px;
        }

        .loading {
            text-align: center;
            margin-top: 20px;
            opacity: 0.7;
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

<script>
const API = "http://127.0.0.1:8000/api/leads";

const loading = document.getElementById("loading");

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
    loading.style.display = "block";

    const res = await fetch(API);
    const data = await res.json();

    tabla.innerHTML = "";

    data.forEach(lead => {
        tabla.innerHTML += `
            <tr>
                <td>${lead.nombre}</td>
                <td>${lead.email}</td>
                <td>${lead.interes}</td>
                <td>
                    <span class="badge ${lead.estado}">
                        ${lead.estado}
                    </span>
                </td>
                <td>
                    <button class="delete-btn" onclick="eliminar(${lead.id})">Eliminar</button>
                </td>
            </tr>
        `;
    });

    loading.style.display = "none";
}

async function eliminar(id) {
    await fetch(API + "/" + id, {
        method: "DELETE"
    });
    cargar();
}

cargar();
</script>

</body>
</html>