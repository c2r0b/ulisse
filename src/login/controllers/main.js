module.exports = ['$scope', '$api', ($scope, $api) => {
  // get string from an account type
  let redirect = t => window.location.replace("../" + ['', 's', 't', 'a'][t]);

  // check if already logged in
  $api.get(
    {},
    data => {
      if (+data) redirect(data);
    },
    'type',
    '../account/get'
  );

  // app general information
  $scope.INFO = {
    appVersion: 'Alpha v0.2.1'
  };

  // on form submission, try to login with inserted data
  $scope.login = () => {
    // remove previous login attempt result if any
    $scope.loginFailed = false;

    $api.put({
        'school_id'   :   $scope.school_id,
        'user_id'     :   $scope.user_id,
        'password'    :   $scope.password
      },
      response => {
        // data is correct and user is now logged in
        if (response) redirect(response);
        // login failed message
        else $scope.loginFailed = true;
      },
      'login',
      '../account/action'
    );
  };
}];
