module.exports = () => {
  return {
    restrict: 'E',
    template: (elem, attrs) => {
      return "<panel g-type='confirm' p-name='remove' title='remove'" +
        "msg='" + attrs.msg + "' " +
        (attrs.acceptPanel ? "accept-panel='" + attrs.acceptPanel + "'" : "") +
        (attrs.denyPanel ? "deny-panel='" + attrs.denyPanel + "'" : "") +
        "callback='remove()'></panel>";
    }
  };
};
