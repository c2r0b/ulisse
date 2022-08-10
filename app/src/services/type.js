module.exports = ['$location', ($location) => {
  var loc = '';

  // get current URL without routing info
  var url = $location.absUrl().split('#')[0];

  // search the URL for the type specifier
  ['student', 'teacher', 'admin'].forEach(t => {
    if (url.indexOf('/' + t.charAt(0) + '/') != -1)
      loc = t.replace('/', '');
  });

  return loc;
}];
