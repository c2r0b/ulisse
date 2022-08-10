'use strict';

module.exports = ['$scope', '$api', '$filter', function($scope, $api, $filter) {
  $scope.marks = {};
  $scope.absences = {};

  // graph options
  $scope.marks.options = {
    margin: {top: 5},
    legend: false,
    series: [],
    axes: {
      x: {
        key: 'x',
        type: 'date',
        label: ''
      },
      y: {
        min: 0,
        max: 10
      }
    }
  };
  $scope.marks.data = {};

  // get data
  $api.get(
    {},
    function(response) {
      // parse each subject
      for (var i in response) {
        var r = response[i];
        // subject graph info
        $scope.marks.options.series.push(
          {
            axis: 'y',
            dataset: 'dataset'+r.id,
            key: 'val',
            label: r.name,
            color: "#"+Math.random().toString(16).slice(2,8).toUpperCase(),
            type: ['line'],
            id: r.id
          }
        );
        // parse each subject mark
        $scope.marks.data['dataset'+r.id] = [];
        for (var j in r.marks) {
          $scope.marks.data['dataset'+r.id].push({x: new Date(r.marks[j].date), val: r.marks[j].mark});
        }
      }
    },
    'marks'
  );
}];
