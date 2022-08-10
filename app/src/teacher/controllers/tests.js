module.exports = ['$scope', '$api', '$filter', ($scope, $api, $filter) => {
  // init var
  $scope.$e = {}; $scope.$n = {};
  $scope.marks = require('../marks');
  // symbols for decimal part in readable marks to display
  $scope.symbols = require('../symbols');

  // get data
  $api.get({}, data => {
    $scope.data = data || [];
  });

  // select test to remember in actions
  $scope.select = data => {
    // parse values
    angular.extend(data,
      {
        date: new Date(data.date)
      }
    );
    // copy data to scope
    angular.copy(($scope.sel_data = data), $scope.$e);
  };

  // select student to remember in actions
  $scope.selectStudent = student => {
    // init objects
    $scope.$v = {};
    $scope.sel_student = {};
    // copy data to scope to edit
    angular.copy(($scope.sel_student = student), $scope.$v);
    // get splitted mark data
    $scope.$v.vote = $filter('toInt')($scope.$v.mark);
    $scope.$v.symbol = $filter('decimalPart')($scope.$v.mark);
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
      // edit info in scope
      response => angular.copy(response || $scope.$e, $scope.sel_data)
    );
  };

  // remove
  $scope.remove = () => {
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

  // save test edited mark
  $scope.saveMark = () => {
    // resolve issue for marks with - sign (n- => (n-1).75)
    if ($scope.$v.symbol == 75) $scope.$v.vote--;
    // construct mark with the integer part and the decimal one
    $scope.$v.mark = $scope.$v.vote + (($scope.$v.symbol || 0) * .01);
    // remove unnecessary info
    delete $scope.$v.vote;
    delete $scope.$v.symbol;
    // structure and launch the API request
    $api.put(
      {
        'cmd'     : 'save',
        'test'    : $scope.$e,
        'student' : $scope.$v
      },
      // update info in scope
      () => angular.copy($scope.$v, $scope.sel_student),
      'test-results'
    );
  };

  // remove test mark
  $scope.removeMark = () => {
    // structure and launch the API request
    $api.put(
      {
        'cmd'     : 'remove',
        'test'    : $scope.$e,
        'student' : $scope.$v
      },
      () => {
        // update info in scope
        $scope.sel_student.mark = null;
      },
      'test-results'
    );
  };

  // get test results
  $scope.getResults = test => {
    // structure and launch the API request
    $api.get(
      {
        'test': test.id
      },
      response => {
        $scope.$e.students = response;
      },
      'test-results'
    );
  };
}];
