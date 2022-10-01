module.exports = () => {
  return (input) => {
    return parseInt((input % 1) * 100);
  };
};
