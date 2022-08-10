module.exports = () => {
  return {
    restrict: 'E',
    replace: true,
    template: (elem, attr) => {
      return '<a href="#!/' + attr.rel + '"><li><icn n="' + attr.icn + '"></icn><span translate="' +
        attr.rel + '"></span></li></a>'
    }
  };
};
