"use strict";
(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[5867],{

/***/ 84164:
/***/ ((module) => {



module.exports = gedcom
gedcom.displayName = 'gedcom'
gedcom.aliases = []
function gedcom(Prism) {
  Prism.languages.gedcom = {
    'line-value': {
      // Preceded by level, optional pointer, and tag
      pattern: /(^\s*\d+ +(?:@\w[\w!"$%&'()*+,\-./:;<=>?[\\\]^`{|}~\x80-\xfe #]*@ +)?\w+ +).+/m,
      lookbehind: true,
      inside: {
        pointer: {
          pattern: /^@\w[\w!"$%&'()*+,\-./:;<=>?[\\\]^`{|}~\x80-\xfe #]*@$/,
          alias: 'variable'
        }
      }
    },
    tag: {
      // Preceded by level and optional pointer
      pattern: /(^\s*\d+ +(?:@\w[\w!"$%&'()*+,\-./:;<=>?[\\\]^`{|}~\x80-\xfe #]*@ +)?)\w+/m,
      lookbehind: true,
      alias: 'string'
    },
    level: {
      pattern: /(^\s*)\d+/m,
      lookbehind: true,
      alias: 'number'
    },
    pointer: {
      pattern: /@\w[\w!"$%&'()*+,\-./:;<=>?[\\\]^`{|}~\x80-\xfe #]*@/,
      alias: 'variable'
    }
  }
}


/***/ })

}]);
//# sourceMappingURL=react-syntax-highlighter_languages_refractor_gedcom.js.map