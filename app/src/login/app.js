// teacher app declaration
var app = angular.module(
  'ulisse',
  [
    'pascalprecht.translate',
    'ulisseTranslations'
  ]
);

// translation init
app.config(['$translateProvider', $translateProvider => {
  $translateProvider.preferredLanguage('it');
  $translateProvider.useSanitizeValueStrategy('sanitizeParameters');
}]);

// services
app.factory('$type', require('../services/type.js'));
app.factory('$api', require('../services/api.js'));

// controllers
app.controller("mainCtrl", require('./controllers/main.js'));
