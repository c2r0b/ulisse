'use strict';

module.exports = ['$scope', '$api', function($scope, $api) {
  // get data
  $api.get({},function(data) {
    $scope.data = data;
  });

  // select to remember in actions
  $scope.select = function(data) {
    angular.copy(($scope.sel_data = data),$scope.$e);
  };
  // clear input
  $scope.clear = function() {
    $scope.$n = {};
  };

  $scope.getAdminInfo = function(admin) {
    $api.put(
      {
        'id' : admin.id
      },
      function(data) {
        $scope.$e = data || {};
        $scope.$e.surname = admin.surname;
        $scope.$e.name = admin.name;
          console.log($scope.$e);
      },
      'admin',
      'get'
    );
  };
  $scope.getPermissions = function(admin) {
    $api.put(
      {
        'id' : admin.id
      },
      function(data) {
        $scope.$e = data || {};
        $scope.$e.surname = admin.surname;
        $scope.$e.name = admin.name;
      },
      'permissions',
      'get'
    );
  };
  $scope.getCredentials = function(admin) {
    $api.put(
      {
        'id' : admin.id
      },
      function(data) {
        $scope.$e = data || {};
        $scope.$e.surname = admin.surname;
        $scope.$e.name = admin.name;
      },
      'adminCredentials',
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
        console.log(response);
        // add info to scope
        $scope.data.splice(0,0, response);
      }
    );
  };

  // edit selected
  $scope.edit = function() {
    // structure and launch the API request
    $api.put(
      {
        'edit': $scope.$e
      },
      function() {
        // edit info in scope
        angular.copy($scope.$e, $scope.sel_data);
      }
    );
  };

  // edit parents of selected admin
  $scope.editPermissions = function() {
    // structure and launch the API request
    $api.put(
      {
        'permissions': $scope.$e
      },
      function() {}
    );
  };

  // remove
  $scope.remove = function() {
    // structure and launch the API request
    $api.put(
      {
        'remove': $scope.$e
      },
      function() {
        // remove from scope
        for (var i in $scope.data)
          if ($scope.data[i] == $scope.sel_data)
            $scope.data.splice(i, 1);
      }
    );
  };
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
            for (var i in $scope.data)
              if ($scope.data[i].id == id)
                $scope.data.splice(i, 1);
          }
        );
      }
    });
  };
}];
