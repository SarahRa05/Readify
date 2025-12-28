var DashboardService = {
    load: function () {
      const u = AuthService.getUser();
      if (!u) return;
  
      $("#dash_welcome").text("Welcome, " + u.full_name + " 👋");
      $("#dash_role").text("Role: " + u.role);
  
      RestClient.request("GET", "/books").done(function (books) {
        $("#kpi_books").text(Array.isArray(books) ? books.length : "-");
      });
  
      RestClient.request("GET", "/loans").done(function (loans) {
        $("#kpi_loans").text(Array.isArray(loans) ? loans.length : "-");
      });
    }
  };
  