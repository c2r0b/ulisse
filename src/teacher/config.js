module.exports = ['$routeProvider', '$translateProvider',
  ($routeProvider, $translateProvider) => {

    // translation init
    $translateProvider.preferredLanguage('it');
    $translateProvider.useSanitizeValueStrategy('sanitizeParameters');

    //routing
    $routeProvider.when('/students',
      {
        templateUrl: 'templates/students/index.html',
        controller: 'studentsCtrl'
      }
    ).when('/signatures',
      {
        templateUrl: 'templates/signatures/index.html',
        controller: 'signaturesCtrl'
      }
    ).when('/absences',
      {
        templateUrl: 'templates/absences/index.html',
        controller: 'absencesCtrl'
      }
    ).when('/marks',
      {
        templateUrl: 'templates/marks/index.html',
        controller: 'marksCtrl'
      }
    ).when('/tests',
      {
        templateUrl: 'templates/tests/index.html',
        controller: 'testsCtrl'
      }
    ).when('/schedule',
      {
        templateUrl: 'templates/schedule/index.html',
        controller: 'scheduleCtrl'
      }
    ).when('/meetings',
      {
        templateUrl: 'templates/meetings/index.html',
        controller: 'meetingsCtrl'
      }
    ).when('/desks-disposal',
      {
        templateUrl: 'templates/desks-disposal/index.html',
        controller: 'desksDisposalCtrl'
      }
    ).when('/:pageId',
      {
        templateUrl: params => 'templates/' + params.pageId + '/index.html',
        controller: 'generalCtrl'
      }
    ).otherwise({ redirectTo:'/dashboard' });
  }
];
