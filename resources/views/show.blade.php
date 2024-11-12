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





    /* ALERTE  */
    .modal {
            display: none; 
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            width: 300px;
        }

        .modal-icon {
            font-size: 40px;
            color: #ff0000;
            margin-bottom: 20px;
        }

        .modal-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .confirm-btn {
            background-color: #ff0000;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .cancel-btn {
            background-color: #ccc;
            color: black;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .cancel-btn:hover, .confirm-btn:hover {
            opacity: 0.8;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 20px;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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
                    <th>Status</th>
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






                            


                            <form action="{{ route('rdv.destroy', $rdv->id) }}" method="POST" class="delete-form" data-id="{{ $rdv->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="delete-btn" onclick="showModal({{ $rdv->id }})">
                                    <i class="fa fa-trash-alt"></i>
                                </button>
                            </form>


                            <!-- Modal -->
                            <div id="confirmModal" class="modal">
                                <div class="modal-content">
                                    <span class="close" onclick="closeModal()">&times;</span>
                                    <div class="modal-icon">
                                        <i class="fa fa-exclamation-circle"></i>
                                    </div>
                                    <h2>Are you sure you want to delete this item?</h2>
                                    <div class="modal-buttons">
                                        <button class="confirm-btn" onclick="confirmDelete()">Yes, Delete</button>
                                        <button class="cancel-btn" onclick="closeModal()">Cancel</button>
                                    </div>
                                </div>
                            </div>

                        </td>
                        <td>
                            <form action="{{ route('rdv.updateStatus', $rdv->id) }}" method="POST" id="statusForm{{ $rdv->id }}">
                                @csrf
                                @method('PUT')
                                <select name="status" id="statusSelect{{ $rdv->id }}">
                                    <!-- Show current status as the first option -->
                                    @foreach($rdvStatuses as $status)
                                        <option value="{{ $status->status }}" 
                                            {{ $rdv->status && $rdv->status->status == $status->status ? 'selected' : '' }} selected disabled>
                                            {{ $status->status }}
                                        </option>
                                        
                                    @endforeach
                                    

                                    <!-- Loop through the possible statuses and show them, excluding the current one -->
                                    @foreach(['Confirmé', 'En attente', 'Annulé'] as $statusOption)
                                        @if($rdv->status != $statusOption) <!-- Exclude the current status -->
                                            <option value="{{ $statusOption }}">
                                                {{ $statusOption }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
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




        // ////////////Confirmation for delete button
        let currentDeleteForm = null;

        function showModal(id) {
            // Store the current form
            currentDeleteForm = document.querySelector(`.delete-form[data-id="${id}"]`);
            document.getElementById("confirmModal").style.display = "flex";
        }

        function closeModal() {
            document.getElementById("confirmModal").style.display = "none";
            currentDeleteForm = null;
        }

        function confirmDelete() {
            if (currentDeleteForm) {
                currentDeleteForm.submit();
            }
            closeModal();
        }

        ///// Close modal when clicking outside
        window.onclick = function(event) {
            let modal = document.getElementById("confirmModal");
            if (event.target === modal) {
                closeModal();
            }
        }




        ///Select submit 

        
        document.querySelectorAll('[id^="statusSelect"]').forEach(select => {
    select.addEventListener('change', function() {
        const selectedStatus = this.value; // Get the selected status value
        const formId = 'statusForm' + this.id.replace('statusSelect', ''); // Get the corresponding form ID
        const form = document.getElementById(formId); // Get the form element
        
        // Create FormData object to send data via AJAX
        const formData = new FormData(form);
        formData.set('status', selectedStatus); // Add the selected status to the FormData
        
        // Make an AJAX request to update the status
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json()) // Assuming the server returns a JSON response
        .then(data => {
            if (data.success) {
                // Optionally handle success, for example updating the UI or showing a message
                alert('Status updated successfully');
            } else {
                // Handle error
                alert('Failed to update status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});

    </script>

</x-app-layout>
