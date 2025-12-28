var RestClient = {
    request: function (method, url, data, options) {
      options = options || {};
      const token = localStorage.getItem("token");
  
      if (options.blockUI !== false) {
        $.blockUI({ message: "<h4>Processing...</h4>" });
      }
  
      return $.ajax({
        url: API_BASE + url,
        method: method,
        data: data ? JSON.stringify(data) : null,
        contentType: "application/json",
        headers: token ? { "Authentication": token } : {}
      }).always(function () {
        if (options.blockUI !== false) {
          $.unblockUI();
        }
      });
    }
  };
  