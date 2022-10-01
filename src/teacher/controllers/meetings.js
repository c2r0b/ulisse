module.exports = ['$scope', '$api', ($scope, $api) => {
  // get data
  $api.get({}, data => {
    $scope.data = data || [];
  });

  // get meeting information
  $scope.select = meeting => {
    // select and remember selected meeting in scope
    $scope.sel_data = meeting;
    // request meeting booked people if not already in scope
    if (!$scope.sel_data.booked) {
      // request to get meeting booked people
      $api.put(
        {
          'id': meeting.id
        },
        data => {
          // success: meeting data to scope panel
          $scope.sel_data.booked = data;
        },
        'booked',
        'get'
      );
    }
  };
}];
