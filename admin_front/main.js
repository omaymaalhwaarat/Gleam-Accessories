const BASE_URL = "http://localhost/fullproject/admin";

// Generic API request handler
function apiRequest(endpoint, method = "GET", data = null, callback) {
  const xhr = new XMLHttpRequest();
  xhr.open(method, `${BASE_URL}${endpoint}`, true);
  xhr.setRequestHeader("Content-Type", "application/json");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      //   console.log(`Response (${method} ${endpoint}):`, xhr.responseText);
      if (xhr.status >= 200 && xhr.status < 300) {
        callback(null, JSON.parse(xhr.responseText));
      } else {
        callback(`Error ${xhr.status}: ${xhr.statusText}`, null);
      }
    }
  };

  xhr.send(data ? JSON.stringify(data) : null);
}

// Fetch all users
function fetchUsers() {
  apiRequest("/users.php", "GET", null, (err, users) => {
    if (err) {
      console.error("Failed to fetch users:", err);
    } else {
      populateUsersTable(users);
    }
  });
}

// Populate users table
function populateUsersTable(users) {
  const tbody = document.getElementById("userBody");
  tbody.innerHTML = ""; // Clear existing rows

  users.forEach((user) => {
    const row = document.createElement("tr");

    row.innerHTML = `
      <td>${user.id}</td>
      <td>${user.first_name + " " + user.last_name}</td>
      <td>${user.email}</td>
      <td>${user.phone}</td>
      <td>${user.address}</td>
      <td>
        <a href="viewUser.html?id=${user.id}">view</a>
        <a href="#" onclick="deleteUser(${user.id}); return false;">delete</a>
        <a href="edituser.html?id=${user.id}">update</a>
      </td>
    `;

    tbody.appendChild(row);
  });
}

// Delete a user
function deleteUser(userId) {
  const confirmDelete = confirm("Are you sure you want to delete this user?");
  if (!confirmDelete) return;

  apiRequest(`/users.php?id=${userId}`, "DELETE", null, (err) => {
    if (err) {
      console.error("Failed to delete user:", err);
      alert("Failed to delete user.");
    } else {
      alert("User deleted successfully!");
      fetchUsers(); // Refresh the users list
    }
  });
}

// Fetch user details for viewing
function fetchUserDetails() {
  const params = new URLSearchParams(window.location.search);
  const userId = params.get("id");

  apiRequest(`/users.php?id=${userId}`, "GET", null, (err, user) => {
    if (err) {
      console.error("Failed to fetch user details:", err);
      document.getElementById("user-details").textContent =
        "Failed to fetch user details.";
    } else {
      document.getElementById("user-details").innerHTML = `
        <p><strong>ID:</strong> ${user.id}</p>
        <p><strong>Name:</strong> ${user.first_name} ${user.last_name}</p>
        <p><strong>Email:</strong> ${user.email}</p>
        <p><strong>Phone:</strong> ${user.phone}</p>
        <p><strong>Address:</strong> ${user.address || "Not provided"}</p>
      `;
    }
  });
}

// Fetch and prefill form for editing user details
function fetchUserData() {
  const params = new URLSearchParams(window.location.search);
  const userId = params.get("id");

  apiRequest(`/users.php?id=${userId}`, "GET", null, (err, user) => {
    if (err) {
      console.error("Failed to fetch user data:", err);
      alert("Failed to load user data.");
    } else {
      document.getElementById(
        "name"
      ).value = `${user.first_name} ${user.last_name}`;
      document.getElementById("email").value = user.email;
      document.getElementById("phone").value = user.phone;
      document.getElementById("address").value = user.address;
    }
  });
}

// Update user details
function updateUser() {
  const params = new URLSearchParams(window.location.search);
  const userId = params.get("id");

  // Extract input fields
  const fullName = document.getElementById("name").value.trim().split(" ");
  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value.trim();
  const phone = document.getElementById("phone").value.trim();
  const address = document.getElementById("address").value.trim();

  // Ensure all required fields are handled
  if (fullName.length < 2) {
    alert("Please enter a full name with at least first and last name.");
    return;
  }

  // Construct the payload
  const payload = {
    first_name: fullName[0] || "",
    second_name: fullName[1] || "", // Assign second name from full name
    last_name: fullName.slice(2).join(" ") || "", // Remaining as last name
    email,
    phone,
    address,
    ...(password ? { password } : {}), // Include password only if provided
  };

  // Debug: Log payload and endpoint
  console.log("Endpoint:", `/users.php?id=${userId}`);
  console.log("Payload:", JSON.stringify(payload));

  // Make the API call
  apiRequest(`/users.php?id=${userId}`, "PUT", payload, (err) => {
    if (err) {
      console.error("Failed to update user:", err);
      alert("Failed to update user.");
    } else {
      alert("User updated successfully!");
      window.location.href = "admindash.html"; // Redirect to admin dashboard
    }
  });
}

// Initialize the appropriate functions on page load
window.onload = () => {
  const currentPage = window.location.pathname.split("/").pop();

  if (currentPage === "admindash.html") {
    fetchUsers();
  } else if (currentPage === "viewUser.html") {
    fetchUserDetails();
  } else if (currentPage === "edituser.html") {
    fetchUserData();

    document.getElementById("editUserForm").addEventListener("submit", (e) => {
      e.preventDefault();
      updateUser();
    });
  }
};

// Add users function
function addUser() {
  // Collect form data
  const fullName = document
    .querySelector('input[name="name"]')
    .value.trim()
    .split(" ");
  const email = document.querySelector('input[name="email"]').value.trim();
  const password = document
    .querySelector('input[name="password"]')
    .value.trim();
  const phone = document.querySelector('input[name="phone"]').value.trim();
  const address = document.querySelector('input[name="address"]').value.trim();

  // Ensure full name is entered correctly
  if (fullName.length < 2) {
    alert("Please enter a full name with at least a first and last name.");
    return;
  }

  // Construct the payload
  const payload = {
    first_name: fullName[0],
    second_name: fullName[1] || "", // Second name is optional
    last_name: fullName.slice(2).join(" "), // Last name could be multiple words
    email,
    phone,
    address,
    username: email, // Assuming email is used as the username
    password,
  };

  // Debug: Log the payload
  console.log("Payload:", JSON.stringify(payload));

  // Make the API request to add the user
  apiRequest("/users.php", "POST", payload, (err, response) => {
    if (err) {
      console.error("Failed to add user:", err);
      alert("Failed to add user.");
    } else {
      alert("User added successfully!");
      window.location.href = "admindash.html"; // Redirect to dashboard after successful addition
    }
  });
}

// Listen for form submission
try {
  document.getElementById("formAddUser").addEventListener("submit", (e) => {
    e.preventDefault();
    addUser();
  });
} catch (error) {}

/* CATEGORIES */

async function fetchCategories() {
  try {
    const response = await fetch(
      "http://localhost/fullproject/admin/categories.php/get"
    );
    if (!response.ok) {
      console.error("Failed to fetch products:", response.status);
      return [];
    }
    return await response.json();
  } catch (error) {
    console.error("Error fetching products:", error);
    return [];
  }
}

async function displayCategories() {
  const products = await fetchCategories();
  const tableBody = document.querySelector("#CategoriesBody");

  if (products.length === 0) {
    console.log("No categories found.");
  }

  // Clear previous rows (in case it's being reloaded)
  tableBody.innerHTML = "";

  // Loop through products and add them to the table
  products.forEach((category) => {
    const row = document.createElement("tr");
    row.innerHTML = `
              <td>${category.id}</td>
              <td>${category.name}</td>
              
              <td>
                  <a href="viewCategory.html?id=${category.id}">View</a>
                  <a href="#" onclick="deleteCategory(${category.id})">Delete</a>
                  <a href="editcategories.html?id=${category.id}">Update</a>
              </td>
          `;
    tableBody.appendChild(row);
  });
}

// Load products when the page is ready
window.addEventListener("DOMContentLoaded", () => {
  displayCategories();
});

//VEIW
async function fetchCategoryById(id) {
  try {
    const response = await fetch(
      `http://localhost/fullproject/admin/categories.php/get/?id=` +
        id
    );
    if (!response.ok) {
      console.error("Failed to fetch product:", response.status);
      return null;
    }
    return await response.json();
  } catch (error) {
    console.error("Error fetching product:", error);
    return null;
  }
}

async function displayCategory() {
  const urlParams = new URLSearchParams(window.location.search);
  const categorytId = urlParams.get("id");

  if (!categorytId) {
    return;
  }

  const category = await fetchCategoryById(categorytId);

  if (!category) {
    document.getElementById("category-details").innerHTML =
      "Product not found.";
    return;
  }

  const categoryDetails = document.getElementById("category-details");
  categoryDetails.innerHTML = `
        <p><strong>ID:</strong> ${category.id}</p>
        <p><strong>Name:</strong> ${category.name}</p>
        
      `;
}

window.addEventListener("DOMContentLoaded", () => {
  displayCategory();
});

//DELETE

// Function to delete a cat by ID
async function deleteCategory(id) {
  const confirmation = confirm(
    "Are you sure you want to delete this category?"
  );
  if (!confirmation) {
    return; // Exit if the user cancels
  }

  try {
    const response = await fetch(
      `http://localhost/fullproject/admin/categories.php/delete?id=${id}`,
      {
        method: "DELETE",
      }
    );
    if (response.ok) {
      alert("Category deleted successfully.");
      // Reload the page to reflect changes
      location.reload();
    } else {
      console.error("Failed to delete category:", response.status);
      alert("Failed to delete category.");
    }
  } catch (error) {
    console.error("Error deleting category:", error);
    alert("Error deleting category.");
  }
}

//UPDATE

/* ORDERS ADmi */

async function fetchOrders() {
  try {
    const response = await fetch(
      "http://localhost/fullproject/admin/crudOrders.php"
    );
    if (!response.ok) {
      console.error("Failed to fetch orders:", response.status);
      return [];
    }
    return await response.json();
  } catch (error) {
    console.error("Error fetching orders:", error);
    return [];
  }
}

async function displayOrders() {
  const products = await fetchOrders();
  const tableBody = document.querySelector("#ordersBody");

  if (products.length === 0) {
    console.log("No orders found.");
  }

  // Clear previous rows (in case it's being reloaded)
  tableBody.innerHTML = "";

  // Loop through products and add them to the table
  products.forEach((order) => {
    const row = document.createElement("tr");
    row.innerHTML = `
                <td>${order.id}</td>
                <td>${order.CustomerName}</td>
                <td>${order.date}</td>
                <td>${order.status}</td>
                
                <td>
                    <a href="viewOrder.html?CustomerName=${order.CustomerName}">View</a>
                    <a href="#" onclick="deleteOrder(${order.id})">Delete</a>
                    <a href="editorder.html?id=${order.id}">Update</a>
                </td>
            `;
    tableBody.appendChild(row);
  });
}

// Load products when the page is ready
window.addEventListener("DOMContentLoaded", () => {
  displayOrders();
});

//VEIW
async function fetchOrderByName(CustomerName) {
  try {
    const response = await fetch(
      `http://localhost/fullproject/admin/crudOrders.php?CustomerName=` +
        encodeURIComponent(CustomerName)
    );
    if (!response.ok) {
      console.error("Failed to fetch order:", response.status);
      return null;
    }
    return await response.json();
  } catch (error) {
    console.error("Error fetching order:", error);
    return null;
  }
}

async function displayOrder() {
  const urlParams = new URLSearchParams(window.location.search);
  const orderName = urlParams.get("CustomerName");

  if (!orderName) {
    return;
  }

  const order = await fetchOrderByName(orderName);

  console.log(order);
  if (!order) {
    document.getElementById("order-details").innerHTML = "Order not found.";
    return;
  }

  const orderDetails = document.getElementById("order-details");
  orderDetails.innerHTML = `
          <p><strong>Customer Name:</strong> ${order[0].CustomerName}</p>
          <p><strong>Item Name:</strong> ${order[0].Item}</p>
          <p><strong>Order Date:</strong> ${order[0].date}</p>
          <p><strong>Order Status:</strong> ${order[0].status}</p>
          <p><strong>Order Price:</strong> ${
            order[0].amount_after_discount == null
              ? order[0].amount
              : order[0].amount_after_discount
          }</p>
          
        `;
}

window.addEventListener("DOMContentLoaded", () => {
  displayOrder();
});

//DELETE

function deleteOrder(id) {
  const confirmDelete = confirm("Are you sure you want to delete this order?");
  if (!confirmDelete) return;

  apiRequest(`/crudOrders.php?id=${id}`, "DELETE", null, (err) => {
    if (err) {
      console.error("Failed to delete order:", err);
      alert("Failed to delete order.");
    } else {
      alert("Order deleted successfully!");
      fetchOrders(); // Refresh the orders list
    }
  });
}

//VEIW
async function fetchCategoryById(id) {
  try {
    const response = await fetch(
      `http://localhost/fullproject/admin/categories.php/get/?id=` +
        id
    );
    if (!response.ok) {
      console.error("Failed to fetch product:", response.status);
      return null;
    }
    return await response.json();
  } catch (error) {
    console.error("Error fetching product:", error);
    return null;
  }
}

async function displayCategory() {
  const urlParams = new URLSearchParams(window.location.search);
  const categorytId = urlParams.get("id");

  if (!categorytId) {
    return;
  }

  const category = await fetchCategoryById(categorytId);

  if (!category) {
    document.getElementById("category-details").innerHTML =
      "Product not found.";
    return;
  }

  const categoryDetails = document.getElementById("category-details");
  categoryDetails.innerHTML = `
        <p><strong>ID:</strong> ${category.id}</p>
        <p><strong>Name:</strong> ${category.name}</p>
        
      `;
}

window.addEventListener("DOMContentLoaded", () => {
  displayCategory();
});

/* -------------------------------------------- */

/* PRODUCTS ADMIN */

// Fetch and display products
async function fetchProducts() {
  try {
    const response = await fetch(
      "http://localhost/fullproject/admin/products.php/get"
    );
    if (!response.ok) {
      console.error("Failed to fetch products:", response.status);
      return [];
    }
    return await response.json();
  } catch (error) {
    console.error("Error fetching products:", error);
    return [];
  }
}

// Function to display products in the table
async function displayProducts() {
  const products = await fetchProducts();
  const tableBody = document.querySelector("#productsTableBody");

  if (products.length === 0) {
    console.log("No products found.");
  }

  // Clear previous rows (in case it's being reloaded)
  tableBody.innerHTML = "";

  // Loop through products and add them to the table
  products.forEach((product) => {
    const row = document.createElement("tr");
    row.innerHTML = `
            <td>${product.id}</td>
            <td>${product.name}</td>
            <td>${product.description}</td>
            <td>${product.quantity}</td>
            <td>
                <a href="viewProduct.html?id=${product.id}">View</a>
                <a href="#" onclick="deleteProduct(${product.id})">Delete</a>
                <a href="editproduct.html?id=${product.id}">Update</a>
            </td>
        `;
    tableBody.appendChild(row);
  });
}

// Load products when the page is ready
window.addEventListener("DOMContentLoaded", () => {
  displayProducts();
});

//VIEW pr

async function fetchProductById(id) {
  try {
    const response = await fetch(
      `http://localhost/fullproject/admin/products.php/get/?id=` +
        id
    );
    if (!response.ok) {
      console.error("Failed to fetch product:", response.status);
      return null;
    }
    return await response.json();
  } catch (error) {
    console.error("Error fetching product:", error);
    return null;
  }
}

async function displayProduct() {
  const urlParams = new URLSearchParams(window.location.search);
  const productId = urlParams.get("id");

  if (!productId) {
    return;
  }

  const product = await fetchProductById(productId);

  if (!product) {
    document.getElementById("product-details").innerHTML = "Product not found.";
    return;
  }

  const productDetails = document.getElementById("product-details");
  productDetails.innerHTML = `
      <p><strong>ID:</strong> ${product.id}</p>
      <p><strong>Name:</strong> ${product.name}</p>
      <p><strong>Description:</strong> ${product.description}</p>
      <p><strong>Quantity:</strong> ${product.quantity}</p>
    `;
}

window.addEventListener("DOMContentLoaded", () => {
  displayProduct();
});

//DELETE

// Function to delete a product by ID
async function deleteProduct(id) {
  const confirmation = confirm("Are you sure you want to delete this product?");
  if (!confirmation) {
    return; // Exit if the user cancels
  }

  try {
    const response = await fetch(
      `http://localhost/fullproject/admin/products.php/delete?id=${id}`,
      {
        method: "DELETE",
      }
    );
    if (response.ok) {
      alert("Product deleted successfully.");
      // Reload the page to reflect changes
      location.reload();
    } else {
      console.error("Failed to delete product:", response.status);
      alert("Failed to delete product.");
    }
  } catch (error) {
    console.error("Error deleting product:", error);
    alert("Error deleting product.");
  }
}

//UPDATE

async function fetchProductById(id) {
  try {
    const response = await fetch(
      `http://localhost/fullproject/admin/products.php/get/?id=${id}`
    );
    if (!response.ok) {
      console.error("Failed to fetch product:", response.status);
      return null;
    }
    return await response.json();
  } catch (error) {
    console.error("Error fetching product:", error);
    return null;
  }
}

async function fillProductForm() {
  const urlParams = new URLSearchParams(window.location.search);
  const productId = urlParams.get("id");

  if (!productId) {
    return;
  }

  const product = await fetchProductById(productId);

  if (!product) {
    return;
  }

  // Fill form fields with product data
  document.getElementById("id").value = product.id;
  document.getElementById("name").value = product.name;
  document.getElementById("price_cost").value = product.price_cost;
  document.getElementById("price_with_Revenue").value =
    product.price_with_Revenue;
  document.getElementById("quantity").value = product.quantity;
  document.getElementById("image").value = product.image;
  document.getElementById("category_id").value = product.category_id;
}

window.addEventListener("DOMContentLoaded", () => {
  fillProductForm();
});

let ss = document
  .getElementById("editProductForm")
  .addEventListener("submit", async (event) => {
    event.preventDefault();

    const formData = new FormData(event.target);
    const productData = {
      id: formData.get("id"),
      name: formData.get("name"),
      price_cost: formData.get("price_cost"),
      price_with_Revenue: formData.get("price_with_Revenue"),
      quantity: formData.get("quantity"),
      image: formData.get("image"),
      category_id: formData.get("category_id"),
    };

    try {
      const response = await fetch(
        "http://localhost/fullproject/admin/products.php/update",
        {
          method: "PUT",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(productData),
        }
      );

      if (response.ok) {
        const result = await response.json();
        alert(result.message || "Product updated successfully.");
        window.location.href = "admindash.html"; // Redirect to dashboard
      } else {
        const error = await response.json();
        alert(error.error || "Failed to update product.");
      }
    } catch (error) {
      console.error("Error updating product:", error);
      alert("An error occurred while updating the product.");
    }
  });
