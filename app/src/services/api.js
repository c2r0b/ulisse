module.exports = ['$http', '$location', '$type', ($http, $location, $type) => {
  var loading;

  // in case of server/connection error
  var serverErrorMsg = "Check your internet connection or try again later";

  // in case of request error
  var requestErrorMsg = "Invalid data";

  function call(req, f, path = $location.path(), dir, api = 1) {
    // show loading frame
    if ((loading = document.getElementById('content_loading')))
      loading.style.display = "block";

    // get APIs url
    if (api) dir = 'api/' + $type.charAt(0) + '/' + dir;

    // execute server request
    return $http.post(dir+'/' + path + '.php', req)
      .then(
        (response) => {
          // in case of a parameter error reported by the APIs
          if (response == 'error')
            alert(requestErrorMsg);
          else
            // launch the success function passed as parameter
            return f(response.data);
        },
        // on error
        () => alert(serverErrorMsg)
      )
      .finally(
        () => {
          // hide content loading
          if (loading)
            loading.style.display = "none";
          // hide page 1loading
          if (loading = document.getElementById('loading'))
            loading.style.display = "none";
        }
      );
  }
  /*
   req -> request data
   f -> function to be executed in case of success
  */
  return {
    // ACTION request to APIs
    put: (req, f, path, dir = 'action', api) => call(req, f, path, dir, api),

    // GET request to APIs
    get: (req, f, path, dir = 'get', api) => call(req, f, path, dir, api)
  };
}];
