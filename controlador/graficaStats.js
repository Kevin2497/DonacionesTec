let grafica; 
let graficaLista = false;

document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('graficaStats').getContext('2d');

    grafica = new Chart(ctx, {
        type: 'line',
        data: {
            labels: window.mesesUsuarios || [],
            datasets: [{
                label: 'Usuarios registrados por mes',
                data: window.totalesUsuarios || [],
                fill: true,
                borderColor: 'blue',
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                tension: 0.3
            }]
        },
        options: {
            animation: {
                onComplete: () => {
                    graficaLista = true;
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});

document.getElementById("generarPDF").addEventListener("click", function (e) {
    e.preventDefault();

    if (!graficaLista) {
        alert("Espera a que la gráfica termine de cargarse.");
        return;
    }

    const canvas = document.getElementById("graficaStats");
    const imgData = canvas.toDataURL("image/png");

    const form = document.createElement("form");
    form.method = "POST";
    form.action = "modelo/generarPDF.php";

    const input = document.createElement("input");
    input.type = "hidden";
    input.name = "graficaBase64";
    input.value = imgData;
    form.appendChild(input);

    document.body.appendChild(form);
    form.submit();
});
