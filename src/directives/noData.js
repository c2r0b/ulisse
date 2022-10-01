module.exports = function() {
  return {
    restrict: 'E',
    template: (elem,attrs) => {
       return '<h1 class="none" translate="nothing-available"></h1>'
    }
  };
};
