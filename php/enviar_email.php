function seleccionarSala(salaId) {
    document.getElementById('sala_id').value = salaId;
    document.getElementById('formulario-reserva').style.display = 'block';
    document.getElementById('formulario-reserva').scrollIntoView({ 
        behavior: 'smooth' 
    });
}

function validarReserva() {
    // Obtener los valores del formulario
    const fecha = document.getElementById('fecha').value;
    const periodo = document.getElementById('periodo').value;
    const curso = document.getElementById('curso').value;
    const asignatura = document.getElementById('asignatura').value;
    const objetivo = document.getElementById('objetivo').value;

    // Validar que todos los campos estén llenos
    if (!fecha || !periodo || !curso || !asignatura || !objetivo) {
        alert('Por favor complete todos los campos');
        return false;
    }

    // Verificar disponibilidad mediante AJAX
    fetch('php/verificar_disponibilidad.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            sala_id: document.getElementById('sala_id').value,
            fecha: fecha,
            periodo: periodo
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.disponible) {
            // Si está disponible, enviar el formulario
            document.getElementById('form-reserva').submit();
        } else {
            alert('El horario seleccionado no está disponible');
        }
    });

    return false; // Prevenir envío del formulario hasta confirmar disponibilidad
}