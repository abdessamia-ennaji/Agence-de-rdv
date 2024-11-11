<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-xl font-bold mb-4">Modifier Rendez-vous</h1>

       

        <form action="{{ route('rdv.update', $rdv->id) }}" method="POST" class="w-full max-w-lg mx-auto p-8 bg-white shadow-md rounded-lg">
            @csrf
            @method('PUT')
            
            <!-- Date -->
            <div class="mb-4">
                <label for="rdv_date" class="block text-gray-700 font-bold mb-2">Date de Rendez-vous</label>
                <input type="date" id="rdv_date" name="rdv_date" value="{{ $rdv->rdv_date }}" class="w-full px-4 py-2 border rounded-md">
            </div>

            <!-- Time -->
            <div class="mb-4">
                <label for="rdv_time" class="block text-gray-700 font-bold mb-2">Heure de Rendez-vous</label>
                <input type="time" id="rdv_time" name="rdv_time" value="{{ $rdv->rdv_time }}" class="w-full px-4 py-2 border rounded-md">
            </div>

            <!-- Other fields -->
            <div class="mb-4">
                <label for="prenom" class="block text-gray-700 font-bold mb-2">Prénom</label>
                <input type="text" id="prenom" name="prenom" value="{{ $rdv->prenom }}" class="w-full px-4 py-2 border rounded-md">
            </div>

            <div class="mb-4">
                <label for="nom" class="block text-gray-700 font-bold mb-2">Nom</label>
                <input type="text" id="nom" name="nom" value="{{ $rdv->nom }}" class="w-full px-4 py-2 border rounded-md">
            </div>

            <div class="mb-4">
                <label for="telephone" class="block text-gray-700 font-bold mb-2">Téléphone</label>
                <input type="text" id="telephone" name="telephone" value="{{ $rdv->telephone }}" class="w-full px-4 py-2 border rounded-md">
            </div>

            <div class="mb-4">
                <label for="adresse" class="block text-gray-700 font-bold mb-2">Adresse</label>
                <input type="text" id="adresse" name="adresse" value="{{ $rdv->adresse }}" class="w-full px-4 py-2 border rounded-md">
            </div>

            <div class="mb-4">
                <label for="ville" class="block text-gray-700 font-bold mb-2">Ville</label>
                <input type="text" id="ville" name="ville" value="{{ $rdv->ville }}" class="w-full px-4 py-2 border rounded-md">
            </div>

            <div class="mb-4">
                <label for="commentaire" class="block text-gray-700 font-bold mb-2">Commentaire</label>
                <textarea id="commentaire" name="commentaire" rows="4" class="w-full px-4 py-2 border rounded-md">{{ $rdv->commentaire }}</textarea>
            </div>

            <button type="submit" class="bg-black-500 text-black font-bold py-2 px-4 rounded-md">Mettre à jour</button>
        </form>
    </div>
</x-app-layout>


