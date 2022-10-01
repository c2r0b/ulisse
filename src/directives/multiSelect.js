module.exports = ['$filter','$api', ($filter, $api) => {
  return {
    restrict: 'EA',
    scope : {
      suggestionsArr : '=',
      modelArr : '=ngModel',
      apiUrl : '@'
    },
    templateUrl: '../templates/multiple.html',
    link : (scope, element, attr) => {

      scope.objectProperty = attr.objectProperty;
      scope.selectedItemIndex = 0;
      scope.name = attr.name;
      scope.isRequired = attr.required;
      scope.errMsgRequired = attr.errMsgRequired;
      scope.placeholder = attr.placeholder;
      scope.optionPlaceholder = attr.optionPlaceholder;
      scope.allowDuplicates = attr.allowDuplicates;
      scope.fillableOption = attr.fillableOption;
      scope.isHover = false;
      scope.isFocused = false;
      scope.selectedItemIndex = undefined;
    },
    controller: ['$scope', (scope) => {

      var getSuggestionsList = () => {
        var url = scope.apiUrl;
        $api.get(
          {},
          (response) => {
            scope.suggestionsArr = response;
          },
          url
        );
      };

      if(scope.suggestionsArr == null || scope.suggestionsArr == "")
        if(scope.apiUrl != null && scope.apiUrl != "")
            getSuggestionsList();

      if(scope.modelArr == null || scope.modelArr == "")
          scope.modelArr = [];

      scope.onFocus = () => { scope.isFocused = true };

      scope.onMouseEnter = () => { scope.isHover = true };

      scope.onMouseLeave = () => { scope.isHover = false };

      scope.onBlur = () => { scope.isFocused = false };

      scope.keyParser = ($event) => {
        var keys = {
          38: 'up',
          40: 'down',
          8 : 'backspace',
          13: 'enter',
          9 : 'tab',
          27: 'esc'
        };
        var key = keys[$event.keyCode];
        if(key == 'backspace' && scope.inputValue == ""){
          if(scope.modelArr.length != 0)
            scope.modelArr.pop();
        }
        else if (key == 'down'){
          var filteredSuggestionArr = $filter('filter')(scope.suggestionsArr, scope.inputValue);
          filteredSuggestionArr = $filter('filter')(filteredSuggestionArr, scope.alreadyAddedValues);
          if(scope.selectedItemIndex < filteredSuggestionArr.length -1)
            scope.selectedItemIndex++;
        }
        else if (key == 'up' && scope.selectedItemIndex > 0){
          scope.selectedItemIndex--;
        }
        else if (key == 'esc'){
          scope.isHover = false;
          scope.isFocused = false;
        }
      };

      scope.addItem = () => {
        if(scope.selectedItemIndex != undefined && scope.inputValue != undefined) {
          // init array if undefined
          if (!scope.modelArr) scope.modelArr = [];
          // add item to selected list
          scope.modelArr.push(scope.inputValue);
          // clear input
          scope.inputValue = undefined;
          scope.inputOption = "";
          scope.selectedItemIndex = undefined;
        }
      };

      scope.selectItem = () => {
        var filteredSuggestionArr = $filter('filter')(scope.suggestionsArr, scope.inputValue);
        filteredSuggestionArr = $filter('filter')(filteredSuggestionArr, scope.alreadyAddedValues);
        scope.inputValue = filteredSuggestionArr[scope.selectedItemIndex];
        scope.isHover = scope.isFocused = false;
      };

      var isDuplicate = (arr, item) => {
        var duplicate = false;

        if(arr == null || arr == "")
            return duplicate;

        for(var i = 0;i < arr.length; i++)
          if((duplicate = angular.equals(arr[i], item)))
            break;

        return duplicate;
      };

      scope.alreadyAddedValues = (item) => {
        var isAdded = true;
        isAdded = !isDuplicate(scope.modelArr, item);
        return isAdded;
      };

      scope.removeAddedValues = (item) => {
        if(scope.modelArr != null && scope.modelArr != "") {
          var itemIndex = scope.modelArr.indexOf(item);
          if (itemIndex != -1)
              scope.modelArr.splice(itemIndex, 1);
        }
      };

      scope.mouseEnterOnItem = (index) => {
        scope.selectedItemIndex = index;
      };
    }]
  };
}];
