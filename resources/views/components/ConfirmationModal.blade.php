<div id="confirmationModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
        <h3 class="text-lg font-semibold mb-4">Confirm Action</h3>
        <p id="confirmationMessage"></p>
        <div class="mt-4 flex justify-end">
            <button onclick="hideConfirmationModal()" class="mr-2 px-4 py-2 bg-gray-300 rounded">Cancel</button>
            <button id="confirmDeleteButton" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
        </div>
    </div>
</div>
