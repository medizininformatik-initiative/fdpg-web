"use strict";
(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[6749],{

/***/ 23478:
/***/ ((module) => {



module.exports = hpkp
hpkp.displayName = 'hpkp'
hpkp.aliases = []
function hpkp(Prism) {
  /**
   * Original by Scott Helme.
   *
   * Reference: https://scotthelme.co.uk/hpkp-cheat-sheet/
   */
  Prism.languages.hpkp = {
    directive: {
      pattern: /\b(?:(?:includeSubDomains|preload|strict)(?: |;)|pin-sha256="[a-zA-Z\d+=/]+"|(?:max-age|report-uri)=|report-to )/,
      alias: 'keyword'
    },
    safe: {
      pattern: /\d{7,}/,
      alias: 'selector'
    },
    unsafe: {
      pattern: /\d{1,6}/,
      alias: 'function'
    }
  }
}


/***/ })

}]);
//# sourceMappingURL=react-syntax-highlighter_languages_refractor_hpkp.js.map