'use strict';

module.exports = ['$routeProvider', '$translateProvider',
  function($routeProvider, $translateProvider) {
    // translation init
    $translateProvider.preferredLanguage('it');
    $translateProvider.useSanitizeValueStrategy('sanitizeParameters');
    //routing
    $routeProvider.when('/select-class',
      {
        templateUrl: 'templates/select-class/index.html',
        controller: 'selectClassCtrl',
      }
    ).when('/schedule',
      {
        templateUrl: 'templates/schedule/index.html',
        controller: 'scheduleCtrl',
      }
    ).when('/settings',
      {
        templateUrl: 'templates/settings/index.html',
        controller: 'settingsCtrl',
      }
    ).when('/analytics',
      {
        templateUrl: 'templates/analytics/index.html',
        controller: 'analyticsCtrl',
      }
    ).when('/:pageId',
      {
        templateUrl: function(params) {
          return 'templates/' + params.pageId + '/index.html';
        },
        controller: 'generalCtrl',
      }
    ).otherwise({redirectTo:'/dashboard'});
  }
];
