document.addEventListener('DOMContentLoaded', function () {
    console.log("JavaScript loaded and ready");

    const dbDropdown = document.getElementById('selected_db');
    const parentCategoryDropdown = document.getElementById('parent_category');
    const categoryNameInput = document.getElementById('category_name');
    const form = document.getElementById('add-category-form');

    // Ensure all elements are available before proceeding
    if (!dbDropdown || !parentCategoryDropdown || !form) {
        console.error('One or more required elements are missing.');
        return;
    }

    // Fetch databases when the page loads
    fetchDatabases();

    // Fetch databases dynamically
    function fetchDatabases() {
        console.log("Attempting to fetch databases...");
        fetch('../forms/get_databases.php')
            .then(response => response.json())
            .then(data => {
                console.log("Databases fetched:", data); // Debug: Show fetched data
                if (data.status === 'success') {
                    populateDatabaseDropdown(data.databases);
                } else {
                    console.error('Error fetching databases:', data.message);
                }
            })
            .catch(error => console.error('Error fetching databases:', error));
    }

    // Populate database dropdown
    function populateDatabaseDropdown(databases) {
        console.log("Populating database dropdown...");
        dbDropdown.innerHTML = '<option value="">-- Select Database --</option>';
        databases.forEach(db => {
            const option = document.createElement('option');
            option.value = db;
            option.textContent = db;
            dbDropdown.appendChild(option);
        });
    }

    // Fetch categories dynamically based on the selected database
    dbDropdown.addEventListener('change', function () {
        const selectedDb = dbDropdown.value;
        if (selectedDb) {
            console.log("Database selected:", selectedDb);
            fetchCategories(selectedDb);
        } else {
            console.log("No database selected, clearing categories");
            parentCategoryDropdown.innerHTML = ''; // Clear the dropdown if no database is selected
        }
    });

    // Fetch categories dynamically
    function fetchCategories(selectedDb) {
        console.log("Attempting to fetch categories for database:", selectedDb);
        fetch('test_get_categories.php?selected_db=' + encodeURIComponent(selectedDb))
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok: " + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                console.log("Categories fetched:", data); // Debug: Show fetched categories
                if (data.status === 'success') {
                    populateCategoriesDropdown(data.categories);
                } else {
                    console.error('Error fetching categories:', data.message);
                }
            })
            .catch(error => console.error('Error fetching categories:', error));
    }

    // Populate categories dropdown
    function populateCategoriesDropdown(categories) {
        console.log("Populating categories dropdown...");
        parentCategoryDropdown.innerHTML = ''; // Clear previous options
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            parentCategoryDropdown.appendChild(option);
        });
    }
});
