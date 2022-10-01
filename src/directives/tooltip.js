module.exports = ['$filter', ($filter) => {
  return {
    restrict: 'E',
    replace: true,
    template: (elem,attrs) => {
      return '<div class="tooltip" title="' +
        $filter('translate')(attrs.t) + '"></div>'
    }
  };
}];
