<style>

.container {
        max-width: 600px;
        display: flex;
        justify-content: center;
        width: 100%;
        align-items: center;
        margin: 0 auto;
    }

    .calendar, .time {
        flex-basis: 48%;
        padding: 20px;
        /* margin: 20px; */
    }



    @media (max-width: 768px) {
        .container {
            flex-direction: column;
        }

        .calendar, .time {
            flex-basis: auto;
            margin: 10px 0;
        }

        .time-scroll-container {
            height: 150px;
        }

        .time-slot {
            font-size: 14px;
        }
    }

    .time-scroll-container {
        display: flex;
        flex-direction: column;
        height: 300px;
        overflow-y: auto;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 8px;
    }

    .time-slot {
        display: block;
        width: 100%;
        padding: 10px;
        margin: 4px 0;
        border: 1px solid #f28b82;
        border-radius: 5px;
        background-color: white;
        color: #f28b82;
        text-align: center;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s, color 0.3s;
    }

    .time-slot:hover {
        background-color: #f28b82;
        color: white;
    }

    .time-slot.selected {
        background-color: #f28b82;
        color: white;
    }




    /*======================================*/
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


</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Flatpickr scripts -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>



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





    <form action="{{ route('rdv.storeStep1') }}" method="POST">
        @csrf
        <div class="container">
            <div class="calendar">
                <label for="rdv_date" class="block text-gray-700 font-bold mb-2">Date de Rendez-vous</label>
                <input type="text" id="rdv_date" name="rdv_date" class="flatpickr w-full px-4 py-2 border rounded-md" style="display: none;"/>
            </div>
               
            <div class="time">
                <label for="rdv_time_display" class="block text-gray-700 font-bold mb-2">Heure de Rendez-vous</label>
                <div id="rdv_time_display" class="time-scroll-container">
                    <!-- Morning time slots -->
                    <button type="button" class="time-slot">09:00</button>
                    <button type="button" class="time-slot">09:30</button>
                    <button type="button" class="time-slot">10:00</button>
                    <button type="button" class="time-slot">10:30</button>
                    <button type="button" class="time-slot">11:00</button>
                    <button type="button" class="time-slot">11:30</button>
                    <button type="button" class="time-slot">12:00</button>
                    <button type="button" class="time-slot">12:30</button>
                    <button type="button" class="time-slot">13:00</button>
            
                    
                    <!-- Afternoon time slots -->
                    <button type="button" class="time-slot">15:00</button>
                    <button type="button" class="time-slot">15:30</button>
                    <button type="button" class="time-slot">16:00</button>
                    <button type="button" class="time-slot">16:30</button>
                    <button type="button" class="time-slot">17:00</button>
                    <button type="button" class="time-slot">17:30</button>
                    <button type="button" class="time-slot">18:00</button>
                </div>
        </div>
        
            
            <!-- Hidden input field to store the selected time -->
            <input type="hidden" id="rdv_time" name="rdv_time">
        </div>
        
                
        <button type="submit" class="bg-blue-500 text-black font-bold py-2 px-4 rounded-md">Suivant</button>
    </form>




    <script>
        // Initialize Flatpickr on the div and set it to show inline
        flatpickr("#rdv_date", {
            inline: true,           // Display calendar inline without clicking
            dateFormat: "Y-m-d",    // Set date format to YYYY-MM-DD
            defaultDate: "today"    // Optional: set default date to today
        });
    
    
    
        
        // for time selecting 
        const timeSlots = document.querySelectorAll('.time-slot');
        const rdvTimeInput = document.getElementById('rdv_time');
    
        timeSlots.forEach(slot => {
            slot.addEventListener('click', () => {
                // Remove 'selected' class from all slots
                timeSlots.forEach(s => s.classList.remove('selected'));
                
                // Add 'selected' class to clicked slot
                slot.classList.add('selected');
                
                // Set the value of the hidden input to the selected time
                rdvTimeInput.value = slot.textContent;
            });
        });
    </script>
</x-app-layout>
    