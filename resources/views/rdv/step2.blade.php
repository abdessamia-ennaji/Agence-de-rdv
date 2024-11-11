<style>
.step-indicator {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 20px 0;
    position: relative;
    padding: 10px 0;
}

.line {
    flex: 1;
    height: 2px;
    background-color: #e0e0e0; /* Inactive line color */
    transition: background-color 0.3s ease;
}

.line.active {
    background-color: #e57373; /* Active line color */
}

.step-item {
    position: relative;
    text-align: center;
    flex: 1;
    z-index: 2;
}

.step-item .step-icon {
    width: 40px;
    height: 40px;
    border: 2px solid #e57373;
    background-color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #e57373;
    margin: 0 auto 8px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.step-item.active .step-icon {
    background-color: #e57373;
    color: #fff;
}

.step-label {
    color: #e57373;
    font-weight: bold;
    font-size: 12px;
}



/* FORM DESIGN */

.form-container {
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #f9f9f9;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-label {
        display: block;
        font-weight: bold;
        color: #333;
        margin-bottom: 6px;
    }

    .form-input, .form-textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    .form-input:focus, .form-textarea:focus {
        border-color: #4A90E2;
        outline: none;
        box-shadow: 0 0 5px rgba(74, 144, 226, 0.2);
    }

    .submit-button {
        display: inline-block;
        width: 100%;
        padding: 12px;
        font-size: 16px;
        font-weight: bold;
        color: #fff;
        background-color: #e57373;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .submit-button:hover {
        background-color: #b45555;
    }




    /* ERROR MESSAGES */
    .error-message {
        color: red;
        font-size: 12px;
        margin-top: 5px;
    }

</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- resources/views/rdv/step2.blade.php -->
<x-app-layout>
    <div class="step-indicator">
        <div class="step-item {{ request()->is('rdv/create/step1') ? 'active' : '' }}">
            <div class="step-icon"><i class="fas fa-user"></i></div>
            <div class="step-label">Personal details</div>
        </div>
        <div class="line {{ request()->is('rdv/create/step2') || request()->is('rdv/create/review') ? 'active' : '' }}"></div>
        
        <div class="step-item {{ request()->is('rdv/create/step2') ? 'active' : '' }}">
            <div class="step-icon"><i class="fas fa-cog"></i></div>
            <div class="step-label">Additional Info</div>
        </div>
        <div class="line {{ request()->is('rdv/create/review') ? 'active' : '' }}"></div>
        
        <div class="step-item {{ request()->is('rdv/create/review') ? 'active' : '' }}">
            <div class="step-icon"><i class="fas fa-check"></i></div>
            <div class="step-label">Last step</div>
        </div>
    </div>

    <div class="form-container">
        <form action="{{ route('rdv.storeStep2') }}" method="POST" id="rdvForm">
            @csrf
            <div class="form-group">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" id="prenom" name="prenom" class="form-input">
                <div id="prenomError" class="error-message" style="display:none;">*Prénom is required.</div>
            </div>
            <div class="form-group">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" id="nom" name="nom" class="form-input">
                <div id="nomError" class="error-message" style="display:none;">*Nom is required.</div>
            </div>
            <div class="form-group">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" id="telephone" name="telephone" class="form-input">
                <div id="telephoneError" class="error-message" style="display:none;">*Téléphone is required.</div>
            </div>
            <div class="form-group">
                <label for="adresse" class="form-label">Adresse</label>
                <input type="text" id="adresse" name="adresse" class="form-input">
                <div id="adresseError" class="error-message" style="display:none;">*Adresse is required.</div>
            </div>
            <div class="form-group">
                <label for="ville" class="form-label">Ville</label>
                <input type="text" id="ville" name="ville" class="form-input">
                <div id="villeError" class="error-message" style="display:none;">*Ville is required.</div>
            </div>
            <div class="form-group">
                <label for="commentaire" class="form-label">Commentaire</label>
                <textarea id="commentaire" name="commentaire" rows="4" class="form-textarea"></textarea>
                <div id="commentaireError" class="error-message" style="display:none;">*Commentaire is required.</div>
            </div>
            <button type="submit" class="submit-button" id="submitButton">Suivant</button>
        </form>
    </div>

    <script>
        document.getElementById('rdvForm').addEventListener('submit', function(event) {
            let isValid = true;

            // Hide all error messages
            const errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach(message => {
                message.style.display = 'none';
            });

            // Check each input
            const fields = ['prenom', 'nom', 'telephone', 'adresse', 'ville', 'commentaire'];
            fields.forEach(field => {
                const input = document.getElementById(field);
                const errorMessage = document.getElementById(field + 'Error');
                
                if (input.value.trim() === '') {
                    errorMessage.style.display = 'block';
                    isValid = false;
                }
            });

            // If not valid, prevent form submission
            if (!isValid) {
                event.preventDefault();
            }
        });
    </script>
</x-app-layout>