'use strict';

module.exports = ['$scope', '$api', '$filter', function($scope, $api, $filter) {
  // get data
  $api.get({},function(data) {
    $scope.data = data;
  });

  // select class to remember in actions
  $scope.select = function(data) {
    angular.copy(($scope.sel_data = data),$scope.$e);
  };
  // select student of class to remember in actions
  $scope.selectStudent = function(data) {
    $scope.sel_student = data;
  };
  // select subject of class to remember in actions
  $scope.selectSubject = function(data) {
    $scope.editedSubject = angular.copy($scope.sel_subject = data);
  };
  // clear input
  $scope.clear = function() {
    $scope.$n = {};
  };

  $scope.getClassInfo = function(el) {
    $api.put(
      {
        'id' : el.id
      },
      function(data) {
        $scope.$e = data || {};
      },
      'class',
      'get'
    );
  };

  $scope.getStudents = function(el) {
    $api.put(
      {
        'id' : el.id
      },
      function(data) {
        angular.copy(el, $scope.$e);
        $scope.$e.students = data || [];
      },
      'classStudents',
      'get'
    );
  };

  $scope.getSchoolStudents = function() {
    $api.put(
      {
        'id' : $scope.sel_data.id
      },
      function(data) {
        $scope.$e.schoolStudents = data || [];
      },
      'classSchoolStudents',
      'get'
    );
  };

  $scope.getSchoolTeachers = function() {
    $api.get({},
      function(data) {
        $scope.$e.schoolTeachers = data || [];
      },
      'teachersList'
    );
  };

  $scope.getSubjects = function(el) {
    $api.put(
      {
        'id' : el.id
      },
      function(data) {
        angular.copy(el, $scope.$e);
        $scope.$e.subjects = data || {};
      },
      'classSubjects',
      'get'
    );
  };

  // add new
  $scope.add = function() {
    // structure and launch the API request
    $api.put(
      {
        'add': $scope.$n
      },
      function(response) {
        // add info to scope
        $scope.data.classes.splice(0,0, response);
      }
    );
  };

  // add new student to class
  $scope.addStudent = function() {
    var req = {
      'class' : $scope.$e.id,
      'student': $scope.$e.selStudToBeAddedToClass
    };
    // structure and launch the API request
    $api.put(
      {
        'add': req
      },
      function(response) {
        // add info to scope
        $scope.$e.students.splice(0,0, response);
      },
      'classStudents',
      'action'
    );
  };

  // add new subject to class
  $scope.addSubject= function() {
    var req = {
      'class' : $scope.$e.id,
      'teacher': $scope.$e.newSubject.teacher,
      'subject': $scope.$e.newSubject.name
    };
    // structure and launch the API request
    $api.put(
      {
        'add': req
      },
      function(response) {
        // add info to scope
        $scope.$e.subjects.splice(0,0, response);
      },
      'classSubjects',
      'action'
    );
    // clear input
    $scope.$e.newSubject = {};
  };

  // edit selected
  $scope.edit = function() {
    // structure and launch the API request
    $api.put(
      {
        'edit': $scope.$e
      },
      function(reponse) {
        // edit info in scope
        angular.copy(reponse, $scope.sel_data);
      }
    );
  };

  // edit selected subject
  $scope.editSubject = function() {
    var req = {
      'class_id' : $scope.$e.id,
      'teacher_id': $scope.editedSubject.teacher_id,
      'subject_id': $scope.sel_subject.id,
      'subject_name': $scope.editedSubject.name
    };
    // structure and launch the API request
    $api.put(
      {
        'edit': req
      },
      function(reponse) {
        // edit info in scope
        angular.copy(reponse, $scope.sel_subject);
      },
      'classSubjects',
      'action'
    );
  };

  // remove class
  $scope.remove = function() {
    // structure and launch the API request
    $api.put(
      {
        'remove': $scope.$e
      },
      function() {
        // remove from scope
        for (var i in $scope.data.classes)
          if ($scope.data.classes[i] == $scope.sel_data)
            $scope.data.classes.splice(i, 1);
      }
    );
  };
  // remove selected classes
  $scope.removeSelected = function() {
    $('.check input.selection').each(function() {
      if ($(this).is(':checked')) {
        var id = $(this).attr('rel');
        // structure and launch the API request
        $api.put(
          {
            'remove': {'id' : id}
          },
          function() {
            // remove from scope
            for (var i in $scope.data.classes)
              if ($scope.data.classes[i].id == id)
                $scope.data.classes.splice(i, 1);
          }
        );
      }
    });
  };
  // remove student from class
  $scope.removeStudent = function() {
    var req = {
      'class' : $scope.$e.id,
      'student': $scope.sel_student.id
    };
    // structure and launch the API request
    $api.put(
      {
        'remove': req
      },
      function() {
        // remove from scope
        for (var i in $scope.$e.students)
          if ($scope.$e.students[i] == $scope.sel_student)
            $scope.$e.students.splice(i, 1);
      },
      'classStudents',
      'action'
    );
  };
  // remove student from class
  $scope.removeSubject = function() {
    var req = {
      'class' : $scope.$e.id,
      'subject': $scope.sel_subject.id
    };
    // structure and launch the API request
    $api.put(
      {
        'remove': req
      },
      function() {
        // remove from scope
        for (var i in $scope.$e.subjects)
          if ($scope.$e.subjects[i] == $scope.sel_subject)
            $scope.$e.subjects.splice(i, 1);
      },
      'classSubjects',
      'action'
    );
  };
}];
