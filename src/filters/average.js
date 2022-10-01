module.exports = () => {
  return (input) => {
    if (!input) return '/';

    var sum = 0;
    for (var i in input)
      sum += parseFloat(input[i].mark);

    return (sum / input.length).toFixed(2);
  };
};
