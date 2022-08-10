module.exports = ['$scope', '$api', ($scope, $api) => {
  $scope.data = [];
  // get data
  $api.get({}, data => {
    // init array for each day
    for (var i = 0; i < 12; i++)
      $scope.data[i] = [];
    // insert schedule in data matrix
    for(var i in data)
      $scope.data[data[i].day][data[i].hour] = data;
  });
}];
