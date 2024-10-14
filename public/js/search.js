document.addEventListener('DOMContentLoaded', function() {
    function initializeSearch(formId, tableBodyId, routeUrl) {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(form);
                fetch(routeUrl + '?' + new URLSearchParams(formData))
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const newTableBody = parser.parseFromString(html, 'text/html')
                            .querySelector(`#${tableBodyId}`).innerHTML;
                        document.getElementById(tableBodyId).innerHTML = newTableBody;
                    })
                    .catch(error => console.error('Error:', error));
            });
        }
    }

    window.initializeSearch = initializeSearch;
});
