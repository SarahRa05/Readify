var app = $.spapp({
  defaultView: "#view_dashboard",
  templateDir: "views/",
  pageNotFound: "view_404"
});

app.run();

// ✅ change if needed
const API_BASE = "http://localhost:8000";

// ---------------- AUTH HELPERS ----------------
function setAuth(token, user) {
  localStorage.setItem("token", token);
  localStorage.setItem("user", JSON.stringify(user));
}

function getToken() {
  return localStorage.getItem("token");
}

function getUser() {
  const u = localStorage.getItem("user");
  return u ? JSON.parse(u) : null;
}

function isLoggedIn() {
  return !!getToken();
}

function isAdmin() {
  const u = getUser();
  return u && u.role === "admin";
}

function logout() {
  localStorage.removeItem("token");
  localStorage.removeItem("user");
  updateNav();
  window.location.hash = "#view_login";
}

// professor-style ajax helper
function api(method, url, data) {
  const token = getToken();
  return $.ajax({
    url: API_BASE + url,
    method: method,
    data: data ? JSON.stringify(data) : null,
    contentType: "application/json",
    headers: token ? { "Authentication": token } : {}
  });
}

// ---------------- ROLE-BASED NAV ----------------
function updateNav() {
  const logged = isLoggedIn();
  const admin = isAdmin();

  $("#navDashboard, #navCatalog, #navMyLoans").toggle(logged);
  $("#navAdmin").toggle(logged && admin);

  $("#navLogin, #navRegister").toggle(!logged);
  $("#navLogout").toggle(logged);
}

// ---------------- PAGE LOADERS ----------------
function loadDashboard() {
  const u = getUser();
  if (!u) return;

  $("#dash_welcome").text("Welcome, " + u.full_name + " 👋");
  $("#dash_role").text("Role: " + u.role);

  // KPIs: books count + my loans count (simple)
  api("GET", "/books").done(function (books) {
    $("#kpi_books").text(Array.isArray(books) ? books.length : "-");
  });

  api("GET", "/loans").done(function (loans) {
    $("#kpi_loans").text(Array.isArray(loans) ? loans.length : "-");
  });
}

function loadCatalog() {
  $("#catalog_list").html("Loading books...");

  api("GET", "/books").done(function (books) {
    if (!Array.isArray(books)) {
      $("#catalog_list").html("Unexpected response from server.");
      return;
    }
    if (books.length === 0) {
      $("#catalog_list").html("<p>No books yet.</p>");
      return;
    }

    let html = `
      <table class="table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Year</th>
            <th>Copies</th>
          </tr>
        </thead>
        <tbody>
    `;

    books.forEach(function (b) {
      html += `
        <tr>
          <td>${b.title ?? ""}</td>
          <td>${b.author ?? ""}</td>
          <td>${b.publication_year ?? ""}</td>
          <td>${b.available_copies ?? ""}</td>
        </tr>
      `;
    });

    html += `</tbody></table>`;
    $("#catalog_list").html(html);
  }).fail(function (xhr) {
    $("#catalog_list").html("Failed to load books. (" + xhr.status + ")");
  });
}

function loadMyLoans() {
  $("#myloans_list").html("Loading your loans...");

  api("GET", "/loans").done(function (loans) {
    if (!Array.isArray(loans)) {
      $("#myloans_list").html("Unexpected response from server.");
      return;
    }
    if (loans.length === 0) {
      $("#myloans_list").html("<p>No loans yet.</p>");
      return;
    }

    let html = `
      <table class="table">
        <thead>
          <tr>
            <th>Loan ID</th>
            <th>Book ID</th>
            <th>Borrow Date</th>
            <th>Due Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
    `;

    loans.forEach(function (l) {
      html += `
        <tr>
          <td>${l.loan_id ?? ""}</td>
          <td>${l.book_id ?? ""}</td>
          <td>${l.borrow_date ?? ""}</td>
          <td>${l.due_date ?? ""}</td>
          <td>${l.status ?? ""}</td>
        </tr>
      `;
    });

    html += `</tbody></table>`;
    $("#myloans_list").html(html);
  }).fail(function (xhr) {
    $("#myloans_list").html("Failed to load loans. (" + xhr.status + ")");
  });
}

// ---------------- FORM HANDLERS ----------------
$(document).on("submit", "#registerForm", function (e) {
  e.preventDefault();

  const full_name = $("#regFullName").val().trim();
  const email = $("#regEmail").val().trim();
  const password = $("#regPassword").val();
  const password2 = $("#regPassword2").val();

  if (password !== password2) {
    alert("Passwords do not match.");
    return;
  }

  api("POST", "/auth/register", { full_name, email, password })
    .done(function () {
      alert("Registered successfully. Now login.");
      window.location.hash = "#view_login";
    })
    .fail(function (xhr) {
      alert("Registration failed: " + (xhr.responseText || xhr.status));
    });
});

$(document).on("submit", "#loginForm", function (e) {
  e.preventDefault();

  const email = $("#loginEmail").val().trim();
  const password = $("#loginPassword").val();

  api("POST", "/auth/login", { email, password })
    .done(function (res) {
      // your API returns: { message, data: { token, user } }
      const token = res.data.token;
      const user = res.data.user;

      setAuth(token, user);
      updateNav();

      window.location.hash = "#view_dashboard";
      // dashboard loads after template renders, so small delay is ok
      setTimeout(loadDashboard, 150);
    })
    .fail(function (xhr) {
      alert("Login failed: " + (xhr.responseText || xhr.status));
    });
});

// ---------------- VERY SIMPLE ROUTE HOOKS ----------------
// SPApp doesn't give us a fancy router API in your version,
// so we just react on hash changes:
function onHashChange() {
  updateNav();

  const h = window.location.hash;

  if (h === "#view_dashboard") loadDashboard();
  if (h === "#view_catalog") loadCatalog();
  if (h === "#view_myloans") loadMyLoans();

  // protect pages: if not logged in -> force login
  if (!isLoggedIn()) {
    if (h === "#view_dashboard" || h === "#view_catalog" || h === "#view_myloans" || h === "#view_admin") {
      window.location.hash = "#view_login";
    }
  }

  // protect admin UI
  if (h === "#view_admin" && !isAdmin()) {
    alert("Admin only.");
    window.location.hash = "#view_dashboard";
  }
}

window.addEventListener("hashchange", onHashChange);

// first run
$(function () {
  updateNav();
  onHashChange();
});

