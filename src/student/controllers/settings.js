'use strict';

module.exports = ['$scope', '$api', function($scope, $api) {
  // save new password
  $scope.changePassword = function() {
    // structure and launch the API request
    $api.put({
        'edit': $scope.$e
      },
      function(response) {
        // edit info in $scope
        angular.copy(response, $scope.sel_data);
      }
    );
  };
  // save UI preferences
  $scope.savePreferences = function() {
    // structure and launch the API request
    $api.put({
        'remove': $scope.$e
      },
      function() {
        // remove from $scope
        for (var i in $scope.data)
          if ($scope.data[i] == $scope.sel_data)
            $scope.data.splice(i, 1);
      }
    );
  };
}];
