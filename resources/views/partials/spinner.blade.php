<div id="spinner-container" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-[1000] hidden">
    <div class="w-16 h-16 border-4 border-transparent border-l-blue-500 rounded-full animate-spin"></div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkAvailabilityButton = document.getElementById('check-availability-button');
        const checkInButton = document.getElementById('check-in-button');
        const spinnerContainer = document.getElementById('spinner-container');

        // Function to show spinner
        function showSpinner() {
            spinnerContainer.style.display = 'flex';
        }

        // Function to hide spinner
        function hideSpinner() {
            spinnerContainer.style.display = 'none';
        }

        // Modify existing fetchAvailableRooms to add spinner
        function fetchAvailableRooms(checkIn, checkOut, stayType) {
            showSpinner();
            fetch("{{ route('user-form.checkAvailability') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        check_in: checkIn,
                        check_out: checkOut,
                        stay_type: stayType
                    })
                })
                .then(response => response.json())
                .then(data => {
                    hideSpinner();
                    roomContainer.innerHTML = '';
                    if (data.rooms && data.rooms.length) {
                        roomContainer.classList.remove('hidden');
                        data.rooms.forEach(room => {
                            const roomDiv = document.createElement('div');
                            roomDiv.classList.add('room-item', 'flex', 'items-center');
                            roomDiv.innerHTML = `
                            <input type="checkbox" name="rooms[]" value="${room.id}" data-price="${room.price}" class="mr-2 room-checkbox">
                            <label class="text-gray-600 text-sm">${room.room_number} - ${room.room_type} - pax(${room.pax}) - ₱${room.price}</label>
                        `;
                            roomContainer.appendChild(roomDiv);
                        });

                        document.querySelectorAll('.room-checkbox').forEach(checkbox => {
                            checkbox.addEventListener('change', updateTotalAmount);
                        });
                    } else {
                        roomContainer.innerHTML =
                            '<p class="text-sm text-gray-600">No available rooms for the selected dates and stay type.</p>';
                        roomContainer.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    hideSpinner();
                    console.error('Error fetching available rooms:', error);
                    alert('An error occurred while checking room availability.');
                });
        }

        // Modify form submission to add spinner
        document.getElementById('check-in-form').addEventListener('submit', function(e) {
            showSpinner();
        });
    });
</script>
