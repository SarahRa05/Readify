var LoansService = {
    load: function () {
      $("#myloans_list").html("Loading your loans...");
  
      RestClient.request("GET", "/loans")
        .done(function (loans) {
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
        })
        .fail(function (xhr) {
          $("#myloans_list").html("Failed to load loans. (" + xhr.status + ")");
        });
    }
  };
  