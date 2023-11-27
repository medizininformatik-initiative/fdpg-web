"use strict";
(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[6495],{

/***/ 1132:
/***/ ((module) => {



module.exports = ini
ini.displayName = 'ini'
ini.aliases = []
function ini(Prism) {
  Prism.languages.ini = {
    comment: /^[ \t]*[;#].*$/m,
    selector: /^[ \t]*\[.*?\]/m,
    constant: /^[ \t]*[^\s=]+?(?=[ \t]*=)/m,
    'attr-value': {
      pattern: /=.*/,
      inside: {
        punctuation: /^[=]/
      }
    }
  }
}


/***/ })

}]);
//# sourceMappingURL=react-syntax-highlighter_languages_refractor_ini.js.map