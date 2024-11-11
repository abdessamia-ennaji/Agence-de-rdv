<style>
.step-indicator {
    display: flex;
    /* justify-content: space-between; */
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
    margin: 0 -10px;
}

.line.active {
    background-color: #e57373; /* Active line color */
}

.step-item {
    position: relative;
    text-align: center;
    flex: 1;
    z-index: 2;
    width: 40px;
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


/* REVIEW CSS */
.review-container {
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #f9f9f9;
    }

    .review-title {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin-bottom: 16px;
        text-align: center;
    }

    .review-list {
        list-style-type: none;
        padding: 0;
        margin-bottom: 20px;
    }

    .review-list li {
        font-size: 16px;
        color: #555;
        padding: 8px 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .review-list li:last-child {
        border-bottom: none;
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
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


<!-- resources/views/rdv/review.blade.php -->
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





    <div class="review-container">
        <h2 class="review-title">Vérifiez les informations de votre rendez-vous</h2>
        <ul class="review-list">
            <li>Date de rendez-vous: {{ session('rdv_date') }}</li>
            <li>Heure de rendez-vous: {{ session('rdv_time') }}</li>
            <li>Prénom: {{ session('prenom') }}</li>
            <li>Nom: {{ session('nom') }}</li>
            <li>Téléphone: {{ session('telephone') }}</li>
            <li>Adresse: {{ session('adresse') }}</li>
            <li>Ville: {{ session('ville') }}</li>
            <li>Commentaire: {{ session('commentaire') }}</li>
        </ul>
        <form action="{{ route('rdv.store') }}" method="POST">
            @csrf
            <button type="submit" class="submit-button">Envoyer</button>
        </form>
    </div>
</x-app-layout>

