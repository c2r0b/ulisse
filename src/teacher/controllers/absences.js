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

  // select an absence to remember in actions
  $scope.selectAbsence = index => {
    sel_absence = index;
  };

  // toggle student is absent/present
  $scope.absentToggle = student => {
    // launch API request
    $api.put(
      {
        'cmd' : (student.isAbsent) ? 'remove' : 'add',
        'student_id' : student.id
      },
      () => {
        // toggle 'A' button
        student.isAbsent = !student.isAbsent;
      }
    );
  };

  // add an already justified absence
  $scope.justifiedAbsence = () => {
    // get selected student objects
    var $student = $scope.sel_student;
    // launch API request
    $api.put(
      {
        'cmd' : 'add',
        'student_id' : $student.id,
        'el' : {
          'type' : 3,
          'justified' : 1,
          'justification' : $scope.justification
        }
      },
      response => {
        // toggle 'A' button
        $student.isAbsent = !$student.isAbsent;
      }
    );
  };

  // remove absence
  $scope.removeAbsence = () => {
    // get selected student and absence objects
    var $student = $scope.sel_student;
    var $absence = $student.absences[sel_absence];
    // structure and launch the API request
    $api.put(
      {
        'cmd' : 'remove',
        'student_id' : $student.id,
        'el' : {
          'date' : $absence.date,
          'type' : $absence.type
        }
      },
      () => {
        // remove from scope
        $student.absences.splice(sel_absence,1);
        /*
          set student as not absent
          if he was and i've just deleted a full-day-absence / entrance
        */
        if ($student.isAbsent && $absence.type != 1)
          $student.isAbsent = false;
        // decrement absences counter
        $student.absencesCount--;
      }
    );
  };

  // justify absence
  $scope.justifyAbsence = () => {
    // get selected student and absence objects
    var $student = $scope.sel_student;
    var $absence = $student.absences[sel_absence];
    // structure and launch the API request
    $api.put(
      {
        'cmd' : 'justify',
        'student_id' : $student.id,
        'el' : {
          'date' : $absence.date,
          'type' : $absence.type,
          'justification' : $scope.justification
        }
      },
      () => {
        // set the new delays value to the studentin scope
        $student.absences.splice(sel_absence,1);
        /*
          set student as not absent
          if he was and i've just deleted a full-day-absence / entrance
        */
        if ($student.isAbsent && $absence.type != 1)
          $student.isAbsent = false;
        // decrement absences counter
        $student.absencesCount--;
      }
    );
  };

  // DELAY: add or remove N
  $scope.changeDelay = n => {
    // get selected student object
    var $student = $scope.sel_student;

    // structure and launch the API request
    $api.put(
      {
        'cmd' : 'delay',
        'student_id' : $student.id,
        'step' : n
      },
      function() {
        // calculate new number of delays
        var n_delays = parseInt($student.delays) + n;
        // set the new delays value to the studentin scope
        $student.delays = n_delays;
        // set student as not absent
        $student.isAbsent = false;
      }
    );
  };

  // ENTRANCE: add
  $scope.addEntrance = () => {
    // get selected student object
    var $student = $scope.sel_student;

    // structure and launch the API request
    var req = {
      'cmd' : 'add',
      'student_id' : $student.id,
      'el': {
        'type' : 1,
        'hour' : $scope.hour,
        'minutes' : $scope.minutes
      }
    };
    if ($scope.justify) {
      req['el']['justified'] = 1;
      req['el']['justification'] = $scope.justification;
    }
    $api.put(
      req,
      response => {
        // if absences array is empty initialize it
        if ($student.absences == "")
          $student.absences = [];
        // add entrance to student absences
        if (!$scope.justify) {
          $student.absences.splice(0,0, response);
          // increment absences counter
          $student.absencesCount++;
        }
        // set student as not absent
        $student.isAbsent = false;
      }
    );
  };

  // LEAVING: add
  $scope.addLeaving = () => {
    // get selected student object
    var $student = $scope.sel_student;

    // structure and launch the API request
    var req = {
      'cmd' : 'add',
      'student_id': $student.id,
      'el': {
        'type' : 2,
        'hour' : $scope.hour,
        'minutes' : $scope.minutes
      }
    };
    if ($scope.justify) {
      req['el']['justified'] = 1;
      req['el']['justification'] = $scope.justification;
    }
    $api.put(
      req,
      response => {
        // if absences array is empty initialize it
        if ($student.absences == "")
          $student.absences = [];
        // add leaving to student absences
        if (!$scope.justify) {
          $student.absences.splice(0,0, response);
          // increment absences counter
          $student.absencesCount++;
        }
        // set student as absent
        $student.isAbsent = true;
        // increment absences counter
        $student.absencesCount++;
      }
    );
  };

  // MULTIPLE REMOVE

  // MULTIPLE JUSTIFY
}];
