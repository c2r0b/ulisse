module.exports = () => {
  return {
    restrict : 'A',
    scope : {
      ngModel : '='
    },
    link: (scope) => {
      if (scope.ngModel) scope.ngModel = new Date(scope.ngModel || '');
    }
  }
};
