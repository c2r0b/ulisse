module.exports = ['$rootScope', '$location', ($rootScope, $location) => {
  return {
    restrict: 'E',
    replace: false,

    templateUrl: (elem, attrs) => {
      // retrieve panels shared with the entire app
      if (attrs.gType) {
        return '../templates/panels/' + attrs.gType + '.html';
      }
      // specific panel request
      var dir = attrs.pDir || $location.path().split("/")[1];
      return 'templates/' + dir + '/panels/' + attrs.pName + '.html'
    },

    link: (scope, elem, attrs) => {
      // copy attributes to gTypes panels scope
      if (attrs.gType)
        angular.extend(scope, attrs);

      // watch when to make this visible
      $rootScope.$watch('selectedPanel', function(n){
         elem.css({
          'display': ((n == attrs.pName) ? 'block' : 'none')
         });
      }, true);
    }
  };
}];
