// frontend/js/services/BooksService.js

var BooksService = {
  loadAddBookView: function () {
    if ($("#addBookForm").length === 0) return;

    if (!AuthService.isAdmin()) {
      alert("Admin only.");
      window.location.hash = "#view_dashboard";
      return;
    }

    // reset dropdown every time
    $("#bookCategoryId").html('<option value="">Loading...</option>');

    CategoriesService.getAll()
      .done(function (res) {
        // ---- normalize response into an array ----
        let cats = res;

        // common wrappers:
        if (!Array.isArray(cats) && cats && Array.isArray(cats.data)) cats = cats.data;
        if (!Array.isArray(cats) && cats && cats.data && Array.isArray(cats.data.data)) cats = cats.data.data;
        if (!Array.isArray(cats) && cats && cats.success && Array.isArray(cats.data)) cats = cats.data;

        if (!Array.isArray(cats)) {
          console.warn("Categories response is not an array:", res);
          $("#bookCategoryId").html('<option value="">Failed to load categories</option>');
          return;
        }

        // ---- build options ----
        let options = '<option value="">Select category</option>';

        cats.forEach(function (c) {
          const id =
            c.category_id ?? c.id ?? c.categoryId ?? c.categoryID ?? c.categoryid;

          const name =
            c.name ?? c.category_name ?? c.title ?? c.categoryName ?? c.category;

          if (id != null) {
            options += `<option value="${id}">${name ?? ("Category " + id)}</option>`;
          }
        });

        // if still only placeholder, show message
        if (options === '<option value="">Select category</option>') {
          console.warn("No usable categories found. Raw:", cats);
          $("#bookCategoryId").html('<option value="">No categories found</option>');
          return;
        }

        $("#bookCategoryId").html(options);
      })
      .fail(function (xhr) {
        console.warn("Failed to load categories:", xhr.status, xhr.responseText);
        $("#bookCategoryId").html('<option value="">Failed to load categories</option>');
      });

    BooksService.applyValidation();
  },

  applyValidation: function () {
    if ($("#addBookForm").length && !$("#addBookForm").data("validator")) {
      $("#addBookForm").validate({
        rules: {
          title: { required: true, minlength: 2 },
          author: { required: true, minlength: 2 },
          category_id: { required: true },
          publication_year: { required: true, digits: true },
          available_copies: { required: true, digits: true, min: 1 }
        }
      });
    }
  },

  init: function () {
    $(document).off("submit", "#addBookForm").on("submit", "#addBookForm", function (e) {
      e.preventDefault();

      if (!AuthService.isAdmin()) {
        alert("Admin only.");
        window.location.hash = "#view_dashboard";
        return;
      }

      if ($("#addBookForm").length && !$("#addBookForm").valid()) return;

      const payload = {
        title: $("#bookTitle").val().trim(),
        author: $("#bookAuthor").val().trim(),
        isbn: $("#bookIsbn").val().trim() || null,
        category_id: $("#bookCategoryId").val()
          ? parseInt($("#bookCategoryId").val(), 10)
          : null,
        publication_year: parseInt($("#bookYear").val(), 10),
        available_copies: parseInt($("#bookCopies").val(), 10)
      };

      RestClient.request("POST", "/books", payload)
        .done(function () {
          alert("Book created successfully!");
          window.location.hash = "#view_admin";
        })
        .fail(function (xhr) {
          alert("Failed to create book: " + (xhr.responseText || xhr.status));
        });
    });
  }
};
