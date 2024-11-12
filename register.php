<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro BabyBuddy</title>
    <style>
        .hidden { display: none; }
        .error-message { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Cadastro</h2>

    <!-- Exibir mensagem de erro se o e-mail já estiver cadastrado -->
    <?php
    session_start();
    if (isset($_SESSION['error_message'])) {
        echo '<p class="error-message">' . $_SESSION['error_message'] . '</p>';
        unset($_SESSION['error_message']); // Limpar a mensagem após exibir
    }
    ?>

    <form action="process_register.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        <label for="userType">Escolha o tipo de usuário:</label>
        <select id="userType" name="userType" onchange="toggleForm()" required>
            <option value="">Selecione...</option>
            <option value="baba">Babá</option>
            <option value="responsavel">Responsável</option>
        </select>
        
        <!-- Formulário da Babá -->
        <div id="babaForm" class="hidden">
            <h3>Cadastro de Babá</h3>
            <label for="babaName">Nome:</label>
            <input type="text" id="babaName" name="babaName" required><br>
            <label for="babaEmail">Email:</label>
            <input type="email" id="babaEmail" name="babaEmail" required><br>
            <label for="babaPassword">Senha:</label>
            <input type="password" id="babaPassword" name="babaPassword" required><br>
            <label for="babaPhoto">Foto:</label>
            <input type="file" id="babaPhoto" name="babaPhoto"><br>
            <label for="babaRate">Valor por hora:</label>
            <input type="number" id="babaRate" name="babaRate" step="0.01" required><br>
            <label for="babaExperience">Experiência:</label>
            <textarea id="babaExperience" name="babaExperience" required></textarea><br>
            <label for="location">Localização:</label>
            <input type="text" id="location" name="location" required><br>
            <button type="button" onclick="getLocation()">Obter Localização</button>
        </div>
        
        <!-- Formulário do Responsável -->
        <div id="responsavelForm" class="hidden">
            <h3>Cadastro de Responsável</h3>
            <label for="responsavelName">Nome:</label>
            <input type="text" id="responsavelName" name="responsavelName" required><br>
            <label for="responsavelEmail">Email:</label>
            <input type="email" id="responsavelEmail" name="responsavelEmail" required><br>
            <label for="responsavelPassword">Senha:</label>
            <input type="password" id="responsavelPassword" name="responsavelPassword" required><br>
            <label for="responsavelLocation">Localização:</label>
            <input type="text" id="responsavelLocation" name="responsavelLocation" required><br>
            <button type="button" onclick="getLocation()">Obter Localização</button>
        </div>
        
        <button type="submit">Cadastrar</button>
    </form>

    <script>
        function toggleForm() {
            const userType = document.getElementById('userType').value;
            const babaForm = document.getElementById('babaForm');
            const responsavelForm = document.getElementById('responsavelForm');

            // Oculta ambos os formulários
            babaForm.classList.add('hidden');
            responsavelForm.classList.add('hidden');

            // Habilita o formulário correspondente ao tipo de usuário
            if (userType === 'baba') {
                babaForm.classList.remove('hidden');
                // Adiciona required para campos da babá
                document.getElementById('babaName').setAttribute('required', 'required');
                document.getElementById('babaEmail').setAttribute('required', 'required');
                document.getElementById('babaPassword').setAttribute('required', 'required');
                document.getElementById('babaRate').setAttribute('required', 'required');
                document.getElementById('babaExperience').setAttribute('required', 'required');
                document.getElementById('location').setAttribute('required', 'required');
                
                // Remove required dos campos do responsável
                document.getElementById('responsavelName').removeAttribute('required');
                document.getElementById('responsavelEmail').removeAttribute('required');
                document.getElementById('responsavelPassword').removeAttribute('required');
                document.getElementById('responsavelLocation').removeAttribute('required');
            } else if (userType === 'responsavel') {
                responsavelForm.classList.remove('hidden');
                // Adiciona required para campos do responsável
                document.getElementById('responsavelName').setAttribute('required', 'required');
                document.getElementById('responsavelEmail').setAttribute('required', 'required');
                document.getElementById('responsavelPassword').setAttribute('required', 'required');
                document.getElementById('responsavelLocation').setAttribute('required', 'required');

                // Remove required dos campos da babá
                document.getElementById('babaName').removeAttribute('required');
                document.getElementById('babaEmail').removeAttribute('required');
                document.getElementById('babaPassword').removeAttribute('required');
                document.getElementById('babaRate').removeAttribute('required');
                document.getElementById('babaExperience').removeAttribute('required');
                document.getElementById('location').removeAttribute('required');
            }
        }

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    document.getElementById('location').value = `Lat: ${lat}, Lon: ${lon}`;
                    document.getElementById('responsavelLocation').value = `Lat: ${lat}, Lon: ${lon}`;
                }, (error) => {
                    alert('Não foi possível obter a localização: ' + error.message);
                });
            } else {
                alert('Geolocalização não é suportada por este navegador.');
            }
        }

        function validateForm() {
            return true; // A validação é feita por visibilidade dos campos
        }
    </script>
</body>
</html>
