<style>
    .container {
        display: flex;
        justify-content: space-around;
        align-items: center;
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .container > div {
        text-align: center;
        flex: 1;
    }

    .container a {
        text-decoration: none;
        color: #333;
    }

    .container h1 {
        font-size: 1.5rem;
        color: #007bff;
        transition: color 0.3s ease, transform 0.3s ease;
        padding: 20px;
        border-radius: 8px;
    }

    .container h1:hover {
        color: #0056b3;
        transform: scale(1.05);
    }

    /* Responsive styling */
    @media (max-width: 768px) {
        .container {
            flex-direction: column;
            padding: 10px;
        }

        .container h1 {
            font-size: 1.2rem;
            padding: 15px;
        }
    }
</style>

<x-app-layout>

    <div class="container">
        <div class="">
            <a href="rdv/create/step1">
    
                <h1>Remplir un RDV</h1>
            </a>
        </div>
    
        <div>
            <a href="rdv">
                <h1>Show All RDV</h1>
            </a>
        </div>
    </div>
    

</x-app-layout>
    
