<!DOCTYPE html>
<html>
<head>
    <title>CRM Leads</title>
    <style>
        body {
            font-family: Arial;
            background: #0f172a;
            color: white;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        input, select {
            padding: 10px;
            border-radius: 8px;
            border: none;
        }

        button {
            background: #22c55e;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
        }

        table {
            width: 100%;
            background: #1e293b;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        tr:hover {
            background: #334155;
        }
    </style>
</head>

<body>

<h1>🚀 CRM Inmobiliario</h1>

<form id="form">
    <input type="text" id="nombre" placeholder="Nombre">
    <input type="email" id="email" placeholder="Email">
    <input type="text" id="telefono" placeholder="Teléfono">
    <select id="interes">
        <option value="casa">Casa</option>
        <option value="departamento">Departamento</option>
    </select>
    <button type="submit">Guardar</button>
</form>

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

<script>
const API = "http://127.0.0.1:8000/api/leads";

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