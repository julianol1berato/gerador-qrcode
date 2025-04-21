document.addEventListener('DOMContentLoaded', function() {
    // Seleciona todos os formulários em todas as abas
    const forms = document.querySelectorAll('form');
    
    // Adiciona um listener para o evento submit em cada formulário
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            generateQRCode(this);
        });
    });
    
    /**
     * Gera o QR code enviando os dados do formulário via AJAX
     * @param {HTMLFormElement} form - O formulário com os dados
     */
    function generateQRCode(form) {
        // Exibe mensagem de carregamento
        document.getElementById('qrResult').innerHTML = '<p>Gerando QR Code...</p>';
        
        // Cria objeto FormData com os dados do formulário
        const formData = new FormData(form);
        
        // Envia a requisição AJAX
        fetch('gerar-qrcode.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                // Se houver erro, exibe mensagem de erro
                document.getElementById('qrResult').innerHTML = `
                    <div class="alert alert-danger">
                        ${data.error}
                    </div>
                `;
            } else if (data.success) {
                // Se for bem-sucedido, exibe o QR code
                document.getElementById('qrResult').innerHTML = `
                    <div class="mb-3">
                        <img src="${data.data}" alt="QR Code" class="img-fluid">
                    </div>
                    <div class="mb-3">
                        <a href="${data.file}" download class="btn btn-success">
                            Baixar QR Code
                        </a>
                        <a href="${data.data}" target="_blank" class="btn btn-info ms-2">
                            Abrir QR Code
                        </a>
                    </div>
                `;
            }
        })
        .catch(error => {
            // Em caso de erro na requisição
            document.getElementById('qrResult').innerHTML = `
                <div class="alert alert-danger">
                    Erro ao processar a solicitação: ${error.message}
                </div>
            `;
        });
    }
});
