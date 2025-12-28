// frontend/js/services/AuthService.js

var AuthService = {
    // ---------- Storage helpers ----------
    setAuth: function (token, user) {
      localStorage.setItem("token", token);
      localStorage.setItem("user", JSON.stringify(user));
    },
  
    getToken: function () {
      return localStorage.getItem("token");
    },
  
    getUser: function () {
      const u = localStorage.getItem("user");
      return u ? JSON.parse(u) : null;
    },
  
    isLoggedIn: function () {
      return !!this.getToken();
    },
  
    isAdmin: function () {
      const u = this.getUser();
      return u && u.role === "admin";
    },
  
    logout: function () {
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      if (typeof AppController !== "undefined") AppController.updateNav();
      window.location.hash = "#view_login";
    },
  
    // ---------- Validation + submit handlers ----------
    applyValidation: function () {
  
      // ---------- LOGIN ----------
      if ($("#loginForm").length && !$("#loginForm").data("validator")) {
        $("#loginForm").validate({
          rules: {
            email: { required: true, email: true },
            password: { required: true, minlength: 2 }
          },
          messages: {
            email: "Please enter a valid email address.",
            password: "Password must be at least 2 characters."
          },
          submitHandler: function () {
            const payload = {
              email: $("#loginEmail").val().trim(),
              password: $("#loginPassword").val()
            };
  
            RestClient.request("POST", "/auth/login", payload)
              .done(function (res) {
                const token = res.data.token;
                const user = res.data.user;
  
                AuthService.setAuth(token, user);
                if (typeof AppController !== "undefined") AppController.updateNav();
  
                window.location.hash = "#view_dashboard";
                setTimeout(function () {
                  if (typeof DashboardService !== "undefined") DashboardService.load();
                }, 150);
              })
              .fail(function (xhr) {
                alert("Login failed: " + (xhr.responseText || xhr.status));
              });
          }
        });
      }
  
      // ---------- REGISTER ----------
      if ($("#registerForm").length && !$("#registerForm").data("validator")) {
        $("#registerForm").validate({
          rules: {
            full_name: { required: true, minlength: 2 },
            email: { required: true, email: true },
            password: { required: true, minlength: 2 },
            password2: { required: true, equalTo: "#regPassword" }
          },
          messages: {
            full_name: "Please enter your name (min 2 characters).",
            email: "Please enter a valid email address.",
            password: "Password must be at least 2 characters.",
            password2: "Passwords must match."
          },
          submitHandler: function () {
            const payload = {
              full_name: $("#regFullName").val().trim(),
              email: $("#regEmail").val().trim(),
              password: $("#regPassword").val()
            };
  
            RestClient.request("POST", "/auth/register", payload)
              .done(function () {
                alert("Registered successfully. Now login.");
                window.location.hash = "#view_login";
              })
              .fail(function (xhr) {
                alert("Registration failed: " + (xhr.responseText || xhr.status));
              });
          }
        });
      }
    },
  
    init: function () {
      AuthService.applyValidation();
  
      window.addEventListener("hashchange", function () {
        setTimeout(function () {
          AuthService.applyValidation();
        }, 50);
      });
    }
  };
  