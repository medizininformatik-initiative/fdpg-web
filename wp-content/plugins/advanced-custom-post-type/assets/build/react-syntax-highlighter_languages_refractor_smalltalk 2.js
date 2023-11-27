"use strict";
(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[2822],{

/***/ 186:
/***/ ((module) => {



module.exports = smalltalk
smalltalk.displayName = 'smalltalk'
smalltalk.aliases = []
function smalltalk(Prism) {
  Prism.languages.smalltalk = {
    comment: /"(?:""|[^"])*"/,
    string: /'(?:''|[^'])*'/,
    symbol: /#[\da-z]+|#(?:-|([+\/\\*~<>=@%|&?!])\1?)|#(?=\()/i,
    'block-arguments': {
      pattern: /(\[\s*):[^\[|]*\|/,
      lookbehind: true,
      inside: {
        variable: /:[\da-z]+/i,
        punctuation: /\|/
      }
    },
    'temporary-variables': {
      pattern: /\|[^|]+\|/,
      inside: {
        variable: /[\da-z]+/i,
        punctuation: /\|/
      }
    },
    keyword: /\b(?:nil|true|false|self|super|new)\b/,
    character: {
      pattern: /\$./,
      alias: 'string'
    },
    number: [
      /\d+r-?[\dA-Z]+(?:\.[\dA-Z]+)?(?:e-?\d+)?/,
      /\b\d+(?:\.\d+)?(?:e-?\d+)?/
    ],
    operator: /[<=]=?|:=|~[~=]|\/\/?|\\\\|>[>=]?|[!^+\-*&|,@]/,
    punctuation: /[.;:?\[\](){}]/
  }
}


/***/ })

}]);
//# sourceMappingURL=react-syntax-highlighter_languages_refractor_smalltalk.js.map