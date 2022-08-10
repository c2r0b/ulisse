module.exports = ['$api', '$type', ($api, $type) => {
  return {
    // get data of a specific account type
    getData: () => {
      return $api.get(
        {},
        data => {
          // not logged in
          if (!data[$type]) window.location.replace('../');
          // get account info
          return data || {};
        },
        'account'
      );
    },

    // logout from the account
    logout: () => $api.put(
      {},
      () => window.location.replace('../login'),
      'logout',
      '../account/action'
    )
  }
}];
