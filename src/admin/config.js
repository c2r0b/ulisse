'use strict';

module.exports = ['$routeProvider', '$translateProvider',
  function($routeProvider, $translateProvider) {
    // translation init
    $translateProvider.preferredLanguage('it');
    $translateProvider.useSanitizeValueStrategy('sanitizeParameters');
    //routing
    $routeProvider.when('/students',
      {
        templateUrl: 'templates/students/index.html',
        controller: 'studentsCtrl'
      }
    ).when('/teachers',
      {
        templateUrl: 'templates/teachers/index.html',
        controller: 'teachersCtrl'
      }
    ).when('/admins',
      {
        templateUrl: 'templates/admins/index.html',
        controller: 'adminsCtrl'
      }
    ).when('/classes',
      {
        templateUrl: 'templates/classes/index.html',
        controller: 'classesCtrl'
      }
    ).when('/meetings',
      {
        templateUrl: 'templates/meetings/index.html',
        controller: 'meetingsCtrl'
      }
    ).when('/taxes',
      {
        templateUrl: 'templates/taxes/index.html',
        controller: 'taxesCtrl'
      }
    ).when('/:pageId',
      {
        templateUrl: function(params) {
          return 'templates/' + params.pageId + '/index.html';
        },
        controller: 'generalCtrl'
      }
    ).otherwise({redirectTo:'/dashboard'});
  }
];
