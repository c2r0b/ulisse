// jquery
var $ = require('jquery-browserify');
require('jquery-ui-browserify');

module.exports = () => {
  return {
    link: (scope, elem, attrs) => {
      elem.draggable();
    }
  };
};
