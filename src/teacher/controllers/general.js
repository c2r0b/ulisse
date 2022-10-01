module.exports = ['$scope', '$api', ($scope, $api) => {
  // init var
  $scope.$e = {};
  $scope.$n = {};

  // get data
  $api.get({}, data => {
    // interpret dates as objects for inputs if the response is an array
    if (data[0])
      data.forEach(e => { e.date = new Date(e.date) });

    // bind API response data to scope
    $scope.data = data || [];
  });

  // select to remember in actions
  $scope.select = data => {
    // copy data to scope
    angular.copy(($scope.sel_data = data), $scope.$e);
  };

  // clear selected item object
  $scope.clear = () => {
    $scope.$e = {};
  };

  // add new
  $scope.add = () => {
    // structure and launch the API request
    $api.put(
      {
        'cmd' : 'add',
        'el'  : $scope.$n
      },
      response => {
        // add info to scope
        $scope.data.splice(0,0, response);
        // clear input values
        $scope.$n = {};
      }
    );
  };

  // edit selected
  $scope.edit = () => {
    // structure and launch the API request
    $api.put(
      {
        'cmd' : 'edit',
        'el'  : $scope.$e
      },
      response => angular.copy(response || $scope.$e, $scope.sel_data)
    );
  };

  // remove
  $scope.remove = () => {
    console.log($scope.$e);
    // structure and launch the API request
    $api.put(
      {
        'cmd' : 'remove',
        'el'  : $scope.$e
      },
      () => {
        // remove from scope
        for (var i in $scope.data)
          if ($scope.data[i] == $scope.sel_data)
            $scope.data.splice(i, 1);
      }
    );
  };
  $scope.removeSelected = () => {
    for (let i of $scope.data) {
      if (i.selected) {
        // structure and launch the API request
        $api.put(
          {
            'cmd' : 'remove',
            'el'  : i
          },
          (data) => {
            // remove from scope
            for (var j in $scope.data)
              if ($scope.data[j] == data)
                $scope.data.splice(j, 1);
          }
        );
      }
    }
  };
}];
