module.exports = ['$scope', '$api', ($scope, $api) => {
  // init scope var
  $scope.data = [];
  $scope.sel_student;
  var sel_absence;
  $scope.sort = ['name',false];
  $scope.info = {};
  $scope.randomStudent = null;

  // UI editable var
  var d = new Date();
  $scope.hour = d.getHours();
  $scope.minutes = parseInt(d.getMinutes() / 5) * 5;
  $scope.from_day = "";
  $scope.to_day = "";
  $scope.justification = "";

  // get data data
  $api.get({}, data => {
      $scope.data = data || [];
  });

  // random student
  $scope.getRandomStudent = () => {
    var rand;
    // get new random index different from the previous one
    do
      rand = Math.floor(Math.random() * ($scope.data.length));
    while(
      rand == $scope.randomStudent
      && $scope.randomStudent != null
      && $scope.data.length > 1
    );
    // assign new random index to scope
    $scope.randomStudent = rand;
  };

  // select a student to remember in actions
  $scope.selectStudent = student => {
    $scope.sel_student = student;
    // get student absences if you don't already have them
    if (!$scope.sel_student.absences) {
      $api.put(
        {
          'id' : student.id
        },
        data => {
          $scope.sel_student.absences = data || [];
        },
        'absencesList',
        'get'
      );
    }
  };

  // get student information
  $scope.getInfo = student => {
    // remember selected student
    $scope.sel_student = student;
    // request student info if not already in scope
    if (!$scope.sel_student.info) {
      // request to get student additional data
      $api.get(
        {
          'id': student.id
        },
        data => {
          // success: student data to scope panel
          $scope.sel_student.info = data;
        },
        'student-info'
      );
    }
  };

  // get student information
  $scope.getContacts = student => {
    // remember selected student
    $scope.sel_student = student;
    // request student info if not already in scope
    if (!$scope.sel_student.contacts) {
      // request to get student additional data
      $api.get(
        {
          'id': student.id
        },
        data => {
          // success: student data to scope panel
          $scope.sel_student.contacts = data;
        },
        'student-contacts'
      );
    }
  };
}];
