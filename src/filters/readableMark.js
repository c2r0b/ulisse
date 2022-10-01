module.exports = () => {
  return (input) => {
    if (input == null) return 0;

    if ((input = input.toString()).indexOf('.75') > -1)
      input = (parseInt(input.replace('.75',''))+1)+'-';

    input = input.replace('.25','+');
    input = input.replace('.5','½');

    return input;
  };
};
