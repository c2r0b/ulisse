'use strict';

module.exports = ['$scope', '$api',function($scope, $api) {
  // get data
  $api.get({},function(data) {
    $scope.data = data || [];
  });

  // get meeting information
  $scope.select = function(meeting) {
    // select and remember selected meeting in scope
    $scope.sel_data = meeting;
    angular.copy(meeting,$scope.$e);
    // request meeting booked people if not already in scope
    if (!$scope.sel_data.booked) {
      // request to get meeting booked people
      $api.put(
        {
          'id': meeting.id
        },
        function(data) {
          // success: meeting data to scope panel
          $scope.sel_data.booked = data;
        },
        'booked',
        'get'
      );
    }
  };

  // add new
  $scope.add = function() {
    // structure and launch the API request
    $api.put({
        'add': $scope.$n
      },
      function(response) {
        // add info to scope
        $scope.data.meetings.splice(0,0, response);
      }
    );
  };

  // edit selected
  $scope.edit = function() {
    // structure and launch the API request
    $api.put({
        'edit': $scope.$e
      },
      function(response) {
        // edit info in scope
        angular.copy(response, $scope.sel_data);
      }
    );
  };

  // remove selected
  $scope.remove = function() {
    // structure and launch the API request
    $api.put({
        'remove': $scope.$e
      },
      function() {
        // remove from scope
        for (var i in $scope.data.meetings)
          if ($scope.data.meetings[i] == $scope.sel_data)
            $scope.data.meetings.splice(i, 1);
      }
    );
  };
}];
