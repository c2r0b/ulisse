module.exports = () => {
  return {
    restrict: 'E',
    replace: true,
    template: (elem,attrs) => {
      return '<i class="fa fa-' + attrs.n + '" style="color:' +
        ((attrs.clr) ? attrs.clr : 'inherit') + '"></i>'
    }
  };
};
