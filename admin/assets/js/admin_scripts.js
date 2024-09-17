document.addEventListener("DOMContentLoaded", function () {
    const selectDatabase = document.getElementById("selectDatabase");
    const categoryForm = document.getElementById("categoryForm");
    const categoryName = document.getElementById("categoryName");
    const categoryDescription = document.getElementById("categoryDescription");
    const parentCategoriesDiv = document.getElementById("parentCategories");

    if (selectDatabase) {
        selectDatabase.addEventListener("change", function () {
            const selectedDb = selectDatabase.value;
            fetchCategories(selectedDb);
        });
    }

    if (categoryForm) {
        categoryForm.addEventListener("submit", function (event) {
            event.preventDefault();
            addCategory();
        });
    }

    // Fetch databases on page load
    fetchDatabases();
});

function fetchDatabases() {
    console.log("Attempting to fetch databases...");
    fetch("get_databases.php")
        .then((response) => response.json())
        .then((data) => {
            console.log("Databases fetched: ", data);
            if (data.status === 'success') {
                populateDatabaseDropdown(data.databases);
            } else {
                displayError(data.message);
            }
        })
        .catch((error) => {
            console.error("Error fetching databases:", error);
            displayError("Error fetching databases. Please try again.");
        });
}

function populateDatabaseDropdown(databases) {
    const selectDatabase = document.getElementById("selectDatabase");
    if (selectDatabase) {
        selectDatabase.innerHTML = "";
        databases.forEach((db) => {
            const option = document.createElement("option");
            option.value = db;
            option.text = db;
            selectDatabase.appendChild(option);
        });
        console.log("Populating database dropdown...");
    }
}

function fetchCategories(selectedDb) {
    console.log("Attempting to fetch categories for database: " + selectedDb);
    fetch(`get_categories.php?selected_db=${encodeURIComponent(selectedDb)}`)
        .then((response) => response.json())
        .then((data) => {
            console.log("Categories fetched: ", data);
            if (data.status === 'success') {
                populateCategoryCheckboxes(data.categories);
            } else {
                displayError(data.message);
                clearCategories();
            }
        })
        .catch((error) => {
            console.error("Error fetching categories:", error);
            displayError("Error fetching categories. Please try again.");
        });
}

function populateCategoryCheckboxes(categories) {
    const parentCategoriesDiv = document.getElementById("parentCategories");
    if (parentCategoriesDiv) {
        parentCategoriesDiv.innerHTML = ""; // Clear existing checkboxes
        if (categories.length === 0) {
            parentCategoriesDiv.innerHTML = "No categories found. You may start by adding new categories.";
            console.log("No categories found in the selected database.");
        } else {
            categories.forEach((category) => {
                const checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.value = category.id;
                checkbox.id = "parent_" + category.id;

                const label = document.createElement("label");
                label.htmlFor = "parent_" + category.id;
                label.textContent = category.name;

                parentCategoriesDiv.appendChild(checkbox);
                parentCategoriesDiv.appendChild(label);
                parentCategoriesDiv.appendChild(document.createElement("br"));
            });
        }
        console.log("Populating categories dropdown...");
    }
}

function addCategory() {
    const categoryName = document.getElementById("categoryName").value;
    const categoryDescription = document.getElementById("categoryDescription").value;
    const selectedDb = document.getElementById("selectDatabase").value;
    const parentCategoryCheckboxes = document.querySelectorAll("#parentCategories input[type='checkbox']:checked");

    const parentCategories = Array.from(parentCategoryCheckboxes).map((checkbox) => checkbox.value);

    const formData = new FormData();
    formData.append("category_name", categoryName);
    formData.append("category_description", categoryDescription);
    formData.append("selected_db", selectedDb);
    formData.append("parent_categories", JSON.stringify(parentCategories));

    console.log("Submitting category form...");

    fetch("process_add_category.php", {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            console.log("Form submitted: ", data);
            if (data.status === 'success') {
                alert("Category added successfully!");
                fetchCategories(selectedDb); // Refresh categories list
            } else {
                displayError(data.message);
            }
        })
        .catch((error) => {
            console.error("Error submitting form:", error);
            displayError("Error submitting form. Please try again.");
        });
}

function displayError(message) {
    const errorDiv = document.getElementById("error");
    if (errorDiv) {
        errorDiv.textContent = "Error: " + message;
        errorDiv.style.color = "red";
    }
}

function clearCategories() {
    const parentCategoriesDiv = document.getElementById("parentCategories");
    if (parentCategoriesDiv) {
        parentCategoriesDiv.innerHTML = "No categories found. You may start by adding new categories.";
    }
}
