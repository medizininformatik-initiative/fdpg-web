(self["webpackChunkadvanced_custom_post_type"] = self["webpackChunkadvanced_custom_post_type"] || []).push([[8879],{

/***/ 18879:
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXPORTS
__webpack_require__.d(__webpack_exports__, {
  "Code": () => (/* binding */ ThemedCode),
  "CodeBlock": () => (/* binding */ ThemedCodeBlock),
  "CopyBlock": () => (/* binding */ ThemedCopyBlock),
  "Snippet": () => (/* binding */ ThemedSnippet),
  "a11yDark": () => (/* binding */ a11yDark),
  "a11yLight": () => (/* binding */ a11yLight),
  "anOldHope": () => (/* binding */ anOldHope),
  "androidstudio": () => (/* binding */ androidstudio),
  "arta": () => (/* binding */ arta),
  "atomOneDark": () => (/* binding */ atomOneDark),
  "atomOneLight": () => (/* binding */ atomOneLight),
  "codepen": () => (/* binding */ codepen),
  "dracula": () => (/* binding */ dracula),
  "far": () => (/* binding */ far),
  "github": () => (/* binding */ github),
  "googlecode": () => (/* binding */ googlecode),
  "hopscotch": () => (/* binding */ hopscotch),
  "hybrid": () => (/* binding */ hybrid),
  "irBlack": () => (/* binding */ irBlack),
  "monoBlue": () => (/* binding */ monoBlue),
  "monokai": () => (/* binding */ monokai),
  "monokaiSublime": () => (/* binding */ monokaiSublime),
  "nord": () => (/* binding */ nord),
  "obsidian": () => (/* binding */ obsidian),
  "ocean": () => (/* binding */ ocean),
  "paraisoDark": () => (/* binding */ paraisoDark),
  "paraisoLight": () => (/* binding */ paraisoLight),
  "pojoaque": () => (/* binding */ pojoaque),
  "purebasic": () => (/* binding */ purebasic),
  "railscast": () => (/* binding */ railscast),
  "rainbow": () => (/* binding */ rainbow),
  "shadesOfPurple": () => (/* binding */ shadesOfPurple),
  "solarizedDark": () => (/* binding */ solarizedDark),
  "solarizedLight": () => (/* binding */ solarizedLight),
  "sunburst": () => (/* binding */ sunburst),
  "tomorrow": () => (/* binding */ tomorrow),
  "tomorrowNight": () => (/* binding */ tomorrowNight),
  "tomorrowNightBlue": () => (/* binding */ tomorrowNightBlue),
  "tomorrowNightBright": () => (/* binding */ tomorrowNightBright),
  "tomorrowNightEighties": () => (/* binding */ tomorrowNightEighties),
  "vs2015": () => (/* binding */ vs2015),
  "xt256": () => (/* binding */ xt256),
  "zenburn": () => (/* binding */ zenburn)
});

// EXTERNAL MODULE: ./node_modules/react/index.js
var react = __webpack_require__(67294);
// EXTERNAL MODULE: ./node_modules/react-is/index.js
var react_is = __webpack_require__(59864);
// EXTERNAL MODULE: ./node_modules/shallowequal/index.js
var shallowequal = __webpack_require__(96774);
var shallowequal_default = /*#__PURE__*/__webpack_require__.n(shallowequal);
;// CONCATENATED MODULE: ./node_modules/@emotion/stylis/dist/stylis.browser.esm.js
function stylis_min (W) {
  function M(d, c, e, h, a) {
    for (var m = 0, b = 0, v = 0, n = 0, q, g, x = 0, K = 0, k, u = k = q = 0, l = 0, r = 0, I = 0, t = 0, B = e.length, J = B - 1, y, f = '', p = '', F = '', G = '', C; l < B;) {
      g = e.charCodeAt(l);
      l === J && 0 !== b + n + v + m && (0 !== b && (g = 47 === b ? 10 : 47), n = v = m = 0, B++, J++);

      if (0 === b + n + v + m) {
        if (l === J && (0 < r && (f = f.replace(N, '')), 0 < f.trim().length)) {
          switch (g) {
            case 32:
            case 9:
            case 59:
            case 13:
            case 10:
              break;

            default:
              f += e.charAt(l);
          }

          g = 59;
        }

        switch (g) {
          case 123:
            f = f.trim();
            q = f.charCodeAt(0);
            k = 1;

            for (t = ++l; l < B;) {
              switch (g = e.charCodeAt(l)) {
                case 123:
                  k++;
                  break;

                case 125:
                  k--;
                  break;

                case 47:
                  switch (g = e.charCodeAt(l + 1)) {
                    case 42:
                    case 47:
                      a: {
                        for (u = l + 1; u < J; ++u) {
                          switch (e.charCodeAt(u)) {
                            case 47:
                              if (42 === g && 42 === e.charCodeAt(u - 1) && l + 2 !== u) {
                                l = u + 1;
                                break a;
                              }

                              break;

                            case 10:
                              if (47 === g) {
                                l = u + 1;
                                break a;
                              }

                          }
                        }

                        l = u;
                      }

                  }

                  break;

                case 91:
                  g++;

                case 40:
                  g++;

                case 34:
                case 39:
                  for (; l++ < J && e.charCodeAt(l) !== g;) {
                  }

              }

              if (0 === k) break;
              l++;
            }

            k = e.substring(t, l);
            0 === q && (q = (f = f.replace(ca, '').trim()).charCodeAt(0));

            switch (q) {
              case 64:
                0 < r && (f = f.replace(N, ''));
                g = f.charCodeAt(1);

                switch (g) {
                  case 100:
                  case 109:
                  case 115:
                  case 45:
                    r = c;
                    break;

                  default:
                    r = O;
                }

                k = M(c, r, k, g, a + 1);
                t = k.length;
                0 < A && (r = X(O, f, I), C = H(3, k, r, c, D, z, t, g, a, h), f = r.join(''), void 0 !== C && 0 === (t = (k = C.trim()).length) && (g = 0, k = ''));
                if (0 < t) switch (g) {
                  case 115:
                    f = f.replace(da, ea);

                  case 100:
                  case 109:
                  case 45:
                    k = f + '{' + k + '}';
                    break;

                  case 107:
                    f = f.replace(fa, '$1 $2');
                    k = f + '{' + k + '}';
                    k = 1 === w || 2 === w && L('@' + k, 3) ? '@-webkit-' + k + '@' + k : '@' + k;
                    break;

                  default:
                    k = f + k, 112 === h && (k = (p += k, ''));
                } else k = '';
                break;

              default:
                k = M(c, X(c, f, I), k, h, a + 1);
            }

            F += k;
            k = I = r = u = q = 0;
            f = '';
            g = e.charCodeAt(++l);
            break;

          case 125:
          case 59:
            f = (0 < r ? f.replace(N, '') : f).trim();
            if (1 < (t = f.length)) switch (0 === u && (q = f.charCodeAt(0), 45 === q || 96 < q && 123 > q) && (t = (f = f.replace(' ', ':')).length), 0 < A && void 0 !== (C = H(1, f, c, d, D, z, p.length, h, a, h)) && 0 === (t = (f = C.trim()).length) && (f = '\x00\x00'), q = f.charCodeAt(0), g = f.charCodeAt(1), q) {
              case 0:
                break;

              case 64:
                if (105 === g || 99 === g) {
                  G += f + e.charAt(l);
                  break;
                }

              default:
                58 !== f.charCodeAt(t - 1) && (p += P(f, q, g, f.charCodeAt(2)));
            }
            I = r = u = q = 0;
            f = '';
            g = e.charCodeAt(++l);
        }
      }

      switch (g) {
        case 13:
        case 10:
          47 === b ? b = 0 : 0 === 1 + q && 107 !== h && 0 < f.length && (r = 1, f += '\x00');
          0 < A * Y && H(0, f, c, d, D, z, p.length, h, a, h);
          z = 1;
          D++;
          break;

        case 59:
        case 125:
          if (0 === b + n + v + m) {
            z++;
            break;
          }

        default:
          z++;
          y = e.charAt(l);

          switch (g) {
            case 9:
            case 32:
              if (0 === n + m + b) switch (x) {
                case 44:
                case 58:
                case 9:
                case 32:
                  y = '';
                  break;

                default:
                  32 !== g && (y = ' ');
              }
              break;

            case 0:
              y = '\\0';
              break;

            case 12:
              y = '\\f';
              break;

            case 11:
              y = '\\v';
              break;

            case 38:
              0 === n + b + m && (r = I = 1, y = '\f' + y);
              break;

            case 108:
              if (0 === n + b + m + E && 0 < u) switch (l - u) {
                case 2:
                  112 === x && 58 === e.charCodeAt(l - 3) && (E = x);

                case 8:
                  111 === K && (E = K);
              }
              break;

            case 58:
              0 === n + b + m && (u = l);
              break;

            case 44:
              0 === b + v + n + m && (r = 1, y += '\r');
              break;

            case 34:
            case 39:
              0 === b && (n = n === g ? 0 : 0 === n ? g : n);
              break;

            case 91:
              0 === n + b + v && m++;
              break;

            case 93:
              0 === n + b + v && m--;
              break;

            case 41:
              0 === n + b + m && v--;
              break;

            case 40:
              if (0 === n + b + m) {
                if (0 === q) switch (2 * x + 3 * K) {
                  case 533:
                    break;

                  default:
                    q = 1;
                }
                v++;
              }

              break;

            case 64:
              0 === b + v + n + m + u + k && (k = 1);
              break;

            case 42:
            case 47:
              if (!(0 < n + m + v)) switch (b) {
                case 0:
                  switch (2 * g + 3 * e.charCodeAt(l + 1)) {
                    case 235:
                      b = 47;
                      break;

                    case 220:
                      t = l, b = 42;
                  }

                  break;

                case 42:
                  47 === g && 42 === x && t + 2 !== l && (33 === e.charCodeAt(t + 2) && (p += e.substring(t, l + 1)), y = '', b = 0);
              }
          }

          0 === b && (f += y);
      }

      K = x;
      x = g;
      l++;
    }

    t = p.length;

    if (0 < t) {
      r = c;
      if (0 < A && (C = H(2, p, r, d, D, z, t, h, a, h), void 0 !== C && 0 === (p = C).length)) return G + p + F;
      p = r.join(',') + '{' + p + '}';

      if (0 !== w * E) {
        2 !== w || L(p, 2) || (E = 0);

        switch (E) {
          case 111:
            p = p.replace(ha, ':-moz-$1') + p;
            break;

          case 112:
            p = p.replace(Q, '::-webkit-input-$1') + p.replace(Q, '::-moz-$1') + p.replace(Q, ':-ms-input-$1') + p;
        }

        E = 0;
      }
    }

    return G + p + F;
  }

  function X(d, c, e) {
    var h = c.trim().split(ia);
    c = h;
    var a = h.length,
        m = d.length;

    switch (m) {
      case 0:
      case 1:
        var b = 0;

        for (d = 0 === m ? '' : d[0] + ' '; b < a; ++b) {
          c[b] = Z(d, c[b], e).trim();
        }

        break;

      default:
        var v = b = 0;

        for (c = []; b < a; ++b) {
          for (var n = 0; n < m; ++n) {
            c[v++] = Z(d[n] + ' ', h[b], e).trim();
          }
        }

    }

    return c;
  }

  function Z(d, c, e) {
    var h = c.charCodeAt(0);
    33 > h && (h = (c = c.trim()).charCodeAt(0));

    switch (h) {
      case 38:
        return c.replace(F, '$1' + d.trim());

      case 58:
        return d.trim() + c.replace(F, '$1' + d.trim());

      default:
        if (0 < 1 * e && 0 < c.indexOf('\f')) return c.replace(F, (58 === d.charCodeAt(0) ? '' : '$1') + d.trim());
    }

    return d + c;
  }

  function P(d, c, e, h) {
    var a = d + ';',
        m = 2 * c + 3 * e + 4 * h;

    if (944 === m) {
      d = a.indexOf(':', 9) + 1;
      var b = a.substring(d, a.length - 1).trim();
      b = a.substring(0, d).trim() + b + ';';
      return 1 === w || 2 === w && L(b, 1) ? '-webkit-' + b + b : b;
    }

    if (0 === w || 2 === w && !L(a, 1)) return a;

    switch (m) {
      case 1015:
        return 97 === a.charCodeAt(10) ? '-webkit-' + a + a : a;

      case 951:
        return 116 === a.charCodeAt(3) ? '-webkit-' + a + a : a;

      case 963:
        return 110 === a.charCodeAt(5) ? '-webkit-' + a + a : a;

      case 1009:
        if (100 !== a.charCodeAt(4)) break;

      case 969:
      case 942:
        return '-webkit-' + a + a;

      case 978:
        return '-webkit-' + a + '-moz-' + a + a;

      case 1019:
      case 983:
        return '-webkit-' + a + '-moz-' + a + '-ms-' + a + a;

      case 883:
        if (45 === a.charCodeAt(8)) return '-webkit-' + a + a;
        if (0 < a.indexOf('image-set(', 11)) return a.replace(ja, '$1-webkit-$2') + a;
        break;

      case 932:
        if (45 === a.charCodeAt(4)) switch (a.charCodeAt(5)) {
          case 103:
            return '-webkit-box-' + a.replace('-grow', '') + '-webkit-' + a + '-ms-' + a.replace('grow', 'positive') + a;

          case 115:
            return '-webkit-' + a + '-ms-' + a.replace('shrink', 'negative') + a;

          case 98:
            return '-webkit-' + a + '-ms-' + a.replace('basis', 'preferred-size') + a;
        }
        return '-webkit-' + a + '-ms-' + a + a;

      case 964:
        return '-webkit-' + a + '-ms-flex-' + a + a;

      case 1023:
        if (99 !== a.charCodeAt(8)) break;
        b = a.substring(a.indexOf(':', 15)).replace('flex-', '').replace('space-between', 'justify');
        return '-webkit-box-pack' + b + '-webkit-' + a + '-ms-flex-pack' + b + a;

      case 1005:
        return ka.test(a) ? a.replace(aa, ':-webkit-') + a.replace(aa, ':-moz-') + a : a;

      case 1e3:
        b = a.substring(13).trim();
        c = b.indexOf('-') + 1;

        switch (b.charCodeAt(0) + b.charCodeAt(c)) {
          case 226:
            b = a.replace(G, 'tb');
            break;

          case 232:
            b = a.replace(G, 'tb-rl');
            break;

          case 220:
            b = a.replace(G, 'lr');
            break;

          default:
            return a;
        }

        return '-webkit-' + a + '-ms-' + b + a;

      case 1017:
        if (-1 === a.indexOf('sticky', 9)) break;

      case 975:
        c = (a = d).length - 10;
        b = (33 === a.charCodeAt(c) ? a.substring(0, c) : a).substring(d.indexOf(':', 7) + 1).trim();

        switch (m = b.charCodeAt(0) + (b.charCodeAt(7) | 0)) {
          case 203:
            if (111 > b.charCodeAt(8)) break;

          case 115:
            a = a.replace(b, '-webkit-' + b) + ';' + a;
            break;

          case 207:
          case 102:
            a = a.replace(b, '-webkit-' + (102 < m ? 'inline-' : '') + 'box') + ';' + a.replace(b, '-webkit-' + b) + ';' + a.replace(b, '-ms-' + b + 'box') + ';' + a;
        }

        return a + ';';

      case 938:
        if (45 === a.charCodeAt(5)) switch (a.charCodeAt(6)) {
          case 105:
            return b = a.replace('-items', ''), '-webkit-' + a + '-webkit-box-' + b + '-ms-flex-' + b + a;

          case 115:
            return '-webkit-' + a + '-ms-flex-item-' + a.replace(ba, '') + a;

          default:
            return '-webkit-' + a + '-ms-flex-line-pack' + a.replace('align-content', '').replace(ba, '') + a;
        }
        break;

      case 973:
      case 989:
        if (45 !== a.charCodeAt(3) || 122 === a.charCodeAt(4)) break;

      case 931:
      case 953:
        if (!0 === la.test(d)) return 115 === (b = d.substring(d.indexOf(':') + 1)).charCodeAt(0) ? P(d.replace('stretch', 'fill-available'), c, e, h).replace(':fill-available', ':stretch') : a.replace(b, '-webkit-' + b) + a.replace(b, '-moz-' + b.replace('fill-', '')) + a;
        break;

      case 962:
        if (a = '-webkit-' + a + (102 === a.charCodeAt(5) ? '-ms-' + a : '') + a, 211 === e + h && 105 === a.charCodeAt(13) && 0 < a.indexOf('transform', 10)) return a.substring(0, a.indexOf(';', 27) + 1).replace(ma, '$1-webkit-$2') + a;
    }

    return a;
  }

  function L(d, c) {
    var e = d.indexOf(1 === c ? ':' : '{'),
        h = d.substring(0, 3 !== c ? e : 10);
    e = d.substring(e + 1, d.length - 1);
    return R(2 !== c ? h : h.replace(na, '$1'), e, c);
  }

  function ea(d, c) {
    var e = P(c, c.charCodeAt(0), c.charCodeAt(1), c.charCodeAt(2));
    return e !== c + ';' ? e.replace(oa, ' or ($1)').substring(4) : '(' + c + ')';
  }

  function H(d, c, e, h, a, m, b, v, n, q) {
    for (var g = 0, x = c, w; g < A; ++g) {
      switch (w = S[g].call(B, d, x, e, h, a, m, b, v, n, q)) {
        case void 0:
        case !1:
        case !0:
        case null:
          break;

        default:
          x = w;
      }
    }

    if (x !== c) return x;
  }

  function T(d) {
    switch (d) {
      case void 0:
      case null:
        A = S.length = 0;
        break;

      default:
        if ('function' === typeof d) S[A++] = d;else if ('object' === typeof d) for (var c = 0, e = d.length; c < e; ++c) {
          T(d[c]);
        } else Y = !!d | 0;
    }

    return T;
  }

  function U(d) {
    d = d.prefix;
    void 0 !== d && (R = null, d ? 'function' !== typeof d ? w = 1 : (w = 2, R = d) : w = 0);
    return U;
  }

  function B(d, c) {
    var e = d;
    33 > e.charCodeAt(0) && (e = e.trim());
    V = e;
    e = [V];

    if (0 < A) {
      var h = H(-1, c, e, e, D, z, 0, 0, 0, 0);
      void 0 !== h && 'string' === typeof h && (c = h);
    }

    var a = M(O, e, c, 0, 0);
    0 < A && (h = H(-2, a, e, e, D, z, a.length, 0, 0, 0), void 0 !== h && (a = h));
    V = '';
    E = 0;
    z = D = 1;
    return a;
  }

  var ca = /^\0+/g,
      N = /[\0\r\f]/g,
      aa = /: */g,
      ka = /zoo|gra/,
      ma = /([,: ])(transform)/g,
      ia = /,\r+?/g,
      F = /([\t\r\n ])*\f?&/g,
      fa = /@(k\w+)\s*(\S*)\s*/,
      Q = /::(place)/g,
      ha = /:(read-only)/g,
      G = /[svh]\w+-[tblr]{2}/,
      da = /\(\s*(.*)\s*\)/g,
      oa = /([\s\S]*?);/g,
      ba = /-self|flex-/g,
      na = /[^]*?(:[rp][el]a[\w-]+)[^]*/,
      la = /stretch|:\s*\w+\-(?:conte|avail)/,
      ja = /([^-])(image-set\()/,
      z = 1,
      D = 1,
      E = 0,
      w = 1,
      O = [],
      S = [],
      A = 0,
      R = null,
      Y = 0,
      V = '';
  B.use = T;
  B.set = U;
  void 0 !== W && U(W);
  return B;
}

/* harmony default export */ const stylis_browser_esm = (stylis_min);

;// CONCATENATED MODULE: ./node_modules/@emotion/unitless/dist/unitless.browser.esm.js
var unitlessKeys = {
  animationIterationCount: 1,
  borderImageOutset: 1,
  borderImageSlice: 1,
  borderImageWidth: 1,
  boxFlex: 1,
  boxFlexGroup: 1,
  boxOrdinalGroup: 1,
  columnCount: 1,
  columns: 1,
  flex: 1,
  flexGrow: 1,
  flexPositive: 1,
  flexShrink: 1,
  flexNegative: 1,
  flexOrder: 1,
  gridRow: 1,
  gridRowEnd: 1,
  gridRowSpan: 1,
  gridRowStart: 1,
  gridColumn: 1,
  gridColumnEnd: 1,
  gridColumnSpan: 1,
  gridColumnStart: 1,
  msGridRow: 1,
  msGridRowSpan: 1,
  msGridColumn: 1,
  msGridColumnSpan: 1,
  fontWeight: 1,
  lineHeight: 1,
  opacity: 1,
  order: 1,
  orphans: 1,
  tabSize: 1,
  widows: 1,
  zIndex: 1,
  zoom: 1,
  WebkitLineClamp: 1,
  // SVG-related properties
  fillOpacity: 1,
  floodOpacity: 1,
  stopOpacity: 1,
  strokeDasharray: 1,
  strokeDashoffset: 1,
  strokeMiterlimit: 1,
  strokeOpacity: 1,
  strokeWidth: 1
};

/* harmony default export */ const unitless_browser_esm = (unitlessKeys);

// EXTERNAL MODULE: ./node_modules/@emotion/memoize/dist/emotion-memoize.esm.js
var emotion_memoize_esm = __webpack_require__(45042);
;// CONCATENATED MODULE: ./node_modules/@emotion/is-prop-valid/dist/emotion-is-prop-valid.esm.js


var reactPropsRegex = /^((children|dangerouslySetInnerHTML|key|ref|autoFocus|defaultValue|defaultChecked|innerHTML|suppressContentEditableWarning|suppressHydrationWarning|valueLink|abbr|accept|acceptCharset|accessKey|action|allow|allowUserMedia|allowPaymentRequest|allowFullScreen|allowTransparency|alt|async|autoComplete|autoPlay|capture|cellPadding|cellSpacing|challenge|charSet|checked|cite|classID|className|cols|colSpan|content|contentEditable|contextMenu|controls|controlsList|coords|crossOrigin|data|dateTime|decoding|default|defer|dir|disabled|disablePictureInPicture|download|draggable|encType|enterKeyHint|form|formAction|formEncType|formMethod|formNoValidate|formTarget|frameBorder|headers|height|hidden|high|href|hrefLang|htmlFor|httpEquiv|id|inputMode|integrity|is|keyParams|keyType|kind|label|lang|list|loading|loop|low|marginHeight|marginWidth|max|maxLength|media|mediaGroup|method|min|minLength|multiple|muted|name|nonce|noValidate|open|optimum|pattern|placeholder|playsInline|poster|preload|profile|radioGroup|readOnly|referrerPolicy|rel|required|reversed|role|rows|rowSpan|sandbox|scope|scoped|scrolling|seamless|selected|shape|size|sizes|slot|span|spellCheck|src|srcDoc|srcLang|srcSet|start|step|style|summary|tabIndex|target|title|translate|type|useMap|value|width|wmode|wrap|about|datatype|inlist|prefix|property|resource|typeof|vocab|autoCapitalize|autoCorrect|autoSave|color|incremental|fallback|inert|itemProp|itemScope|itemType|itemID|itemRef|on|option|results|security|unselectable|accentHeight|accumulate|additive|alignmentBaseline|allowReorder|alphabetic|amplitude|arabicForm|ascent|attributeName|attributeType|autoReverse|azimuth|baseFrequency|baselineShift|baseProfile|bbox|begin|bias|by|calcMode|capHeight|clip|clipPathUnits|clipPath|clipRule|colorInterpolation|colorInterpolationFilters|colorProfile|colorRendering|contentScriptType|contentStyleType|cursor|cx|cy|d|decelerate|descent|diffuseConstant|direction|display|divisor|dominantBaseline|dur|dx|dy|edgeMode|elevation|enableBackground|end|exponent|externalResourcesRequired|fill|fillOpacity|fillRule|filter|filterRes|filterUnits|floodColor|floodOpacity|focusable|fontFamily|fontSize|fontSizeAdjust|fontStretch|fontStyle|fontVariant|fontWeight|format|from|fr|fx|fy|g1|g2|glyphName|glyphOrientationHorizontal|glyphOrientationVertical|glyphRef|gradientTransform|gradientUnits|hanging|horizAdvX|horizOriginX|ideographic|imageRendering|in|in2|intercept|k|k1|k2|k3|k4|kernelMatrix|kernelUnitLength|kerning|keyPoints|keySplines|keyTimes|lengthAdjust|letterSpacing|lightingColor|limitingConeAngle|local|markerEnd|markerMid|markerStart|markerHeight|markerUnits|markerWidth|mask|maskContentUnits|maskUnits|mathematical|mode|numOctaves|offset|opacity|operator|order|orient|orientation|origin|overflow|overlinePosition|overlineThickness|panose1|paintOrder|pathLength|patternContentUnits|patternTransform|patternUnits|pointerEvents|points|pointsAtX|pointsAtY|pointsAtZ|preserveAlpha|preserveAspectRatio|primitiveUnits|r|radius|refX|refY|renderingIntent|repeatCount|repeatDur|requiredExtensions|requiredFeatures|restart|result|rotate|rx|ry|scale|seed|shapeRendering|slope|spacing|specularConstant|specularExponent|speed|spreadMethod|startOffset|stdDeviation|stemh|stemv|stitchTiles|stopColor|stopOpacity|strikethroughPosition|strikethroughThickness|string|stroke|strokeDasharray|strokeDashoffset|strokeLinecap|strokeLinejoin|strokeMiterlimit|strokeOpacity|strokeWidth|surfaceScale|systemLanguage|tableValues|targetX|targetY|textAnchor|textDecoration|textRendering|textLength|to|transform|u1|u2|underlinePosition|underlineThickness|unicode|unicodeBidi|unicodeRange|unitsPerEm|vAlphabetic|vHanging|vIdeographic|vMathematical|values|vectorEffect|version|vertAdvY|vertOriginX|vertOriginY|viewBox|viewTarget|visibility|widths|wordSpacing|writingMode|x|xHeight|x1|x2|xChannelSelector|xlinkActuate|xlinkArcrole|xlinkHref|xlinkRole|xlinkShow|xlinkTitle|xlinkType|xmlBase|xmlns|xmlnsXlink|xmlLang|xmlSpace|y|y1|y2|yChannelSelector|z|zoomAndPan|for|class|autofocus)|(([Dd][Aa][Tt][Aa]|[Aa][Rr][Ii][Aa]|x)-.*))$/; // https://esbench.com/bench/5bfee68a4cd7e6009ef61d23

var isPropValid = /* #__PURE__ */(0,emotion_memoize_esm/* default */.Z)(function (prop) {
  return reactPropsRegex.test(prop) || prop.charCodeAt(0) === 111
  /* o */
  && prop.charCodeAt(1) === 110
  /* n */
  && prop.charCodeAt(2) < 91;
}
/* Z+1 */
);

/* harmony default export */ const emotion_is_prop_valid_esm = (isPropValid);

// EXTERNAL MODULE: ./node_modules/hoist-non-react-statics/dist/hoist-non-react-statics.cjs.js
var hoist_non_react_statics_cjs = __webpack_require__(8679);
var hoist_non_react_statics_cjs_default = /*#__PURE__*/__webpack_require__.n(hoist_non_react_statics_cjs);
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/styled-components/dist/styled-components.browser.esm.js
function v(){return(v=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}var g=function(e,t){for(var n=[e[0]],r=0,o=t.length;r<o;r+=1)n.push(t[r],e[r+1]);return n},S=function(t){return null!==t&&"object"==typeof t&&"[object Object]"===(t.toString?t.toString():Object.prototype.toString.call(t))&&!(0,react_is.typeOf)(t)},w=Object.freeze([]),E=Object.freeze({});function b(e){return"function"==typeof e}function _(e){return false||e.displayName||e.name||"Component"}function N(e){return e&&"string"==typeof e.styledComponentId}var A="undefined"!=typeof process&&void 0!==({"NODE_ENV":"production"})&&(({"NODE_ENV":"production"}).REACT_APP_SC_ATTR||({"NODE_ENV":"production"}).SC_ATTR)||"data-styled",C="5.3.8",I="undefined"!=typeof window&&"HTMLElement"in window,P=Boolean("boolean"==typeof SC_DISABLE_SPEEDY?SC_DISABLE_SPEEDY:"undefined"!=typeof process&&void 0!==({"NODE_ENV":"production"})&&(void 0!==({"NODE_ENV":"production"}).REACT_APP_SC_DISABLE_SPEEDY&&""!==({"NODE_ENV":"production"}).REACT_APP_SC_DISABLE_SPEEDY?"false"!==({"NODE_ENV":"production"}).REACT_APP_SC_DISABLE_SPEEDY&&({"NODE_ENV":"production"}).REACT_APP_SC_DISABLE_SPEEDY:void 0!==({"NODE_ENV":"production"}).SC_DISABLE_SPEEDY&&""!==({"NODE_ENV":"production"}).SC_DISABLE_SPEEDY?"false"!==({"NODE_ENV":"production"}).SC_DISABLE_SPEEDY&&({"NODE_ENV":"production"}).SC_DISABLE_SPEEDY:"production"!=="production")),O={},R= false?0:{};function D(){for(var e=arguments.length<=0?void 0:arguments[0],t=[],n=1,r=arguments.length;n<r;n+=1)t.push(n<0||arguments.length<=n?void 0:arguments[n]);return t.forEach((function(t){e=e.replace(/%[a-z]/,t)})),e}function j(e){for(var t=arguments.length,n=new Array(t>1?t-1:0),r=1;r<t;r++)n[r-1]=arguments[r];throw true?new Error("An error occurred. See https://git.io/JUIaE#"+e+" for more information."+(n.length>0?" Args: "+n.join(", "):"")):0}var T=function(){function e(e){this.groupSizes=new Uint32Array(512),this.length=512,this.tag=e}var t=e.prototype;return t.indexOfGroup=function(e){for(var t=0,n=0;n<e;n++)t+=this.groupSizes[n];return t},t.insertRules=function(e,t){if(e>=this.groupSizes.length){for(var n=this.groupSizes,r=n.length,o=r;e>=o;)(o<<=1)<0&&j(16,""+e);this.groupSizes=new Uint32Array(o),this.groupSizes.set(n),this.length=o;for(var s=r;s<o;s++)this.groupSizes[s]=0}for(var i=this.indexOfGroup(e+1),a=0,c=t.length;a<c;a++)this.tag.insertRule(i,t[a])&&(this.groupSizes[e]++,i++)},t.clearGroup=function(e){if(e<this.length){var t=this.groupSizes[e],n=this.indexOfGroup(e),r=n+t;this.groupSizes[e]=0;for(var o=n;o<r;o++)this.tag.deleteRule(n)}},t.getGroup=function(e){var t="";if(e>=this.length||0===this.groupSizes[e])return t;for(var n=this.groupSizes[e],r=this.indexOfGroup(e),o=r+n,s=r;s<o;s++)t+=this.tag.getRule(s)+"/*!sc*/\n";return t},e}(),x=new Map,k=new Map,V=1,B=function(e){if(x.has(e))return x.get(e);for(;k.has(V);)V++;var t=V++;return false&&0,x.set(e,t),k.set(t,e),t},z=function(e){return k.get(e)},M=function(e,t){t>=V&&(V=t+1),x.set(e,t),k.set(t,e)},G="style["+A+'][data-styled-version="5.3.8"]',L=new RegExp("^"+A+'\\.g(\\d+)\\[id="([\\w\\d-]+)"\\].*?"([^"]*)'),F=function(e,t,n){for(var r,o=n.split(","),s=0,i=o.length;s<i;s++)(r=o[s])&&e.registerName(t,r)},Y=function(e,t){for(var n=(t.textContent||"").split("/*!sc*/\n"),r=[],o=0,s=n.length;o<s;o++){var i=n[o].trim();if(i){var a=i.match(L);if(a){var c=0|parseInt(a[1],10),u=a[2];0!==c&&(M(u,c),F(e,u,a[3]),e.getTag().insertRules(c,r)),r.length=0}else r.push(i)}}},q=function(){return true?__webpack_require__.nc:0},H=function(e){var t=document.head,n=e||t,r=document.createElement("style"),o=function(e){for(var t=e.childNodes,n=t.length;n>=0;n--){var r=t[n];if(r&&1===r.nodeType&&r.hasAttribute(A))return r}}(n),s=void 0!==o?o.nextSibling:null;r.setAttribute(A,"active"),r.setAttribute("data-styled-version","5.3.8");var i=q();return i&&r.setAttribute("nonce",i),n.insertBefore(r,s),r},$=function(){function e(e){var t=this.element=H(e);t.appendChild(document.createTextNode("")),this.sheet=function(e){if(e.sheet)return e.sheet;for(var t=document.styleSheets,n=0,r=t.length;n<r;n++){var o=t[n];if(o.ownerNode===e)return o}j(17)}(t),this.length=0}var t=e.prototype;return t.insertRule=function(e,t){try{return this.sheet.insertRule(t,e),this.length++,!0}catch(e){return!1}},t.deleteRule=function(e){this.sheet.deleteRule(e),this.length--},t.getRule=function(e){var t=this.sheet.cssRules[e];return void 0!==t&&"string"==typeof t.cssText?t.cssText:""},e}(),W=function(){function e(e){var t=this.element=H(e);this.nodes=t.childNodes,this.length=0}var t=e.prototype;return t.insertRule=function(e,t){if(e<=this.length&&e>=0){var n=document.createTextNode(t),r=this.nodes[e];return this.element.insertBefore(n,r||null),this.length++,!0}return!1},t.deleteRule=function(e){this.element.removeChild(this.nodes[e]),this.length--},t.getRule=function(e){return e<this.length?this.nodes[e].textContent:""},e}(),U=function(){function e(e){this.rules=[],this.length=0}var t=e.prototype;return t.insertRule=function(e,t){return e<=this.length&&(this.rules.splice(e,0,t),this.length++,!0)},t.deleteRule=function(e){this.rules.splice(e,1),this.length--},t.getRule=function(e){return e<this.length?this.rules[e]:""},e}(),J=I,X={isServer:!I,useCSSOMInjection:!P},Z=function(){function e(e,t,n){void 0===e&&(e=E),void 0===t&&(t={}),this.options=v({},X,{},e),this.gs=t,this.names=new Map(n),this.server=!!e.isServer,!this.server&&I&&J&&(J=!1,function(e){for(var t=document.querySelectorAll(G),n=0,r=t.length;n<r;n++){var o=t[n];o&&"active"!==o.getAttribute(A)&&(Y(e,o),o.parentNode&&o.parentNode.removeChild(o))}}(this))}e.registerId=function(e){return B(e)};var t=e.prototype;return t.reconstructWithOptions=function(t,n){return void 0===n&&(n=!0),new e(v({},this.options,{},t),this.gs,n&&this.names||void 0)},t.allocateGSInstance=function(e){return this.gs[e]=(this.gs[e]||0)+1},t.getTag=function(){return this.tag||(this.tag=(n=(t=this.options).isServer,r=t.useCSSOMInjection,o=t.target,e=n?new U(o):r?new $(o):new W(o),new T(e)));var e,t,n,r,o},t.hasNameForId=function(e,t){return this.names.has(e)&&this.names.get(e).has(t)},t.registerName=function(e,t){if(B(e),this.names.has(e))this.names.get(e).add(t);else{var n=new Set;n.add(t),this.names.set(e,n)}},t.insertRules=function(e,t,n){this.registerName(e,t),this.getTag().insertRules(B(e),n)},t.clearNames=function(e){this.names.has(e)&&this.names.get(e).clear()},t.clearRules=function(e){this.getTag().clearGroup(B(e)),this.clearNames(e)},t.clearTag=function(){this.tag=void 0},t.toString=function(){return function(e){for(var t=e.getTag(),n=t.length,r="",o=0;o<n;o++){var s=z(o);if(void 0!==s){var i=e.names.get(s),a=t.getGroup(o);if(i&&a&&i.size){var c=A+".g"+o+'[id="'+s+'"]',u="";void 0!==i&&i.forEach((function(e){e.length>0&&(u+=e+",")})),r+=""+a+c+'{content:"'+u+'"}/*!sc*/\n'}}}return r}(this)},e}(),K=/(a)(d)/gi,Q=function(e){return String.fromCharCode(e+(e>25?39:97))};function ee(e){var t,n="";for(t=Math.abs(e);t>52;t=t/52|0)n=Q(t%52)+n;return(Q(t%52)+n).replace(K,"$1-$2")}var te=function(e,t){for(var n=t.length;n;)e=33*e^t.charCodeAt(--n);return e},ne=function(e){return te(5381,e)};function re(e){for(var t=0;t<e.length;t+=1){var n=e[t];if(b(n)&&!N(n))return!1}return!0}var oe=ne("5.3.8"),se=function(){function e(e,t,n){this.rules=e,this.staticRulesId="",this.isStatic= true&&(void 0===n||n.isStatic)&&re(e),this.componentId=t,this.baseHash=te(oe,t),this.baseStyle=n,Z.registerId(t)}return e.prototype.generateAndInjectStyles=function(e,t,n){var r=this.componentId,o=[];if(this.baseStyle&&o.push(this.baseStyle.generateAndInjectStyles(e,t,n)),this.isStatic&&!n.hash)if(this.staticRulesId&&t.hasNameForId(r,this.staticRulesId))o.push(this.staticRulesId);else{var s=Ne(this.rules,e,t,n).join(""),i=ee(te(this.baseHash,s)>>>0);if(!t.hasNameForId(r,i)){var a=n(s,"."+i,void 0,r);t.insertRules(r,i,a)}o.push(i),this.staticRulesId=i}else{for(var c=this.rules.length,u=te(this.baseHash,n.hash),l="",d=0;d<c;d++){var h=this.rules[d];if("string"==typeof h)l+=h, false&&(0);else if(h){var p=Ne(h,e,t,n),f=Array.isArray(p)?p.join(""):p;u=te(u,f+d),l+=f}}if(l){var m=ee(u>>>0);if(!t.hasNameForId(r,m)){var y=n(l,"."+m,void 0,r);t.insertRules(r,m,y)}o.push(m)}}return o.join(" ")},e}(),ie=/^\s*\/\/.*$/gm,ae=[":","[",".","#"];function ce(e){var t,n,r,o,s=void 0===e?E:e,i=s.options,a=void 0===i?E:i,c=s.plugins,u=void 0===c?w:c,l=new stylis_browser_esm(a),d=[],h=function(e){function t(t){if(t)try{e(t+"}")}catch(e){}}return function(n,r,o,s,i,a,c,u,l,d){switch(n){case 1:if(0===l&&64===r.charCodeAt(0))return e(r+";"),"";break;case 2:if(0===u)return r+"/*|*/";break;case 3:switch(u){case 102:case 112:return e(o[0]+r),"";default:return r+(0===d?"/*|*/":"")}case-2:r.split("/*|*/}").forEach(t)}}}((function(e){d.push(e)})),f=function(e,r,s){return 0===r&&-1!==ae.indexOf(s[n.length])||s.match(o)?e:"."+t};function m(e,s,i,a){void 0===a&&(a="&");var c=e.replace(ie,""),u=s&&i?i+" "+s+" { "+c+" }":c;return t=a,n=s,r=new RegExp("\\"+n+"\\b","g"),o=new RegExp("(\\"+n+"\\b){2,}"),l(i||!s?"":s,u)}return l.use([].concat(u,[function(e,t,o){2===e&&o.length&&o[0].lastIndexOf(n)>0&&(o[0]=o[0].replace(r,f))},h,function(e){if(-2===e){var t=d;return d=[],t}}])),m.hash=u.length?u.reduce((function(e,t){return t.name||j(15),te(e,t.name)}),5381).toString():"",m}var ue=react.createContext(),le=ue.Consumer,de=react.createContext(),he=(de.Consumer,new Z),pe=ce();function fe(){return (0,react.useContext)(ue)||he}function me(){return (0,react.useContext)(de)||pe}function ye(e){var t=(0,react.useState)(e.stylisPlugins),n=t[0],s=t[1],c=fe(),u=(0,react.useMemo)((function(){var t=c;return e.sheet?t=e.sheet:e.target&&(t=t.reconstructWithOptions({target:e.target},!1)),e.disableCSSOMInjection&&(t=t.reconstructWithOptions({useCSSOMInjection:!1})),t}),[e.disableCSSOMInjection,e.sheet,e.target]),l=(0,react.useMemo)((function(){return ce({options:{prefix:!e.disableVendorPrefixes},plugins:n})}),[e.disableVendorPrefixes,n]);return (0,react.useEffect)((function(){shallowequal_default()(n,e.stylisPlugins)||s(e.stylisPlugins)}),[e.stylisPlugins]),react.createElement(ue.Provider,{value:u},react.createElement(de.Provider,{value:l}, false?0:e.children))}var ve=function(){function e(e,t){var n=this;this.inject=function(e,t){void 0===t&&(t=pe);var r=n.name+t.hash;e.hasNameForId(n.id,r)||e.insertRules(n.id,r,t(n.rules,r,"@keyframes"))},this.toString=function(){return j(12,String(n.name))},this.name=e,this.id="sc-keyframes-"+e,this.rules=t}return e.prototype.getName=function(e){return void 0===e&&(e=pe),this.name+e.hash},e}(),ge=/([A-Z])/,Se=/([A-Z])/g,we=/^ms-/,Ee=function(e){return"-"+e.toLowerCase()};function be(e){return ge.test(e)?e.replace(Se,Ee).replace(we,"-ms-"):e}var _e=function(e){return null==e||!1===e||""===e};function Ne(e,n,r,o){if(Array.isArray(e)){for(var s,i=[],a=0,c=e.length;a<c;a+=1)""!==(s=Ne(e[a],n,r,o))&&(Array.isArray(s)?i.push.apply(i,s):i.push(s));return i}if(_e(e))return"";if(N(e))return"."+e.styledComponentId;if(b(e)){if("function"!=typeof(l=e)||l.prototype&&l.prototype.isReactComponent||!n)return e;var u=e(n);return false&&0,Ne(u,n,r,o)}var l;return e instanceof ve?r?(e.inject(r,o),e.getName(o)):e:S(e)?function e(t,n){var r,o,s=[];for(var i in t)t.hasOwnProperty(i)&&!_e(t[i])&&(Array.isArray(t[i])&&t[i].isCss||b(t[i])?s.push(be(i)+":",t[i],";"):S(t[i])?s.push.apply(s,e(t[i],i)):s.push(be(i)+": "+(r=i,null==(o=t[i])||"boolean"==typeof o||""===o?"":"number"!=typeof o||0===o||r in unitless_browser_esm?String(o).trim():o+"px")+";"));return n?[n+" {"].concat(s,["}"]):s}(e):e.toString()}var Ae=function(e){return Array.isArray(e)&&(e.isCss=!0),e};function Ce(e){for(var t=arguments.length,n=new Array(t>1?t-1:0),r=1;r<t;r++)n[r-1]=arguments[r];return b(e)||S(e)?Ae(Ne(g(w,[e].concat(n)))):0===n.length&&1===e.length&&"string"==typeof e[0]?e:Ae(Ne(g(e,n)))}var Ie=/invalid hook call/i,Pe=new Set,Oe=function(e,t){if(false){ var o, n, r; }},Re=function(e,t,n){return void 0===n&&(n=E),e.theme!==n.theme&&e.theme||t||n.theme},De=/[!"#$%&'()*+,./:;<=>?@[\\\]^`{|}~-]+/g,je=/(^-|-$)/g;function Te(e){return e.replace(De,"-").replace(je,"")}var xe=function(e){return ee(ne(e)>>>0)};function ke(e){return"string"==typeof e&&( true||0)}var Ve=function(e){return"function"==typeof e||"object"==typeof e&&null!==e&&!Array.isArray(e)},Be=function(e){return"__proto__"!==e&&"constructor"!==e&&"prototype"!==e};function ze(e,t,n){var r=e[n];Ve(t)&&Ve(r)?Me(r,t):e[n]=t}function Me(e){for(var t=arguments.length,n=new Array(t>1?t-1:0),r=1;r<t;r++)n[r-1]=arguments[r];for(var o=0,s=n;o<s.length;o++){var i=s[o];if(Ve(i))for(var a in i)Be(a)&&ze(e,i[a],a)}return e}var Ge=react.createContext(),Le=Ge.Consumer;function Fe(e){var t=s(Ge),n=i((function(){return function(e,t){if(!e)return j(14);if(b(e)){var n=e(t);return true?n:0}return Array.isArray(e)||"object"!=typeof e?j(8):t?v({},t,{},e):e}(e.theme,t)}),[e.theme,t]);return e.children?r.createElement(Ge.Provider,{value:n},e.children):null}var Ye={};function qe(e,t,n){var o=N(e),i=!ke(e),a=t.attrs,c=void 0===a?w:a,d=t.componentId,h=void 0===d?function(e,t){var n="string"!=typeof e?"sc":Te(e);Ye[n]=(Ye[n]||0)+1;var r=n+"-"+xe("5.3.8"+n+Ye[n]);return t?t+"-"+r:r}(t.displayName,t.parentComponentId):d,p=t.displayName,f=void 0===p?function(e){return ke(e)?"styled."+e:"Styled("+_(e)+")"}(e):p,g=t.displayName&&t.componentId?Te(t.displayName)+"-"+t.componentId:t.componentId||h,S=o&&e.attrs?Array.prototype.concat(e.attrs,c).filter(Boolean):c,A=t.shouldForwardProp;o&&e.shouldForwardProp&&(A=t.shouldForwardProp?function(n,r,o){return e.shouldForwardProp(n,r,o)&&t.shouldForwardProp(n,r,o)}:e.shouldForwardProp);var C,I=new se(n,g,o?e.componentStyle:void 0),P=I.isStatic&&0===c.length,O=function(e,t){return function(e,t,n,r){var o=e.attrs,i=e.componentStyle,a=e.defaultProps,c=e.foldedComponentIds,d=e.shouldForwardProp,h=e.styledComponentId,p=e.target; false&&0;var f=function(e,t,n){void 0===e&&(e=E);var r=v({},t,{theme:e}),o={};return n.forEach((function(e){var t,n,s,i=e;for(t in b(i)&&(i=i(r)),i)r[t]=o[t]="className"===t?(n=o[t],s=i[t],n&&s?n+" "+s:n||s):i[t]})),[r,o]}(Re(t,(0,react.useContext)(Ge),a)||E,t,o),y=f[0],g=f[1],S=function(e,t,n,r){var o=fe(),s=me(),i=t?e.generateAndInjectStyles(E,o,s):e.generateAndInjectStyles(n,o,s);return false&&0, false&&0,i}(i,r,y, false?0:void 0),w=n,_=g.$as||t.$as||g.as||t.as||p,N=ke(_),A=g!==t?v({},t,{},g):t,C={};for(var I in A)"$"!==I[0]&&"as"!==I&&("forwardedAs"===I?C.as=A[I]:(d?d(I,emotion_is_prop_valid_esm,_):!N||emotion_is_prop_valid_esm(I))&&(C[I]=A[I]));return t.style&&g.style!==t.style&&(C.style=v({},t.style,{},g.style)),C.className=Array.prototype.concat(c,h,S!==h?S:null,t.className,g.className).filter(Boolean).join(" "),C.ref=w,(0,react.createElement)(_,C)}(C,e,t,P)};return O.displayName=f,(C=react.forwardRef(O)).attrs=S,C.componentStyle=I,C.displayName=f,C.shouldForwardProp=A,C.foldedComponentIds=o?Array.prototype.concat(e.foldedComponentIds,e.styledComponentId):w,C.styledComponentId=g,C.target=o?e.target:e,C.withComponent=function(e){var r=t.componentId,o=function(e,t){if(null==e)return{};var n,r,o={},s=Object.keys(e);for(r=0;r<s.length;r++)n=s[r],t.indexOf(n)>=0||(o[n]=e[n]);return o}(t,["componentId"]),s=r&&r+"-"+(ke(e)?e:Te(_(e)));return qe(e,v({},o,{attrs:S,componentId:s}),n)},Object.defineProperty(C,"defaultProps",{get:function(){return this._foldedDefaultProps},set:function(t){this._foldedDefaultProps=o?Me({},e.defaultProps,t):t}}), false&&(0),C.toString=function(){return"."+C.styledComponentId},i&&hoist_non_react_statics_cjs_default()(C,e,{attrs:!0,componentStyle:!0,displayName:!0,foldedComponentIds:!0,shouldForwardProp:!0,styledComponentId:!0,target:!0,withComponent:!0}),C}var He=function(e){return function e(t,r,o){if(void 0===o&&(o=E),!(0,react_is.isValidElementType)(r))return j(1,String(r));var s=function(){return t(r,o,Ce.apply(void 0,arguments))};return s.withConfig=function(n){return e(t,r,v({},o,{},n))},s.attrs=function(n){return e(t,r,v({},o,{attrs:Array.prototype.concat(o.attrs,n).filter(Boolean)}))},s}(qe,e)};["a","abbr","address","area","article","aside","audio","b","base","bdi","bdo","big","blockquote","body","br","button","canvas","caption","cite","code","col","colgroup","data","datalist","dd","del","details","dfn","dialog","div","dl","dt","em","embed","fieldset","figcaption","figure","footer","form","h1","h2","h3","h4","h5","h6","head","header","hgroup","hr","html","i","iframe","img","input","ins","kbd","keygen","label","legend","li","link","main","map","mark","marquee","menu","menuitem","meta","meter","nav","noscript","object","ol","optgroup","option","output","p","param","picture","pre","progress","q","rp","rt","ruby","s","samp","script","section","select","small","source","span","strong","style","sub","summary","sup","table","tbody","td","textarea","tfoot","th","thead","time","title","tr","track","u","ul","var","video","wbr","circle","clipPath","defs","ellipse","foreignObject","g","image","line","linearGradient","marker","mask","path","pattern","polygon","polyline","radialGradient","rect","stop","svg","text","textPath","tspan"].forEach((function(e){He[e]=He(e)}));var $e=function(){function e(e,t){this.rules=e,this.componentId=t,this.isStatic=re(e),Z.registerId(this.componentId+1)}var t=e.prototype;return t.createStyles=function(e,t,n,r){var o=r(Ne(this.rules,t,n,r).join(""),""),s=this.componentId+e;n.insertRules(s,s,o)},t.removeStyles=function(e,t){t.clearRules(this.componentId+e)},t.renderStyles=function(e,t,n,r){e>2&&Z.registerId(this.componentId+e),this.removeStyles(e,n),this.createStyles(e,t,n,r)},e}();function We(e){for(var t=arguments.length,n=new Array(t>1?t-1:0),o=1;o<t;o++)n[o-1]=arguments[o];var i=Ce.apply(void 0,[e].concat(n)),a="sc-global-"+xe(JSON.stringify(i)),u=new $e(i,a);function l(e){var t=fe(),n=me(),o=s(Ge),l=c(t.allocateGSInstance(a)).current;return false&&0, false&&0,t.server&&h(l,e,t,o,n),d((function(){if(!t.server)return h(l,e,t,o,n),function(){return u.removeStyles(l,t)}}),[l,e,t,o,n]),null}function h(e,t,n,r,o){if(u.isStatic)u.renderStyles(e,O,n,o);else{var s=v({},t,{theme:Re(t,r,l.defaultProps)});u.renderStyles(e,s,n,o)}}return false&&0,r.memo(l)}function Ue(e){ false&&0;for(var t=arguments.length,n=new Array(t>1?t-1:0),r=1;r<t;r++)n[r-1]=arguments[r];var o=Ce.apply(void 0,[e].concat(n)).join(""),s=xe(o);return new ve(s,o)}var Je=function(){function e(){var e=this;this._emitSheetCSS=function(){var t=e.instance.toString();if(!t)return"";var n=q();return"<style "+[n&&'nonce="'+n+'"',A+'="true"','data-styled-version="5.3.8"'].filter(Boolean).join(" ")+">"+t+"</style>"},this.getStyleTags=function(){return e.sealed?j(2):e._emitSheetCSS()},this.getStyleElement=function(){var t;if(e.sealed)return j(2);var n=((t={})[A]="",t["data-styled-version"]="5.3.8",t.dangerouslySetInnerHTML={__html:e.instance.toString()},t),o=q();return o&&(n.nonce=o),[react.createElement("style",v({},n,{key:"sc-0-0"}))]},this.seal=function(){e.sealed=!0},this.instance=new Z({isServer:!0}),this.sealed=!1}var t=e.prototype;return t.collectStyles=function(e){return this.sealed?j(2):react.createElement(ye,{sheet:this.instance},e)},t.interleaveWithNodeStream=function(e){return j(3)},e}(),Xe=function(e){var t=react.forwardRef((function(t,n){var o=(0,react.useContext)(Ge),i=e.defaultProps,a=Re(t,o,i);return false&&0,react.createElement(e,v({},t,{theme:a,ref:n}))}));return hoist_non_react_statics_cjs_default()(t,e),t.displayName="WithTheme("+_(e)+")",t},Ze=function(){return s(Ge)},Ke={StyleSheet:Z,masterSheet:he}; false&&0, false&&(0);/* harmony default export */ const styled_components_browser_esm = (He);
//# sourceMappingURL=styled-components.browser.esm.js.map

// EXTERNAL MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/node_modules/@babel/runtime/regenerator/index.js
var regenerator = __webpack_require__(32681);
var regenerator_default = /*#__PURE__*/__webpack_require__.n(regenerator);
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/node_modules/@babel/runtime/helpers/esm/asyncToGenerator.js
function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) {
  try {
    var info = gen[key](arg);
    var value = info.value;
  } catch (error) {
    reject(error);
    return;
  }
  if (info.done) {
    resolve(value);
  } else {
    Promise.resolve(value).then(_next, _throw);
  }
}
function _asyncToGenerator(fn) {
  return function () {
    var self = this,
      args = arguments;
    return new Promise(function (resolve, reject) {
      var gen = fn.apply(self, args);
      function _next(value) {
        asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value);
      }
      function _throw(err) {
        asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err);
      }
      _next(undefined);
    });
  };
}
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/node_modules/@babel/runtime/helpers/esm/extends.js
function _extends() {
  _extends = Object.assign ? Object.assign.bind() : function (target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i];
      for (var key in source) {
        if (Object.prototype.hasOwnProperty.call(source, key)) {
          target[key] = source[key];
        }
      }
    }
    return target;
  };
  return _extends.apply(this, arguments);
}
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/node_modules/@babel/runtime/helpers/esm/classCallCheck.js
function _classCallCheck(instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
}
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/node_modules/@babel/runtime/helpers/esm/typeof.js
function _typeof(obj) {
  "@babel/helpers - typeof";

  return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) {
    return typeof obj;
  } : function (obj) {
    return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
  }, _typeof(obj);
}
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/node_modules/@babel/runtime/helpers/esm/toPrimitive.js

function _toPrimitive(input, hint) {
  if (_typeof(input) !== "object" || input === null) return input;
  var prim = input[Symbol.toPrimitive];
  if (prim !== undefined) {
    var res = prim.call(input, hint || "default");
    if (_typeof(res) !== "object") return res;
    throw new TypeError("@@toPrimitive must return a primitive value.");
  }
  return (hint === "string" ? String : Number)(input);
}
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/node_modules/@babel/runtime/helpers/esm/toPropertyKey.js


function _toPropertyKey(arg) {
  var key = _toPrimitive(arg, "string");
  return _typeof(key) === "symbol" ? key : String(key);
}
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/node_modules/@babel/runtime/helpers/esm/createClass.js

function _defineProperties(target, props) {
  for (var i = 0; i < props.length; i++) {
    var descriptor = props[i];
    descriptor.enumerable = descriptor.enumerable || false;
    descriptor.configurable = true;
    if ("value" in descriptor) descriptor.writable = true;
    Object.defineProperty(target, _toPropertyKey(descriptor.key), descriptor);
  }
}
function _createClass(Constructor, protoProps, staticProps) {
  if (protoProps) _defineProperties(Constructor.prototype, protoProps);
  if (staticProps) _defineProperties(Constructor, staticProps);
  Object.defineProperty(Constructor, "prototype", {
    writable: false
  });
  return Constructor;
}
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js
function _assertThisInitialized(self) {
  if (self === void 0) {
    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
  }
  return self;
}
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js


function _possibleConstructorReturn(self, call) {
  if (call && (_typeof(call) === "object" || typeof call === "function")) {
    return call;
  } else if (call !== void 0) {
    throw new TypeError("Derived constructors may only return object or undefined");
  }
  return _assertThisInitialized(self);
}
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js
function _getPrototypeOf(o) {
  _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf.bind() : function _getPrototypeOf(o) {
    return o.__proto__ || Object.getPrototypeOf(o);
  };
  return _getPrototypeOf(o);
}
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/node_modules/@babel/runtime/helpers/esm/setPrototypeOf.js
function _setPrototypeOf(o, p) {
  _setPrototypeOf = Object.setPrototypeOf ? Object.setPrototypeOf.bind() : function _setPrototypeOf(o, p) {
    o.__proto__ = p;
    return o;
  };
  return _setPrototypeOf(o, p);
}
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/node_modules/@babel/runtime/helpers/esm/inherits.js

function _inherits(subClass, superClass) {
  if (typeof superClass !== "function" && superClass !== null) {
    throw new TypeError("Super expression must either be null or a function");
  }
  subClass.prototype = Object.create(superClass && superClass.prototype, {
    constructor: {
      value: subClass,
      writable: true,
      configurable: true
    }
  });
  Object.defineProperty(subClass, "prototype", {
    writable: false
  });
  if (superClass) _setPrototypeOf(subClass, superClass);
}
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/node_modules/@babel/runtime/helpers/esm/defineProperty.js

function _defineProperty(obj, key, value) {
  key = _toPropertyKey(key);
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }
  return obj;
}
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/node_modules/@babel/runtime/helpers/esm/objectWithoutPropertiesLoose.js
function _objectWithoutPropertiesLoose(source, excluded) {
  if (source == null) return {};
  var target = {};
  var sourceKeys = Object.keys(source);
  var key, i;
  for (i = 0; i < sourceKeys.length; i++) {
    key = sourceKeys[i];
    if (excluded.indexOf(key) >= 0) continue;
    target[key] = source[key];
  }
  return target;
}
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/node_modules/@babel/runtime/helpers/esm/objectWithoutProperties.js

function _objectWithoutProperties(source, excluded) {
  if (source == null) return {};
  var target = _objectWithoutPropertiesLoose(source, excluded);
  var key, i;
  if (Object.getOwnPropertySymbols) {
    var sourceSymbolKeys = Object.getOwnPropertySymbols(source);
    for (i = 0; i < sourceSymbolKeys.length; i++) {
      key = sourceSymbolKeys[i];
      if (excluded.indexOf(key) >= 0) continue;
      if (!Object.prototype.propertyIsEnumerable.call(source, key)) continue;
      target[key] = source[key];
    }
  }
  return target;
}
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/node_modules/@babel/runtime/helpers/esm/objectSpread.js

function _objectSpread(target) {
  for (var i = 1; i < arguments.length; i++) {
    var source = arguments[i] != null ? Object(arguments[i]) : {};
    var ownKeys = Object.keys(source);
    if (typeof Object.getOwnPropertySymbols === 'function') {
      ownKeys.push.apply(ownKeys, Object.getOwnPropertySymbols(source).filter(function (sym) {
        return Object.getOwnPropertyDescriptor(source, sym).enumerable;
      }));
    }
    ownKeys.forEach(function (key) {
      _defineProperty(target, key, source[key]);
    });
  }
  return target;
}
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/dist/esm/create-element.js



function createStyleObject(classNames) {
  var elementStyle = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
  var stylesheet = arguments.length > 2 ? arguments[2] : undefined;
  return classNames.reduce(function (styleObject, className) {
    return _objectSpread({}, styleObject, stylesheet[className]);
  }, elementStyle);
}
function createClassNameString(classNames) {
  return classNames.join(' ');
}
function createChildren(stylesheet, useInlineStyles) {
  var childrenCount = 0;
  return function (children) {
    childrenCount += 1;
    return children.map(function (child, i) {
      return createElement({
        node: child,
        stylesheet: stylesheet,
        useInlineStyles: useInlineStyles,
        key: "code-segment-".concat(childrenCount, "-").concat(i)
      });
    });
  };
}
function createElement(_ref) {
  var node = _ref.node,
      stylesheet = _ref.stylesheet,
      _ref$style = _ref.style,
      style = _ref$style === void 0 ? {} : _ref$style,
      useInlineStyles = _ref.useInlineStyles,
      key = _ref.key;
  var properties = node.properties,
      type = node.type,
      TagName = node.tagName,
      value = node.value;

  if (type === 'text') {
    return value;
  } else if (TagName) {
    var childrenCreator = createChildren(stylesheet, useInlineStyles);
    var nonStylesheetClassNames = useInlineStyles && properties.className && properties.className.filter(function (className) {
      return !stylesheet[className];
    });
    var className = nonStylesheetClassNames && nonStylesheetClassNames.length ? nonStylesheetClassNames : undefined;
    var props = useInlineStyles ? _objectSpread({}, properties, {
      className: className && createClassNameString(className)
    }, {
      style: createStyleObject(properties.className, Object.assign({}, properties.style, style), stylesheet)
    }) : _objectSpread({}, properties, {
      className: createClassNameString(properties.className)
    });
    var children = childrenCreator(node.children);
    return react.createElement(TagName, _extends({
      key: key
    }, props), children);
  }
}
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/dist/esm/highlight.js




var newLineRegex = /\n/g;

function getNewLines(str) {
  return str.match(newLineRegex);
}

function getLineNumbers(_ref) {
  var lines = _ref.lines,
      startingLineNumber = _ref.startingLineNumber,
      _ref$numberProps = _ref.numberProps,
      numberProps = _ref$numberProps === void 0 ? {} : _ref$numberProps;
  return lines.map(function (_, i) {
    var number = i + startingLineNumber;
    var properties = typeof numberProps === 'function' ? numberProps(number) : numberProps;
    return react.createElement("span", _extends({
      key: "line-".concat(i),
      className: "react-syntax-highlighter-line-number"
    }, properties), "".concat(number, "\n"));
  });
}

function LineNumbers(_ref2) {
  var codeString = _ref2.codeString,
      codeStyle = _ref2.codeStyle,
      _ref2$containerProps = _ref2.containerProps,
      containerProps = _ref2$containerProps === void 0 ? {} : _ref2$containerProps,
      numberProps = _ref2.numberProps,
      startingLineNumber = _ref2.startingLineNumber;
  containerProps.style = containerProps.style || {
    float: 'left',
    paddingRight: '10px'
  };
  return react.createElement("code", _extends({}, containerProps, {
    style: Object.assign({}, codeStyle, containerProps.style)
  }), getLineNumbers({
    lines: codeString.replace(/\n$/, '').split('\n'),
    numberProps: numberProps,
    startingLineNumber: startingLineNumber
  }));
}

function createLineElement(_ref3) {
  var children = _ref3.children,
      lineNumber = _ref3.lineNumber,
      lineProps = _ref3.lineProps,
      _ref3$className = _ref3.className,
      className = _ref3$className === void 0 ? [] : _ref3$className;
  var properties = (typeof lineProps === 'function' ? lineProps(lineNumber) : lineProps) || {};
  properties.className = properties.className ? className.concat(properties.className) : className;
  return {
    type: 'element',
    tagName: 'span',
    properties: properties,
    children: children
  };
}

function flattenCodeTree(tree) {
  var className = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];
  var newTree = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : [];

  for (var i = 0; i < tree.length; i++) {
    var node = tree[i];

    if (node.type === 'text') {
      newTree.push(createLineElement({
        children: [node],
        className: className
      }));
    } else if (node.children) {
      var classNames = className.concat(node.properties.className);
      newTree = newTree.concat(flattenCodeTree(node.children, classNames));
    }
  }

  return newTree;
}

function wrapLinesInSpan(codeTree, lineProps) {
  var tree = flattenCodeTree(codeTree.value);
  var newTree = [];
  var lastLineBreakIndex = -1;
  var index = 0;

  var _loop = function _loop() {
    var node = tree[index];
    var value = node.children[0].value;
    var newLines = getNewLines(value);

    if (newLines) {
      var splitValue = value.split('\n');
      splitValue.forEach(function (text, i) {
        var lineNumber = newTree.length + 1;
        var newChild = {
          type: 'text',
          value: "".concat(text, "\n")
        };

        if (i === 0) {
          var _children = tree.slice(lastLineBreakIndex + 1, index).concat(createLineElement({
            children: [newChild],
            className: node.properties.className
          }));

          newTree.push(createLineElement({
            children: _children,
            lineNumber: lineNumber,
            lineProps: lineProps
          }));
        } else if (i === splitValue.length - 1) {
          var stringChild = tree[index + 1] && tree[index + 1].children && tree[index + 1].children[0];

          if (stringChild) {
            var lastLineInPreviousSpan = {
              type: 'text',
              value: "".concat(text)
            };
            var newElem = createLineElement({
              children: [lastLineInPreviousSpan],
              className: node.properties.className
            });
            tree.splice(index + 1, 0, newElem);
          } else {
            newTree.push(createLineElement({
              children: [newChild],
              lineNumber: lineNumber,
              lineProps: lineProps,
              className: node.properties.className
            }));
          }
        } else {
          newTree.push(createLineElement({
            children: [newChild],
            lineNumber: lineNumber,
            lineProps: lineProps,
            className: node.properties.className
          }));
        }
      });
      lastLineBreakIndex = index;
    }

    index++;
  };

  while (index < tree.length) {
    _loop();
  }

  if (lastLineBreakIndex !== tree.length - 1) {
    var children = tree.slice(lastLineBreakIndex + 1, tree.length);

    if (children && children.length) {
      newTree.push(createLineElement({
        children: children,
        lineNumber: newTree.length + 1,
        lineProps: lineProps
      }));
    }
  }

  return newTree;
}

function defaultRenderer(_ref4) {
  var rows = _ref4.rows,
      stylesheet = _ref4.stylesheet,
      useInlineStyles = _ref4.useInlineStyles;
  return rows.map(function (node, i) {
    return createElement({
      node: node,
      stylesheet: stylesheet,
      useInlineStyles: useInlineStyles,
      key: "code-segement".concat(i)
    });
  });
}

function getCodeTree(_ref5) {
  var astGenerator = _ref5.astGenerator,
      language = _ref5.language,
      code = _ref5.code,
      defaultCodeValue = _ref5.defaultCodeValue;

  if (astGenerator.getLanguage) {
    var hasLanguage = language && astGenerator.getLanguage(language);

    if (language === 'text') {
      return {
        value: defaultCodeValue,
        language: 'text'
      };
    } else if (hasLanguage) {
      return astGenerator.highlight(language, code);
    } else {
      return astGenerator.highlightAuto(code);
    }
  }

  try {
    return language && language !== 'text' ? {
      value: astGenerator.highlight(code, language)
    } : {
      value: defaultCodeValue
    };
  } catch (e) {
    return {
      value: defaultCodeValue
    };
  }
}

/* harmony default export */ function highlight(defaultAstGenerator, defaultStyle) {
  return function SyntaxHighlighter(_ref6) {
    var language = _ref6.language,
        children = _ref6.children,
        _ref6$style = _ref6.style,
        style = _ref6$style === void 0 ? defaultStyle : _ref6$style,
        _ref6$customStyle = _ref6.customStyle,
        customStyle = _ref6$customStyle === void 0 ? {} : _ref6$customStyle,
        _ref6$codeTagProps = _ref6.codeTagProps,
        codeTagProps = _ref6$codeTagProps === void 0 ? {
      style: style['code[class*="language-"]']
    } : _ref6$codeTagProps,
        _ref6$useInlineStyles = _ref6.useInlineStyles,
        useInlineStyles = _ref6$useInlineStyles === void 0 ? true : _ref6$useInlineStyles,
        _ref6$showLineNumbers = _ref6.showLineNumbers,
        showLineNumbers = _ref6$showLineNumbers === void 0 ? false : _ref6$showLineNumbers,
        _ref6$startingLineNum = _ref6.startingLineNumber,
        startingLineNumber = _ref6$startingLineNum === void 0 ? 1 : _ref6$startingLineNum,
        lineNumberContainerProps = _ref6.lineNumberContainerProps,
        lineNumberProps = _ref6.lineNumberProps,
        wrapLines = _ref6.wrapLines,
        _ref6$lineProps = _ref6.lineProps,
        lineProps = _ref6$lineProps === void 0 ? {} : _ref6$lineProps,
        renderer = _ref6.renderer,
        _ref6$PreTag = _ref6.PreTag,
        PreTag = _ref6$PreTag === void 0 ? 'pre' : _ref6$PreTag,
        _ref6$CodeTag = _ref6.CodeTag,
        CodeTag = _ref6$CodeTag === void 0 ? 'code' : _ref6$CodeTag,
        _ref6$code = _ref6.code,
        code = _ref6$code === void 0 ? Array.isArray(children) ? children[0] : children : _ref6$code,
        astGenerator = _ref6.astGenerator,
        rest = _objectWithoutProperties(_ref6, ["language", "children", "style", "customStyle", "codeTagProps", "useInlineStyles", "showLineNumbers", "startingLineNumber", "lineNumberContainerProps", "lineNumberProps", "wrapLines", "lineProps", "renderer", "PreTag", "CodeTag", "code", "astGenerator"]);

    astGenerator = astGenerator || defaultAstGenerator;
    var lineNumbers = showLineNumbers ? react.createElement(LineNumbers, {
      containerProps: lineNumberContainerProps,
      codeStyle: codeTagProps.style || {},
      numberProps: lineNumberProps,
      startingLineNumber: startingLineNumber,
      codeString: code
    }) : null;
    var defaultPreStyle = style.hljs || style['pre[class*="language-"]'] || {
      backgroundColor: '#fff'
    };
    var preProps = useInlineStyles ? Object.assign({}, rest, {
      style: Object.assign({}, defaultPreStyle, customStyle)
    }) : Object.assign({}, rest, {
      className: 'hljs'
    });

    if (!astGenerator) {
      return react.createElement(PreTag, preProps, lineNumbers, react.createElement(CodeTag, codeTagProps, code));
    }
    /*
     * some custom renderers rely on individual row elements so we need to turn wrapLines on
     * if renderer is provided and wrapLines is undefined
     */


    wrapLines = renderer && wrapLines === undefined ? true : wrapLines;
    renderer = renderer || defaultRenderer;
    var defaultCodeValue = [{
      type: 'text',
      value: code
    }];
    var codeTree = getCodeTree({
      astGenerator: astGenerator,
      language: language,
      code: code,
      defaultCodeValue: defaultCodeValue
    });

    if (codeTree.language === null) {
      codeTree.value = defaultCodeValue;
    }

    var tree = wrapLines ? wrapLinesInSpan(codeTree, lineProps) : codeTree.value;
    return react.createElement(PreTag, preProps, lineNumbers, react.createElement(CodeTag, codeTagProps, renderer({
      rows: tree,
      stylesheet: style,
      useInlineStyles: useInlineStyles
    })));
  };
}
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/dist/esm/async-syntax-highlighter.js











/* harmony default export */ const async_syntax_highlighter = (function (options) {
  var loader = options.loader,
      isLanguageRegistered = options.isLanguageRegistered,
      registerLanguage = options.registerLanguage,
      languageLoaders = options.languageLoaders,
      noAsyncLoadingLanguages = options.noAsyncLoadingLanguages;

  var ReactAsyncHighlighter =
  /*#__PURE__*/
  function (_React$PureComponent) {
    _inherits(ReactAsyncHighlighter, _React$PureComponent);

    function ReactAsyncHighlighter() {
      _classCallCheck(this, ReactAsyncHighlighter);

      return _possibleConstructorReturn(this, _getPrototypeOf(ReactAsyncHighlighter).apply(this, arguments));
    }

    _createClass(ReactAsyncHighlighter, [{
      key: "componentDidUpdate",
      value: function componentDidUpdate() {
        if (!ReactAsyncHighlighter.isRegistered(this.props.language) && languageLoaders) {
          this.loadLanguage();
        }
      }
    }, {
      key: "componentDidMount",
      value: function componentDidMount() {
        var _this = this;

        if (!ReactAsyncHighlighter.astGeneratorPromise) {
          ReactAsyncHighlighter.loadAstGenerator();
        }

        if (!ReactAsyncHighlighter.astGenerator) {
          ReactAsyncHighlighter.astGeneratorPromise.then(function () {
            _this.forceUpdate();
          });
        }

        if (!ReactAsyncHighlighter.isRegistered(this.props.language) && languageLoaders) {
          this.loadLanguage();
        }
      }
    }, {
      key: "loadLanguage",
      value: function loadLanguage() {
        var _this2 = this;

        var language = this.props.language;

        if (language === 'text') {
          return;
        }

        ReactAsyncHighlighter.loadLanguage(language).then(function () {
          _this2.forceUpdate();
        });
      }
    }, {
      key: "normalizeLanguage",
      value: function normalizeLanguage(language) {
        return ReactAsyncHighlighter.isSupportedLanguage(language) ? language : 'text';
      }
    }, {
      key: "render",
      value: function render() {
        return react.createElement(ReactAsyncHighlighter.highlightInstance, _extends({}, this.props, {
          language: this.normalizeLanguage(this.props.language),
          astGenerator: ReactAsyncHighlighter.astGenerator
        }));
      }
    }], [{
      key: "preload",
      value: function preload() {
        return ReactAsyncHighlighter.loadAstGenerator();
      }
    }, {
      key: "loadLanguage",
      value: function () {
        var _loadLanguage = _asyncToGenerator(
        /*#__PURE__*/
        regenerator_default().mark(function _callee(language) {
          var languageLoader;
          return regenerator_default().wrap(function _callee$(_context) {
            while (1) {
              switch (_context.prev = _context.next) {
                case 0:
                  languageLoader = languageLoaders[language];

                  if (!(typeof languageLoader === 'function')) {
                    _context.next = 5;
                    break;
                  }

                  return _context.abrupt("return", languageLoader(ReactAsyncHighlighter.registerLanguage));

                case 5:
                  throw new Error("Language ".concat(language, " not supported"));

                case 6:
                case "end":
                  return _context.stop();
              }
            }
          }, _callee, this);
        }));

        return function loadLanguage(_x) {
          return _loadLanguage.apply(this, arguments);
        };
      }()
    }, {
      key: "isSupportedLanguage",
      value: function isSupportedLanguage(language) {
        return ReactAsyncHighlighter.isRegistered(language) || typeof languageLoaders[language] === 'function';
      }
    }, {
      key: "loadAstGenerator",
      value: function loadAstGenerator() {
        ReactAsyncHighlighter.astGeneratorPromise = loader().then(function (astGenerator) {
          ReactAsyncHighlighter.astGenerator = astGenerator;

          if (registerLanguage) {
            ReactAsyncHighlighter.languages.forEach(function (language, name) {
              return registerLanguage(astGenerator, name, language);
            });
          }
        });
        return ReactAsyncHighlighter.astGeneratorPromise;
      }
    }]);

    return ReactAsyncHighlighter;
  }(react.PureComponent);

  _defineProperty(ReactAsyncHighlighter, "astGenerator", null);

  _defineProperty(ReactAsyncHighlighter, "highlightInstance", highlight(null, {}));

  _defineProperty(ReactAsyncHighlighter, "astGeneratorPromise", null);

  _defineProperty(ReactAsyncHighlighter, "languages", new Map());

  _defineProperty(ReactAsyncHighlighter, "supportedLanguages", options.supportedLanguages || Object.keys(languageLoaders || {}));

  _defineProperty(ReactAsyncHighlighter, "isRegistered", function (language) {
    if (noAsyncLoadingLanguages) {
      return true;
    }

    if (!registerLanguage) {
      throw new Error("Current syntax highlighter doesn't support registration of languages");
    }

    if (!ReactAsyncHighlighter.astGenerator) {
      // Ast generator not available yet, but language will be registered once it is.
      return ReactAsyncHighlighter.languages.has(language);
    }

    return isLanguageRegistered(ReactAsyncHighlighter.astGenerator, language);
  });

  _defineProperty(ReactAsyncHighlighter, "registerLanguage", function (name, language) {
    if (!registerLanguage) {
      throw new Error("Current syntax highlighter doesn't support registration of languages");
    }

    if (ReactAsyncHighlighter.astGenerator) {
      return registerLanguage(ReactAsyncHighlighter.astGenerator, name, language);
    } else {
      ReactAsyncHighlighter.languages.set(name, language);
    }
  });

  return ReactAsyncHighlighter;
});
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/dist/esm/async-languages/create-language-async-loader.js


/* harmony default export */ const create_language_async_loader = (function (name, loader) {
  return (
    /*#__PURE__*/
    function () {
      var _ref = _asyncToGenerator(
      /*#__PURE__*/
      regenerator_default().mark(function _callee(registerLanguage) {
        var module;
        return regenerator_default().wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                _context.next = 2;
                return loader();

              case 2:
                module = _context.sent;
                registerLanguage(name, module.default || module);

              case 4:
              case "end":
                return _context.stop();
            }
          }
        }, _callee, this);
      }));

      return function (_x) {
        return _ref.apply(this, arguments);
      };
    }()
  );
});
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/dist/esm/async-languages/prism.js

/* harmony default export */ const prism = ({
  abap: create_language_async_loader("abap", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_abap */ 3412).then(__webpack_require__.t.bind(__webpack_require__, 4678, 23));
  }),
  actionscript: create_language_async_loader("actionscript", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_actionscript */ 3971).then(__webpack_require__.t.bind(__webpack_require__, 18113, 23));
  }),
  ada: create_language_async_loader("ada", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_ada */ 6084).then(__webpack_require__.t.bind(__webpack_require__, 28839, 23));
  }),
  apacheconf: create_language_async_loader("apacheconf", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_apacheconf */ 5524).then(__webpack_require__.t.bind(__webpack_require__, 60501, 23));
  }),
  apl: create_language_async_loader("apl", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_apl */ 6670).then(__webpack_require__.t.bind(__webpack_require__, 97726, 23));
  }),
  applescript: create_language_async_loader("applescript", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_applescript */ 4098).then(__webpack_require__.t.bind(__webpack_require__, 64032, 23));
  }),
  arduino: create_language_async_loader("arduino", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_arduino */ 3384).then(__webpack_require__.t.bind(__webpack_require__, 70375, 23));
  }),
  arff: create_language_async_loader("arff", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_arff */ 1438).then(__webpack_require__.t.bind(__webpack_require__, 15472, 23));
  }),
  asciidoc: create_language_async_loader("asciidoc", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_asciidoc */ 1554).then(__webpack_require__.t.bind(__webpack_require__, 16927, 23));
  }),
  asm6502: create_language_async_loader("asm6502", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_asm6502 */ 5696).then(__webpack_require__.t.bind(__webpack_require__, 81114, 23));
  }),
  aspnet: create_language_async_loader("aspnet", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_aspnet */ 8030).then(__webpack_require__.t.bind(__webpack_require__, 61461, 23));
  }),
  autohotkey: create_language_async_loader("autohotkey", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_autohotkey */ 2065).then(__webpack_require__.t.bind(__webpack_require__, 98053, 23));
  }),
  autoit: create_language_async_loader("autoit", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_autoit */ 8333).then(__webpack_require__.t.bind(__webpack_require__, 62567, 23));
  }),
  bash: create_language_async_loader("bash", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_bash */ 8765).then(__webpack_require__.t.bind(__webpack_require__, 38921, 23));
  }),
  basic: create_language_async_loader("basic", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_basic */ 7504).then(__webpack_require__.t.bind(__webpack_require__, 188, 23));
  }),
  batch: create_language_async_loader("batch", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_batch */ 400).then(__webpack_require__.t.bind(__webpack_require__, 71123, 23));
  }),
  bison: create_language_async_loader("bison", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_bison */ 948).then(__webpack_require__.t.bind(__webpack_require__, 57908, 23));
  }),
  brainfuck: create_language_async_loader("brainfuck", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_brainfuck */ 5539).then(__webpack_require__.t.bind(__webpack_require__, 6217, 23));
  }),
  bro: create_language_async_loader("bro", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_bro */ 3694).then(__webpack_require__.t.bind(__webpack_require__, 35468, 23));
  }),
  c: create_language_async_loader("c", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_c */ 8950).then(__webpack_require__.t.bind(__webpack_require__, 9158, 23));
  }),
  clike: create_language_async_loader("clike", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_clike */ 131).then(__webpack_require__.t.bind(__webpack_require__, 99875, 23));
  }),
  clojure: create_language_async_loader("clojure", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_clojure */ 7966).then(__webpack_require__.t.bind(__webpack_require__, 721, 23));
  }),
  coffeescript: create_language_async_loader("coffeescript", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_coffeescript */ 6118).then(__webpack_require__.t.bind(__webpack_require__, 14622, 23));
  }),
  cpp: create_language_async_loader("cpp", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_cpp */ 9692).then(__webpack_require__.t.bind(__webpack_require__, 82545, 23));
  }),
  crystal: create_language_async_loader("crystal", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_crystal */ 1130).then(__webpack_require__.t.bind(__webpack_require__, 30946, 23));
  }),
  csharp: create_language_async_loader("csharp", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_csharp */ 3318).then(__webpack_require__.t.bind(__webpack_require__, 1658, 23));
  }),
  csp: create_language_async_loader("csp", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_csp */ 5299).then(__webpack_require__.t.bind(__webpack_require__, 69847, 23));
  }),
  cssExtras: create_language_async_loader("cssExtras", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_cssExtras */ 7475).then(__webpack_require__.t.bind(__webpack_require__, 39117, 23));
  }),
  css: create_language_async_loader("css", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_css */ 5008).then(__webpack_require__.t.bind(__webpack_require__, 94299, 23));
  }),
  d: create_language_async_loader("d", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_d */ 3717).then(__webpack_require__.t.bind(__webpack_require__, 91543, 23));
  }),
  dart: create_language_async_loader("dart", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_dart */ 7769).then(__webpack_require__.t.bind(__webpack_require__, 82860, 23));
  }),
  diff: create_language_async_loader("diff", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_diff */ 6247).then(__webpack_require__.t.bind(__webpack_require__, 32443, 23));
  }),
  django: create_language_async_loader("django", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_django */ 7899).then(__webpack_require__.t.bind(__webpack_require__, 43145, 23));
  }),
  docker: create_language_async_loader("docker", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_docker */ 2051).then(__webpack_require__.t.bind(__webpack_require__, 4009, 23));
  }),
  eiffel: create_language_async_loader("eiffel", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_eiffel */ 2182).then(__webpack_require__.t.bind(__webpack_require__, 17046, 23));
  }),
  elixir: create_language_async_loader("elixir", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_elixir */ 6343).then(__webpack_require__.t.bind(__webpack_require__, 46577, 23));
  }),
  elm: create_language_async_loader("elm", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_elm */ 7838).then(__webpack_require__.t.bind(__webpack_require__, 46475, 23));
  }),
  erb: create_language_async_loader("erb", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_erb */ 2584).then(__webpack_require__.t.bind(__webpack_require__, 3275, 23));
  }),
  erlang: create_language_async_loader("erlang", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_erlang */ 2013).then(__webpack_require__.t.bind(__webpack_require__, 73137, 23));
  }),
  flow: create_language_async_loader("flow", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_flow */ 9742).then(__webpack_require__.t.bind(__webpack_require__, 5745, 23));
  }),
  fortran: create_language_async_loader("fortran", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_fortran */ 2044).then(__webpack_require__.t.bind(__webpack_require__, 8774, 23));
  }),
  fsharp: create_language_async_loader("fsharp", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_fsharp */ 741).then(__webpack_require__.t.bind(__webpack_require__, 19607, 23));
  }),
  gedcom: create_language_async_loader("gedcom", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_gedcom */ 5867).then(__webpack_require__.t.bind(__webpack_require__, 84164, 23));
  }),
  gherkin: create_language_async_loader("gherkin", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_gherkin */ 6051).then(__webpack_require__.t.bind(__webpack_require__, 51374, 23));
  }),
  git: create_language_async_loader("git", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_git */ 2564).then(__webpack_require__.t.bind(__webpack_require__, 85735, 23));
  }),
  glsl: create_language_async_loader("glsl", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_glsl */ 158).then(__webpack_require__.t.bind(__webpack_require__, 97868, 23));
  }),
  go: create_language_async_loader("go", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_go */ 6626).then(__webpack_require__.t.bind(__webpack_require__, 35910, 23));
  }),
  graphql: create_language_async_loader("graphql", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_graphql */ 8921).then(__webpack_require__.t.bind(__webpack_require__, 78233, 23));
  }),
  groovy: create_language_async_loader("groovy", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_groovy */ 5259).then(__webpack_require__.t.bind(__webpack_require__, 9065, 23));
  }),
  haml: create_language_async_loader("haml", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_haml */ 6487).then(__webpack_require__.t.bind(__webpack_require__, 55217, 23));
  }),
  handlebars: create_language_async_loader("handlebars", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_handlebars */ 3846).then(__webpack_require__.t.bind(__webpack_require__, 91465, 23));
  }),
  haskell: create_language_async_loader("haskell", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_haskell */ 1007).then(__webpack_require__.t.bind(__webpack_require__, 16741, 23));
  }),
  haxe: create_language_async_loader("haxe", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_haxe */ 3224).then(__webpack_require__.t.bind(__webpack_require__, 99305, 23));
  }),
  hpkp: create_language_async_loader("hpkp", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_hpkp */ 6749).then(__webpack_require__.t.bind(__webpack_require__, 23478, 23));
  }),
  hsts: create_language_async_loader("hsts", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_hsts */ 3140).then(__webpack_require__.t.bind(__webpack_require__, 92080, 23));
  }),
  http: create_language_async_loader("http", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_http */ 6508).then(__webpack_require__.t.bind(__webpack_require__, 43651, 23));
  }),
  ichigojam: create_language_async_loader("ichigojam", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_ichigojam */ 5056).then(__webpack_require__.t.bind(__webpack_require__, 75075, 23));
  }),
  icon: create_language_async_loader("icon", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_icon */ 2413).then(__webpack_require__.t.bind(__webpack_require__, 14892, 23));
  }),
  inform7: create_language_async_loader("inform7", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_inform7 */ 2996).then(__webpack_require__.t.bind(__webpack_require__, 63487, 23));
  }),
  ini: create_language_async_loader("ini", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_ini */ 6495).then(__webpack_require__.t.bind(__webpack_require__, 1132, 23));
  }),
  io: create_language_async_loader("io", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_io */ 7801).then(__webpack_require__.t.bind(__webpack_require__, 14150, 23));
  }),
  j: create_language_async_loader("j", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_j */ 4701).then(__webpack_require__.t.bind(__webpack_require__, 27377, 23));
  }),
  java: create_language_async_loader("java", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_java */ 3980).then(__webpack_require__.t.bind(__webpack_require__, 16942, 23));
  }),
  javascript: create_language_async_loader("javascript", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_javascript */ 7279).then(__webpack_require__.t.bind(__webpack_require__, 63233, 23));
  }),
  jolie: create_language_async_loader("jolie", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_jolie */ 8458).then(__webpack_require__.t.bind(__webpack_require__, 33502, 23));
  }),
  json: create_language_async_loader("json", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_json */ 3657).then(__webpack_require__.t.bind(__webpack_require__, 15915, 23));
  }),
  jsx: create_language_async_loader("jsx", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_jsx */ 4657).then(__webpack_require__.t.bind(__webpack_require__, 51467, 23));
  }),
  julia: create_language_async_loader("julia", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_julia */ 5508).then(__webpack_require__.t.bind(__webpack_require__, 30931, 23));
  }),
  keyman: create_language_async_loader("keyman", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_keyman */ 3819).then(__webpack_require__.t.bind(__webpack_require__, 447, 23));
  }),
  kotlin: create_language_async_loader("kotlin", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_kotlin */ 4630).then(__webpack_require__.t.bind(__webpack_require__, 27754, 23));
  }),
  latex: create_language_async_loader("latex", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_latex */ 4732).then(__webpack_require__.t.bind(__webpack_require__, 97503, 23));
  }),
  less: create_language_async_loader("less", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_less */ 5951).then(__webpack_require__.t.bind(__webpack_require__, 37831, 23));
  }),
  liquid: create_language_async_loader("liquid", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_liquid */ 1323).then(__webpack_require__.t.bind(__webpack_require__, 38600, 23));
  }),
  lisp: create_language_async_loader("lisp", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_lisp */ 3520).then(__webpack_require__.t.bind(__webpack_require__, 11349, 23));
  }),
  livescript: create_language_async_loader("livescript", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_livescript */ 4698).then(__webpack_require__.t.bind(__webpack_require__, 35450, 23));
  }),
  lolcode: create_language_async_loader("lolcode", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_lolcode */ 7719).then(__webpack_require__.t.bind(__webpack_require__, 98197, 23));
  }),
  lua: create_language_async_loader("lua", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_lua */ 8119).then(__webpack_require__.t.bind(__webpack_require__, 90800, 23));
  }),
  makefile: create_language_async_loader("makefile", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_makefile */ 7576).then(__webpack_require__.t.bind(__webpack_require__, 61863, 23));
  }),
  markdown: create_language_async_loader("markdown", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_markdown */ 9835).then(__webpack_require__.t.bind(__webpack_require__, 71917, 23));
  }),
  markupTemplating: create_language_async_loader("markupTemplating", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_markupTemplating */ 3047).then(__webpack_require__.t.bind(__webpack_require__, 90303, 23));
  }),
  markup: create_language_async_loader("markup", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_markup */ 2496).then(__webpack_require__.t.bind(__webpack_require__, 73710, 23));
  }),
  matlab: create_language_async_loader("matlab", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_matlab */ 8404).then(__webpack_require__.t.bind(__webpack_require__, 83040, 23));
  }),
  mel: create_language_async_loader("mel", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_mel */ 226).then(__webpack_require__.t.bind(__webpack_require__, 23002, 23));
  }),
  mizar: create_language_async_loader("mizar", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_mizar */ 4069).then(__webpack_require__.t.bind(__webpack_require__, 7943, 23));
  }),
  monkey: create_language_async_loader("monkey", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_monkey */ 8513).then(__webpack_require__.t.bind(__webpack_require__, 41753, 23));
  }),
  n4js: create_language_async_loader("n4js", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_n4js */ 5014).then(__webpack_require__.t.bind(__webpack_require__, 23796, 23));
  }),
  nasm: create_language_async_loader("nasm", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_nasm */ 7253).then(__webpack_require__.t.bind(__webpack_require__, 57159, 23));
  }),
  nginx: create_language_async_loader("nginx", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_nginx */ 4052).then(__webpack_require__.t.bind(__webpack_require__, 9010, 23));
  }),
  nim: create_language_async_loader("nim", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_nim */ 3025).then(__webpack_require__.t.bind(__webpack_require__, 84322, 23));
  }),
  nix: create_language_async_loader("nix", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_nix */ 3821).then(__webpack_require__.t.bind(__webpack_require__, 18919, 23));
  }),
  nsis: create_language_async_loader("nsis", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_nsis */ 3502).then(__webpack_require__.t.bind(__webpack_require__, 33614, 23));
  }),
  objectivec: create_language_async_loader("objectivec", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_objectivec */ 8336).then(__webpack_require__.t.bind(__webpack_require__, 84547, 23));
  }),
  ocaml: create_language_async_loader("ocaml", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_ocaml */ 8992).then(__webpack_require__.t.bind(__webpack_require__, 88315, 23));
  }),
  opencl: create_language_async_loader("opencl", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_opencl */ 8000).then(__webpack_require__.t.bind(__webpack_require__, 2953, 23));
  }),
  oz: create_language_async_loader("oz", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_oz */ 7658).then(__webpack_require__.t.bind(__webpack_require__, 13645, 23));
  }),
  parigp: create_language_async_loader("parigp", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_parigp */ 9979).then(__webpack_require__.t.bind(__webpack_require__, 63226, 23));
  }),
  parser: create_language_async_loader("parser", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_parser */ 672).then(__webpack_require__.t.bind(__webpack_require__, 52888, 23));
  }),
  pascal: create_language_async_loader("pascal", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_pascal */ 7833).then(__webpack_require__.t.bind(__webpack_require__, 38439, 23));
  }),
  perl: create_language_async_loader("perl", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_perl */ 4157).then(__webpack_require__.t.bind(__webpack_require__, 98990, 23));
  }),
  phpExtras: create_language_async_loader("phpExtras", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_phpExtras */ 5793).then(__webpack_require__.t.bind(__webpack_require__, 28725, 23));
  }),
  php: create_language_async_loader("php", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_php */ 2227).then(__webpack_require__.t.bind(__webpack_require__, 96972, 23));
  }),
  plsql: create_language_async_loader("plsql", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_plsql */ 8840).then(__webpack_require__.t.bind(__webpack_require__, 52155, 23));
  }),
  powershell: create_language_async_loader("powershell", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_powershell */ 342).then(__webpack_require__.t.bind(__webpack_require__, 95052, 23));
  }),
  processing: create_language_async_loader("processing", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_processing */ 9770).then(__webpack_require__.t.bind(__webpack_require__, 72994, 23));
  }),
  prolog: create_language_async_loader("prolog", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_prolog */ 4045).then(__webpack_require__.t.bind(__webpack_require__, 66471, 23));
  }),
  properties: create_language_async_loader("properties", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_properties */ 81).then(__webpack_require__.t.bind(__webpack_require__, 99081, 23));
  }),
  protobuf: create_language_async_loader("protobuf", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_protobuf */ 979).then(__webpack_require__.t.bind(__webpack_require__, 17269, 23));
  }),
  pug: create_language_async_loader("pug", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_pug */ 9851).then(__webpack_require__.t.bind(__webpack_require__, 20810, 23));
  }),
  puppet: create_language_async_loader("puppet", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_puppet */ 6861).then(__webpack_require__.t.bind(__webpack_require__, 85979, 23));
  }),
  pure: create_language_async_loader("pure", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_pure */ 9315).then(__webpack_require__.t.bind(__webpack_require__, 49022, 23));
  }),
  python: create_language_async_loader("python", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_python */ 2891).then(__webpack_require__.t.bind(__webpack_require__, 64084, 23));
  }),
  q: create_language_async_loader("q", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_q */ 1751).then(__webpack_require__.t.bind(__webpack_require__, 66268, 23));
  }),
  qore: create_language_async_loader("qore", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_qore */ 2547).then(__webpack_require__.t.bind(__webpack_require__, 45629, 23));
  }),
  r: create_language_async_loader("r", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_r */ 7882).then(__webpack_require__.t.bind(__webpack_require__, 95275, 23));
  }),
  reason: create_language_async_loader("reason", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_reason */ 8811).then(__webpack_require__.t.bind(__webpack_require__, 34230, 23));
  }),
  renpy: create_language_async_loader("renpy", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_renpy */ 9291).then(__webpack_require__.t.bind(__webpack_require__, 39417, 23));
  }),
  rest: create_language_async_loader("rest", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_rest */ 2348).then(__webpack_require__.t.bind(__webpack_require__, 62569, 23));
  }),
  rip: create_language_async_loader("rip", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_rip */ 1768).then(__webpack_require__.t.bind(__webpack_require__, 69730, 23));
  }),
  roboconf: create_language_async_loader("roboconf", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_roboconf */ 3236).then(__webpack_require__.t.bind(__webpack_require__, 24996, 23));
  }),
  ruby: create_language_async_loader("ruby", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_ruby */ 369).then(__webpack_require__.t.bind(__webpack_require__, 21417, 23));
  }),
  rust: create_language_async_loader("rust", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_rust */ 1001).then(__webpack_require__.t.bind(__webpack_require__, 95778, 23));
  }),
  sas: create_language_async_loader("sas", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_sas */ 8067).then(__webpack_require__.t.bind(__webpack_require__, 80853, 23));
  }),
  sass: create_language_async_loader("sass", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_sass */ 9797).then(__webpack_require__.t.bind(__webpack_require__, 85020, 23));
  }),
  scala: create_language_async_loader("scala", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_scala */ 3818).then(__webpack_require__.t.bind(__webpack_require__, 16554, 23));
  }),
  scheme: create_language_async_loader("scheme", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_scheme */ 5085).then(__webpack_require__.t.bind(__webpack_require__, 78738, 23));
  }),
  scss: create_language_async_loader("scss", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_scss */ 7286).then(__webpack_require__.t.bind(__webpack_require__, 25312, 23));
  }),
  smalltalk: create_language_async_loader("smalltalk", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_smalltalk */ 2822).then(__webpack_require__.t.bind(__webpack_require__, 186, 23));
  }),
  smarty: create_language_async_loader("smarty", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_smarty */ 849).then(__webpack_require__.t.bind(__webpack_require__, 74367, 23));
  }),
  soy: create_language_async_loader("soy", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_soy */ 1423).then(__webpack_require__.t.bind(__webpack_require__, 11985, 23));
  }),
  sql: create_language_async_loader("sql", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_sql */ 7055).then(__webpack_require__.t.bind(__webpack_require__, 58387, 23));
  }),
  stylus: create_language_async_loader("stylus", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_stylus */ 1621).then(__webpack_require__.t.bind(__webpack_require__, 28042, 23));
  }),
  swift: create_language_async_loader("swift", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_swift */ 3327).then(__webpack_require__.t.bind(__webpack_require__, 30829, 23));
  }),
  tap: create_language_async_loader("tap", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_tap */ 6975).then(__webpack_require__.t.bind(__webpack_require__, 82340, 23));
  }),
  tcl: create_language_async_loader("tcl", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_tcl */ 5165).then(__webpack_require__.t.bind(__webpack_require__, 15743, 23));
  }),
  textile: create_language_async_loader("textile", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_textile */ 7097).then(__webpack_require__.t.bind(__webpack_require__, 71019, 23));
  }),
  tsx: create_language_async_loader("tsx", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_tsx */ 2509).then(__webpack_require__.t.bind(__webpack_require__, 77416, 23));
  }),
  tt2: create_language_async_loader("tt2", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_tt2 */ 3444).then(__webpack_require__.t.bind(__webpack_require__, 20114, 23));
  }),
  twig: create_language_async_loader("twig", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_twig */ 8827).then(__webpack_require__.t.bind(__webpack_require__, 18307, 23));
  }),
  typescript: create_language_async_loader("typescript", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_typescript */ 9461).then(__webpack_require__.t.bind(__webpack_require__, 81663, 23));
  }),
  vbnet: create_language_async_loader("vbnet", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_vbnet */ 5896).then(__webpack_require__.t.bind(__webpack_require__, 30250, 23));
  }),
  velocity: create_language_async_loader("velocity", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_velocity */ 2980).then(__webpack_require__.t.bind(__webpack_require__, 72029, 23));
  }),
  verilog: create_language_async_loader("verilog", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_verilog */ 8819).then(__webpack_require__.t.bind(__webpack_require__, 97439, 23));
  }),
  vhdl: create_language_async_loader("vhdl", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_vhdl */ 1167).then(__webpack_require__.t.bind(__webpack_require__, 60169, 23));
  }),
  vim: create_language_async_loader("vim", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_vim */ 1929).then(__webpack_require__.t.bind(__webpack_require__, 88530, 23));
  }),
  visualBasic: create_language_async_loader("visualBasic", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_visualBasic */ 6558).then(__webpack_require__.t.bind(__webpack_require__, 48619, 23));
  }),
  wasm: create_language_async_loader("wasm", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_wasm */ 206).then(__webpack_require__.t.bind(__webpack_require__, 89582, 23));
  }),
  wiki: create_language_async_loader("wiki", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_wiki */ 1253).then(__webpack_require__.t.bind(__webpack_require__, 29495, 23));
  }),
  xeora: create_language_async_loader("xeora", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_xeora */ 6574).then(__webpack_require__.t.bind(__webpack_require__, 82020, 23));
  }),
  xojo: create_language_async_loader("xojo", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_xojo */ 3116).then(__webpack_require__.t.bind(__webpack_require__, 57480, 23));
  }),
  xquery: create_language_async_loader("xquery", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_xquery */ 982).then(__webpack_require__.t.bind(__webpack_require__, 4932, 23));
  }),
  yaml: create_language_async_loader("yaml", function () {
    return __webpack_require__.e(/* import() | react-syntax-highlighter_languages_refractor_yaml */ 5983).then(__webpack_require__.t.bind(__webpack_require__, 16143, 23));
  })
});
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/node_modules/react-syntax-highlighter/dist/esm/prism-async-light.js


/* harmony default export */ const prism_async_light = (async_syntax_highlighter({
  loader: function loader() {
    return __webpack_require__.e(/* import() | react-syntax-highlighter/refractor-core-import */ 5082).then(__webpack_require__.t.bind(__webpack_require__, 33782, 23)).then(function (module) {
      // Webpack 3 returns module.exports as default as module, but webpack 4 returns module.exports as module.default
      return module.default || module;
    });
  },
  isLanguageRegistered: function isLanguageRegistered(instance, language) {
    return instance.registered(language);
  },
  languageLoaders: prism,
  registerLanguage: function registerLanguage(instance, name, language) {
    return instance.register(language);
  }
}));
;// CONCATENATED MODULE: ./node_modules/react-code-blocks/dist/react-code-blocks.esm.js




function react_code_blocks_esm_extends() {
  react_code_blocks_esm_extends = Object.assign || function (target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i];

      for (var key in source) {
        if (Object.prototype.hasOwnProperty.call(source, key)) {
          target[key] = source[key];
        }
      }
    }

    return target;
  };

  return react_code_blocks_esm_extends.apply(this, arguments);
}

function _inheritsLoose(subClass, superClass) {
  subClass.prototype = Object.create(superClass.prototype);
  subClass.prototype.constructor = subClass;
  subClass.__proto__ = superClass;
}

function react_code_blocks_esm_objectWithoutPropertiesLoose(source, excluded) {
  if (source == null) return {};
  var target = {};
  var sourceKeys = Object.keys(source);
  var key, i;

  for (i = 0; i < sourceKeys.length; i++) {
    key = sourceKeys[i];
    if (excluded.indexOf(key) >= 0) continue;
    target[key] = source[key];
  }

  return target;
}

function _taggedTemplateLiteralLoose(strings, raw) {
  if (!raw) {
    raw = strings.slice(0);
  }

  strings.raw = raw;
  return strings;
}

var DEFAULT_THEME_MODE = 'light'; // Resolves the different types of theme objects in the current API

function getTheme(props) {
  // If format not supported (or no theme provided), return standard theme
  return react_code_blocks_esm_extends({
    mode: DEFAULT_THEME_MODE
  }, props === null || props === void 0 ? void 0 : props.theme);
}

function themed(modesOrVariant) {
  var modes = modesOrVariant;
  return function (props) {
    var theme = getTheme(props);
    return modes[theme.mode];
  };
}

var defaultColors = function defaultColors(theme) {
  var rcbTheme = {
    theme: theme
  };
  return {
    lineNumberColor: themed({
      light: "#383a42",
      dark: "#abb2bf"
    })(rcbTheme),
    lineNumberBgColor: themed({
      light: "#fafafa",
      dark: "#282c34"
    })(rcbTheme),
    backgroundColor: themed({
      light: "#fafafa",
      dark: "#282c34"
    })(rcbTheme),
    textColor: themed({
      light: "#383a42",
      dark: "#abb2bf"
    })(rcbTheme),
    substringColor: themed({
      light: "#e45649",
      dark: "#e06c75"
    })(rcbTheme),
    keywordColor: themed({
      light: "#a626a4",
      dark: "#c678dd"
    })(rcbTheme),
    attributeColor: themed({
      light: "#50a14f",
      dark: "#98c379"
    })(rcbTheme),
    selectorAttributeColor: themed({
      light: "#e45649",
      dark: "#e06c75"
    })(rcbTheme),
    docTagColor: themed({
      light: "#a626a4",
      dark: "#c678dd"
    })(rcbTheme),
    nameColor: themed({
      light: "#e45649",
      dark: "#e06c75"
    })(rcbTheme),
    builtInColor: themed({
      light: "#c18401",
      dark: "#e6c07b"
    })(rcbTheme),
    literalColor: themed({
      light: "#0184bb",
      dark: "#56b6c2"
    })(rcbTheme),
    bulletColor: themed({
      light: "#4078f2",
      dark: "#61aeee"
    })(rcbTheme),
    codeColor: themed({
      light: "#383a42",
      dark: "#abb2bf"
    })(rcbTheme),
    additionColor: themed({
      light: "#50a14f",
      dark: "#98c379"
    })(rcbTheme),
    regexpColor: themed({
      light: "#50a14f",
      dark: "#98c379"
    })(rcbTheme),
    symbolColor: themed({
      light: "#4078f2",
      dark: "#61aeee"
    })(rcbTheme),
    variableColor: themed({
      light: "#986801",
      dark: "#d19a66"
    })(rcbTheme),
    templateVariableColor: themed({
      light: "#986801",
      dark: "#d19a66"
    })(rcbTheme),
    linkColor: themed({
      light: "#4078f2",
      dark: "#61aeee"
    })(rcbTheme),
    selectorClassColor: themed({
      light: "#986801",
      dark: "#d19a66"
    })(rcbTheme),
    typeColor: themed({
      light: "#986801",
      dark: "#d19a66"
    })(rcbTheme),
    stringColor: themed({
      light: "#50a14f",
      dark: "#98c379"
    })(rcbTheme),
    selectorIdColor: themed({
      light: "#4078f2",
      dark: "#61aeee"
    })(rcbTheme),
    quoteColor: themed({
      light: "#a0a1a7",
      dark: "#5c6370"
    })(rcbTheme),
    templateTagColor: themed({
      light: "#383a42",
      dark: "#abb2bf"
    })(rcbTheme),
    deletionColor: themed({
      light: "#e45649",
      dark: "#e06c75"
    })(rcbTheme),
    titleColor: themed({
      light: "#4078f2",
      dark: "#61aeee"
    })(rcbTheme),
    sectionColor: themed({
      light: "#e45649",
      dark: "#e06c75"
    })(rcbTheme),
    commentColor: themed({
      light: "#a0a1a7",
      dark: "#5c6370"
    })(rcbTheme),
    metaKeywordColor: themed({
      light: "#383a42",
      dark: "#abb2bf"
    })(rcbTheme),
    metaColor: themed({
      light: "#4078f2",
      dark: "#61aeee"
    })(rcbTheme),
    functionColor: themed({
      light: "#383a42",
      dark: "#abb2bf"
    })(rcbTheme),
    numberColor: themed({
      light: "#986801",
      dark: "#d19a66"
    })(rcbTheme)
  };
};

var codeFontFamily = "inherit";
var fontSize = "inherit";
var codeContainerStyle = {
  fontSize: fontSize,
  fontFamily: codeFontFamily,
  lineHeight: 20 / 12,
  padding: 8
};

var lineNumberContainerStyle = function lineNumberContainerStyle(theme) {
  return {
    fontSize: fontSize,
    lineHeight: 20 / 14,
    color: theme.lineNumberColor,
    backgroundColor: theme.lineNumberBgColor,
    flexShrink: 0,
    padding: 8,
    textAlign: "right",
    userSelect: "none"
  };
};

var sharedCodeStyle = function sharedCodeStyle(theme) {
  return {
    key: {
      color: theme.keywordColor,
      fontWeight: "bolder"
    },
    keyword: {
      color: theme.keywordColor,
      fontWeight: "bolder"
    },
    'attr-name': {
      color: theme.attributeColor
    },
    selector: {
      color: theme.selectorTagColor
    },
    comment: {
      color: theme.commentColor,
      fontFamily: codeFontFamily,
      fontStyle: "italic"
    },
    'block-comment': {
      color: theme.commentColor,
      fontFamily: codeFontFamily,
      fontStyle: "italic"
    },
    'function-name': {
      color: theme.sectionColor
    },
    'class-name': {
      color: theme.sectionColor
    },
    doctype: {
      color: theme.docTagColor
    },
    substr: {
      color: theme.substringColor
    },
    namespace: {
      color: theme.nameColor
    },
    builtin: {
      color: theme.builtInColor
    },
    entity: {
      color: theme.literalColor
    },
    bullet: {
      color: theme.bulletColor
    },
    code: {
      color: theme.codeColor
    },
    addition: {
      color: theme.additionColor
    },
    regex: {
      color: theme.regexpColor
    },
    symbol: {
      color: theme.symbolColor
    },
    variable: {
      color: theme.variableColor
    },
    url: {
      color: theme.linkColor
    },
    'selector-attr': {
      color: theme.selectorAttributeColor
    },
    'selector-pseudo': {
      color: theme.selectorPseudoColor
    },
    type: {
      color: theme.typeColor
    },
    string: {
      color: theme.stringColor
    },
    quote: {
      color: theme.quoteColor
    },
    tag: {
      color: theme.templateTagColor
    },
    deletion: {
      color: theme.deletionColor
    },
    title: {
      color: theme.titleColor
    },
    section: {
      color: theme.sectionColor
    },
    'meta-keyword': {
      color: theme.metaKeywordColor
    },
    meta: {
      color: theme.metaColor
    },
    italic: {
      fontStyle: "italic"
    },
    bold: {
      fontWeight: "bolder"
    },
    "function": {
      color: theme.functionColor
    },
    number: {
      color: theme.numberColor
    }
  };
};

var codeStyle = function codeStyle(theme) {
  return {
    fontSize: fontSize,
    fontFamily: codeFontFamily,
    background: theme.backgroundColor,
    color: theme.textColor,
    borderRadius: 3,
    display: "flex",
    lineHeight: 20 / 14,
    overflowX: "auto",
    whiteSpace: "pre"
  };
};

var codeBlockStyle = function codeBlockStyle(theme) {
  return react_code_blocks_esm_extends({
    'pre[class*="language-"]': codeStyle(theme)
  }, sharedCodeStyle(theme));
};

var inlineCodeStyle = function inlineCodeStyle(theme) {
  return react_code_blocks_esm_extends({
    'pre[class*="language-"]': react_code_blocks_esm_extends({}, codeStyle(theme), {
      padding: '2px 4px',
      display: 'inline',
      whiteSpace: 'pre-wrap'
    })
  }, sharedCodeStyle(theme));
};

function applyTheme(theme) {
  if (theme === void 0) {
    theme = {
      mode: 'light'
    };
  }

  var newTheme = react_code_blocks_esm_extends({}, defaultColors(theme), theme);

  return {
    lineNumberContainerStyle: lineNumberContainerStyle(newTheme),
    codeBlockStyle: codeBlockStyle(newTheme),
    inlineCodeStyle: inlineCodeStyle(newTheme),
    codeContainerStyle: codeContainerStyle
  };
}

var SUPPORTED_LANGUAGE_ALIASES = /*#__PURE__*/Object.freeze([{
  name: 'PHP',
  alias: ['php', 'php3', 'php4', 'php5'],
  value: 'php'
}, {
  name: 'Java',
  alias: ['java'],
  value: 'java'
}, {
  name: 'CSharp',
  alias: ['csharp', 'c#'],
  value: 'cs'
}, {
  name: 'Python',
  alias: ['python', 'py'],
  value: 'python'
}, {
  name: 'JavaScript',
  alias: ['javascript', 'js'],
  value: 'javascript'
}, {
  name: 'XML',
  alias: ['xml'],
  value: 'xml'
}, {
  name: 'HTML',
  alias: ['html', 'htm'],
  value: 'markup'
}, {
  name: 'C++',
  alias: ['c++', 'cpp', 'clike'],
  value: 'cpp'
}, {
  name: 'Ruby',
  alias: ['ruby', 'rb', 'duby'],
  value: 'ruby'
}, {
  name: 'Objective-C',
  alias: ['objective-c', 'objectivec', 'obj-c', 'objc'],
  value: 'objectivec'
}, {
  name: 'C',
  alias: ['c'],
  value: 'cpp'
}, {
  name: 'Swift',
  alias: ['swift'],
  value: 'swift'
}, {
  name: 'TeX',
  alias: ['tex', 'latex'],
  value: 'tex'
}, {
  name: 'Shell',
  alias: ['shell', 'sh', 'ksh', 'zsh'],
  value: 'bash'
}, {
  name: 'Scala',
  alias: ['scala'],
  value: 'scala'
}, {
  name: 'Go',
  alias: ['go'],
  value: 'go'
}, {
  name: 'ActionScript',
  alias: ['actionscript', 'actionscript3', 'as'],
  value: 'actionscript'
}, {
  name: 'ColdFusion',
  alias: ['coldfusion'],
  value: 'xml'
}, {
  name: 'JavaFX',
  alias: ['javafx', 'jfx'],
  value: 'java'
}, {
  name: 'VbNet',
  alias: ['vbnet', 'vb.net'],
  value: 'vbnet'
}, {
  name: 'JSON',
  alias: ['json'],
  value: 'json'
}, {
  name: 'MATLAB',
  alias: ['matlab'],
  value: 'matlab'
}, {
  name: 'Groovy',
  alias: ['groovy'],
  value: 'groovy'
}, {
  name: 'SQL',
  alias: ['sql', 'postgresql', 'postgres', 'plpgsql', 'psql', 'postgresql-console', 'postgres-console', 'tsql', 't-sql', 'mysql', 'sqlite'],
  value: 'sql'
}, {
  name: 'R',
  alias: ['r'],
  value: 'r'
}, {
  name: 'Perl',
  alias: ['perl', 'pl'],
  value: 'perl'
}, {
  name: 'Lua',
  alias: ['lua'],
  value: 'lua'
}, {
  name: 'Delphi',
  alias: ['delphi', 'pas', 'pascal', 'objectpascal'],
  value: 'delphi'
}, {
  name: 'XML',
  alias: ['xml'],
  value: 'xml'
}, {
  name: 'TypeScript',
  alias: ['typescript', 'ts', 'tsx'],
  value: 'typescript'
}, {
  name: 'CoffeeScript',
  alias: ['coffeescript', 'coffee-script', 'coffee'],
  value: 'coffeescript'
}, {
  name: 'Haskell',
  alias: ['haskell', 'hs'],
  value: 'haskell'
}, {
  name: 'Puppet',
  alias: ['puppet'],
  value: 'puppet'
}, {
  name: 'Arduino',
  alias: ['arduino'],
  value: 'arduino'
}, {
  name: 'Fortran',
  alias: ['fortran'],
  value: 'fortran'
}, {
  name: 'Erlang',
  alias: ['erlang', 'erl'],
  value: 'erlang'
}, {
  name: 'PowerShell',
  alias: ['powershell', 'posh', 'ps1', 'psm1'],
  value: 'powershell'
}, {
  name: 'Haxe',
  alias: ['haxe', 'hx', 'hxsl'],
  value: 'haxe'
}, {
  name: 'Elixir',
  alias: ['elixir', 'ex', 'exs'],
  value: 'elixir'
}, {
  name: 'Verilog',
  alias: ['verilog', 'v'],
  value: 'verilog'
}, {
  name: 'Rust',
  alias: ['rust'],
  value: 'rust'
}, {
  name: 'VHDL',
  alias: ['vhdl'],
  value: 'vhdl'
}, {
  name: 'Sass',
  alias: ['sass'],
  value: 'less'
}, {
  name: 'OCaml',
  alias: ['ocaml'],
  value: 'ocaml'
}, {
  name: 'Dart',
  alias: ['dart'],
  value: 'dart'
}, {
  name: 'CSS',
  alias: ['css'],
  value: 'css'
}, {
  name: 'reStructuredText',
  alias: ['restructuredtext', 'rst', 'rest'],
  value: 'rest'
}, {
  name: 'ObjectPascal',
  alias: ['objectpascal'],
  value: 'delphi'
}, {
  name: 'Kotlin',
  alias: ['kotlin'],
  value: 'kotlin'
}, {
  name: 'D',
  alias: ['d'],
  value: 'd'
}, {
  name: 'Octave',
  alias: ['octave'],
  value: 'matlab'
}, {
  name: 'QML',
  alias: ['qbs', 'qml'],
  value: 'qml'
}, {
  name: 'Prolog',
  alias: ['prolog'],
  value: 'prolog'
}, {
  name: 'FoxPro',
  alias: ['foxpro', 'vfp', 'clipper', 'xbase'],
  value: 'vbnet'
}, {
  name: 'Scheme',
  alias: ['scheme', 'scm'],
  value: 'scheme'
}, {
  name: 'CUDA',
  alias: ['cuda', 'cu'],
  value: 'cpp'
}, {
  name: 'Julia',
  alias: ['julia', 'jl'],
  value: 'julia'
}, {
  name: 'Racket',
  alias: ['racket', 'rkt'],
  value: 'lisp'
}, {
  name: 'Ada',
  alias: ['ada', 'ada95', 'ada2005'],
  value: 'ada'
}, {
  name: 'Tcl',
  alias: ['tcl'],
  value: 'tcl'
}, {
  name: 'Mathematica',
  alias: ['mathematica', 'mma', 'nb'],
  value: 'mathematica'
}, {
  name: 'Autoit',
  alias: ['autoit'],
  value: 'autoit'
}, {
  name: 'StandardML',
  alias: ['standardmL', 'sml', 'standardml'],
  value: 'sml'
}, {
  name: 'Objective-J',
  alias: ['objective-j', 'objectivej', 'obj-j', 'objj'],
  value: 'objectivec'
}, {
  name: 'Smalltalk',
  alias: ['smalltalk', 'squeak', 'st'],
  value: 'smalltalk'
}, {
  name: 'Vala',
  alias: ['vala', 'vapi'],
  value: 'vala'
}, {
  name: 'ABAP',
  alias: ['abap'],
  value: 'sql'
}, {
  name: 'LiveScript',
  alias: ['livescript', 'live-script'],
  value: 'livescript'
}, {
  name: 'XQuery',
  alias: ['xquery', 'xqy', 'xq', 'xql', 'xqm'],
  value: 'xquery'
}, {
  name: 'PlainText',
  alias: ['text', 'plaintext'],
  value: 'text'
}, {
  name: 'Yaml',
  alias: ['yaml', 'yml'],
  value: 'yaml'
}, {
  name: 'GraphQL',
  alias: ['graphql', 'gql'],
  value: 'graphql'
}]);
var normalizeLanguage = function normalizeLanguage(language) {
  if (!language) {
    return '';
  }

  var match = SUPPORTED_LANGUAGE_ALIASES.find(function (val) {
    return val.name === language || val.alias.includes(language);
  }); // Fallback to plain monospaced text if language passed but not supported

  return match ? match.value : language || 'text';
};

var Code = /*#__PURE__*/function (_PureComponent) {
  _inheritsLoose(Code, _PureComponent);

  function Code() {
    var _this;

    _this = _PureComponent.apply(this, arguments) || this;
    _this._isMounted = false;
    return _this;
  }

  var _proto = Code.prototype;

  _proto.componentDidMount = function componentDidMount() {
    this._isMounted = true;
  };

  _proto.componentWillUnmount = function componentWillUnmount() {
    this._isMounted = false;
  };

  _proto.getLineOpacity = function getLineOpacity(lineNumber) {
    if (!this.props.highlight) {
      return 1;
    }

    var highlight = this.props.highlight.split(',').map(function (num) {
      if (num.indexOf('-') > 0) {
        // We found a line group, e.g. 1-3
        var _num$split$map$sort = num.split('-').map(Number).sort(),
            from = _num$split$map$sort[0],
            to = _num$split$map$sort[1];

        return Array(to + 1).fill(undefined).map(function (_, index) {
          return index;
        }).slice(from, to + 1);
      }

      return Number(num);
    }).reduce(function (acc, val) {
      return acc.concat(val);
    }, []);

    if (highlight.length === 0) {
      return 1;
    }

    if (highlight.includes(lineNumber)) {
      return 1;
    }

    return 0.3;
  };

  _proto.render = function render() {
    var _this2 = this;

    var _applyTheme = applyTheme(this.props.theme),
        inlineCodeStyle = _applyTheme.inlineCodeStyle;

    var language = normalizeLanguage(this.props.language);
    var props = {
      language: language,
      PreTag: this.props.preTag,
      style: this.props.codeStyle || inlineCodeStyle,
      showLineNumbers: this.props.showLineNumbers,
      startingLineNumber: this.props.startingLineNumber,
      codeTagProps: this.props.codeTagProps
    };
    return react.createElement(prism_async_light, Object.assign({}, props, {
      // Wrap lines is needed to set styles on the line.
      // We use this to set opacity if highlight specific lines.
      wrapLines: this.props.highlight.length > 0,
      customStyle: this.props.customStyle,
      // Types are incorrect.
      // @ts-ignore
      lineProps: function lineProps(lineNumber) {
        return {
          style: react_code_blocks_esm_extends({
            opacity: _this2.getLineOpacity(lineNumber)
          }, _this2.props.lineNumberContainerStyle)
        };
      }
    }), this.props.text);
  };

  return Code;
}(react.PureComponent);
Code.defaultProps = {
  theme: {},
  showLineNumbers: false,
  startingLineNumber: 1,
  lineNumberContainerStyle: {},
  codeTagProps: {},
  preTag: 'span',
  highlight: '',
  customStyle: {}
};

var CodeWithTheme = /*#__PURE__*/Xe(Code);
function ThemedCode (props) {
  return react.createElement(CodeWithTheme, Object.assign({}, props));
}

var LANGUAGE_FALLBACK = 'text';

var CodeBlock = /*#__PURE__*/function (_PureComponent) {
  _inheritsLoose(CodeBlock, _PureComponent);

  function CodeBlock() {
    var _this;

    _this = _PureComponent.apply(this, arguments) || this;
    _this._isMounted = false;

    _this.handleCopy = function (event) {
      /**
       * We don't want to copy the markup after highlighting, but rather the preformatted text in the selection
       */
      var data = event.nativeEvent.clipboardData;

      if (data) {
        event.preventDefault();
        var selection = window.getSelection();

        if (selection === null) {
          return;
        }

        var selectedText = selection.toString();
        var document = "<!doctype html><html><head></head><body><pre>" + selectedText + "</pre></body></html>";
        data.clearData();
        data.setData('text/html', document);
        data.setData('text/plain', selectedText);
      }
    };

    return _this;
  }

  var _proto = CodeBlock.prototype;

  _proto.componentDidMount = function componentDidMount() {
    this._isMounted = true;
  };

  _proto.componentWillUnmount = function componentWillUnmount() {
    this._isMounted = false;
  };

  _proto.render = function render() {
    var _this$props, _this$props2, _this$props3, _this$props4;

    var _applyTheme = applyTheme(this.props.theme),
        lineNumberContainerStyle = _applyTheme.lineNumberContainerStyle,
        codeBlockStyle = _applyTheme.codeBlockStyle,
        codeContainerStyle = _applyTheme.codeContainerStyle;

    var props = {
      language: this.props.language || LANGUAGE_FALLBACK,
      codeStyle: react_code_blocks_esm_extends({}, codeBlockStyle, (_this$props = this.props) === null || _this$props === void 0 ? void 0 : _this$props.codeBlockStyle),
      customStyle: (_this$props2 = this.props) === null || _this$props2 === void 0 ? void 0 : _this$props2.customStyle,
      showLineNumbers: this.props.showLineNumbers,
      startingLineNumber: this.props.startingLineNumber,
      codeTagProps: {
        style: react_code_blocks_esm_extends({}, codeContainerStyle, (_this$props3 = this.props) === null || _this$props3 === void 0 ? void 0 : _this$props3.codeContainerStyle)
      },
      lineNumberContainerStyle: react_code_blocks_esm_extends({}, lineNumberContainerStyle, (_this$props4 = this.props) === null || _this$props4 === void 0 ? void 0 : _this$props4.lineNumberContainerStyle),
      text: this.props.text.toString(),
      highlight: this.props.highlight
    };
    return react.createElement(Code, Object.assign({}, props));
  };

  return CodeBlock;
}(react.PureComponent);
CodeBlock.displayName = 'CodeBlock';
CodeBlock.defaultProps = {
  showLineNumbers: true,
  startingLineNumber: 1,
  language: LANGUAGE_FALLBACK,
  theme: {},
  highlight: '',
  lineNumberContainerStyle: {},
  customStyle: {},
  codeBlockStyle: {}
};

var CodeBlockWithTheme = /*#__PURE__*/Xe(CodeBlock);
function ThemedCodeBlock (props) {
  return react.createElement(CodeBlockWithTheme, Object.assign({}, props));
}

var ClipboardListIcon = function ClipboardListIcon(_ref) {
  var size = _ref.size,
      color = _ref.color,
      props = react_code_blocks_esm_objectWithoutPropertiesLoose(_ref, ["size", "color"]);

  return react.createElement("svg", Object.assign({}, props, {
    viewBox: "0 0 384 512",
    width: size,
    height: size,
    fill: color
  }), react.createElement("path", {
    d: "M280 240H168c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h112c4.4 0 8-3.6 8-8v-16c0-4.4-3.6-8-8-8zm0 96H168c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h112c4.4 0 8-3.6 8-8v-16c0-4.4-3.6-8-8-8zM112 232c-13.3 0-24 10.7-24 24s10.7 24 24 24 24-10.7 24-24-10.7-24-24-24zm0 96c-13.3 0-24 10.7-24 24s10.7 24 24 24 24-10.7 24-24-10.7-24-24-24zM336 64h-80c0-35.3-28.7-64-64-64s-64 28.7-64 64H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48zM192 48c8.8 0 16 7.2 16 16s-7.2 16-16 16-16-7.2-16-16 7.2-16 16-16zm144 408c0 4.4-3.6 8-8 8H56c-4.4 0-8-3.6-8-8V120c0-4.4 3.6-8 8-8h40v32c0 8.8 7.2 16 16 16h160c8.8 0 16-7.2 16-16v-32h40c4.4 0 8 3.6 8 8v336z"
  }));
};

ClipboardListIcon.displayName = "ClipboardListIcon";
ClipboardListIcon.defaultProps = {
  size: '16pt',
  color: "currentcolor"
};

var ClipboardCheckIcon = function ClipboardCheckIcon(_ref2) {
  var size = _ref2.size,
      color = _ref2.color,
      props = react_code_blocks_esm_objectWithoutPropertiesLoose(_ref2, ["size", "color"]);

  return react.createElement("svg", Object.assign({}, props, {
    viewBox: "0 0 384 512",
    width: size,
    height: size,
    fill: color
  }), react.createElement("path", {
    d: "M336 64h-80c0-35.3-28.7-64-64-64s-64 28.7-64 64H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48zM192 40c13.3 0 24 10.7 24 24s-10.7 24-24 24-24-10.7-24-24 10.7-24 24-24zm121.2 231.8l-143 141.8c-4.7 4.7-12.3 4.6-17-.1l-82.6-83.3c-4.7-4.7-4.6-12.3.1-17L99.1 285c4.7-4.7 12.3-4.6 17 .1l46 46.4 106-105.2c4.7-4.7 12.3-4.6 17 .1l28.2 28.4c4.7 4.8 4.6 12.3-.1 17z"
  }));
};

ClipboardCheckIcon.displayName = "ClipboardCheckIcon";
ClipboardCheckIcon.defaultProps = {
  size: '16pt',
  color: "currentcolor"
};
function Copy (_ref3) {
  var size = _ref3.size,
      color = _ref3.color,
      copied = _ref3.copied,
      props = react_code_blocks_esm_objectWithoutPropertiesLoose(_ref3, ["size", "color", "copied"]);

  if (copied) {
    return react.createElement(ClipboardCheckIcon, Object.assign({}, {
      color: color,
      size: size
    }, props));
  }

  return react.createElement(ClipboardListIcon, Object.assign({}, {
    color: color,
    size: size
  }, props));
}

var isBrowser = function isBrowser() {
  return Boolean(typeof window !== 'undefined' && window.document && window.document.createElement);
};

var useSSR = function useSSR() {
  var _useState = (0,react.useState)(false),
      browser = _useState[0],
      setBrowser = _useState[1];

  (0,react.useEffect)(function () {
    setBrowser(isBrowser());
  }, []);
  return {
    isBrowser: browser,
    isServer: !browser
  };
};

var getId = function getId() {
  return Math.random().toString(32).slice(2, 10);
};

var react_code_blocks_esm_createElement = function createElement(id) {
  var el = document.createElement('div');
  el.setAttribute('id', id);
  return el;
};

var usePortal = function usePortal(selectId) {
  if (selectId === void 0) {
    selectId = getId();
  }

  var id = "zeit-ui-" + selectId;

  var _useSSR = useSSR(),
      isBrowser = _useSSR.isBrowser;

  var _useState = (0,react.useState)(isBrowser ? react_code_blocks_esm_createElement(id) : null),
      elSnapshot = _useState[0],
      setElSnapshot = _useState[1];

  (0,react.useEffect)(function () {
    var hasElement = document.querySelector("#" + id);
    var el = hasElement || react_code_blocks_esm_createElement(id);

    if (!hasElement) {
      document.body.appendChild(el);
    }

    setElSnapshot(el);
  }, []);
  return elSnapshot;
};

var warningStack = {};

var useWarning = function useWarning(message, component) {
  var tag = component ? " [" + component + "]" : ' ';
  var log = "[Zeit UI]" + tag + ": " + message;
  if (typeof console === 'undefined') return;
  if (warningStack[log]) return;
  warningStack[log] = true;

  if (false) {}

  console.warn(log);
};

var defaultOptions = {
  onError: function onError() {
    return useWarning('Failed to copy.', 'use-clipboard');
  }
};

var useClipboard = function useClipboard(options) {
  if (options === void 0) {
    options = defaultOptions;
  }

  var el = usePortal('clipboard');

  var copyText = function copyText(el, text) {
    if (!el || !text) return;
    var selection = window.getSelection();
    if (!selection) return;
    el.style.whiteSpace = 'pre';
    el.textContent = text;
    var range = window.document.createRange();
    selection.removeAllRanges();
    range.selectNode(el);
    selection.addRange(range);

    try {
      window.document.execCommand('copy');
    } catch (e) {
      options.onError && options.onError();
    }

    selection.removeAllRanges();

    if (el) {
      el.textContent = '';
    }
  };

  var copy = (0,react.useCallback)(function (text) {
    copyText(el, text);
  }, [el]);
  return {
    copy: copy
  };
};

function _templateObject2() {
  var data = _taggedTemplateLiteralLoose(["\n  position: relative;\n  background: ", ";\n  border-radius: 0.25rem;\n  padding: ", ";\n"]);

  _templateObject2 = function _templateObject2() {
    return data;
  };

  return data;
}

function _templateObject() {
  var data = _taggedTemplateLiteralLoose(["\n  position: absolute;\n  top: 0.5em;\n  right: 0.75em;\n  display: flex;\n  flex-wrap: wrap;\n  justify-content: center;\n  align-items: center;\n  background: ", ";\n  margin-top: 0.15rem;\n  border-radius: 0.25rem;\n  max-height: 2rem;\n  max-width: 2rem;\n  padding: 0.25rem;\n  &:hover {\n    opacity: ", ";\n  }\n  &:focus {\n    outline: none;\n    opacity: 1;\n  }\n  .icon {\n    width: 1rem;\n    height: 1rem;\n  }\n"]);

  _templateObject = function _templateObject() {
    return data;
  };

  return data;
}
var Button = /*#__PURE__*/styled_components_browser_esm.button( /*#__PURE__*/_templateObject(), function (p) {
  return p.theme.backgroundColor;
}, function (p) {
  return p.copied ? 1 : 0.5;
});
var Snippet = /*#__PURE__*/styled_components_browser_esm.div( /*#__PURE__*/_templateObject2(), function (p) {
  return p.theme.backgroundColor;
}, function (p) {
  return p.codeBlock ? "0.25rem 0.5rem 0.25rem 0.25rem" : "0.25rem";
});
function CopyBlock(_ref) {
  var theme = _ref.theme,
      text = _ref.text,
      _ref$codeBlock = _ref.codeBlock,
      codeBlock = _ref$codeBlock === void 0 ? false : _ref$codeBlock,
      _ref$customStyle = _ref.customStyle,
      customStyle = _ref$customStyle === void 0 ? {} : _ref$customStyle,
      onCopy = _ref.onCopy,
      rest = react_code_blocks_esm_objectWithoutPropertiesLoose(_ref, ["theme", "text", "codeBlock", "customStyle", "onCopy"]);

  var _useState = (0,react.useState)(false),
      copied = _useState[0],
      toggleCopy = _useState[1];

  var _useClipboard = useClipboard(),
      copy = _useClipboard.copy;

  var handler = function handler() {
    copy(text);
    onCopy ? onCopy() : toggleCopy(!copied);
  };

  return react.createElement(Snippet, Object.assign({}, {
    codeBlock: codeBlock
  }, {
    style: customStyle,
    theme: theme
  }), codeBlock ? // @ts-ignore
  react.createElement(CodeBlock, Object.assign({
    text: text,
    theme: theme
  }, rest)) : // @ts-ignore
  react.createElement(Code, Object.assign({
    text: text,
    theme: theme
  }, rest)), react.createElement(Button, Object.assign({
    type: "button",
    onClick: handler
  }, {
    theme: theme,
    copied: copied
  }), react.createElement(Copy, {
    color: copied ? theme.stringColor : theme.textColor,
    copied: copied,
    className: "icon",
    size: "16pt"
  })));
}

var CopyBlockWithTheme = /*#__PURE__*/Xe(CopyBlock);
function ThemedCopyBlock (props) {
  return react.createElement(CopyBlockWithTheme, Object.assign({}, props));
}

var withDefaults = function withDefaults(component, defaultProps) {
  component.defaultProps = defaultProps;
  return component;
};

var getStyles = function getStyles(theme) {
  var styles = {
    color: theme.textColor,
    border: theme.lineNumberBgColor,
    bgColor: theme.backgroundColor
  };
  return styles;
};

var SnippetIcon = function SnippetIcon() {
  return react.createElement("svg", {
    viewBox: "0 0 24 24",
    width: "22",
    height: "22",
    stroke: "currentColor",
    strokeWidth: "1.5",
    strokeLinecap: "round",
    strokeLinejoin: "round",
    fill: "none",
    shapeRendering: "geometricPrecision",
    style: {
      color: 'currentcolor'
    }
  }, react.createElement("path", {
    d: "M8 17.929H6c-1.105 0-2-.912-2-2.036V5.036C4 3.91 4.895 3 6 3h8c1.105 0 2 .911 2 2.036v1.866m-6 .17h8c1.105 0 2 .91 2 2.035v10.857C20 21.09 19.105 22 18 22h-8c-1.105 0-2-.911-2-2.036V9.107c0-1.124.895-2.036 2-2.036z"
  }));
};

var SnippetIcon$1 = /*#__PURE__*/react.memo(SnippetIcon);

function _templateObject$1() {
  var data = _taggedTemplateLiteralLoose(["\n   {\n    position: relative;\n    width: ", ";\n    max-width: 100%;\n    padding: 8pt;\n    padding-right: calc(2 * 16pt);\n    color: ", ";\n    background-color: ", ";\n    border: 1px solid ", ";\n    border-radius: 5px;\n  }\n  pre {\n    margin: 0;\n    padding: 0;\n    border: none;\n    background-color: transparent;\n    color: ", ";\n    font-size: 0.8125rem;\n  }\n  pre::before {\n    content: '$ ';\n    user-select: none;\n  }\n  pre :global(*) {\n    margin: 0;\n    padding: 0;\n    font-size: inherit;\n    color: inherit;\n  }\n  .copy {\n    position: absolute;\n    right: 0;\n    top: -2px;\n    transform: translateY(50%);\n    background-color: ", ";\n    display: inline-flex;\n    justify-content: center;\n    align-items: center;\n    width: calc(2 * 16pt);\n    color: inherit;\n    transition: opacity 0.2s ease 0s;\n    border-radius: 5px;\n    cursor: pointer;\n    user-select: none;\n  }\n  .copy:hover {\n    opacity: 0.7;\n  }\n"]);

  _templateObject$1 = function _templateObject() {
    return data;
  };

  return data;
}
var SnippetWrapper = /*#__PURE__*/styled_components_browser_esm.div( /*#__PURE__*/_templateObject$1(), function (_ref) {
  var width = _ref.width;
  return width;
}, function (_ref2) {
  var style = _ref2.style;
  return style.color;
}, function (_ref3) {
  var style = _ref3.style;
  return style.bgColor;
}, function (_ref4) {
  var style = _ref4.style;
  return style.border;
}, function (_ref5) {
  var style = _ref5.style;
  return style.color;
}, function (_ref6) {
  var style = _ref6.style;
  return style.bgColor;
});
var defaultProps = {
  width: 'initial',
  copy: 'default',
  className: ''
};

var textArrayToString = function textArrayToString(text) {
  return text.reduce(function (pre, current) {
    if (!current) return pre;
    return pre ? pre + "\n" + current : current;
  }, '');
};

var Snippet$1 = function Snippet(_ref7) {
  var children = _ref7.children,
      text = _ref7.text,
      copyType = _ref7.copy,
      className = _ref7.className,
      props = react_code_blocks_esm_objectWithoutPropertiesLoose(_ref7, ["children", "text", "width", "copy", "className"]);

  var _useClipboard = useClipboard(),
      copy = _useClipboard.copy;

  var ref = (0,react.useRef)(null);
  var isMultiLine = text && Array.isArray(text);
  var theme = (0,react.useContext)(Ge);
  var style = (0,react.useMemo)(function () {
    return getStyles(theme);
  }, [theme]);
  var showCopyIcon = (0,react.useMemo)(function () {
    return copyType !== 'prevent';
  }, [copyType]);
  var childText = (0,react.useMemo)(function () {
    if (isMultiLine) return textArrayToString(text);
    if (!children) return text;
    if (!ref.current) return '';
    return ref.current.textContent;
  }, [ref.current, children, text]);

  var clickHandler = function clickHandler() {
    if (!childText || !showCopyIcon) return;
    copy(childText);
    if (copyType === 'slient') return;
  };

  return react.createElement(SnippetWrapper, Object.assign({
    className: "" + className
  }, props, {
    style: style
  }), isMultiLine ? text.map(function (t, index) {
    return react.createElement("pre", {
      key: "snippet-" + index + "-" + t
    }, t);
  }) : react.createElement("pre", {
    ref: ref
  }, children || text), showCopyIcon && react.createElement("div", {
    className: "copy",
    onClick: clickHandler
  }, react.createElement(SnippetIcon$1, null)));
};

var MemoSnippet = /*#__PURE__*/react.memo(Snippet$1);
var Snippet$2 = /*#__PURE__*/withDefaults(MemoSnippet, defaultProps);

var SnippetWithTheme = /*#__PURE__*/Xe(Snippet$2);
function ThemedSnippet (props) {
  return react.createElement(SnippetWithTheme, Object.assign({}, props));
}

var a11yDark = {
  lineNumberColor: "#f8f8f2",
  lineNumberBgColor: "#2b2b2b",
  backgroundColor: "#2b2b2b",
  textColor: "#f8f8f2",
  substringColor: "#f8f8f2",
  keywordColor: "#dcc6e0",
  attributeColor: "#ffd700",
  selectorAttributeColor: "#dcc6e0",
  docTagColor: "#f8f8f2",
  nameColor: "#ffa07a",
  builtInColor: "#f5ab35",
  literalColor: "#f5ab35",
  bulletColor: "#abe338",
  codeColor: "#f8f8f2",
  additionColor: "#abe338",
  regexpColor: "#ffa07a",
  symbolColor: "#abe338",
  variableColor: "#ffa07a",
  templateVariableColor: "#ffa07a",
  linkColor: "#f5ab35",
  selectorClassColor: "#ffa07a",
  typeColor: "#f5ab35",
  stringColor: "#abe338",
  selectorIdColor: "#ffa07a",
  quoteColor: "#d4d0ab",
  templateTagColor: "#f8f8f2",
  deletionColor: "#ffa07a",
  titleColor: "#00e0e0",
  sectionColor: "#00e0e0",
  commentColor: "#d4d0ab",
  metaKeywordColor: "#f8f8f2",
  metaColor: "#f5ab35",
  functionColor: "#f8f8f2",
  numberColor: "#f5ab35"
};

var a11yLight = {
  lineNumberColor: "#545454",
  lineNumberBgColor: "#fefefe",
  backgroundColor: "#fefefe",
  textColor: "#545454",
  substringColor: "#545454",
  keywordColor: "#7928a1",
  attributeColor: "#aa5d00",
  selectorAttributeColor: "#7928a1",
  docTagColor: "#545454",
  nameColor: "#d91e18",
  builtInColor: "#aa5d00",
  literalColor: "#aa5d00",
  bulletColor: "#008000",
  codeColor: "#545454",
  additionColor: "#008000",
  regexpColor: "#d91e18",
  symbolColor: "#008000",
  variableColor: "#d91e18",
  templateVariableColor: "#d91e18",
  linkColor: "#aa5d00",
  selectorClassColor: "#d91e18",
  typeColor: "#aa5d00",
  stringColor: "#008000",
  selectorIdColor: "#d91e18",
  quoteColor: "#696969",
  templateTagColor: "#545454",
  deletionColor: "#d91e18",
  titleColor: "#007faa",
  sectionColor: "#007faa",
  commentColor: "#696969",
  metaKeywordColor: "#545454",
  metaColor: "#aa5d00",
  functionColor: "#545454",
  numberColor: "#aa5d00"
};

var anOldHope = {
  lineNumberColor: "#c0c5ce",
  lineNumberBgColor: "#1C1D21",
  backgroundColor: "#1C1D21",
  textColor: "#c0c5ce",
  substringColor: "#c0c5ce",
  keywordColor: "#B45EA4",
  attributeColor: "#EE7C2B",
  selectorAttributeColor: "#B45EA4",
  docTagColor: "#c0c5ce",
  nameColor: "#EB3C54",
  builtInColor: "#E7CE56",
  literalColor: "#E7CE56",
  bulletColor: "#4FB4D7",
  codeColor: "#c0c5ce",
  additionColor: "#4FB4D7",
  regexpColor: "#EB3C54",
  symbolColor: "#4FB4D7",
  variableColor: "#EB3C54",
  templateVariableColor: "#EB3C54",
  linkColor: "#E7CE56",
  selectorClassColor: "#EB3C54",
  typeColor: "#E7CE56",
  stringColor: "#4FB4D7",
  selectorIdColor: "#EB3C54",
  quoteColor: "#B6B18B",
  templateTagColor: "#c0c5ce",
  deletionColor: "#EB3C54",
  titleColor: "#78BB65",
  sectionColor: "#78BB65",
  commentColor: "#B6B18B",
  metaKeywordColor: "#c0c5ce",
  metaColor: "#E7CE56",
  functionColor: "#c0c5ce",
  numberColor: "#E7CE56"
};

var androidstudio = {
  lineNumberColor: "#a9b7c6",
  lineNumberBgColor: "#282b2e",
  backgroundColor: "#282b2e",
  textColor: "#a9b7c6",
  substringColor: "#a9b7c6",
  keywordColor: "#cc7832",
  attributeColor: "#6A8759",
  selectorAttributeColor: "#cc7832",
  docTagColor: "#a9b7c6",
  nameColor: "#e8bf6a",
  builtInColor: "#a9b7c6",
  literalColor: "#6897BB",
  bulletColor: "#6897BB",
  codeColor: "#a9b7c6",
  additionColor: "#6A8759",
  regexpColor: "#a9b7c6",
  symbolColor: "#6897BB",
  variableColor: "#629755",
  templateVariableColor: "#629755",
  linkColor: "#629755",
  selectorClassColor: "#e8bf6a",
  typeColor: "#ffc66d",
  stringColor: "#6A8759",
  selectorIdColor: "#e8bf6a",
  quoteColor: "#808080",
  templateTagColor: "#a9b7c6",
  deletionColor: "#cc7832",
  titleColor: "#ffc66d",
  sectionColor: "#ffc66d",
  commentColor: "#808080",
  metaKeywordColor: "#a9b7c6",
  metaColor: "#bbb529",
  functionColor: "#a9b7c6",
  numberColor: "#6897BB"
};

var arta = {
  lineNumberColor: "#aaa",
  lineNumberBgColor: "#222",
  backgroundColor: "#222",
  textColor: "#aaa",
  substringColor: "#aaa",
  keywordColor: "#6644aa",
  attributeColor: "#32aaee",
  selectorAttributeColor: "#6644aa",
  docTagColor: undefined,
  nameColor: "#6644aa",
  builtInColor: "#32aaee",
  literalColor: "#32aaee",
  bulletColor: "#ffcc33",
  codeColor: "#aaa",
  additionColor: "#00cc66",
  regexpColor: "#ffcc33",
  symbolColor: "#ffcc33",
  variableColor: "#bb1166",
  templateVariableColor: "#32aaee",
  linkColor: "#32aaee",
  selectorClassColor: "#6644aa",
  typeColor: "#32aaee",
  stringColor: "#ffcc33",
  selectorIdColor: "#6644aa",
  quoteColor: "#444",
  templateTagColor: "#bb1166",
  deletionColor: "#bb1166",
  titleColor: "#bb1166",
  sectionColor: "#fff",
  commentColor: "#444",
  metaKeywordColor: "#aaa",
  metaColor: "#444",
  functionColor: "#aaa",
  numberColor: "#00cc66"
};

var atomOneDark = {
  lineNumberColor: "#abb2bf",
  lineNumberBgColor: "#282c34",
  backgroundColor: "#282c34",
  textColor: "#abb2bf",
  substringColor: "#e06c75",
  keywordColor: "#c678dd",
  attributeColor: "#98c379",
  selectorAttributeColor: "#e06c75",
  docTagColor: "#c678dd",
  nameColor: "#e06c75",
  builtInColor: "#e6c07b",
  literalColor: "#56b6c2",
  bulletColor: "#61aeee",
  codeColor: "#abb2bf",
  additionColor: "#98c379",
  regexpColor: "#98c379",
  symbolColor: "#61aeee",
  variableColor: "#d19a66",
  templateVariableColor: "#d19a66",
  linkColor: "#61aeee",
  selectorClassColor: "#d19a66",
  typeColor: "#d19a66",
  stringColor: "#98c379",
  selectorIdColor: "#61aeee",
  quoteColor: "#5c6370",
  templateTagColor: "#abb2bf",
  deletionColor: "#e06c75",
  titleColor: "#61aeee",
  sectionColor: "#e06c75",
  commentColor: "#5c6370",
  metaKeywordColor: "#abb2bf",
  metaColor: "#61aeee",
  functionColor: "#abb2bf",
  numberColor: "#d19a66"
};

var atomOneLight = {
  lineNumberColor: "#383a42",
  lineNumberBgColor: "#fafafa",
  backgroundColor: "#fafafa",
  textColor: "#383a42",
  substringColor: "#e45649",
  keywordColor: "#a626a4",
  attributeColor: "#50a14f",
  selectorAttributeColor: "#e45649",
  docTagColor: "#a626a4",
  nameColor: "#e45649",
  builtInColor: "#c18401",
  literalColor: "#0184bb",
  bulletColor: "#4078f2",
  codeColor: "#383a42",
  additionColor: "#50a14f",
  regexpColor: "#50a14f",
  symbolColor: "#4078f2",
  variableColor: "#986801",
  templateVariableColor: "#986801",
  linkColor: "#4078f2",
  selectorClassColor: "#986801",
  typeColor: "#986801",
  stringColor: "#50a14f",
  selectorIdColor: "#4078f2",
  quoteColor: "#a0a1a7",
  templateTagColor: "#383a42",
  deletionColor: "#e45649",
  titleColor: "#4078f2",
  sectionColor: "#e45649",
  commentColor: "#a0a1a7",
  metaKeywordColor: "#383a42",
  metaColor: "#4078f2",
  functionColor: "#383a42",
  numberColor: "#986801"
};

var codepen = {
  lineNumberColor: "#fff",
  lineNumberBgColor: "#222",
  backgroundColor: "#222",
  textColor: "#fff",
  substringColor: "#fff",
  keywordColor: "#8f9c6c",
  attributeColor: "#9b869b",
  selectorTagColor: "#8f9c6c",
  docTagColor: "#fff",
  nameColor: "#9b869b",
  builtInColor: "#ab875d",
  literalColor: "#ab875d",
  bulletColor: "#ab875d",
  codeColor: "#fff",
  additionColor: "#8f9c6c",
  regexpColor: "#ab875d",
  symbolColor: "#ab875d",
  variableColor: "#ab875d",
  templateVariableColor: "#ab875d",
  linkColor: "#ab875d",
  selectorAttributeColor: "#fff",
  selectorPseudoColor: "#ff",
  typeColor: "#9b869b",
  stringColor: "#8f9c6c",
  selectorIdColor: "#9b869b",
  selectorClassColor: "#9b869b",
  quoteColor: "#777",
  templateTagColor: "#ab875d",
  deletionColor: "#ab875d",
  titleColor: "#9b869b",
  sectionColor: "#9b869b",
  commentColor: "#777",
  metaKeywordColor: "#ab875d",
  metaColor: "#ab875d",
  functionColor: "#fff",
  numberColor: "#ab875d"
};

var dracula = {
  lineNumberColor: "#6272a4",
  lineNumberBgColor: "#282a36",
  backgroundColor: "#282a36",
  textColor: "#f8f8f2",
  substringColor: "#f1fa8c",
  keywordColor: "#ff79c6",
  attributeColor: "#50fa7b",
  selectorTagColor: "#8be9fd",
  docTagColor: "#f1fa8c",
  nameColor: "#66d9ef",
  builtInColor: "#50fa7b",
  literalColor: "#FF79C6",
  bulletColor: "#8BE9FD",
  codeColor: "#50FA7B",
  additionColor: "#f1fa8c",
  regexpColor: "#F1FA8C",
  symbolColor: "#F1FA8C",
  variableColor: "#F8F8F2",
  templateVariableColor: "#FF79C6",
  linkColor: "#00bcd4",
  selectorAttributeColor: "#FF79C6",
  selectorPseudoColor: "#FF79C6",
  typeColor: "#8BE9FD",
  stringColor: "#F1FA8C",
  selectorIdColor: "#50FA7B",
  selectorClassColor: "#50FA7B",
  quoteColor: "#E9F284",
  templateTagColor: "#FF79C6",
  deletionColor: "#FF79C6",
  titleColor: "#ff555580",
  sectionColor: "#F8F8F2",
  commentColor: "#6272A4",
  metaKeywordColor: "#50FA7B",
  metaColor: "#50FA7B",
  functionColor: "#50FA7B",
  numberColor: "#bd93f9"
};

var far = {
  lineNumberColor: "#0ff",
  lineNumberBgColor: "#000080",
  backgroundColor: "#000080",
  textColor: "#0ff",
  substringColor: "#0ff",
  keywordColor: "#fff",
  attributeColor: "#ff0",
  selectorAttributeColor: "#fff",
  docTagColor: "#888",
  nameColor: "#fff",
  builtInColor: "#ff0",
  literalColor: "#0f0",
  bulletColor: "#ff0",
  codeColor: "#0ff",
  additionColor: "#ff0",
  regexpColor: "#0f0",
  symbolColor: "#ff0",
  variableColor: "#fff",
  templateVariableColor: "#ff0",
  linkColor: "#0f0",
  selectorClassColor: "#fff",
  typeColor: "#fff",
  stringColor: "#ff0",
  selectorIdColor: "#fff",
  quoteColor: "#888",
  templateTagColor: "#ff0",
  deletionColor: "#888",
  titleColor: "#0ff",
  sectionColor: "#fff",
  commentColor: "#888",
  metaKeywordColor: "#0ff",
  metaColor: "#008080",
  functionColor: "#0ff",
  numberColor: "#0f0"
};

var github = {
  lineNumberColor: "#333333",
  lineNumberBgColor: "white",
  backgroundColor: "white",
  textColor: "#333333",
  substringColor: "#333333",
  keywordColor: "#a71d5d",
  attributeColor: "#0086b3",
  selectorAttributeColor: "#a71d5d",
  docTagColor: "#333333",
  nameColor: "#63a35c",
  builtInColor: "#333333",
  literalColor: "#0086b3",
  bulletColor: "#0086b3",
  codeColor: "#333333",
  additionColor: "#55a532",
  regexpColor: "#333333",
  symbolColor: "#0086b3",
  variableColor: "#df5000",
  templateVariableColor: "#df5000",
  linkColor: "#0366d6",
  selectorClassColor: "#795da3",
  typeColor: "#a71d5d",
  stringColor: "#df5000",
  selectorIdColor: "#795da3",
  quoteColor: "#df5000",
  templateTagColor: "#333333",
  deletionColor: "#bd2c00",
  titleColor: "#795da3",
  sectionColor: "#63a35c",
  commentColor: "#969896",
  metaKeywordColor: "#333333",
  metaColor: "#969896",
  functionColor: "#333333",
  numberColor: "#333333"
};

var googlecode = {
  lineNumberColor: "black",
  lineNumberBgColor: "white",
  backgroundColor: "white",
  textColor: "black",
  substringColor: "#000",
  keywordColor: "#008",
  attributeColor: "#000",
  selectorAttributeColor: "#008",
  docTagColor: "#606",
  nameColor: "#008",
  builtInColor: "#606",
  literalColor: "#066",
  bulletColor: "#066",
  codeColor: "black",
  additionColor: undefined,
  regexpColor: "#080",
  symbolColor: "#066",
  variableColor: "#660",
  templateVariableColor: "#660",
  linkColor: "#066",
  selectorClassColor: "#9B703F",
  typeColor: "#606",
  stringColor: "#080",
  selectorIdColor: "#9B703F",
  quoteColor: "#800",
  templateTagColor: "black",
  deletionColor: undefined,
  titleColor: "#606",
  sectionColor: "#008",
  commentColor: "#800",
  metaKeywordColor: "black",
  metaColor: "#066",
  functionColor: "black",
  numberColor: "#066"
};

var hopscotch = {
  lineNumberColor: "#b9b5b8",
  lineNumberBgColor: "#322931",
  backgroundColor: "#322931",
  textColor: "#b9b5b8",
  substringColor: "#b9b5b8",
  keywordColor: "#c85e7c",
  attributeColor: "#dd464c",
  selectorAttributeColor: "#c85e7c",
  docTagColor: "#b9b5b8",
  nameColor: "#dd464c",
  builtInColor: "#fd8b19",
  literalColor: "#fd8b19",
  bulletColor: "#8fc13e",
  codeColor: "#b9b5b8",
  additionColor: "#8fc13e",
  regexpColor: "#dd464c",
  symbolColor: "#8fc13e",
  variableColor: "#dd464c",
  templateVariableColor: "#dd464c",
  linkColor: "#dd464c",
  selectorClassColor: "#dd464c",
  typeColor: "#fd8b19",
  stringColor: "#8fc13e",
  selectorIdColor: "#dd464c",
  quoteColor: "#989498",
  templateTagColor: "#b9b5b8",
  deletionColor: "#dd464c",
  titleColor: "#1290bf",
  sectionColor: "#1290bf",
  commentColor: "#989498",
  metaKeywordColor: "#b9b5b8",
  metaColor: "#149b93",
  functionColor: "#1290bf",
  numberColor: "#fd8b19"
};

var hybrid = {
  lineNumberColor: "#c5c8c6",
  lineNumberBgColor: "#1d1f21",
  backgroundColor: "#1d1f21",
  textColor: "#c5c8c6",
  substringColor: "#8abeb7",
  keywordColor: "#81a2be",
  attributeColor: "#b294bb",
  selectorAttributeColor: "#81a2be",
  docTagColor: "#b5bd68",
  nameColor: "#f0c674",
  builtInColor: "#de935f",
  literalColor: "#cc6666",
  bulletColor: "#81a2be",
  codeColor: "#b294bb",
  additionColor: "#b5bd68",
  regexpColor: "#b5bd68",
  symbolColor: "#cc6666",
  variableColor: "#8abeb7",
  templateVariableColor: "#8abeb7",
  linkColor: "#cc6666",
  selectorClassColor: "#de935f",
  typeColor: "#de935f",
  stringColor: "#b5bd68",
  selectorIdColor: "#b294bb",
  quoteColor: "#de935f",
  templateTagColor: "#8abeb7",
  deletionColor: "#cc6666",
  titleColor: "#f0c674",
  sectionColor: "#de935f",
  commentColor: "#707880",
  metaKeywordColor: "#c5c8c6",
  metaColor: "#707880",
  functionColor: "#c5c8c6",
  numberColor: "#cc6666"
};

var irBlack = {
  lineNumberColor: "#f8f8f8",
  lineNumberBgColor: "#000",
  backgroundColor: "#000",
  textColor: "#f8f8f8",
  substringColor: "#daefa3",
  keywordColor: "#96cbfe",
  attributeColor: "#ffffb6",
  selectorAttributeColor: "#96cbfe",
  docTagColor: "#ffffb6",
  nameColor: "#96cbfe",
  builtInColor: "#f8f8f8",
  literalColor: "#c6c5fe",
  bulletColor: "#c6c5fe",
  codeColor: "#f8f8f8",
  additionColor: "#a8ff60",
  regexpColor: "#e9c062",
  symbolColor: "#c6c5fe",
  variableColor: "#c6c5fe",
  templateVariableColor: "#c6c5fe",
  linkColor: "#e9c062",
  selectorClassColor: "#f8f8f8",
  typeColor: "#ffffb6",
  stringColor: "#a8ff60",
  selectorIdColor: "#ffffb6",
  quoteColor: "#7c7c7c",
  templateTagColor: "#f8f8f8",
  deletionColor: "#ff73fd",
  titleColor: "#ffffb6",
  sectionColor: "#ffffb6",
  commentColor: "#7c7c7c",
  metaKeywordColor: "#f8f8f8",
  metaColor: "#7c7c7c",
  functionColor: "#f8f8f8",
  numberColor: "#ff73fd"
};

var monoBlue = {
  lineNumberColor: "#00193a",
  lineNumberBgColor: "#eaeef3",
  backgroundColor: "#eaeef3",
  textColor: "#00193a",
  substringColor: "#4c81c9",
  keywordColor: undefined,
  attributeColor: "#4c81c9",
  selectorAttributeColor: undefined,
  docTagColor: undefined,
  nameColor: "#0048ab",
  builtInColor: "#0048ab",
  literalColor: "#0048ab",
  bulletColor: "#4c81c9",
  codeColor: "#00193a",
  additionColor: "#0048ab",
  regexpColor: "#4c81c9",
  symbolColor: "#4c81c9",
  variableColor: "#4c81c9",
  templateVariableColor: "#4c81c9",
  linkColor: "#4c81c9",
  selectorClassColor: "#0048ab",
  typeColor: "#0048ab",
  stringColor: "#0048ab",
  selectorIdColor: "#0048ab",
  quoteColor: "#0048ab",
  templateTagColor: "#00193a",
  deletionColor: "#4c81c9",
  titleColor: "#0048ab",
  sectionColor: "#0048ab",
  commentColor: "#738191",
  metaKeywordColor: "#00193a",
  metaColor: "#4c81c9",
  functionColor: "#00193a",
  numberColor: "#00193a"
};

var monokaiSublime = {
  lineNumberColor: "#f8f8f2",
  lineNumberBgColor: "#23241f",
  backgroundColor: "#23241f",
  textColor: "#f8f8f2",
  substringColor: "#f8f8f2",
  keywordColor: "#f92672",
  attributeColor: "#66d9ef",
  selectorAttributeColor: "#f92672",
  docTagColor: "#f8f8f2",
  nameColor: "#f92672",
  builtInColor: "#e6db74",
  literalColor: "#ae81ff",
  bulletColor: "#ae81ff",
  codeColor: "#a6e22e",
  additionColor: "#e6db74",
  regexpColor: "#ae81ff",
  symbolColor: "#66d9ef",
  variableColor: "#e6db74",
  templateVariableColor: "#e6db74",
  linkColor: "#ae81ff",
  selectorClassColor: "#a6e22e",
  typeColor: "#e6db74",
  stringColor: "#e6db74",
  selectorIdColor: "#e6db74",
  quoteColor: "#ae81ff",
  templateTagColor: "#f8f8f2",
  deletionColor: "#75715e",
  titleColor: "#a6e22e",
  sectionColor: "#a6e22e",
  commentColor: "#75715e",
  metaKeywordColor: "#f8f8f2",
  metaColor: "#75715e",
  functionColor: "#f8f8f2",
  numberColor: "#ae81ff"
};

var monokai = {
  lineNumberColor: "#ddd",
  lineNumberBgColor: "#272822",
  backgroundColor: "#272822",
  textColor: "#ddd",
  substringColor: "#a6e22e",
  keywordColor: "#f92672",
  attributeColor: "#bf79db",
  selectorAttributeColor: "#f92672",
  docTagColor: undefined,
  nameColor: "#f92672",
  builtInColor: "#a6e22e",
  literalColor: "#f92672",
  bulletColor: "#a6e22e",
  codeColor: "#66d9ef",
  additionColor: "#a6e22e",
  regexpColor: "#bf79db",
  symbolColor: "#bf79db",
  variableColor: "#a6e22e",
  templateVariableColor: "#a6e22e",
  linkColor: "#bf79db",
  selectorClassColor: "#ddd",
  typeColor: "#a6e22e",
  stringColor: "#a6e22e",
  selectorIdColor: undefined,
  quoteColor: "#75715e",
  templateTagColor: "#a6e22e",
  deletionColor: "#75715e",
  titleColor: "#a6e22e",
  sectionColor: "#a6e22e",
  commentColor: "#75715e",
  metaKeywordColor: "#ddd",
  metaColor: "#75715e",
  functionColor: "#ddd",
  numberColor: "#ddd"
};

var nord = {
  lineNumberColor: "#D8DEE9",
  lineNumberBgColor: "#2E3440",
  backgroundColor: "#2E3440",
  textColor: "#D8DEE9",
  substringColor: "#D8DEE9",
  keywordColor: "#81A1C1",
  attributeColor: "#D8DEE9",
  selectorAttributeColor: "#81A1C1",
  docTagColor: "#8FBCBB",
  nameColor: "#81A1C1",
  builtInColor: "#8FBCBB",
  literalColor: "#81A1C1",
  bulletColor: "#81A1C1",
  codeColor: "#8FBCBB",
  additionColor: "#a3be8c",
  regexpColor: "#EBCB8B",
  symbolColor: "#81A1C1",
  variableColor: "#D8DEE9",
  templateVariableColor: "#D8DEE9",
  linkColor: "#D8DEE9",
  selectorClassColor: "#8FBCBB",
  typeColor: "#8FBCBB",
  stringColor: "#A3BE8C",
  selectorIdColor: "#8FBCBB",
  quoteColor: "#4C566A",
  templateTagColor: "#5E81AC",
  deletionColor: "#bf616a",
  titleColor: "#8FBCBB",
  sectionColor: "#88C0D0",
  commentColor: "#4C566A",
  metaKeywordColor: "#5E81AC",
  metaColor: "#5E81AC",
  functionColor: "#88C0D0",
  numberColor: "#B48EAD"
};

var obsidian = {
  lineNumberColor: "#e0e2e4",
  lineNumberBgColor: "#282b2e",
  backgroundColor: "#282b2e",
  textColor: "#e0e2e4",
  substringColor: "#8cbbad",
  keywordColor: "#93c763",
  attributeColor: "#668bb0",
  selectorAttributeColor: "#93c763",
  docTagColor: undefined,
  nameColor: "#8cbbad",
  builtInColor: "#8cbbad",
  literalColor: "#93c763",
  bulletColor: "#8cbbad",
  codeColor: "white",
  additionColor: "#8cbbad",
  regexpColor: "#d39745",
  symbolColor: "#ec7600",
  variableColor: "#8cbbad",
  templateVariableColor: "#8cbbad",
  linkColor: "#d39745",
  selectorClassColor: "#A082BD",
  typeColor: "#8cbbad",
  stringColor: "#ec7600",
  selectorIdColor: "#93c763",
  quoteColor: "#818e96",
  templateTagColor: "#8cbbad",
  deletionColor: "#818e96",
  titleColor: undefined,
  sectionColor: "white",
  commentColor: "#818e96",
  metaKeywordColor: "#e0e2e4",
  metaColor: "#557182",
  functionColor: "#e0e2e4",
  numberColor: "#ffcd22"
};

var ocean = {
  lineNumberColor: "#c0c5ce",
  lineNumberBgColor: "#2b303b",
  backgroundColor: "#2b303b",
  textColor: "#c0c5ce",
  substringColor: "#c0c5ce",
  keywordColor: "#b48ead",
  attributeColor: "#ebcb8b",
  selectorAttributeColor: "#b48ead",
  docTagColor: "#c0c5ce",
  nameColor: "#bf616a",
  builtInColor: "#d08770",
  literalColor: "#d08770",
  bulletColor: "#a3be8c",
  codeColor: "#c0c5ce",
  additionColor: "#a3be8c",
  regexpColor: "#bf616a",
  symbolColor: "#a3be8c",
  variableColor: "#bf616a",
  templateVariableColor: "#bf616a",
  linkColor: "#d08770",
  selectorClassColor: "#bf616a",
  typeColor: "#d08770",
  stringColor: "#a3be8c",
  selectorIdColor: "#bf616a",
  quoteColor: "#65737e",
  templateTagColor: "#c0c5ce",
  deletionColor: "#bf616a",
  titleColor: "#8fa1b3",
  sectionColor: "#8fa1b3",
  commentColor: "#65737e",
  metaKeywordColor: "#c0c5ce",
  metaColor: "#d08770",
  functionColor: "#c0c5ce",
  numberColor: "#d08770"
};

var paraisoDark = {
  lineNumberColor: "#a39e9b",
  lineNumberBgColor: "#2f1e2e",
  backgroundColor: "#2f1e2e",
  textColor: "#a39e9b",
  substringColor: "#a39e9b",
  keywordColor: "#815ba4",
  attributeColor: "#fec418",
  selectorAttributeColor: "#815ba4",
  docTagColor: "#a39e9b",
  nameColor: "#ef6155",
  builtInColor: "#f99b15",
  literalColor: "#f99b15",
  bulletColor: "#48b685",
  codeColor: "#a39e9b",
  additionColor: "#48b685",
  regexpColor: "#ef6155",
  symbolColor: "#48b685",
  variableColor: "#ef6155",
  templateVariableColor: "#ef6155",
  linkColor: "#ef6155",
  selectorClassColor: "#ef6155",
  typeColor: "#f99b15",
  stringColor: "#48b685",
  selectorIdColor: "#ef6155",
  quoteColor: "#8d8687",
  templateTagColor: "#a39e9b",
  deletionColor: "#f99b15",
  titleColor: "#fec418",
  sectionColor: "#fec418",
  commentColor: "#8d8687",
  metaKeywordColor: "#a39e9b",
  metaColor: "#ef6155",
  functionColor: "#a39e9b",
  numberColor: "#f99b15"
};

var paraisoLight = {
  lineNumberColor: "#4f424c",
  lineNumberBgColor: "#e7e9db",
  backgroundColor: "#e7e9db",
  textColor: "#4f424c",
  substringColor: "#4f424c",
  keywordColor: "#815ba4",
  attributeColor: "#fec418",
  selectorAttributeColor: "#815ba4",
  docTagColor: "#4f424c",
  nameColor: "#ef6155",
  builtInColor: "#f99b15",
  literalColor: "#f99b15",
  bulletColor: "#48b685",
  codeColor: "#4f424c",
  additionColor: "#48b685",
  regexpColor: "#ef6155",
  symbolColor: "#48b685",
  variableColor: "#ef6155",
  templateVariableColor: "#ef6155",
  linkColor: "#ef6155",
  selectorClassColor: "#ef6155",
  typeColor: "#f99b15",
  stringColor: "#48b685",
  selectorIdColor: "#ef6155",
  quoteColor: "#776e71",
  templateTagColor: "#4f424c",
  deletionColor: "#f99b15",
  titleColor: "#fec418",
  sectionColor: "#fec418",
  commentColor: "#776e71",
  metaKeywordColor: "#4f424c",
  metaColor: "#ef6155",
  functionColor: "#4f424c",
  numberColor: "#f99b15"
};

var pojoaque = {
  lineNumberColor: "#dccf8f",
  lineNumberBgColor: "#181914 url(\"data:image/jpeg;base64,/9j/4AAQSkZJRgABAgAAZABkAAD/7AARRHVja3kAAQAEAAAAMAAA/+4ADkFkb2JlAGTAAAAAAf/bAIQACQYGBgcGCQcHCQ0IBwgNDwsJCQsPEQ4ODw4OERENDg4ODg0RERQUFhQUERoaHBwaGiYmJiYmKysrKysrKysrKwEJCAgJCgkMCgoMDwwODA8TDg4ODhMVDg4PDg4VGhMRERERExoXGhYWFhoXHR0aGh0dJCQjJCQrKysrKysrKysr/8AAEQgAjACMAwEiAAIRAQMRAf/EAF4AAQEBAAAAAAAAAAAAAAAAAAABBwEBAQAAAAAAAAAAAAAAAAAAAAIQAAEDAwIHAQEAAAAAAAAAAADwAREhYaExkUFRcYGxwdHh8REBAAAAAAAAAAAAAAAAAAAAAP/aAAwDAQACEQMRAD8AyGFEjHaBS2fDDs2zkhKmBKktb7km+ZwwCnXPkLVmCTMItj6AXFxRS465/BTnkAJvkLkJe+7AKKoi2AtRS2zuAWsCb5GOlBN8gKfmuGHZ8MFqIth3ALmFoFwbwKWyAlTAp17uKqBvgBD8sM4fTjhvAhkzhaRkBMKBrfs7jGPIpzy7gFrAqnC0C0gB0EWwBDW2cBVQwm+QtPpa3wBO3sVvszCnLAhkzgL5/RLf13cLQd8/AGlu0Cb5HTx9KuAEieGJEdcehS3eRTp2ATdt3CpIm+QtZwAhROXFeb7swp/ahaM3kBE/jSIUBc/AWrgBN8uNFAl+b7sAXFxFn2YLUU5Ns7gFX8C4ib+hN8gFWXwK3bZglxEJm+gKdciLPsFV/TClsgJUwKJ5FVA7tvIFrfZhVfGJDcsCKaYgAqv6YRbE+RWOWBtu7+AL3yRalXLyKqAIIfk+zARbDgFyEsncYwJvlgFRW+GEWntIi2P0BooyFxcNr8Ep3+ANLbMO+QyhvbiqdgC0kVvgUUiLYgBS2QtPbiVI1/sgOmG9uO+Y8DW+7jS2zAOnj6O2BndwuIAUtkdRN8gFoK3wwXMQyZwHVbClsuNLd4E3yAUR6FVDBR+BafQGt93LVMxJTv8ABts4CVLhcfYWsCb5kC9/BHdU8CLYFY5bMAd+eX9MGthhpbA1vu4B7+RKkaW2Yq4AQtVBBFsAJU/AuIXBhN8gGWnstefhiZyWvLAEnbYS1uzSFP6Jvn4Baxx70JKkQojLib5AVTey1jjgkKJGO0AKWyOm7N7cSpgSpAdPH0Tfd/gp1z5C1ZgKqN9J2wFxcUUuAFLZAm+QC0Fb4YUVRFsAOvj4KW2dwtYE3yAWk/wS/PLMKfmuGHZ8MAXF/Ja32Yi5haAKWz4Ydm2cSpgU693Atb7km+Zwwh+WGcPpxw3gAkzCLY+iYUDW/Z3Adc/gpzyFrAqnALkJe+7DoItgAtRS2zuKqGE3yAx0oJvkdvYrfZmALURbDuL5/RLf13cAuDeBS2RpbtAm+QFVA3wR+3fUtFHoBDJnC0jIXH0HWsgMY8inPLuOkd9chp4z20ALQLSA8cI9jYAIa2zjzjBd8gRafS1vgiUho/kAKcsCGTOGWvoOpkAtB3z8Hm8x2Ff5ADp4+lXAlIvcmwH/2Q==\") repeat left top",
  backgroundColor: "#181914 url(\"data:image/jpeg;base64,/9j/4AAQSkZJRgABAgAAZABkAAD/7AARRHVja3kAAQAEAAAAMAAA/+4ADkFkb2JlAGTAAAAAAf/bAIQACQYGBgcGCQcHCQ0IBwgNDwsJCQsPEQ4ODw4OERENDg4ODg0RERQUFhQUERoaHBwaGiYmJiYmKysrKysrKysrKwEJCAgJCgkMCgoMDwwODA8TDg4ODhMVDg4PDg4VGhMRERERExoXGhYWFhoXHR0aGh0dJCQjJCQrKysrKysrKysr/8AAEQgAjACMAwEiAAIRAQMRAf/EAF4AAQEBAAAAAAAAAAAAAAAAAAABBwEBAQAAAAAAAAAAAAAAAAAAAAIQAAEDAwIHAQEAAAAAAAAAAADwAREhYaExkUFRcYGxwdHh8REBAAAAAAAAAAAAAAAAAAAAAP/aAAwDAQACEQMRAD8AyGFEjHaBS2fDDs2zkhKmBKktb7km+ZwwCnXPkLVmCTMItj6AXFxRS465/BTnkAJvkLkJe+7AKKoi2AtRS2zuAWsCb5GOlBN8gKfmuGHZ8MFqIth3ALmFoFwbwKWyAlTAp17uKqBvgBD8sM4fTjhvAhkzhaRkBMKBrfs7jGPIpzy7gFrAqnC0C0gB0EWwBDW2cBVQwm+QtPpa3wBO3sVvszCnLAhkzgL5/RLf13cLQd8/AGlu0Cb5HTx9KuAEieGJEdcehS3eRTp2ATdt3CpIm+QtZwAhROXFeb7swp/ahaM3kBE/jSIUBc/AWrgBN8uNFAl+b7sAXFxFn2YLUU5Ns7gFX8C4ib+hN8gFWXwK3bZglxEJm+gKdciLPsFV/TClsgJUwKJ5FVA7tvIFrfZhVfGJDcsCKaYgAqv6YRbE+RWOWBtu7+AL3yRalXLyKqAIIfk+zARbDgFyEsncYwJvlgFRW+GEWntIi2P0BooyFxcNr8Ep3+ANLbMO+QyhvbiqdgC0kVvgUUiLYgBS2QtPbiVI1/sgOmG9uO+Y8DW+7jS2zAOnj6O2BndwuIAUtkdRN8gFoK3wwXMQyZwHVbClsuNLd4E3yAUR6FVDBR+BafQGt93LVMxJTv8ABts4CVLhcfYWsCb5kC9/BHdU8CLYFY5bMAd+eX9MGthhpbA1vu4B7+RKkaW2Yq4AQtVBBFsAJU/AuIXBhN8gGWnstefhiZyWvLAEnbYS1uzSFP6Jvn4Baxx70JKkQojLib5AVTey1jjgkKJGO0AKWyOm7N7cSpgSpAdPH0Tfd/gp1z5C1ZgKqN9J2wFxcUUuAFLZAm+QC0Fb4YUVRFsAOvj4KW2dwtYE3yAWk/wS/PLMKfmuGHZ8MAXF/Ja32Yi5haAKWz4Ydm2cSpgU693Atb7km+Zwwh+WGcPpxw3gAkzCLY+iYUDW/Z3Adc/gpzyFrAqnALkJe+7DoItgAtRS2zuKqGE3yAx0oJvkdvYrfZmALURbDuL5/RLf13cAuDeBS2RpbtAm+QFVA3wR+3fUtFHoBDJnC0jIXH0HWsgMY8inPLuOkd9chp4z20ALQLSA8cI9jYAIa2zjzjBd8gRafS1vgiUho/kAKcsCGTOGWvoOpkAtB3z8Hm8x2Ff5ADp4+lXAlIvcmwH/2Q==\") repeat left top",
  textColor: "#dccf8f",
  substringColor: "#cb4b16",
  keywordColor: "#b64926",
  attributeColor: "#b89859",
  selectorAttributeColor: "#b64926",
  docTagColor: "#468966",
  nameColor: "#ffb03b",
  builtInColor: "#ffb03b",
  literalColor: "#b64926",
  bulletColor: "#cb4b16",
  codeColor: "#dccf8f",
  additionColor: "#b64926",
  regexpColor: "#468966",
  symbolColor: "#cb4b16",
  variableColor: "#b58900",
  templateVariableColor: "#b58900",
  linkColor: "#cb4b16",
  selectorClassColor: "#d3a60c",
  typeColor: "#b58900",
  stringColor: "#468966",
  selectorIdColor: "#d3a60c",
  quoteColor: "#586e75",
  templateTagColor: "#dccf8f",
  deletionColor: "#dc322f",
  titleColor: "#ffb03b",
  sectionColor: "#ffb03b",
  commentColor: "#586e75",
  metaKeywordColor: "#dccf8f",
  metaColor: "#cb4b16",
  functionColor: "#dccf8f",
  numberColor: "#468966"
};

var purebasic = {
  lineNumberColor: "#000000",
  lineNumberBgColor: "#FFFFDF",
  backgroundColor: "#FFFFDF",
  textColor: "#000000",
  substringColor: "#000000",
  keywordColor: "#006666",
  attributeColor: "#924B72",
  selectorAttributeColor: "#000000",
  docTagColor: "#000000",
  nameColor: "#000000",
  builtInColor: "#006666",
  literalColor: "#924B72",
  bulletColor: "#000000",
  codeColor: "#006666",
  additionColor: "#00AAAA",
  regexpColor: "#00AAAA",
  symbolColor: "#924B72",
  variableColor: "#006666",
  templateVariableColor: "#000000",
  linkColor: "#924B72",
  selectorClassColor: "#006666",
  typeColor: "#000000",
  stringColor: "#0080FF",
  selectorIdColor: "#924B72",
  quoteColor: "#000000",
  templateTagColor: "#000000",
  deletionColor: "#924B72",
  titleColor: "#006666",
  sectionColor: "#00AAAA",
  commentColor: "#00AAAA",
  metaKeywordColor: "#006666",
  metaColor: "#924B72",
  functionColor: "#000000",
  numberColor: "#000000"
};

var railscast = {
  lineNumberColor: "#e6e1dc",
  lineNumberBgColor: "#232323",
  backgroundColor: "#232323",
  textColor: "#e6e1dc",
  substringColor: "#519f50",
  keywordColor: "#c26230",
  attributeColor: "#cda869",
  selectorAttributeColor: "#c26230",
  docTagColor: "#e6e1dc",
  nameColor: "#e8bf6a",
  builtInColor: "#6d9cbe",
  literalColor: "#e6e1dc",
  bulletColor: "#6d9cbe",
  codeColor: "#e6e1dc",
  additionColor: "#e6e1dc",
  regexpColor: "#a5c261",
  symbolColor: "#6d9cbe",
  variableColor: "#a5c261",
  templateVariableColor: "#a5c261",
  linkColor: "#6d9cbe",
  selectorClassColor: "#9b703f",
  typeColor: "#da4939",
  stringColor: "#a5c261",
  selectorIdColor: "#8b98ab",
  quoteColor: "#bc9458",
  templateTagColor: "#e6e1dc",
  deletionColor: "#e6e1dc",
  titleColor: "#ffc66d",
  sectionColor: "#ffc66d",
  commentColor: "#bc9458",
  metaKeywordColor: "#e6e1dc",
  metaColor: "#9b859d",
  functionColor: "#e6e1dc",
  numberColor: "#a5c261"
};

var rainbow = {
  lineNumberColor: "#d1d9e1",
  lineNumberBgColor: "#474949",
  backgroundColor: "#474949",
  textColor: "#d1d9e1",
  substringColor: "#f99157",
  keywordColor: "#cc99cc",
  attributeColor: "#81a2be",
  selectorAttributeColor: "#cc99cc",
  docTagColor: "#8abeb7",
  nameColor: "#b5bd68",
  builtInColor: "#b5bd68",
  literalColor: "#cc99cc",
  bulletColor: "#f99157",
  codeColor: "#d1d9e1",
  additionColor: "#cc99cc",
  regexpColor: "#8abeb7",
  symbolColor: "#f99157",
  variableColor: "#ffcc66",
  templateVariableColor: "#ffcc66",
  linkColor: "#f99157",
  selectorClassColor: "#d1d9e1",
  typeColor: "#cc99cc",
  stringColor: "#8abeb7",
  selectorIdColor: "#ffcc66",
  quoteColor: "#969896",
  templateTagColor: "#d1d9e1",
  deletionColor: "#dc322f",
  titleColor: "#b5bd68",
  sectionColor: "#b5bd68",
  commentColor: "#969896",
  metaKeywordColor: "#d1d9e1",
  metaColor: "#f99157",
  functionColor: "#d1d9e1",
  numberColor: "#f99157"
};

var shadesOfPurple = {
  lineNumberColor: "#e3dfff",
  lineNumberBgColor: "#2d2b57",
  backgroundColor: "#2d2b57",
  textColor: "#e3dfff",
  substringColor: "#e3dfff",
  keywordColor: "#fb9e00",
  attributeColor: "#4cd213",
  selectorAttributeColor: "#fb9e00",
  docTagColor: "#e3dfff",
  nameColor: "#a1feff",
  builtInColor: "#fb9e00",
  literalColor: "#fa658d",
  bulletColor: "#4cd213",
  codeColor: "#4cd213",
  additionColor: "#4cd213",
  regexpColor: "#4cd213",
  symbolColor: "#4cd213",
  variableColor: "#e3dfff",
  templateVariableColor: "#e3dfff",
  linkColor: "#e3dfff",
  selectorClassColor: "#4cd213",
  typeColor: "#e3dfff",
  stringColor: "#4cd213",
  selectorIdColor: "#e3dfff",
  quoteColor: "#4cd213",
  templateTagColor: "#4cd213",
  deletionColor: "#4cd213",
  titleColor: "#fad000",
  sectionColor: "#fb9e00",
  commentColor: "#ac65ff",
  metaKeywordColor: "#e3dfff",
  metaColor: "#fb9e00",
  functionColor: "#e3dfff",
  numberColor: "#fa658d"
};

var solarizedDark = {
  lineNumberColor: "#839496",
  lineNumberBgColor: "#002b36",
  backgroundColor: "#002b36",
  textColor: "#839496",
  substringColor: "#cb4b16",
  keywordColor: "#859900",
  attributeColor: "#b58900",
  selectorAttributeColor: "#859900",
  docTagColor: "#2aa198",
  nameColor: "#268bd2",
  builtInColor: "#dc322f",
  literalColor: "#2aa198",
  bulletColor: "#cb4b16",
  codeColor: "#839496",
  additionColor: "#859900",
  regexpColor: "#2aa198",
  symbolColor: "#cb4b16",
  variableColor: "#b58900",
  templateVariableColor: "#b58900",
  linkColor: "#cb4b16",
  selectorClassColor: "#268bd2",
  typeColor: "#b58900",
  stringColor: "#2aa198",
  selectorIdColor: "#268bd2",
  quoteColor: "#586e75",
  templateTagColor: "#839496",
  deletionColor: "#dc322f",
  titleColor: "#268bd2",
  sectionColor: "#268bd2",
  commentColor: "#586e75",
  metaKeywordColor: "#839496",
  metaColor: "#cb4b16",
  functionColor: "#839496",
  numberColor: "#2aa198"
};

var solarizedLight = {
  lineNumberColor: "#657b83",
  lineNumberBgColor: "#fdf6e3",
  backgroundColor: "#fdf6e3",
  textColor: "#657b83",
  substringColor: "#cb4b16",
  keywordColor: "#859900",
  attributeColor: "#b58900",
  selectorAttributeColor: "#859900",
  docTagColor: "#2aa198",
  nameColor: "#268bd2",
  builtInColor: "#dc322f",
  literalColor: "#2aa198",
  bulletColor: "#cb4b16",
  codeColor: "#657b83",
  additionColor: "#859900",
  regexpColor: "#2aa198",
  symbolColor: "#cb4b16",
  variableColor: "#b58900",
  templateVariableColor: "#b58900",
  linkColor: "#cb4b16",
  selectorClassColor: "#268bd2",
  typeColor: "#b58900",
  stringColor: "#2aa198",
  selectorIdColor: "#268bd2",
  quoteColor: "#93a1a1",
  templateTagColor: "#657b83",
  deletionColor: "#dc322f",
  titleColor: "#268bd2",
  sectionColor: "#268bd2",
  commentColor: "#93a1a1",
  metaKeywordColor: "#657b83",
  metaColor: "#cb4b16",
  functionColor: "#657b83",
  numberColor: "#2aa198"
};

var sunburst = {
  lineNumberColor: "#f8f8f8",
  lineNumberBgColor: "#000",
  backgroundColor: "#000",
  textColor: "#f8f8f8",
  substringColor: "#daefa3",
  keywordColor: "#e28964",
  attributeColor: "#cda869",
  selectorAttributeColor: "#e28964",
  docTagColor: undefined,
  nameColor: "#89bdff",
  builtInColor: "#f8f8f8",
  literalColor: "#f8f8f8",
  bulletColor: "#3387cc",
  codeColor: "#f8f8f8",
  additionColor: "#f8f8f8",
  regexpColor: "#e9c062",
  symbolColor: "#3387cc",
  variableColor: "#3e87e3",
  templateVariableColor: "#3e87e3",
  linkColor: "#e9c062",
  selectorClassColor: "#9b703f",
  typeColor: "#e28964",
  stringColor: "#65b042",
  selectorIdColor: "#8b98ab",
  quoteColor: "#aeaeae",
  templateTagColor: "#f8f8f8",
  deletionColor: "#f8f8f8",
  titleColor: "#89bdff",
  sectionColor: "#89bdff",
  commentColor: "#aeaeae",
  metaKeywordColor: "#f8f8f8",
  metaColor: "#8996a8",
  functionColor: "#f8f8f8",
  numberColor: "#3387cc"
};

var tomorrowNightBlue = {
  lineNumberColor: "white",
  lineNumberBgColor: "#002451",
  backgroundColor: "#002451",
  textColor: "white",
  substringColor: "white",
  keywordColor: "#ebbbff",
  attributeColor: "#ffeead",
  selectorAttributeColor: "#ebbbff",
  docTagColor: "white",
  nameColor: "#ff9da4",
  builtInColor: "#ffc58f",
  literalColor: "#ffc58f",
  bulletColor: "#d1f1a9",
  codeColor: "white",
  additionColor: "#d1f1a9",
  regexpColor: "#ff9da4",
  symbolColor: "#d1f1a9",
  variableColor: "#ff9da4",
  templateVariableColor: "#ff9da4",
  linkColor: "#ffc58f",
  selectorClassColor: "#ff9da4",
  typeColor: "#ffc58f",
  stringColor: "#d1f1a9",
  selectorIdColor: "#ff9da4",
  quoteColor: "#7285b7",
  templateTagColor: "white",
  deletionColor: "#ff9da4",
  titleColor: "#bbdaff",
  sectionColor: "#bbdaff",
  commentColor: "#7285b7",
  metaKeywordColor: "white",
  metaColor: "#ffc58f",
  functionColor: "white",
  numberColor: "#ffc58f"
};

var tomorrowNightBright = {
  lineNumberColor: "#eaeaea",
  lineNumberBgColor: "black",
  backgroundColor: "black",
  textColor: "#eaeaea",
  substringColor: "#eaeaea",
  keywordColor: "#c397d8",
  attributeColor: "#e7c547",
  selectorAttributeColor: "#c397d8",
  docTagColor: "#eaeaea",
  nameColor: "#d54e53",
  builtInColor: "#e78c45",
  literalColor: "#e78c45",
  bulletColor: "#b9ca4a",
  codeColor: "#eaeaea",
  additionColor: "#b9ca4a",
  regexpColor: "#d54e53",
  symbolColor: "#b9ca4a",
  variableColor: "#d54e53",
  templateVariableColor: "#d54e53",
  linkColor: "#e78c45",
  selectorClassColor: "#d54e53",
  typeColor: "#e78c45",
  stringColor: "#b9ca4a",
  selectorIdColor: "#d54e53",
  quoteColor: "#969896",
  templateTagColor: "#eaeaea",
  deletionColor: "#d54e53",
  titleColor: "#7aa6da",
  sectionColor: "#7aa6da",
  commentColor: "#969896",
  metaKeywordColor: "#eaeaea",
  metaColor: "#e78c45",
  functionColor: "#eaeaea",
  numberColor: "#e78c45"
};

var tomorrowNightEighties = {
  lineNumberColor: "#cccccc",
  lineNumberBgColor: "#2d2d2d",
  backgroundColor: "#2d2d2d",
  textColor: "#cccccc",
  substringColor: "#cccccc",
  keywordColor: "#cc99cc",
  attributeColor: "#ffcc66",
  selectorAttributeColor: "#cc99cc",
  docTagColor: "#cccccc",
  nameColor: "#f2777a",
  builtInColor: "#f99157",
  literalColor: "#f99157",
  bulletColor: "#99cc99",
  codeColor: "#cccccc",
  additionColor: "#99cc99",
  regexpColor: "#f2777a",
  symbolColor: "#99cc99",
  variableColor: "#f2777a",
  templateVariableColor: "#f2777a",
  linkColor: "#f99157",
  selectorClassColor: "#f2777a",
  typeColor: "#f99157",
  stringColor: "#99cc99",
  selectorIdColor: "#f2777a",
  quoteColor: "#999999",
  templateTagColor: "#cccccc",
  deletionColor: "#f2777a",
  titleColor: "#6699cc",
  sectionColor: "#6699cc",
  commentColor: "#999999",
  metaKeywordColor: "#cccccc",
  metaColor: "#f99157",
  functionColor: "#cccccc",
  numberColor: "#f99157"
};

var tomorrowNight = {
  lineNumberColor: "#c5c8c6",
  lineNumberBgColor: "#1d1f21",
  backgroundColor: "#1d1f21",
  textColor: "#c5c8c6",
  substringColor: "#c5c8c6",
  keywordColor: "#b294bb",
  attributeColor: "#f0c674",
  selectorAttributeColor: "#b294bb",
  docTagColor: "#c5c8c6",
  nameColor: "#cc6666",
  builtInColor: "#de935f",
  literalColor: "#de935f",
  bulletColor: "#b5bd68",
  codeColor: "#c5c8c6",
  additionColor: "#b5bd68",
  regexpColor: "#cc6666",
  symbolColor: "#b5bd68",
  variableColor: "#cc6666",
  templateVariableColor: "#cc6666",
  linkColor: "#de935f",
  selectorClassColor: "#cc6666",
  typeColor: "#de935f",
  stringColor: "#b5bd68",
  selectorIdColor: "#cc6666",
  quoteColor: "#969896",
  templateTagColor: "#c5c8c6",
  deletionColor: "#cc6666",
  titleColor: "#81a2be",
  sectionColor: "#81a2be",
  commentColor: "#969896",
  metaKeywordColor: "#c5c8c6",
  metaColor: "#de935f",
  functionColor: "#c5c8c6",
  numberColor: "#de935f"
};

var tomorrow = {
  lineNumberColor: "#4d4d4c",
  lineNumberBgColor: "white",
  backgroundColor: "white",
  textColor: "#4d4d4c",
  substringColor: "#4d4d4c",
  keywordColor: "#8959a8",
  attributeColor: "#eab700",
  selectorAttributeColor: "#8959a8",
  docTagColor: "#4d4d4c",
  nameColor: "#c82829",
  builtInColor: "#f5871f",
  literalColor: "#f5871f",
  bulletColor: "#718c00",
  codeColor: "#4d4d4c",
  additionColor: "#718c00",
  regexpColor: "#c82829",
  symbolColor: "#718c00",
  variableColor: "#c82829",
  templateVariableColor: "#c82829",
  linkColor: "#f5871f",
  selectorClassColor: "#c82829",
  typeColor: "#f5871f",
  stringColor: "#718c00",
  selectorIdColor: "#c82829",
  quoteColor: "#8e908c",
  templateTagColor: "#4d4d4c",
  deletionColor: "#c82829",
  titleColor: "#4271ae",
  sectionColor: "#4271ae",
  commentColor: "#8e908c",
  metaKeywordColor: "#4d4d4c",
  metaColor: "#f5871f",
  functionColor: "#4d4d4c",
  numberColor: "#f5871f"
};

var vs2015 = {
  lineNumberColor: "#DCDCDC",
  lineNumberBgColor: "#1E1E1E",
  backgroundColor: "#1E1E1E",
  textColor: "#DCDCDC",
  substringColor: "#DCDCDC",
  keywordColor: "#569CD6",
  attributeColor: "#9CDCFE",
  selectorAttributeColor: "#D7BA7D",
  docTagColor: "#608B4E",
  nameColor: "#569CD6",
  builtInColor: "#4EC9B0",
  literalColor: "#569CD6",
  bulletColor: "#D7BA7D",
  codeColor: "#DCDCDC",
  additionColor: undefined,
  regexpColor: "#9A5334",
  symbolColor: "#569CD6",
  variableColor: "#BD63C5",
  templateVariableColor: "#BD63C5",
  linkColor: "#569CD6",
  selectorClassColor: "#D7BA7D",
  typeColor: "#4EC9B0",
  stringColor: "#D69D85",
  selectorIdColor: "#D7BA7D",
  quoteColor: "#57A64A",
  templateTagColor: "#9A5334",
  deletionColor: undefined,
  titleColor: "#DCDCDC",
  sectionColor: "gold",
  commentColor: "#57A64A",
  metaKeywordColor: "#9B9B9B",
  metaColor: "#9B9B9B",
  functionColor: "#DCDCDC",
  numberColor: "#B8D7A3"
};

var xt256 = {
  lineNumberColor: "#eaeaea",
  lineNumberBgColor: "#000",
  backgroundColor: "#000",
  textColor: "#eaeaea",
  substringColor: "#eaeaea",
  keywordColor: "#fff000",
  attributeColor: "#ff00ff",
  selectorAttributeColor: "#000fff",
  docTagColor: "#eaeaea",
  nameColor: "#ff0000",
  builtInColor: "#ff00ff",
  literalColor: "#ff0000",
  bulletColor: "#00ff00",
  codeColor: "#eaeaea",
  additionColor: "#eaeaea",
  regexpColor: "#ff00ff",
  symbolColor: "#fff000",
  variableColor: "#00ffff",
  templateVariableColor: "#00ffff",
  linkColor: "#ff00ff",
  selectorClassColor: "#fff000",
  typeColor: "#eaeaea",
  stringColor: "#00ff00",
  selectorIdColor: "#00ffff",
  quoteColor: "#00ffff",
  templateTagColor: "#eaeaea",
  deletionColor: "#eaeaea",
  titleColor: "#00ffff",
  sectionColor: "#000fff",
  commentColor: "#969896",
  metaKeywordColor: "#eaeaea",
  metaColor: "#fff",
  functionColor: "#eaeaea",
  numberColor: "#ff0000"
};

var zenburn = {
  lineNumberColor: "#dcdcdc",
  lineNumberBgColor: "#3f3f3f",
  backgroundColor: "#3f3f3f",
  textColor: "#dcdcdc",
  substringColor: "#8f8f8f",
  keywordColor: "#e3ceab",
  attributeColor: "#efdcbc",
  selectorAttributeColor: "#e3ceab",
  docTagColor: "#dcdcdc",
  nameColor: "#efef8f",
  builtInColor: "#cc9393",
  literalColor: "#efefaf",
  bulletColor: "#dca3a3",
  codeColor: "#dcdcdc",
  additionColor: "#7f9f7f",
  regexpColor: "#dcdcdc",
  symbolColor: "#dca3a3",
  variableColor: "#efdcbc",
  templateVariableColor: "#efdcbc",
  linkColor: "#dca3a3",
  selectorClassColor: "#efef8f",
  typeColor: "#efef8f",
  stringColor: "#cc9393",
  selectorIdColor: "#efef8f",
  quoteColor: "#7f9f7f",
  templateTagColor: "#dcdcdc",
  deletionColor: "#cc9393",
  titleColor: "#efef8f",
  sectionColor: "#efef8f",
  commentColor: "#7f9f7f",
  metaKeywordColor: "#dcdcdc",
  metaColor: "#7f9f7f",
  functionColor: "#dcdcdc",
  numberColor: "#8cd0d3"
};


//# sourceMappingURL=react-code-blocks.esm.js.map


/***/ }),

/***/ 96774:
/***/ ((module) => {

//

module.exports = function shallowEqual(objA, objB, compare, compareContext) {
  var ret = compare ? compare.call(compareContext, objA, objB) : void 0;

  if (ret !== void 0) {
    return !!ret;
  }

  if (objA === objB) {
    return true;
  }

  if (typeof objA !== "object" || !objA || typeof objB !== "object" || !objB) {
    return false;
  }

  var keysA = Object.keys(objA);
  var keysB = Object.keys(objB);

  if (keysA.length !== keysB.length) {
    return false;
  }

  var bHasOwnProperty = Object.prototype.hasOwnProperty.bind(objB);

  // Test for A's keys different from B.
  for (var idx = 0; idx < keysA.length; idx++) {
    var key = keysA[idx];

    if (!bHasOwnProperty(key)) {
      return false;
    }

    var valueA = objA[key];
    var valueB = objB[key];

    ret = compare ? compare.call(compareContext, valueA, valueB, key) : void 0;

    if (ret === false || (ret === void 0 && valueA !== valueB)) {
      return false;
    }
  }

  return true;
};


/***/ }),

/***/ 44874:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var _typeof = (__webpack_require__(98969)["default"]);
function _regeneratorRuntime() {
  "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */
  module.exports = _regeneratorRuntime = function _regeneratorRuntime() {
    return exports;
  }, module.exports.__esModule = true, module.exports["default"] = module.exports;
  var exports = {},
    Op = Object.prototype,
    hasOwn = Op.hasOwnProperty,
    defineProperty = Object.defineProperty || function (obj, key, desc) {
      obj[key] = desc.value;
    },
    $Symbol = "function" == typeof Symbol ? Symbol : {},
    iteratorSymbol = $Symbol.iterator || "@@iterator",
    asyncIteratorSymbol = $Symbol.asyncIterator || "@@asyncIterator",
    toStringTagSymbol = $Symbol.toStringTag || "@@toStringTag";
  function define(obj, key, value) {
    return Object.defineProperty(obj, key, {
      value: value,
      enumerable: !0,
      configurable: !0,
      writable: !0
    }), obj[key];
  }
  try {
    define({}, "");
  } catch (err) {
    define = function define(obj, key, value) {
      return obj[key] = value;
    };
  }
  function wrap(innerFn, outerFn, self, tryLocsList) {
    var protoGenerator = outerFn && outerFn.prototype instanceof Generator ? outerFn : Generator,
      generator = Object.create(protoGenerator.prototype),
      context = new Context(tryLocsList || []);
    return defineProperty(generator, "_invoke", {
      value: makeInvokeMethod(innerFn, self, context)
    }), generator;
  }
  function tryCatch(fn, obj, arg) {
    try {
      return {
        type: "normal",
        arg: fn.call(obj, arg)
      };
    } catch (err) {
      return {
        type: "throw",
        arg: err
      };
    }
  }
  exports.wrap = wrap;
  var ContinueSentinel = {};
  function Generator() {}
  function GeneratorFunction() {}
  function GeneratorFunctionPrototype() {}
  var IteratorPrototype = {};
  define(IteratorPrototype, iteratorSymbol, function () {
    return this;
  });
  var getProto = Object.getPrototypeOf,
    NativeIteratorPrototype = getProto && getProto(getProto(values([])));
  NativeIteratorPrototype && NativeIteratorPrototype !== Op && hasOwn.call(NativeIteratorPrototype, iteratorSymbol) && (IteratorPrototype = NativeIteratorPrototype);
  var Gp = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(IteratorPrototype);
  function defineIteratorMethods(prototype) {
    ["next", "throw", "return"].forEach(function (method) {
      define(prototype, method, function (arg) {
        return this._invoke(method, arg);
      });
    });
  }
  function AsyncIterator(generator, PromiseImpl) {
    function invoke(method, arg, resolve, reject) {
      var record = tryCatch(generator[method], generator, arg);
      if ("throw" !== record.type) {
        var result = record.arg,
          value = result.value;
        return value && "object" == _typeof(value) && hasOwn.call(value, "__await") ? PromiseImpl.resolve(value.__await).then(function (value) {
          invoke("next", value, resolve, reject);
        }, function (err) {
          invoke("throw", err, resolve, reject);
        }) : PromiseImpl.resolve(value).then(function (unwrapped) {
          result.value = unwrapped, resolve(result);
        }, function (error) {
          return invoke("throw", error, resolve, reject);
        });
      }
      reject(record.arg);
    }
    var previousPromise;
    defineProperty(this, "_invoke", {
      value: function value(method, arg) {
        function callInvokeWithMethodAndArg() {
          return new PromiseImpl(function (resolve, reject) {
            invoke(method, arg, resolve, reject);
          });
        }
        return previousPromise = previousPromise ? previousPromise.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg();
      }
    });
  }
  function makeInvokeMethod(innerFn, self, context) {
    var state = "suspendedStart";
    return function (method, arg) {
      if ("executing" === state) throw new Error("Generator is already running");
      if ("completed" === state) {
        if ("throw" === method) throw arg;
        return doneResult();
      }
      for (context.method = method, context.arg = arg;;) {
        var delegate = context.delegate;
        if (delegate) {
          var delegateResult = maybeInvokeDelegate(delegate, context);
          if (delegateResult) {
            if (delegateResult === ContinueSentinel) continue;
            return delegateResult;
          }
        }
        if ("next" === context.method) context.sent = context._sent = context.arg;else if ("throw" === context.method) {
          if ("suspendedStart" === state) throw state = "completed", context.arg;
          context.dispatchException(context.arg);
        } else "return" === context.method && context.abrupt("return", context.arg);
        state = "executing";
        var record = tryCatch(innerFn, self, context);
        if ("normal" === record.type) {
          if (state = context.done ? "completed" : "suspendedYield", record.arg === ContinueSentinel) continue;
          return {
            value: record.arg,
            done: context.done
          };
        }
        "throw" === record.type && (state = "completed", context.method = "throw", context.arg = record.arg);
      }
    };
  }
  function maybeInvokeDelegate(delegate, context) {
    var methodName = context.method,
      method = delegate.iterator[methodName];
    if (undefined === method) return context.delegate = null, "throw" === methodName && delegate.iterator["return"] && (context.method = "return", context.arg = undefined, maybeInvokeDelegate(delegate, context), "throw" === context.method) || "return" !== methodName && (context.method = "throw", context.arg = new TypeError("The iterator does not provide a '" + methodName + "' method")), ContinueSentinel;
    var record = tryCatch(method, delegate.iterator, context.arg);
    if ("throw" === record.type) return context.method = "throw", context.arg = record.arg, context.delegate = null, ContinueSentinel;
    var info = record.arg;
    return info ? info.done ? (context[delegate.resultName] = info.value, context.next = delegate.nextLoc, "return" !== context.method && (context.method = "next", context.arg = undefined), context.delegate = null, ContinueSentinel) : info : (context.method = "throw", context.arg = new TypeError("iterator result is not an object"), context.delegate = null, ContinueSentinel);
  }
  function pushTryEntry(locs) {
    var entry = {
      tryLoc: locs[0]
    };
    1 in locs && (entry.catchLoc = locs[1]), 2 in locs && (entry.finallyLoc = locs[2], entry.afterLoc = locs[3]), this.tryEntries.push(entry);
  }
  function resetTryEntry(entry) {
    var record = entry.completion || {};
    record.type = "normal", delete record.arg, entry.completion = record;
  }
  function Context(tryLocsList) {
    this.tryEntries = [{
      tryLoc: "root"
    }], tryLocsList.forEach(pushTryEntry, this), this.reset(!0);
  }
  function values(iterable) {
    if (iterable) {
      var iteratorMethod = iterable[iteratorSymbol];
      if (iteratorMethod) return iteratorMethod.call(iterable);
      if ("function" == typeof iterable.next) return iterable;
      if (!isNaN(iterable.length)) {
        var i = -1,
          next = function next() {
            for (; ++i < iterable.length;) if (hasOwn.call(iterable, i)) return next.value = iterable[i], next.done = !1, next;
            return next.value = undefined, next.done = !0, next;
          };
        return next.next = next;
      }
    }
    return {
      next: doneResult
    };
  }
  function doneResult() {
    return {
      value: undefined,
      done: !0
    };
  }
  return GeneratorFunction.prototype = GeneratorFunctionPrototype, defineProperty(Gp, "constructor", {
    value: GeneratorFunctionPrototype,
    configurable: !0
  }), defineProperty(GeneratorFunctionPrototype, "constructor", {
    value: GeneratorFunction,
    configurable: !0
  }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, toStringTagSymbol, "GeneratorFunction"), exports.isGeneratorFunction = function (genFun) {
    var ctor = "function" == typeof genFun && genFun.constructor;
    return !!ctor && (ctor === GeneratorFunction || "GeneratorFunction" === (ctor.displayName || ctor.name));
  }, exports.mark = function (genFun) {
    return Object.setPrototypeOf ? Object.setPrototypeOf(genFun, GeneratorFunctionPrototype) : (genFun.__proto__ = GeneratorFunctionPrototype, define(genFun, toStringTagSymbol, "GeneratorFunction")), genFun.prototype = Object.create(Gp), genFun;
  }, exports.awrap = function (arg) {
    return {
      __await: arg
    };
  }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, asyncIteratorSymbol, function () {
    return this;
  }), exports.AsyncIterator = AsyncIterator, exports.async = function (innerFn, outerFn, self, tryLocsList, PromiseImpl) {
    void 0 === PromiseImpl && (PromiseImpl = Promise);
    var iter = new AsyncIterator(wrap(innerFn, outerFn, self, tryLocsList), PromiseImpl);
    return exports.isGeneratorFunction(outerFn) ? iter : iter.next().then(function (result) {
      return result.done ? result.value : iter.next();
    });
  }, defineIteratorMethods(Gp), define(Gp, toStringTagSymbol, "Generator"), define(Gp, iteratorSymbol, function () {
    return this;
  }), define(Gp, "toString", function () {
    return "[object Generator]";
  }), exports.keys = function (val) {
    var object = Object(val),
      keys = [];
    for (var key in object) keys.push(key);
    return keys.reverse(), function next() {
      for (; keys.length;) {
        var key = keys.pop();
        if (key in object) return next.value = key, next.done = !1, next;
      }
      return next.done = !0, next;
    };
  }, exports.values = values, Context.prototype = {
    constructor: Context,
    reset: function reset(skipTempReset) {
      if (this.prev = 0, this.next = 0, this.sent = this._sent = undefined, this.done = !1, this.delegate = null, this.method = "next", this.arg = undefined, this.tryEntries.forEach(resetTryEntry), !skipTempReset) for (var name in this) "t" === name.charAt(0) && hasOwn.call(this, name) && !isNaN(+name.slice(1)) && (this[name] = undefined);
    },
    stop: function stop() {
      this.done = !0;
      var rootRecord = this.tryEntries[0].completion;
      if ("throw" === rootRecord.type) throw rootRecord.arg;
      return this.rval;
    },
    dispatchException: function dispatchException(exception) {
      if (this.done) throw exception;
      var context = this;
      function handle(loc, caught) {
        return record.type = "throw", record.arg = exception, context.next = loc, caught && (context.method = "next", context.arg = undefined), !!caught;
      }
      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i],
          record = entry.completion;
        if ("root" === entry.tryLoc) return handle("end");
        if (entry.tryLoc <= this.prev) {
          var hasCatch = hasOwn.call(entry, "catchLoc"),
            hasFinally = hasOwn.call(entry, "finallyLoc");
          if (hasCatch && hasFinally) {
            if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0);
            if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc);
          } else if (hasCatch) {
            if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0);
          } else {
            if (!hasFinally) throw new Error("try statement without catch or finally");
            if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc);
          }
        }
      }
    },
    abrupt: function abrupt(type, arg) {
      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        if (entry.tryLoc <= this.prev && hasOwn.call(entry, "finallyLoc") && this.prev < entry.finallyLoc) {
          var finallyEntry = entry;
          break;
        }
      }
      finallyEntry && ("break" === type || "continue" === type) && finallyEntry.tryLoc <= arg && arg <= finallyEntry.finallyLoc && (finallyEntry = null);
      var record = finallyEntry ? finallyEntry.completion : {};
      return record.type = type, record.arg = arg, finallyEntry ? (this.method = "next", this.next = finallyEntry.finallyLoc, ContinueSentinel) : this.complete(record);
    },
    complete: function complete(record, afterLoc) {
      if ("throw" === record.type) throw record.arg;
      return "break" === record.type || "continue" === record.type ? this.next = record.arg : "return" === record.type ? (this.rval = this.arg = record.arg, this.method = "return", this.next = "end") : "normal" === record.type && afterLoc && (this.next = afterLoc), ContinueSentinel;
    },
    finish: function finish(finallyLoc) {
      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        if (entry.finallyLoc === finallyLoc) return this.complete(entry.completion, entry.afterLoc), resetTryEntry(entry), ContinueSentinel;
      }
    },
    "catch": function _catch(tryLoc) {
      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        if (entry.tryLoc === tryLoc) {
          var record = entry.completion;
          if ("throw" === record.type) {
            var thrown = record.arg;
            resetTryEntry(entry);
          }
          return thrown;
        }
      }
      throw new Error("illegal catch attempt");
    },
    delegateYield: function delegateYield(iterable, resultName, nextLoc) {
      return this.delegate = {
        iterator: values(iterable),
        resultName: resultName,
        nextLoc: nextLoc
      }, "next" === this.method && (this.arg = undefined), ContinueSentinel;
    }
  }, exports;
}
module.exports = _regeneratorRuntime, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ 98969:
/***/ ((module) => {

function _typeof(obj) {
  "@babel/helpers - typeof";

  return (module.exports = _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) {
    return typeof obj;
  } : function (obj) {
    return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
  }, module.exports.__esModule = true, module.exports["default"] = module.exports), _typeof(obj);
}
module.exports = _typeof, module.exports.__esModule = true, module.exports["default"] = module.exports;

/***/ }),

/***/ 32681:
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

// TODO(Babel 8): Remove this file.

var runtime = __webpack_require__(44874)();
module.exports = runtime;

// Copied from https://github.com/facebook/regenerator/blob/main/packages/runtime/runtime.js#L736=
try {
  regeneratorRuntime = runtime;
} catch (accidentalStrictMode) {
  if (typeof globalThis === "object") {
    globalThis.regeneratorRuntime = runtime;
  } else {
    Function("r", "regeneratorRuntime = r")(runtime);
  }
}


/***/ })

}]);
//# sourceMappingURL=8879.js.map