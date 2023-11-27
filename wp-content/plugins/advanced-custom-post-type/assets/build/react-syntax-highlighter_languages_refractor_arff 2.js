"use strict";
(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[1438],{

/***/ 15472:
/***/ ((module) => {



module.exports = arff
arff.displayName = 'arff'
arff.aliases = []
function arff(Prism) {
  Prism.languages.arff = {
    comment: /%.*/,
    string: {
      pattern: /(["'])(?:\\.|(?!\1)[^\\\r\n])*\1/,
      greedy: true
    },
    keyword: /@(?:attribute|data|end|relation)\b/i,
    number: /\b\d+(?:\.\d+)?\b/,
    punctuation: /[{},]/
  }
}


/***/ })

}]);
//# sourceMappingURL=react-syntax-highlighter_languages_refractor_arff.js.map