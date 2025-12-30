// frontend/js/app.js

// ---------- SPApp init ----------
var app = $.spapp({
  defaultView: "#view_dashboard",
  templateDir: "views/",
  pageNotFound: "view_404"
});
app.run();

// ✅ change if needed
const API_BASE = "http://localhost:8000";

// ---------- App Controller (MVC: Controller only) ----------
var AppController = {
  updateNav: function () {
    const logged = AuthService.isLoggedIn();
    const admin = AuthService.isAdmin();

    $("#navDashboard, #navCatalog, #navMyLoans").toggle(logged);
    $("#navAdmin").toggle(logged && admin);

    $("#navLogin, #navRegister").toggle(!logged);
    $("#navLogout").toggle(logged);
  },

  protectRoutes: function (hash) {
    // If NOT logged in, block protected pages
    if (!AuthService.isLoggedIn()) {
      const protectedViews = [
        "#view_dashboard",
        "#view_catalog",
        "#view_myloans",
        "#view_admin",
        "#view_addbook"
      ];

      if (protectedViews.includes(hash)) {
        window.location.hash = "#view_login";
        return false;
      }
    }

    // If NOT admin, block admin-only pages
    const adminOnlyViews = ["#view_admin", "#view_addbook"];
    if (adminOnlyViews.includes(hash) && !AuthService.isAdmin()) {
      alert("Admin only.");
      window.location.hash = "#view_dashboard";
      return false;
    }

    return true;
  },

  handleRoute: function () {
    AppController.updateNav();

    const h = window.location.hash || "#view_dashboard";
    if (!AppController.protectRoutes(h)) return;

    // Call the correct service per view
    if (h === "#view_dashboard") DashboardService.load();
    if (h === "#view_catalog") CatalogService.load();
    if (h === "#view_myloans") LoansService.load();
    if (h === "#view_admin") AdminService.load();

    // Add Book view: load dropdown + apply validation
    if (h === "#view_addbook") {
      if (typeof BooksService !== "undefined" && BooksService.loadAddBookView) {
        BooksService.loadAddBookView();
      }
    }

    // Apply auth validations after view loads (safe no-op if not on auth pages)
    if (typeof AuthService !== "undefined" && AuthService.applyValidation) {
      setTimeout(function () {
        AuthService.applyValidation();
      }, 50);
    }
  },

  init: function () {
    // Bind service event handlers ONCE
    AuthService.init();
    if (typeof BooksService !== "undefined" && BooksService.init) BooksService.init();

    // Initial render + routing
    AppController.updateNav();
    AppController.handleRoute();

    // React to SPA hash navigation
    window.addEventListener("hashchange", AppController.handleRoute);
  }
};

// keep inline onclick="logout()" working without changing HTML
function logout() {
  AuthService.logout();
}

// ---------- Start ----------
$(function () {
  AppController.init();
});
