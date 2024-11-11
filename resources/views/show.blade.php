<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    /* Reset default margin and padding */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Main container */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px;
    font-family: 'Arial', sans-serif;
}

/* Title */
.title {
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: #333;
}

/* Search Box */
.search-box {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.search-input {
    padding: 12px 20px;
    border: 1px solid #ccc;
    border-radius: 25px;
    font-size: 1rem;
    width: 100%;
    max-width: 300px;
}

.filter-btn {
    background-color: transparent;
    border: none;
    cursor: pointer;
    margin-left: 10px;
    font-size: 1.2rem;
    color: #007bff;
}

.results-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.results-table th, .results-table td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd;
}

.results-table th {
    background-color: #f4f4f4;
    font-weight: bold;
}

.results-table tr:hover {
    background-color: #f9f9f9;
}

.actions {
    display: flex;
    gap: 10px;
}

.edit-btn {
    color: #007bff;
    text-decoration: none;
}

.delete-btn {
    background-color: transparent;
    border: none;
    color: #e74c3c;
    cursor: pointer;
}

.delete-form {
    display: inline;
}



</style>
<x-app-layout>
    <div class="container">
        <h1 class="title">Liste des Rendez-vous</h1>
    
        <!-- Search Box with Filter Icon -->
        <div class="search-box">
            <input type="text" id="search" placeholder="Rechercher par prénom ou nom" value="{{ old('search', $search) }}" class="search-input" />
            <button class="filter-btn">
                <i class="fa fa-filter"></i>
            </button>
        </div>
    
        <!-- Table of Results -->
        <table class="results-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="rdv-list">
                @foreach ($rdvs as $rdv)
                    <tr>
                        <td>{{ $rdv->id }}</td>
                        <td>{{ $rdv->prenom }}</td>
                        <td>{{ $rdv->nom }}</td>
                        <td>{{ $rdv->rdv_date }}</td>
                        <td>{{ $rdv->rdv_time }}</td>
                        <td class="actions">
                            <a href="{{ route('edit', $rdv->id) }}" class="edit-btn">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('rdv.destroy', $rdv->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">
                                    <i class="fa fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    
        <!-- Pagination Controls -->
        <div id="pagination-links" class="pagination">
            {{ $rdvs->appends(['search' => $search])->links() }}
        </div>
    </div>
    

    <!-- JavaScript for Dynamic Filtering -->
    <script>
        document.getElementById('search').addEventListener('input', function() {
            let searchQuery = this.value.trim();
            let url = new URL(window.location.href);  // Get current URL

            // Update search query in the URL (without reloading the page)
            if (searchQuery) {
                url.searchParams.set('search', searchQuery);
            } else {
                url.searchParams.delete('search');
            }

            // Get the current page from pagination (if exists)
            let page = url.searchParams.get('page'); // Do not override page number automatically

            // If no page parameter is found, set it to 1
            if (!page) {
                url.searchParams.set('page', '1');
            }

            // Make AJAX request with the updated search query
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    // Update the table and pagination dynamically
                    let tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;

                    // Replace the table and pagination content
                    document.getElementById('rdv-list').innerHTML = tempDiv.querySelector('#rdv-list').innerHTML;
                    document.getElementById('pagination-links').innerHTML = tempDiv.querySelector('#pagination-links').innerHTML;
                });
        });
    </script>

</x-app-layout>
