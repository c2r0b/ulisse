module.exports = ['$scope', '$api', ($scope, $api) => {
  // get data
  $api.get({}, data => {
    $scope.data = data || [];
  });

  // sign
  $scope.sign = () => $api.put(
    {},
    data => $scope.data.splice(0, 0, data)
  );
}];
