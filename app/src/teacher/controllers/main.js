// jquery
var $ = require('jquery-browserify');

module.exports = ['$scope', '$api', '$basic', ($scope, $api, $basic) => {
  // main functions and values
  $basic.then(response => angular.extend($scope, response));

  // buttons that close the panels
  $(document).on('click', '.close', function() {
    $(this).closest('panel').css('display', 'none');
    $scope.showPanel();
  });

  // get teacher classes
  $api.get(
    {},
    data => $scope.teacherClasses = data || [],
    'classes'
  );

  // select class
  $scope.selectClass = item => {
    // structure and launch the API request
    $api.put(
      {
        class: item.class_id,
        subject: item.subject_id
      },
      response => {
        $scope.INFO.class = response.class;
        $scope.INFO.subject = response.subject;
      },
      'change-class'
    );
  };

  // disconnect
  $scope.disconnectClass = () => {
    $api.put(
      {},
      () => {
        $scope.INFO.class = null;
        $scope.INFO.subject = null;
      },
      'change-class'
    );
  };
}];
