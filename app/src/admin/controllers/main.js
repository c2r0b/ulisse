module.exports = ['$scope', '$basic', function($scope, $basic) {
  // main functions and values
  $basic.then(function(response) {
    angular.extend($scope, response);
  });
}];
