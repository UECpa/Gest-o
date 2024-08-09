
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const propostaInput = document.querySelector('input[name="apolice"]');

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const proposta = propostaInput.value;

        // Verifica se a proposta já existe
        fetch('../PHP_ACTION/verificar_proposta.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'apolice': proposta
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                // Mostra o popup de confirmação
                if (confirm('Esta proposta já existe. Deseja continuar mesmo assim?')) {
                    form.submit();
                }
            } else {
                form.submit();
            }
        })
        .catch(error => console.error('Erro:', error));
    });
});


