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
app.filter('average', require('../filters/average'));

// directives
app.directive('formatDate', require('../directives/formatDate.js'));
app.directive('tooltip', require('../directives/tooltip.js'));
app.directive('icn', require('../directives/icn.js'));
app.directive('noData', require('../directives/noData.js'));
app.directive('sort', require('../directives/sort.js'));
app.directive('draggable', require('../directives/draggable.js'));
app.directive('panel', require('../directives/panel.js'));
app.directive('confirmRemoval', require('../directives/confirmRemoval.js'));
app.directive('multiSelect', require('../directives/multiSelect.js'));
app.directive('menuLi', require('../directives/menuLi.js'));

// services
app.factory('$api', require('../services/api.js'));

// aside user menu controller
app.controller('mainCtrl', require('./controllers/main'));
app.controller('adminsCtrl', require('./controllers/admins'));
app.controller('classesCtrl', require('./controllers/classes'));
app.controller('generalCtrl', require('./controllers/general'));
app.controller('meetingsCtrl', require('./controllers/meetings'));
app.controller('studentsCtrl', require('./controllers/students'));
app.controller('taxesCtrl', require('./controllers/taxes'));
app.controller('teachersCtrl', require('./controllers/teachers'));
