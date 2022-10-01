module.exports = ['$rootScope', '$account', ($rootScope, $account) => {
  // init panels manager
  $rootScope.selectedPanel = '';

  return $account.getData().then((data) => {
    return {
      // account info
      INFO: data,

      // logout function
      logout: $account.logout,

      // range of values
      range: n => new Array(n),

      // today date
      today: new Date().toJSON().slice(0, 10),

      // panels interaction
      showPanel: (p = false) => $rootScope.selectedPanel = p,
      closePanel: () => $rootScope.selectedPanel = false,
      isPanelSelected: p => {
        return ($rootScope.selectedPanel == p);
      },
      selectedPanel: () => {
        return $rootScope.selectedPanel;
      }
    }
  });
}];
