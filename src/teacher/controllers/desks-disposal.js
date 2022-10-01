module.exports = ['$scope', '$api', ($scope, $api) =>{
  // get data
  $api.get({}, data => {
    $scope.data = data || [];
  });

  // save desks disposal
  $scope.save = () => {
    // parent position
    var parentOffset = $('#dndspace').offset();
    console.log(parentOffset);
    // update each desk position coordinates
    for (var index = 0; index < $scope.data.length; index++) {
      // get desk position
      var offset = $('.desk#'+$scope.data[index].student_id).offset();
      // update coordinates
      $scope.data[index].x = offset.left - parentOffset.left;
      $scope.data[index].y = offset.top - parentOffset.top;
    }
    console.log($scope.data);
    $api.put(
      $scope.data,
      () => {}
    );
  };
}];
