// teacher app declaration
var app = angular.module(
  'ulisse',
  [
    'ngRoute',
    'pascalprecht.translate',
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
app.directive('draggable', require('../directives/draggable.js'));
app.directive('panel', require('../directives/panel.js'));
app.directive('confirmRemoval', require('../directives/confirmRemoval.js'));
app.directive('multiSelect', require('../directives/multiSelect.js'));
app.directive('menuLi', require('../directives/menuLi.js'));

// services
app.factory('$type', require('../services/type.js'));
app.factory('$api', require('../services/api.js'));
app.factory('$account', require('../services/account.js'));
app.factory('$basic', require('../services/basic.js'));

// controllers
app.controller('mainCtrl', require('./controllers/main'));
app.controller('generalCtrl', require('./controllers/general'));
app.controller('studentsCtrl', require('./controllers/students'));
app.controller('absencesCtrl', require('./controllers/absences'));
app.controller('marksCtrl', require('./controllers/marks'));
app.controller('meetingsCtrl', require('./controllers/meetings'));
app.controller('scheduleCtrl', require('./controllers/schedule'));
app.controller('settingsCtrl', require('./controllers/settings'));
app.controller('desksDisposalCtrl', require('./controllers/desks-disposal'));
app.controller('signaturesCtrl', require('./controllers/signatures'));
app.controller('testsCtrl', require('./controllers/tests'));
