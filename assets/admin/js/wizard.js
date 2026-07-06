"use strict";

function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t["return"] || t["return"](); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function _regenerator() { /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ var e, t, r = "function" == typeof Symbol ? Symbol : {}, n = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag"; function i(r, n, o, i) { var c = n && n.prototype instanceof Generator ? n : Generator, u = Object.create(c.prototype); return _regeneratorDefine2(u, "_invoke", function (r, n, o) { var i, c, u, f = 0, p = o || [], y = !1, G = { p: 0, n: 0, v: e, a: d, f: d.bind(e, 4), d: function d(t, r) { return i = t, c = 0, u = e, G.n = r, a; } }; function d(r, n) { for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) { var o, i = p[t], d = G.p, l = i[2]; r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0)); } if (o || r > 1) return a; throw y = !0, n; } return function (o, p, l) { if (f > 1) throw TypeError("Generator is already running"); for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) { i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u); try { if (f = 2, i) { if (c || (o = "next"), t = i[o]) { if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object"); if (!t.done) return t; u = t.value, c < 2 && (c = 0); } else 1 === c && (t = i["return"]) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1); i = e; } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break; } catch (t) { i = e, c = 1, u = t; } finally { f = 1; } } return { value: t, done: y }; }; }(r, o, i), !0), u; } var a = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} t = Object.getPrototypeOf; var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () { return this; }), t), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c); function f(e) { return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () { return this; }), _regeneratorDefine2(u, "toString", function () { return "[object Generator]"; }), (_regenerator = function _regenerator() { return { w: i, m: f }; })(); }
function _regeneratorDefine2(e, r, n, t) { var i = Object.defineProperty; try { i({}, "", {}); } catch (e) { i = 0; } _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) { function o(r, n) { _regeneratorDefine2(e, r, function (e) { return this._invoke(r, n, e); }); } r ? i ? i(e, r, { value: n, enumerable: !t, configurable: !t, writable: !t }) : e[r] = n : (o("next", 0), o("throw", 1), o("return", 2)); }, _regeneratorDefine2(e, r, n, t); }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
var Wizard = {
  data: function data() {
    return {
      loaded: false,
      screen: 0,
      hasCache: false,
      currentPage: "welcome",
      userType: "normal",
      hasConsent: true,
      steps: [{
        key: "welcome",
        name: "Welcome",
        isComplete: false
      }, {
        key: "widgets",
        name: "Widgets",
        isComplete: false
      }, {
        key: "features",
        name: "Features",
        isComplete: false
      }, {
        key: "bepro",
        name: "Be a pro!",
        isComplete: false
      }, {
        key: "contribute",
        name: "Contribute",
        isComplete: false
      }, {
        key: "congrats",
        name: "Congrats",
        isComplete: false
      }],
      widgetList: [],
      disabledWidgets: [],
      featureList: [],
      disabledFeatures: [],
      settings: {
        welcome: {
          userType: null
        },
        widgets: [],
        features: null,
        contribute: false,
        all: [],
        checkedWidgets: []
      },
      widgetMore: true
    };
  },
  mounted: function mounted() {
    this.fetchCache();
    this.getCurrentPage();
  },
  methods: {
    fetchWidgetData: function fetchWidgetData() {
      var _this = this;
      return _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee() {
        var url;
        return _regenerator().w(function (_context) {
          while (1) switch (_context.n) {
            case 0:
              url = window.HappyWizard.apiBase + "/widgets/all/";
              _context.n = 1;
              return fetch(url, {
                method: "GET",
                headers: {
                  "X-WP-Nonce": window.HappyWizard.nonce
                }
              }).then(function (response) {
                return response.json();
              }).then(function (data) {
                if (data) {
                  _this.widgetList = data.all;
                  _this.disabledWidgets = data.disabled;
                }
              })["catch"](function (error) {
                console.error("Error:", error);
              });
            case 1:
              return _context.a(2);
          }
        }, _callee);
      }))();
    },
    fetchCache: function fetchCache() {
      var _this2 = this;
      return _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee2() {
        var url;
        return _regenerator().w(function (_context2) {
          while (1) switch (_context2.n) {
            case 0:
              url = window.HappyWizard.apiBase + "/wizard/cache";
              _context2.n = 1;
              return fetch(url, {
                method: "GET",
                headers: {
                  "X-WP-Nonce": window.HappyWizard.nonce
                }
              }).then(function (response) {
                return response.json();
              }).then(function (data) {
                if (data.data) {
                  if (data.data.steps) {
                    _this2.steps = data.data.steps;
                  }
                  if (data.data.currentPage) {
                    _this2.currentPage = data.data.currentPage;
                  }
                  if (data.data.userType) {
                    _this2.userType = data.data.userType;
                  }
                  if (data.data.widgets) {
                    _this2.widgetList = data.data.widgets;
                  }
                  if (data.data.widgets_disabled) {
                    _this2.disabledWidgets = data.data.widgets_disabled;
                  }
                  if (data.data.features) {
                    _this2.featureList = data.data.features;
                  }
                  if (data.data.features_disabled) {
                    _this2.disabledFeatures = data.data.features_disabled;
                  }
                  _this2.loaded = true;
                } else {
                  _this2.fetchPreset(_this2.userType);
                }
              })["catch"](function (error) {
                console.error("Error:", error);
              });
            case 1:
              return _context2.a(2);
          }
        }, _callee2);
      }))();
    },
    fetchPreset: function fetchPreset(userType) {
      var _this3 = this;
      return _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee3() {
        var url;
        return _regenerator().w(function (_context3) {
          while (1) switch (_context3.n) {
            case 0:
              url = window.HappyWizard.apiBase + "/wizard/preset/" + userType;
              _context3.n = 1;
              return fetch(url, {
                method: "GET",
                headers: {
                  "X-WP-Nonce": window.HappyWizard.nonce
                }
              }).then(function (response) {
                return response.json();
              }).then(function (data) {
                if (data) {
                  _this3.widgetList = data.widgets.all;
                  _this3.disabledWidgets = data.widgets.disabled;
                  _this3.featureList = data.features.all;
                  _this3.disabledFeatures = data.features.disabled;
                }
                _this3.loaded = true;
              })["catch"](function (error) {
                console.error("Error:", error);
              });
            case 1:
              return _context3.a(2);
          }
        }, _callee3);
      }))();
    },
    saveWizardData: function saveWizardData() {
      var _arguments = arguments,
        _this4 = this;
      return _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee4() {
        var mode, url, data;
        return _regenerator().w(function (_context4) {
          while (1) switch (_context4.n) {
            case 0:
              mode = _arguments.length > 0 && _arguments[0] !== undefined ? _arguments[0] : '';
              url = window.HappyWizard.apiBase + "/wizard/save";
              data = {
                'widget': _this4.disabledWidgets,
                'features': _this4.disabledFeatures,
                'consent': _this4.consent ? 'yes' : 'no'
              };
              if (mode == "cache") {
                url = window.HappyWizard.apiBase + "/wizard/save-cache";
                data = {
                  'currentPage': _this4.currentPage,
                  'userType': _this4.userType,
                  'steps': _this4.steps,
                  'widgets': _this4.widgetList,
                  'widgets_disabled': _this4.disabledWidgets,
                  'features': _this4.featureList,
                  'features_disabled': _this4.disabledFeatures,
                  'consent': _this4.hasConsent ? 'yes' : 'no'
                };
              }
              _context4.n = 1;
              return fetch(url, {
                method: "POST",
                headers: {
                  "X-WP-Nonce": window.HappyWizard.nonce
                },
                body: JSON.stringify(data),
                contentType: "application/json; charset=utf-8"
              }).then(function (response) {
                return response.json();
              }).then(function (data) {
                if (data && data.status === 200) {
                  if (mode === "cache") {} else {
                    window.open(window.HappyWizard.haAdmin, "_self");
                  }
                }
              })["catch"](function (error) {
                console.error("Error:", error);
              });
            case 1:
              return _context4.a(2);
          }
        }, _callee4);
      }))();
    },
    endWizard: function endWizard() {
      return _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee5() {
        var agee, url;
        return _regenerator().w(function (_context5) {
          while (1) switch (_context5.n) {
            case 0:
              agee = confirm('Head’s up. This action is non reversible and you won\’t be able to see this wizard again. Proceed?');
              if (!agee) {
                _context5.n = 1;
                break;
              }
              url = window.HappyWizard.apiBase + "/wizard/skip";
              _context5.n = 1;
              return fetch(url, {
                method: "POST",
                headers: {
                  "X-WP-Nonce": window.HappyWizard.nonce
                }
              }).then(function (response) {
                return response.json();
              }).then(function (data) {
                if (data && data.status === 200) {
                  window.open(window.HappyWizard.haAdmin, "_self");
                }
              })["catch"](function (error) {
                console.error("Error:", error);
              });
            case 1:
              return _context5.a(2);
          }
        }, _callee5);
      }))();
    },
    setUserType: function setUserType(type) {
      this.userType = type;
      this.fetchPreset(type);
    },
    setTab: function setTab(screen) {
      if (screen) {
        if (screen == 'buypro') {
          window.open('https://happyaddons.com/go/get-pro', '_blank').focus();
        } else if (screen == 'done') {
          this.saveWizardData();
        } else {
          this.setStepComplete(this.currentPage);
          this.currentPage = screen;
          this.screen = screen;
        }
        this.saveWizardData("cache");
      }
    },
    setStepComplete: function setStepComplete(step) {
      var _iterator = _createForOfIteratorHelper(this.steps),
        _step;
      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var elem = _step.value;
          if (elem.key == step) {
            elem.isComplete = true;
            break;
          }
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }
    },
    revealWidgetList: function revealWidgetList() {
      this.widgetMore = false;
    },
    getCurrentPage: function getCurrentPage() {
      var _iterator2 = _createForOfIteratorHelper(this.steps),
        _step2;
      try {
        for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
          var elem = _step2.value;
          if (elem.isComplete == false) {
            this.currentPage = elem.key;
            break;
          }
        }
      } catch (err) {
        _iterator2.e(err);
      } finally {
        _iterator2.f();
      }
      return this.currentPage;
    },
    goNext: function goNext(screen) {
      this.setTab(screen);
    },
    allAdd: function allAdd(key) {
      var modified = this.widgetList[key];
      var localThis = this;
      Object.keys(modified).forEach(function (item) {
        modified[item].is_active = true;
        localThis.isActive(modified[item].slug, false);
      });
      if (this.settings.all.indexOf(key) === -1) {
        this.settings.all.push(key);
      }
      return modified;
    },
    allRemove: function allRemove(key) {
      var modified = this.widgetList[key];
      var localThis = this;
      Object.keys(modified).forEach(function (item) {
        modified[item].is_active = false;
        localThis.isActive(modified[item].slug, true);
      });
      this.settings.all = this.settings.all.filter(function (value, index, arr) {
        return value != key;
      });
      return modified;
    },
    isActive: function isActive(key, stat) {
      if (stat === true) {
        if (this.disabledWidgets.indexOf(key) === -1) {
          this.disabledWidgets.push(key);
        }
      } else {
        this.disabledWidgets = this.disabledWidgets.filter(function (value, index, arr) {
          return value != key;
        });
      }
    },
    isFeatureActive: function isFeatureActive(key, stat) {
      if (stat === true) {
        if (this.disabledFeatures.indexOf(key) === -1) {
          this.disabledFeatures.push(key);
        }
      } else {
        this.disabledFeatures = this.disabledFeatures.filter(function (value, index, arr) {
          return value != key;
        });
      }
    },
    makeTitle: function makeTitle(slug) {
      var title = slug.replace(/-/g, " ").replace("and", "&");
      return title.charAt(0).toUpperCase() + title.slice(1);
    },
    makeLabel: function makeLabel(isPro) {
      if (isPro) {
        return "PRO";
      }
      return "FREE";
    },
    sortByTitle: function sortByTitle(list) {
      return list.sort(function (a, b) {
        return a['title'] < b['title'] ? -1 : 1;
      });
    }
  },
  watch: {
    "settings.checkedWidgets": function settingsCheckedWidgets(val) {},
    "settings.all": function settingsAll(val) {},
    hasConsent: function hasConsent(val) {}
  },
  computed: {}
};
var app = Vue.createApp(Wizard);
app.config.globalProperties.window = window;
app.component("ha-step", {
  props: {
    active: String,
    complete: Boolean,
    step: String,
    title: String,
    index: Number
  },
  emits: ["setTab"],
  computed: {
    isActive: function isActive() {
      return this.active == this.step ? true : false;
    }
  },
  methods: {
    handleClick: function handleClick(step) {
      if (this.complete) {
        this.$emit('setTab', step);
      }
    }
  },
  template: "<div class=\"ha-stepper__step\" :class=\"{ 'is-complete': this.complete, 'is-active': this.isActive }\" @click=\"handleClick(step)\">\n\t<button class=\"ha-stepper__step-label-wrapper\">\n\t\t<div class=\"ha-stepper__step-icon\">\n\t\t\t<span class=\"ha-stepper__step-number\">{{index}}</span>\n\t\t\t<svg width=\"15\" height=\"11\" viewBox=\"0 0 15 11\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n\t\t\t\t<path d=\"M5.09467 10.784L0.219661 5.98988C-0.0732203 5.70186 -0.0732203 5.23487 0.219661 4.94682L1.2803 3.90377C1.57318 3.61572 2.04808 3.61572 2.34096 3.90377L5.625 7.13326L12.659 0.216014C12.9519 -0.0720048 13.4268 -0.0720048 13.7197 0.216014L14.7803 1.25907C15.0732 1.54709 15.0732 2.01408 14.7803 2.30213L6.15533 10.784C5.86242 11.072 5.38755 11.072 5.09467 10.784Z\" fill=\"white\"/>\n\t\t\t</svg>\n\t\t</div>\n\t\t<div class=\"ha-stepper__step-text\">\n\t\t\t<span class=\"ha-stepper__step-label\">{{title}}</span>\n\t\t</div>\n\t</button>\n</div>\n<div class=\"ha-stepper__step-divider\">\n<svg width=\"20\" height=\"21\" viewBox=\"0 0 20 21\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n<path d=\"M14.2218 4.80762C13.8313 4.4171 13.1981 4.4171 12.8076 4.80762C12.4171 5.19815 12.4171 5.83131 12.8076 6.22184L14.2218 4.80762ZM18.4853 10.4853L19.1924 11.1924L19.8995 10.4853L19.1924 9.77818L18.4853 10.4853ZM12.8076 14.7487C12.4171 15.1393 12.4171 15.7724 12.8076 16.163C13.1981 16.5535 13.8313 16.5535 14.2218 16.163L12.8076 14.7487ZM7.19238 4.80762C6.80186 4.4171 6.16869 4.4171 5.77817 4.80762C5.38764 5.19814 5.38764 5.83131 5.77817 6.22183L7.19238 4.80762ZM11.4558 10.4853L12.1629 11.1924L12.87 10.4853L12.1629 9.77818L11.4558 10.4853ZM5.77817 14.7487C5.38764 15.1393 5.38764 15.7724 5.77817 16.163C6.16869 16.5535 6.80186 16.5535 7.19238 16.163L5.77817 14.7487ZM12.8076 6.22184L17.7782 11.1924L19.1924 9.77818L14.2218 4.80762L12.8076 6.22184ZM17.7782 9.77818L12.8076 14.7487L14.2218 16.163L19.1924 11.1924L17.7782 9.77818ZM5.77817 6.22183L10.7487 11.1924L12.1629 9.77818L7.19238 4.80762L5.77817 6.22183ZM10.7487 9.77818L5.77817 14.7487L7.19238 16.163L12.1629 11.1924L10.7487 9.77818Z\" fill=\"currentColor\"/>\n</svg>\n</div>"
});
app.component("ha-nav", {
  props: {
    prev: String,
    next: String,
    done: String,
    bepro: String
  },
  emits: ["setTab"],
  template: "<div class=\"ha-setup-wizard__nav\">\n        <button class=\"ha-setup-wizard__nav_prev\" v-if=\"prev\" @click=\"$emit('setTab',prev)\">\n            <svg width=\"12\" height=\"8\" viewBox=\"0 0 12 8\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n                <path d=\"M12 3.33333H2.55333L4.94 0.94L4 0L0 4L4 8L4.94 7.06L2.55333 4.66667H12V3.33333Z\" fill=\"black\"/>\n            </svg>\n            <span>Back</span>\n        </button>\n\t\t<button class=\"ha-setup-wizard__nav_bepro\" v-if=\"bepro\" @click=\"$emit('setTab','buypro')\">\n\t\t\t<svg width=\"20\" height=\"16\" viewBox=\"0 0 20 16\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\n\t\t\t\t<path d=\"M19.8347 5.42149C19.8347 6.21488 19.1736 6.87603 18.3802 6.87603C18.2479 6.87603 18.2479 6.87603 18.1157 6.87603L15.8678 12.9587H3.96694L1.71901 6.87603C1.58678 6.87603 1.58678 6.87603 1.45455 6.87603C0.661157 6.87603 0 6.21488 0 5.42149C0 4.6281 0.661157 3.96694 1.45455 3.96694C2.24793 3.96694 2.90909 4.6281 2.90909 5.42149C2.90909 5.68595 2.90909 5.81818 2.77686 6.08264L5.02479 7.40496C5.55372 7.66942 6.08264 7.53719 6.34711 7.00826L8.99174 2.64463C8.59504 2.38017 8.46281 1.98347 8.46281 1.45455C8.46281 0.661157 9.12397 0 9.91736 0C10.7107 0 11.3719 0.661157 11.3719 1.45455C11.3719 1.98347 11.1074 2.38017 10.843 2.64463L13.3554 7.00826C13.6198 7.53719 14.281 7.66942 14.6777 7.40496L16.9256 6.08264C16.7934 5.95041 16.7934 5.68595 16.7934 5.42149C16.7934 4.6281 17.4545 3.96694 18.2479 3.96694C19.0413 3.96694 19.8347 4.6281 19.8347 5.42149ZM16.9256 14.4132V15.4711C16.9256 15.7355 16.6612 16 16.3967 16H3.43802C3.17355 16 2.90909 15.7355 2.90909 15.4711V14.4132C2.90909 14.1488 3.17355 13.8843 3.43802 13.8843H16.3967C16.6612 13.8843 16.9256 14.1488 16.9256 14.4132Z\" fill=\"#FFC5C5\"/>\n\t\t\t</svg>\t\t\n\t\t\t<span>Be A Pro</span>\n\t\t</button>\n        <button class=\"ha-setup-wizard__nav_next\" v-if=\"next\" @click=\"$emit('setTab',next)\"><span>Next</span></button>\n        <button class=\"ha-setup-wizard__nav_done\" v-if=\"done\" @click=\"$emit('setTab','done')\"><span>Done</span></button>\n    </div>\n\t"
});
app.mount("#ha-setup-wizard");