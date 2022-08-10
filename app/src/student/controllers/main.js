'use strict';

module.exports = ['$scope', '$api', '$basic', function($scope, $api, $basic) {
  // main functions and values
  $basic.then(function(response) {
    angular.extend($scope, response);
  });

  // get student classes
  $api.get(
    {},
    function(data) {
      $scope.studentClasses = data || [];
    },
    'classes'
  );

  // select class
  $scope.selectClass = function(item) {
    // structure and launch the API request
    $api.put(
      {
        'class': item.class_id
      },
      function(response) {
        $scope.INFO.class = response.class;
      },
      'change-class'
    );
  };

  // disconnect
  $scope.disconnectClass = function() {
    $api.put(
      {},
      function() {
        $scope.INFO.class = null;
      },
      'change-class'
    );
  };
}];
