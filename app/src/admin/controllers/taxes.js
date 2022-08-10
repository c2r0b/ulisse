'use strict';

module.exports = ['$scope', '$api', function($scope, $api) {
  // get data
  $api.get({},function(data) {
    $scope.data = data;
  });

  // select to remember in actions
  $scope.select = function(data) {
    angular.copy(($scope.sel_data = data),$scope.$e);
  };

  // clear selected item object
  $scope.clear = function() {
    $scope.$e = {};
  };

  // add new
  $scope.add = function() {
    // structure and launch the API request
    $api.put({
        'add': $scope.$n
      },
      function(response) {
        // add info to scope
        $scope.data.splice(0,0, response);
      }
    );
  };

  $scope.getPayments = function(tax) {
    $api.put(
      {
        'id' : tax.id
      },
      function(data) {
        $scope.$e.payments = data || [];
      },
      'payments',
      'get'
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
        angular.copy(response || $scope.$e, $scope.sel_data);
      }
    );
  };

  // edit selected
  $scope.savePayments = function() {
    // structure and launch the API request
    $api.put({
        'tax_id': $scope.sel_data.id,
        'payments': $scope.$e.payments
      },
      function() {}
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
        for (var i in $scope.data)
          if ($scope.data[i] == $scope.sel_data)
            $scope.data.splice(i, 1);
      }
    );
  };
}];
