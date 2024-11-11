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
                    border-radius: 5px;
                    text-align: center;
                    font-size: 16px;
                    cursor: pointer;
                    transition: all 0.3s;
                }


                .time-slot.available {
            border: 1px solid #4CAF50;
            background-color: white;
            color: #4CAF50;
            cursor: pointer;
        }

        .time-slot.available:hover {
            background-color: #4CAF50;
            color: white;
        }

        .time-slot.booked {
            border: 1px solid #f44336;
            background-color: #ffebee;
            color: #f44336;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .time-slot.selected {
            background-color: #4CAF50;
            color: white;
        }





            /*===========================================*/
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
        <!-- Step Indicator Code Here -->
    </div>

    <form action="{{ route('rdv.storeStep1') }}" method="POST" id="appointmentForm">
        @csrf
        <div class="container">
            <div class="calendar">
                <label for="rdv_date" class="block text-gray-700 font-bold mb-2">Date de Rendez-vous</label>
                <input type="text" id="rdv_date" name="rdv_date" class="flatpickr w-full px-4 py-2 border rounded-md" style="display: none;" required/>
            </div>
            <div class="time">
                <label for="rdv_time_display" class="block text-gray-700 font-bold mb-2">Heure de Rendez-vous</label>
                <div id="rdv_time_display" class="time-scroll-container">
                    <!-- Time slots will be populated dynamically by JavaScript -->
                </div>
            </div>
            <input type="hidden" id="rdv_time" name="rdv_time" required>
        </div>

        <div class="text-center mt-4">
            <button type="submit" 
                    class="bg-blue-500 text-black font-bold py-2 px-4 rounded-md disabled:opacity-50 disabled:cursor-not-allowed" 
                    id="submitBtn" 
                    disabled>
                Suivant
            </button>
        </div>
    </form>

    <script>
        // Initialize data from PHP
        const bookedSlots = @json($bookedSlots); // Format: { "2024-11-11": ["09:00:00", "10:00:00", ...], "2024-11-12": [...] }
        const timeSlots = ["09:00", "09:30", "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", "13:00", "15:00", "15:30", "16:00", "16:30", "17:00", "17:30", "18:00"];
        
        let selectedDate = null;
    
        // Initialize Flatpickr for date selection
        const fp = flatpickr("#rdv_date", {
            inline: true,
            dateFormat: "Y-m-d",
            minDate: "{{ $minDate }}",
            maxDate: "{{ $maxDate }}",
            disable: [
                function(date) {
                    const dateStr = flatpickr.formatDate(date, "Y-m-d");
                    const bookedTimesForDate = bookedSlots[dateStr] || [];
                    // Disable the date if all time slots are booked or if it's Sunday
                    return (bookedTimesForDate.length === timeSlots.length) || (date.getDay() === 0); // 0 represents Sunday
                }
            ],
            onChange: function(selectedDates, dateStr) {
                selectedDate = dateStr;
                updateTimeSlots(dateStr);
                // Clear any previously selected time
                document.getElementById('rdv_time').value = '';
                document.getElementById('submitBtn').disabled = true;
                console.log('Selected Date:', dateStr);
            }
        });
    
        // Function to update time slots based on the selected date
        function updateTimeSlots(date) {
            const container = document.getElementById('rdv_time_display');
            container.innerHTML = ''; // Clear existing slots
    
            const bookedTimesForDate = bookedSlots[date] || []; // Default to empty array if no booked times
    
            console.log('Available Time Slots for ' + date + ':');
    
            // Check if it's Sunday and disable all time slots
            const isSunday = new Date(date).getDay() === 0; // 0 represents Sunday
            if (isSunday) {
                // Disable all time slots if it's Sunday
                timeSlots.forEach(time => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.textContent = time;
                    button.className = 'time-slot unavailable';
                    button.disabled = true; // Disable the button
                    container.appendChild(button);
                    console.log(time + ' is unavailable on Sunday');
                });
                return; // Exit the function early
            }
    
            // Check if it's Saturday and disable specific time slots
            const isSaturday = new Date(date).getDay() === 6; // 6 represents Saturday
            const blockedSaturdaySlots = ["15:00", "15:30", "16:00", "16:30", "17:00", "17:30", "18:00"];
            
            // Otherwise, show the available time slots
            timeSlots.forEach(time => {
                const button = document.createElement('button');
                button.type = 'button';
                button.textContent = time;
    
                // Adjust time format for comparison (ensure both are in the same format)
                const timeToCompare = time + ":00"; // Adding seconds to match the format in database, e.g., "09:00:00"
                
                // Check if time is booked or blocked for Saturday
                if (bookedTimesForDate.includes(timeToCompare) || (isSaturday && blockedSaturdaySlots.includes(time))) {
                    // Mark as booked or unavailable and disable selection
                    button.className = 'time-slot booked';
                    button.disabled = true; // Disable the button
                } else {
                    // Mark as available and add click event
                    button.className = 'time-slot available';
                    button.addEventListener('click', () => selectTimeSlot(button, time));
                }
    
                container.appendChild(button);
    
                // Log the available times
                if (!bookedTimesForDate.includes(timeToCompare) && !(isSaturday && blockedSaturdaySlots.includes(time))) {
                    console.log(time + ' is available');
                } else {
                    console.log(time + ' is booked or unavailable on Saturday');
                }
            });
        }
    
        // Function to handle time slot selection
        function selectTimeSlot(button, time) {
            // Remove selection from all slots
            document.querySelectorAll('.time-slot').forEach(slot => {
                slot.classList.remove('selected');
            });
    
            // Add selection to clicked slot
            button.classList.add('selected');
    
            // Update hidden input with selected time
            document.getElementById('rdv_time').value = time;
    
            // Enable submit button
            document.getElementById('submitBtn').disabled = false;
        }
    </script>
    
    
    
</x-app-layout>

    