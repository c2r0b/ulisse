module.exports = ['$scope', '$api', '$filter', ($scope, $api, $filter) => {
  // init var
  $scope.$e = {}; $scope.$n = {};
  $scope.marks = require('../marks');
  
  // symbols for decimal part in readable marks to display
  $scope.symbols = require('../symbols');

  // get data data
  $api.get({}, data => {
    $scope.data = data;
  });

  // select student to add new mark
  $scope.selectForNew = student => {
  	$scope.sel_student = student;
  	$scope.$n = { 'type': 0 };
  };

  // select student and mark to remember in actions
  $scope.select = (student, mark) => {
    // parse values
    angular.extend(mark,
      {
        vote: $filter('toInt')(mark.mark),
        symbol: $filter('decimalPart')(mark.mark),
        date: new Date(mark.date)
      }
    );
    // copy data to scope
  	angular.copy(mark, $scope.$e);

  	$scope.sel_student = student;
  	$scope.sel_mark = mark;
  };

  // add new mark
  $scope.add = () => {
    // resolve issue for marks with - sign (n- => (n-1).75)
    if ($scope.$n.symbol == 75) $scope.$n.vote--;
    // construct mark with the integer part and the decimal one
    $scope.$n.mark = $scope.$n.vote + (($scope.$n.symbol || 0) * .01);
    // remove unnecessary info
    delete $scope.$n.vote;
    delete $scope.$n.symbol;
    // structure and launch the API request
    $api.put(
      {
        'cmd' : 'add',
        'student_id': $scope.sel_student.id,
        'mark' : $scope.$n
      },
      response => {
        // create the marks array if it doesn't exist already
        if (!$scope.sel_student.marks) $scope.sel_student.marks = [];
        // save the new mark
        $scope.sel_student.marks.splice(0, 0, response);
      }
    );
  };

  // edit mark
  $scope.edit = () => {
    // resolve issue for marks with - sign (n- => (n-1).75)
    if ($scope.$e.symbol == 75) $scope.$e.vote--;
    // construct mark with the integer part and the decimal one
    $scope.$e.mark = parseFloat($scope.$e.vote + ($scope.$e.symbol * .01));
    // remove unnecessary info
    delete $scope.$e.vote;
    delete $scope.$e.symbol;
    // structure and launch the API request
    $api.put(
      {
        'cmd' : 'edit',
        'student_id': $scope.sel_student.id,
        'mark' : $scope.$e
      },
      () => angular.copy($scope.$e, $scope.sel_mark)
    );
  };

  // edit a mark from a test
  $scope.editTestMark = () => {
    // resolve issue for marks with - sign (n- => (n-1).75)
    if ($scope.$e.symbol == 75) $scope.$e.vote--;
    // construct mark with the integer part and the decimal one
    $scope.$e.mark = parseFloat($scope.$e.vote + ($scope.$e.symbol * .01));
    // remove unnecessary info
    delete $scope.$e.vote;
    delete $scope.$e.symbol;
    // structure and launch the API request
    $api.put(
      {
        'cmd' : 'editTestMark',
        'student_id': $scope.sel_student.id,
        'mark' : $scope.$e
      },
      () => angular.copy($scope.$e, $scope.sel_mark)
    );
  };

  // remove mark
  $scope.remove = () => {
    // structure and launch the API request
    $api.put(
      {
        'cmd' : 'remove',
        'mark_id': $scope.$e.id,
        'student_id' : $scope.sel_student.id
      },
      () => {
        for (var i in $scope.sel_student.marks) {
          if($scope.sel_student.marks[i].id == $scope.$e.id) {
             $scope.sel_student.marks.splice(i,1);
             return;
          }
        }
      }
    );
  };
}];
