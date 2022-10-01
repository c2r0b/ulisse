module.exports = () => {
  return (input) => {
    return parseInt((input - input % 1) + ((input % 1 > .50) ? 1 : 0));
  };
};
