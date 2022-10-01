'use strict';

module.exports = ['$scope', '$api', function($scope, $api) {
  // get data
  $api.get({},function(data) {
    // init array for each day
    for (var i = 0; i < 12; i++)
      $scope.data[i] = [];
    // insert schedule in data matrix
    for(var i in data)
      $scope.data[data[i].day][data[i].hour] = data;
  });
}];
