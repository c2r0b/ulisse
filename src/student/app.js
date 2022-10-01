'use strict';

// student app declaration
var app = angular.module(
  'ulisse',
  [
    'ngRoute',
    'pascalprecht.translate',
    //'n3-line-chart',
    'ulisseTranslations'
  ]
);

// routing
app.config(require('./config.js'));

// filters
app.filter('toInt', require('../filters/toInt'));
app.filter('decimalPart', require('../filters/decimalPart'));
app.filter('readableMark', require('../filters/readableMark'));
app.filter('average', require('../filters/average'));

// directives
app.directive('formatDate', require('../directives/formatDate.js'));
app.directive('tooltip', require('../directives/tooltip.js'));
app.directive('icn', require('../directives/icn.js'));
app.directive('noData', require('../directives/noData.js'));
app.directive('sort', require('../directives/sort.js'));
app.directive('panel', require('../directives/panel.js'));
app.directive('menuLi', require('../directives/menuLi.js'));

// services
app.factory('$api', require('../services/api.js'));

// controllers
app.controller('mainCtrl', require('./controllers/main'));
app.controller('analyticsCtrl', require('./controllers/analytics'));
app.controller('generalCtrl', require('./controllers/general'));
app.controller('scheduleCtrl', require('./controllers/schedule'));
app.controller('settingsCtrl', require('./controllers/settings'));
