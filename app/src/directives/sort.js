module.exports = () => {
  return {
    restrict: 'E',
    scope: {
      sortReverse: '=',
      sortType: '=',
      by: '@'
    },
    templateUrl: '../templates/sort.html'
  };
};
