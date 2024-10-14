// public/js/confirmation.js

function showConfirmationModal(message, formId) {
    // Set the confirmation message
    document.getElementById('confirmationMessage').innerText = message;

    // Show the modal
    const modal = document.getElementById('confirmationModal');
    modal.classList.remove('hidden');

    // Set the confirm button action
    const confirmButton = document.getElementById('confirmDeleteButton');
    confirmButton.onclick = function () {
        document.getElementById(formId).submit();
    };
}

function hideConfirmationModal() {
    // Hide the modal
    const modal = document.getElementById('confirmationModal');
    modal.classList.add('hidden');
}
