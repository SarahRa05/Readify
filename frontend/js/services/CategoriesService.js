var CategoriesService = {
    getAll: function () {
      return RestClient.request("GET", "/categories");
    }
  };

  