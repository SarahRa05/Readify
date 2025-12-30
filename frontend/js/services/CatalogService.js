var CatalogService = {
    load: function () {
      $("#catalog_list").html("Loading books...");
  
      RestClient.request("GET", "/books")
        .done(function (books) {
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
        })
        .fail(function (xhr) {
          $("#catalog_list").html("Failed to load books. (" + xhr.status + ")");
        });
    }
  };
  