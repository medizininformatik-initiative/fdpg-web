"use strict";
(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[5539],{

/***/ 6217:
/***/ ((module) => {



module.exports = brainfuck
brainfuck.displayName = 'brainfuck'
brainfuck.aliases = []
function brainfuck(Prism) {
  Prism.languages.brainfuck = {
    pointer: {
      pattern: /<|>/,
      alias: 'keyword'
    },
    increment: {
      pattern: /\+/,
      alias: 'inserted'
    },
    decrement: {
      pattern: /-/,
      alias: 'deleted'
    },
    branching: {
      pattern: /\[|\]/,
      alias: 'important'
    },
    operator: /[.,]/,
    comment: /\S+/
  }
}


/***/ })

}]);
//# sourceMappingURL=react-syntax-highlighter_languages_refractor_brainfuck.js.map