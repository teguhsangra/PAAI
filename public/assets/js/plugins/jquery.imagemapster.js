/*!
 * imagemapster - v1.5.4 - 2021-02-20
 * https://github.com/jamietre/ImageMapster/
 * Copyright (c) 2011 - 2021 James Treworgy
 * License: MIT
 */

! function (a) {
    "function" == typeof define && define.amd ? define(["jquery"], a) : "object" == typeof module && module.exports ? module.exports = function (e, t) {
        return void 0 === t && (t = "undefined" != typeof window ? require("jquery") : require("jquery")(e)), a(t), t
    } : a(jQuery)
}(function (jQuery) {
    ! function (a) {
        "use strict";
        a.event && a.event.special && function () {
            var i, e = !1;
            try {
                var t = Object.defineProperty({}, "passive", {
                    get: function () {
                        return e = !0
                    }
                });
                window.addEventListener("testPassive.mapster", function () {}, t), window.removeEventListener("testPassive.mapster", function () {}, t)
            } catch (e) {}
            e && (i = function (e, t, a) {
                if (!e.includes("noPreventDefault")) return console.warn("non-passive events - listener not added"), !1;
                window.addEventListener(t, a, {
                    passive: !0
                })
            }, a.event.special.touchstart = {
                setup: function (e, t, a) {
                    return i(t, "touchstart", a)
                }
            }, a.event.special.touchend = {
                setup: function (e, t, a) {
                    return i(t, "touchend", a)
                }
            })
        }()
    }(jQuery),
    function ($) {
        "use strict";
        var mapster_version = "1.5.4",
            Aa, Ba, Ca;
        $.fn.mapster = function (e) {
            var t = $.mapster.impl;
            return $.mapster.utils.isFunction(t[e]) ? t[e].apply(this, Array.prototype.slice.call(arguments, 1)) : "object" != typeof e && e ? void $.error("Method " + e + " does not exist on jQuery.mapster") : t.bind.apply(this, arguments)
        }, $.mapster = {
            version: mapster_version,
            render_defaults: {
                isSelectable: !0,
                isDeselectable: !0,
                fade: !1,
                fadeDuration: 150,
                fill: !0,
                fillColor: "FFFFFF",
                fillColorMask: "FFFFFF",
                fillOpacity: .7,
                highlight: !0,
                stroke: !1,
                strokeColor: "ff0000",
                strokeOpacity: 1,
                strokeWidth: 1,
                includeKeys: "",
                altImage: null,
                altImageId: null,
                altImages: {}
            },
            defaults: {
                clickNavigate: !1,
                navigateMode: "location",
                wrapClass: null,
                wrapCss: null,
                onGetList: null,
                sortList: !1,
                listenToList: !1,
                mapKey: "",
                mapValue: "",
                singleSelect: !1,
                listKey: "value",
                listSelectedAttribute: "selected",
                listSelectedClass: null,
                onClick: null,
                onMouseover: null,
                onMouseout: null,
                mouseoutDelay: 0,
                onStateChange: null,
                boundList: null,
                onConfigured: null,
                configTimeout: 3e4,
                noHrefIsMask: !0,
                scaleMap: !0,
                enableAutoResizeSupport: !1,
                autoResize: !1,
                autoResizeDelay: 0,
                autoResizeDuration: 0,
                onAutoResize: null,
                safeLoad: !1,
                areas: []
            },
            shared_defaults: {
                render_highlight: {
                    fade: !0
                },
                render_select: {
                    fade: !1
                },
                staticState: null,
                selected: null
            },
            area_defaults: {
                includeKeys: "",
                isMask: !1
            },
            canvas_style: {
                position: "absolute",
                left: 0,
                top: 0,
                padding: 0,
                border: 0
            },
            hasCanvas: null,
            map_cache: [],
            hooks: {},
            addHook: function (e, t) {
                this.hooks[e] = (this.hooks[e] || []).push(t)
            },
            callHooks: function (e, a) {
                $.each(this.hooks[e] || [], function (e, t) {
                    t.apply(a)
                })
            },
            utils: {
                when: {
                    all: function (e) {
                        return Promise.all(e)
                    },
                    defer: function () {
                        return new function () {
                            this.promise = new Promise(function (e, t) {
                                this.resolve = e, this.reject = t
                            }.bind(this)), this.then = this.promise.then.bind(this.promise), this.catch = this.promise.catch.bind(this.promise)
                        }
                    }
                },
                defer: function () {
                    return this.when.defer()
                },
                subclass: function (a, i) {
                    function e() {
                        var e = this,
                            t = Array.prototype.slice.call(arguments, 0);
                        e.base = a.prototype, e.base.init = function () {
                            a.prototype.constructor.apply(e, t)
                        }, i.apply(e, t)
                    }
                    return (e.prototype = new a).constructor = e
                },
                asArray: function (e) {
                    return e.constructor === Array ? e : this.split(e)
                },
                split: function (e, t) {
                    for (var a, i = e.split(","), n = 0; n < i.length; n++) "" === (a = i[n] ? i[n].trim() : "") ? i.splice(n, 1) : i[n] = t ? t(a) : a;
                    return i
                },
                updateProps: function (e, t) {
                    var i = e || {},
                        e = $.isEmptyObject(i) ? t : e,
                        n = [];
                    return $.each(e, function (e) {
                        n.push(e)
                    }), $.each(Array.prototype.slice.call(arguments, 1), function (e, a) {
                        $.each(a || {}, function (e) {
                            var t;
                            (!n || 0 <= $.inArray(e, n)) && (t = a[e], $.isPlainObject(t) ? i[e] = $.extend(i[e] || {}, t) : t && t.constructor === Array ? i[e] = t.slice(0) : void 0 !== t && (i[e] = a[e]))
                        })
                    }), i
                },
                isElement: function (e) {
                    return "object" == typeof HTMLElement ? e instanceof HTMLElement : e && "object" == typeof e && 1 === e.nodeType && "string" == typeof e.nodeName
                },
                indexOf: function (e, t) {
                    if (Array.prototype.indexOf) return Array.prototype.indexOf.call(e, t);
                    for (var a = 0; a < e.length; a++)
                        if (e[a] === t) return a;
                    return -1
                },
                indexOfProp: function (e, a, i) {
                    var n = e.constructor === Array ? -1 : null;
                    return $.each(e, function (e, t) {
                        if (t && (a ? t[a] : t) === i) return n = e, !1
                    }), n
                },
                boolOrDefault: function (e, t) {
                    return this.isBool(e) ? e : t || !1
                },
                isBool: function (e) {
                    return "boolean" == typeof e
                },
                isUndef: function (e) {
                    return void 0 === e
                },
                isFunction: function (e) {
                    return "function" == typeof e
                },
                ifFunction: function (e, t, a) {
                    this.isFunction(e) && e.call(t, a)
                },
                size: function (e, t) {
                    var a = $.mapster.utils;
                    return {
                        width: t ? e.width || e.naturalWidth : a.imgWidth(e, !0),
                        height: t ? e.height || e.naturalHeight : a.imgHeight(e, !0),
                        complete: function () {
                            return !!this.height && !!this.width
                        }
                    }
                },
                setOpacity: function (e, a) {
                    $.mapster.hasCanvas() ? e.style.opacity = a : $(e).each(function (e, t) {
                        void 0 !== t.opacity ? t.opacity = a : $(t).css("opacity", a)
                    })
                },
                fader: (Aa = {}, Ba = 0, Ca = function (e, t, a, i) {
                    var n, o, s = i / 15,
                        r = $.mapster.utils;
                    if ("number" == typeof e) {
                        if (!(o = Aa[e])) return
                    } else(n = r.indexOfProp(Aa, null, e)) && delete Aa[n], Aa[++Ba] = o = e, e = Ba;
                    t = (a = a || 1) - .01 < t + a / s ? a : t + a / s, r.setOpacity(o, t), t < a && setTimeout(function () {
                        Ca(e, t, a, i)
                    }, 15)
                }, Ca),
                getShape: function (e) {
                    return (e.shape || "rect").toLowerCase()
                },
                hasAttribute: function (e, t) {
                    t = $(e).attr(t);
                    return void 0 !== t && !1 !== t
                }
            },
            getBoundList: function (a, e) {
                if (!a.boundList) return null;
                var i, n, o = $(),
                    s = $.mapster.utils.split(e);
                return a.boundList.each(function (e, t) {
                    for (i = 0; i < s.length; i++) n = s[i], $(t).is("[" + a.listKey + '="' + n + '"]') && (o = o.add(t))
                }), o
            },
            getMapDataIndex: function (e) {
                var t, a;
                switch (e.tagName && e.tagName.toLowerCase()) {
                    case "area":
                        a = $(e).parent().attr("name"), t = $("img[usemap='#" + a + "']")[0];
                        break;
                    case "img":
                        t = e
                }
                return t ? this.utils.indexOfProp(this.map_cache, "image", t) : -1
            },
            getMapData: function (e) {
                e = this.getMapDataIndex(e.length ? e[0] : e);
                if (0 <= e) return 0 <= e ? this.map_cache[e] : null
            },
            queueCommand: function (e, t, a, i) {
                return !!e && (!(e.complete && !e.currentAction) && (e.commands.push({
                    that: t,
                    command: a,
                    args: i
                }), !0))
            },
            unload: function () {
                return this.impl.unload(), this.utils = null, this.impl = null, $.fn.mapster = null, $.mapster = null, $("*").off(".mapster")
            }
        };
        var m = $.mapster,
            u = m.utils,
            ap = Array.prototype;
        $.each(["width", "height"], function (e, a) {
            var i = a.substr(0, 1).toUpperCase() + a.substr(1);
            u["img" + i] = function (e, t) {
                return (t ? $(e)[a]() : 0) || e[a] || e["natural" + i] || e["client" + i] || e["offset" + i]
            }
        }), m.Method = function (e, t, a, i) {
            var n = this;
            n.name = i.name, n.output = e, n.input = e, n.first = i.first || !1, n.args = i.args ? ap.slice.call(i.args, 0) : [], n.key = i.key, n.func_map = t, n.func_area = a, n.name = i.name, n.allowAsync = i.allowAsync || !1
        }, m.Method.prototype = {
            constructor: m.Method,
            go: function () {
                for (var e, t, a, i = this.input, n = [], o = this, s = i.length, r = 0; r < s; r++)
                    if (e = $.mapster.getMapData(i[r]))
                        if (o.allowAsync || !m.queueCommand(e, o.input, o.name, o.args)) {
                            if ((t = e.getData("AREA" === i[r].nodeName ? i[r] : this.key)) ? $.inArray(t, n) < 0 && n.push(t) : a = this.func_map.apply(e, o.args), this.first || void 0 !== a) break
                        } else this.first && (a = "");
                return $(n).each(function (e, t) {
                    a = o.func_area.apply(t, o.args)
                }), void 0 !== a ? a : this.output
            }
        }, $.mapster.impl = function () {
            var me = {},
                addMap = function (e) {
                    return m.map_cache.push(e) - 1
                },
                removeMap = function (e) {
                    m.map_cache.splice(e.index, 1);
                    for (var t = m.map_cache.length - 1; t >= e.index; t--) m.map_cache[t].index--
                };

            function hasVml() {
                var e = $("<div />").appendTo("body");
                e.html('<v:shape id="vml_flag1" adj="1" />');
                var t = e[0].firstChild;
                t.style.behavior = "url(#default#VML)";
                t = !t || "object" == typeof t.adj;
                return e.remove(), t
            }

            function namespaces() {
                return "object" == typeof document.namespaces ? document.namespaces : null
            }

            function hasCanvas() {
                var e = namespaces();
                return (!e || !e.g_vml_) && !!$("<canvas />")[0].getContext
            }

            function merge_areas(a, e) {
                var i, n = a.options.areas;
                e && $.each(e, function (e, t) {
                    t && t.key && (0 <= (i = u.indexOfProp(n, "key", t.key)) ? $.extend(n[i], t) : n.push(t), (i = a.getDataForKey(t.key)) && $.extend(i.options, t))
                })
            }

            function merge_options(e, t) {
                var a = u.updateProps({}, t);
                delete a.areas, u.updateProps(e.options, a), merge_areas(e, t.areas), u.updateProps(e.area_options, e.options)
            }
            return me.get = function (e) {
                var t = m.getMapData(this);
                if (!t || !t.complete) throw "Can't access data until binding complete.";
                return new m.Method(this, function () {
                    return this.getSelected()
                }, function () {
                    return this.isSelected()
                }, {
                    name: "get",
                    args: arguments,
                    key: e,
                    first: !0,
                    allowAsync: !0,
                    defaultReturn: ""
                }).go()
            }, me.data = function (e) {
                return new m.Method(this, null, function () {
                    return this
                }, {
                    name: "data",
                    args: arguments,
                    key: e
                }).go()
            }, me.highlight = function (t) {
                return new m.Method(this, function () {
                    if (!1 !== t) {
                        var e = this.highlightId;
                        return 0 <= e ? this.data[e].key : null
                    }
                    this.ensureNoHighlight()
                }, function () {
                    this.highlight()
                }, {
                    name: "highlight",
                    args: arguments,
                    key: t,
                    first: !0
                }).go()
            }, me.keys = function (e, i) {
                var n = [],
                    a = m.getMapData(this);
                if (!a || !a.complete) throw "Can't access data until binding complete.";

                function o(e) {
                    var t, a = [];
                    i ? (t = e.areas(), $.each(t, function (e, t) {
                        a = a.concat(t.keys)
                    })) : a.push(e.key), $.each(a, function (e, t) {
                        $.inArray(t, n) < 0 && n.push(t)
                    })
                }
                return a && a.complete ? ("string" == typeof e ? i ? o(a.getDataForKey(e)) : n = [a.getKeysForGroup(e)] : (i = e, this.each(function (e, t) {
                    "AREA" === t.nodeName && o(a.getDataForArea(t))
                })), n.join(",")) : ""
            }, me.select = function () {
                me.set.call(this, !0)
            }, me.deselect = function () {
                me.set.call(this, !1)
            }, me.set = function (i, n, e) {
                var o, s, r, c, l = e;

                function h(e) {
                    e && $.inArray(e, c) < 0 && (c.push(e), r += ("" === r ? "" : ",") + e.key)
                }

                function p(e) {
                    $.each(c, function (e, t) {
                        ! function (e) {
                            if (e) {
                                switch (i) {
                                    case !0:
                                        e.select(l);
                                        break;
                                    case !1:
                                        e.deselect(!0);
                                        break;
                                    default:
                                        e.toggle(l)
                                }
                            }
                        }(t)
                    }), i || e.removeSelectionFinish()
                }
                return this.filter("img,area").each(function (e, t) {
                    var a;
                    (s = m.getMapData(t)) !== o && (o && p(o), c = [], r = ""), s && (a = "", "IMG" === t.nodeName.toUpperCase() ? m.queueCommand(s, $(t), "set", [i, n, l]) || (n instanceof Array ? n.length && (a = n.join(",")) : a = n, a && $.each(u.split(a), function (e, t) {
                        h(s.getDataForKey(t.toString())), o = s
                    })) : (l = n, m.queueCommand(s, $(t), "set", [i, l]) || (h(s.getDataForArea(t)), o = s)))
                }), s && p(s), this
            }, me.unbind = function (e) {
                return new m.Method(this, function () {
                    this.clearEvents(), this.clearMapData(e), removeMap(this)
                }, null, {
                    name: "unbind",
                    args: arguments
                }).go()
            }, me.rebind = function (t) {
                return new m.Method(this, function () {
                    var e = this;
                    e.complete = !1, e.configureOptions(t), e.bindImages().then(function () {
                        e.buildDataset(!0), e.complete = !0, e.onConfigured()
                    })
                }, null, {
                    name: "rebind",
                    args: arguments
                }).go()
            }, me.get_options = function (e, t) {
                var a = u.isBool(e) ? e : t;
                return new m.Method(this, function () {
                    var e = $.extend({}, this.options);
                    return a && (e.render_select = u.updateProps({}, m.render_defaults, e, e.render_select), e.render_highlight = u.updateProps({}, m.render_defaults, e, e.render_highlight)), e
                }, function () {
                    return a ? this.effectiveOptions() : this.options
                }, {
                    name: "get_options",
                    args: arguments,
                    first: !0,
                    allowAsync: !0,
                    key: e
                }).go()
            }, me.set_options = function (e) {
                return new m.Method(this, function () {
                    merge_options(this, e)
                }, null, {
                    name: "set_options",
                    args: arguments
                }).go()
            }, me.unload = function () {
                for (var e = m.map_cache.length - 1; 0 <= e; e--) m.map_cache[e] && me.unbind.call($(m.map_cache[e].image));
                me.graphics = null
            }, me.snapshot = function () {
                return new m.Method(this, function () {
                    $.each(this.data, function (e, t) {
                        t.selected = !1
                    }), this.base_canvas = this.graphics.createVisibleCanvas(this), $(this.image).before(this.base_canvas)
                }, null, {
                    name: "snapshot"
                }).go()
            }, me.state = function () {
                var a, i = null;
                return $(this).each(function (e, t) {
                    if ("IMG" === t.nodeName) return (a = m.getMapData(t)) && (i = a.state()), !1
                }), i
            }, me.bind = function (o) {
                return this.each(function (e, t) {
                    var a, i = $(t),
                        n = m.getMapData(t);
                    if (n) {
                        if (me.unbind.apply(i), !n.complete) return !0;
                        n = null
                    }
                    if (t = (a = this.getAttribute("usemap")) && $('map[name="' + a.substr(1) + '"]'), !(i.is("img") && a && 0 < t.length)) return !0;
                    i.css("border", 0), n || ((n = new m.MapData(this, o)).index = addMap(n), n.map = t, n.bindImages().then(function () {
                        n.initialize()
                    }))
                })
            }, me.init = function (e) {
                var a, t;
                m.hasCanvas = function () {
                    return u.isBool(m.hasCanvas.value) || (m.hasCanvas.value = u.isBool(e) ? e : hasCanvas()), m.hasCanvas.value
                }, m.hasVml = function () {
                    var e;
                    return u.isBool(m.hasVml.value) || ((e = namespaces()) && !e.v && (e.add("v", "urn:schemas-microsoft-com:vml"), a = document.createStyleSheet(), t = ["shape", "rect", "oval", "circ", "fill", "stroke", "imagedata", "group", "textbox"], $.each(t, function (e, t) {
                        a.addRule("v\\:" + t, "behavior: url(#default#VML); antialias:true")
                    })), m.hasVml.value = hasVml()), m.hasVml.value
                }, $.extend(m.defaults, m.render_defaults, m.shared_defaults), $.extend(m.area_defaults, m.render_defaults, m.shared_defaults)
            }, me.test = function (obj) {
                return eval(obj)
            }, me
        }(), $.mapster.impl.init()
    }(jQuery),
    function (h) {
        "use strict";
        var i, n, o, r = h.mapster,
            c = r.utils;

        function l(e, t, a) {
            var i = e,
                n = i.map_data,
                o = a.isMask;
            h.each(t.areas(), function (e, t) {
                a.isMask = o || t.nohref && n.options.noHrefIsMask, i.addShape(t, a)
            }), a.isMask = o
        }

        function a(e) {
            return Math.max(0, Math.min(parseInt(e, 16), 255))
        }

        function u(e, t) {
            return "rgba(" + a(e.substr(0, 2)) + "," + a(e.substr(2, 2)) + "," + a(e.substr(4, 2)) + "," + t + ")"
        }

        function s() {}
        r.Graphics = function (e) {
            var t = this;
            t.active = !1, t.canvas = null, t.width = 0, t.height = 0, t.shapes = [], t.masks = [], t.map_data = e
        }, i = r.Graphics.prototype = {
            constructor: r.Graphics,
            begin: function (e, t) {
                var a = h(e);
                this.elementName = t, this.canvas = e, this.width = a.width(), this.height = a.height(), this.shapes = [], this.masks = [], this.active = !0
            },
            addShape: function (e, t) {
                (t.isMask ? this.masks : this.shapes).push({
                    mapArea: e,
                    options: t
                })
            },
            createVisibleCanvas: function (e) {
                return h(this.createCanvasFor(e)).addClass("mapster_el").css(r.canvas_style)[0]
            },
            addShapeGroup: function (e, a, t) {
                var i, n = this,
                    o = this.map_data,
                    s = e.effectiveRenderOptions(a);
                t && h.extend(s, t), t = "select" === a ? (i = "static_" + e.areaId.toString(), o.base_canvas) : o.overlay_canvas, n.begin(t, i), s.includeKeys && (i = c.split(s.includeKeys), h.each(i, function (e, t) {
                    t = o.getDataForKey(t.toString());
                    l(n, t, t.effectiveRenderOptions(a))
                })), l(n, e, s), n.render(), s.fade && c.fader(r.hasCanvas() ? t : h(t).find("._fill").not(".mapster_mask"), 0, r.hasCanvas() ? 1 : s.fillOpacity, s.fadeDuration)
            }
        }, n = {
            renderShape: function (e, t, a) {
                var i, n = t.coords(null, a);
                switch (t.shape) {
                    case "rect":
                    case "rectangle":
                        e.rect(n[0], n[1], n[2] - n[0], n[3] - n[1]);
                        break;
                    case "poly":
                    case "polygon":
                        for (e.moveTo(n[0], n[1]), i = 2; i < t.length; i += 2) e.lineTo(n[i], n[i + 1]);
                        e.lineTo(n[0], n[1]);
                        break;
                    case "circ":
                    case "circle":
                        e.arc(n[0], n[1], n[2], 0, 2 * Math.PI, !1)
                }
            },
            addAltImage: function (e, t, a, i) {
                e.beginPath(), this.renderShape(e, a), e.closePath(), e.clip(), e.globalAlpha = i.altImageOpacity || i.fillOpacity, e.drawImage(t, 0, 0, a.owner.scaleInfo.width, a.owner.scaleInfo.height)
            },
            render: function () {
                var e, a, i = this,
                    n = i.map_data,
                    t = i.masks.length,
                    o = i.createCanvasFor(n),
                    s = o.getContext("2d"),
                    r = i.canvas.getContext("2d");
                return t && (e = i.createCanvasFor(n), (a = e.getContext("2d")).clearRect(0, 0, e.width, e.height), h.each(i.masks, function (e, t) {
                    a.save(), a.beginPath(), i.renderShape(a, t.mapArea), a.closePath(), a.clip(), a.lineWidth = 0, a.fillStyle = "#000", a.fill(), a.restore()
                })), h.each(i.shapes, function (e, t) {
                    s.save(), t.options.fill && (t.options.altImageId ? i.addAltImage(s, n.images[t.options.altImageId], t.mapArea, t.options) : (s.beginPath(), i.renderShape(s, t.mapArea), s.closePath(), s.fillStyle = u(t.options.fillColor, t.options.fillOpacity), s.fill())), s.restore()
                }), h.each(i.shapes.concat(i.masks), function (e, t) {
                    var a = 1 === t.options.strokeWidth ? .5 : 0;
                    t.options.stroke && (s.save(), s.strokeStyle = u(t.options.strokeColor, t.options.strokeOpacity), s.lineWidth = t.options.strokeWidth, s.beginPath(), i.renderShape(s, t.mapArea, a), s.closePath(), s.stroke(), s.restore())
                }), t ? (a.globalCompositeOperation = "source-out", a.drawImage(o, 0, 0), r.drawImage(e, 0, 0)) : r.drawImage(o, 0, 0), i.active = !1, i.canvas
            },
            createCanvasFor: function (e) {
                return h('<canvas width="' + e.scaleInfo.width + '" height="' + e.scaleInfo.height + '"></canvas>')[0]
            },
            clearHighlight: function () {
                var e = this.map_data.overlay_canvas;
                e.getContext("2d").clearRect(0, 0, e.width, e.height)
            },
            refreshSelections: function () {
                var e = this.map_data,
                    t = e.base_canvas;
                e.base_canvas = this.createVisibleCanvas(e), h(e.base_canvas).hide(), h(t).before(e.base_canvas), e.redrawSelections(), h(e.base_canvas).show(), h(t).remove()
            }
        }, o = {
            renderShape: function (e, t, a) {
                var i, n = this,
                    o = e.coords(),
                    s = n.elementName ? 'name="' + n.elementName + '" ' : "",
                    r = a ? 'class="' + a + '" ' : "",
                    c = '<v:fill color="#' + t.fillColor + '" class="_fill" opacity="' + (t.fill ? t.fillOpacity : 0) + '" /><v:stroke class="_fill" opacity="' + t.strokeOpacity + '"/>',
                    l = t.stroke ? " strokeweight=" + t.strokeWidth + ' stroked="t" strokecolor="#' + t.strokeColor + '"' : ' stroked="f"',
                    u = t.fill ? ' filled="t"' : ' filled="f"';
                switch (e.shape) {
                    case "rect":
                    case "rectangle":
                        i = "<v:rect " + r + s + u + l + ' style="zoom:1;margin:0;padding:0;display:block;position:absolute;left:' + o[0] + "px;top:" + o[1] + "px;width:" + (o[2] - o[0]) + "px;height:" + (o[3] - o[1]) + 'px;">' + c + "</v:rect>";
                        break;
                    case "poly":
                    case "polygon":
                        i = "<v:shape " + r + s + u + l + ' coordorigin="0,0" coordsize="' + n.width + "," + n.height + '" path="m ' + o[0] + "," + o[1] + " l " + o.slice(2).join(",") + ' x e" style="zoom:1;margin:0;padding:0;display:block;position:absolute;top:0px;left:0px;width:' + n.width + "px;height:" + n.height + 'px;">' + c + "</v:shape>";
                        break;
                    case "circ":
                    case "circle":
                        i = "<v:oval " + r + s + u + l + ' style="zoom:1;margin:0;padding:0;display:block;position:absolute;left:' + (o[0] - o[2]) + "px;top:" + (o[1] - o[2]) + "px;width:" + 2 * o[2] + "px;height:" + 2 * o[2] + 'px;">' + c + "</v:oval>"
                }
                return e = h(i), h(n.canvas).append(e), e
            },
            render: function () {
                var a, i = this;
                return h.each(this.shapes, function (e, t) {
                    i.renderShape(t.mapArea, t.options)
                }), this.masks.length && h.each(this.masks, function (e, t) {
                    a = c.updateProps({}, t.options, {
                        fillOpacity: 1,
                        fillColor: t.options.fillColorMask
                    }), i.renderShape(t.mapArea, a, "mapster_mask")
                }), this.active = !1, this.canvas
            },
            createCanvasFor: function (e) {
                var t = e.scaleInfo.width,
                    e = e.scaleInfo.height;
                return h('<var width="' + t + '" height="' + e + '" style="zoom:1;overflow:hidden;display:block;width:' + t + "px;height:" + e + 'px;"></var>')[0]
            },
            clearHighlight: function () {
                h(this.map_data.overlay_canvas).children().remove()
            },
            removeSelections: function (e) {
                (0 <= e ? h(this.map_data.base_canvas).find('[name="static_' + e.toString() + '"]') : h(this.map_data.base_canvas).children()).remove()
            }
        }, h.each(["renderShape", "addAltImage", "render", "createCanvasFor", "clearHighlight", "removeSelections", "refreshSelections"], function (e, t) {
            var a;
            i[t] = (a = t, function () {
                return i[a] = (r.hasCanvas() ? n : o)[a] || s, i[a].apply(this, arguments)
            })
        })
    }(jQuery),
    function (o) {
        "use strict";
        var e = o.mapster,
            n = e.utils,
            t = [];
        e.MapImages = function (e) {
            this.owner = e, this.clear()
        }, e.MapImages.prototype = {
            constructor: e.MapImages,
            slice: function () {
                return t.slice.apply(this, arguments)
            },
            splice: function () {
                return t.slice.apply(this.status, arguments), t.slice.apply(this, arguments)
            },
            complete: function () {
                return o.inArray(!1, this.status) < 0
            },
            _add: function (e) {
                e = t.push.call(this, e) - 1;
                return this.status[e] = !1, e
            },
            indexOf: function (e) {
                return n.indexOf(this, e)
            },
            clear: function () {
                var a = this;
                a.ids && 0 < a.ids.length && o.each(a.ids, function (e, t) {
                    delete a[t]
                }), a.ids = [], a.length = 0, a.status = [], a.splice(0)
            },
            add: function (e, t) {
                var a, i, n = this;
                if (e) {
                    if ("string" == typeof e) {
                        if ("object" == typeof (e = n[i = e])) return n.indexOf(e);
                        e = o("<img />").addClass("mapster_el").hide(), a = n._add(e[0]), e.on("load.mapster", function (e) {
                            n.imageLoaded.call(n, e)
                        }).on("error.mapster", function (e) {
                            n.imageLoadError.call(n, e)
                        }), e.attr("src", i)
                    } else a = n._add(o(e)[0]);
                    if (t) {
                        if (this[t]) throw t + " is already used or is not available as an altImage alias.";
                        n.ids.push(t), n[t] = n[a]
                    }
                    return a
                }
            },
            bind: function () {
                var t = this,
                    a = t.owner.options.configTimeout / 200,
                    i = function () {
                        for (var e = t.length; 0 < e-- && t.isLoaded(e););
                        t.complete() ? t.resolve() : 0 < a-- ? t.imgTimeout = window.setTimeout(function () {
                            i.call(t, !0)
                        }, 50) : t.imageLoadError.call(t)
                    },
                    e = t.deferred = n.defer();
                return i(), e
            },
            resolve: function () {
                var e = this.deferred;
                e && (this.deferred = null, e.resolve())
            },
            imageLoaded: function (e) {
                e = this.indexOf(e.target);
                0 <= e && (this.status[e] = !0, o.inArray(!1, this.status) < 0 && this.resolve())
            },
            imageLoadError: function (e) {
                throw clearTimeout(this.imgTimeout), this.triesLeft = 0, e ? "The image " + e.target.src + " failed to load." : "The images never seemed to finish loading. You may just need to increase the configTimeout if images could take a long time to load."
            },
            isLoaded: function (e) {
                var t, a = this.status;
                return !!a[e] || (void 0 !== (t = this[e]).complete ? a[e] = t.complete : a[e] = !!n.imgWidth(t), a[e])
            }
        }
    }(jQuery),
    function (y) {
        "use strict";
        var w = y.mapster,
            d = w.utils;

        function a(e) {
            var t = e.options,
                a = e.images;
            w.hasCanvas() && (y.each(t.altImages || {}, function (e, t) {
                a.add(t, e)
            }), y.each([t].concat(t.areas), function (e, t) {
                y.each([t = t, t.render_highlight, t.render_select], function (e, t) {
                    t && t.altImage && (t.altImageId = a.add(t.altImage))
                })
            })), e.area_options = d.updateProps({}, w.area_defaults, t)
        }

        function b(e) {
            return !!e && "#" !== e
        }

        function f(e) {
            w.hasCanvas() || this.blur(), e.preventDefault()
        }

        function i(t, e) {
            var a = t.getDataForArea(this),
                i = t.options;
            t.currentAreaId < 0 || !a || t.getDataForArea(e.relatedTarget) !== a && (t.currentAreaId = -1, a.area = null, function e(t, a, i, n) {
                return n = n || d.when.defer(), t.activeAreaEvent && (window.clearTimeout(t.activeAreaEvent), t.activeAreaEvent = 0), a < 0 ? n.resolve({
                    completeAction: !1
                }) : i.owner.currentAction || a ? t.activeAreaEvent = window.setTimeout(function () {
                    e(t, 0, i, n)
                }, a || 100) : (a = i.areaId, t.currentAreaId !== a && 0 <= t.highlightId && n.resolve({
                    completeAction: !0
                })), n
            }(t, i.mouseoutDelay, a).then(function (e) {
                e.completeAction && t.clearEffects()
            }), d.isFunction(i.onMouseout) && i.onMouseout.call(this, {
                e: e,
                options: i,
                key: a.key,
                selected: a.isSelected()
            }))
        }
        w.MapData = function (e, t) {
            var a = this;
            a.image = e, a.images = new w.MapImages(a), a.graphics = new w.Graphics(a), a.imgCssText = e.style.cssText || null, e = a, y.extend(e, {
                complete: !1,
                map: null,
                base_canvas: null,
                overlay_canvas: null,
                commands: [],
                data: [],
                mapAreas: [],
                _xref: {},
                highlightId: -1,
                currentAreaId: -1,
                _tooltip_events: [],
                scaleInfo: null,
                index: -1,
                activeAreaEvent: null,
                autoResizeTimer: null
            }), a.configureOptions(t), a.mouseover = function (e) {
                ! function (e, t) {
                    var a = e.getAllDataForArea(this),
                        i = a.length ? a[0] : null;
                    !i || i.isNotRendered() || i.owner.currentAction || e.currentAreaId !== i.areaId && (e.highlightId !== i.areaId && (e.clearEffects(), i.highlight(), e.options.showToolTip && y.each(a, function (e, t) {
                        t.effectiveOptions().toolTip && t.showToolTip()
                    })), e.currentAreaId = i.areaId, d.isFunction(e.options.onMouseover) && e.options.onMouseover.call(this, {
                        e: t,
                        options: i.effectiveOptions(),
                        key: i.key,
                        selected: i.isSelected()
                    }))
                }.call(this, a, e)
            }, a.mouseout = function (e) {
                i.call(this, a, e)
            }, a.click = function (e) {
                ! function (a, i) {
                    var n, o, s, r, e, c = this,
                        t = a.getDataForArea(this),
                        l = a.options;

                    function u(e, t, a) {
                        return "open" !== e ? void(window.location.href = t) : void window.open(t, a || "_self")
                    }

                    function h(e, t, a) {
                        if ("open" !== t) return {
                            href: a
                        };
                        t = y(e.area).attr("href"), a = b(t);
                        return {
                            href: a ? t : e.href,
                            target: a ? y(e.area).attr("target") : e.hrefTarget
                        }
                    }

                    function p(e) {
                        var t;
                        if (s = e.isSelectable() && (e.isDeselectable() || !e.isSelected()), o = s ? !e.isSelected() : e.isSelected(), n = w.getBoundList(l, e.key), d.isFunction(l.onClick) && (r = l.onClick.call(c, {
                                e: i,
                                listTarget: n,
                                key: e.key,
                                selected: o
                            }), d.isBool(r))) {
                            if (!r) return;
                            if (b((t = h(e, l.navigateMode, y(e.area).attr("href"))).href)) return void u(l.navigateMode, t.href, t.target)
                        }
                        s && e.toggle()
                    }
                    f.call(this, i), e = h(t, l.navigateMode, t.href), l.clickNavigate && b(e.href) ? u(l.navigateMode, e.href, e.target) : t && !t.owner.currentAction && (l = a.options, p(t), (t = t.effectiveOptions()).includeKeys && (t = d.split(t.includeKeys), y.each(t, function (e, t) {
                        t = a.getDataForKey(t.toString());
                        t.options.isMask || p(t)
                    })))
                }.call(this, a, e)
            }, a.clearEffects = function (e) {
                ! function (e) {
                    var t = e.options;
                    e.ensureNoHighlight(), t.toolTipClose && 0 <= y.inArray("area-mouseout", t.toolTipClose) && e.activeToolTip && e.clearToolTip()
                }.call(this, a, e)
            }, a.mousedown = function (e) {
                f.call(this, e)
            }
        }, w.MapData.prototype = {
            constructor: w.MapData,
            configureOptions: function (e) {
                this.options = d.updateProps({}, w.defaults, e)
            },
            bindImages: function () {
                var e = this,
                    t = e.images;
                return 2 < t.length ? t.splice(2) : 0 === t.length && (t.add(e.image), t.add(e.image.src)), a(e), e.images.bind()
            },
            isActive: function () {
                return !this.complete || this.currentAction
            },
            state: function () {
                return {
                    complete: this.complete,
                    resizing: "resizing" === this.currentAction,
                    zoomed: this.zoomed,
                    zoomedArea: this.zoomedArea,
                    scaleInfo: this.scaleInfo
                }
            },
            wrapId: function () {
                return "mapster_wrap_" + this.index
            },
            instanceEventNamespace: function () {
                return ".mapster." + this.wrapId()
            },
            _idFromKey: function (e) {
                return "string" == typeof e && Object.prototype.hasOwnProperty.call(this._xref, e) ? this._xref[e] : -1
            },
            getSelected: function () {
                var a = "";
                return y.each(this.data, function (e, t) {
                    t.isSelected() && (a += (a ? "," : "") + this.key)
                }), a
            },
            getAllDataForArea: function (e, t) {
                var a, i, n, o = y(e).filter("area").attr(this.options.mapKey);
                if (o)
                    for (n = [], o = d.split(o), a = 0; a < (t || o.length); a++)(i = this.data[this._idFromKey(o[a])]) && (i.area = e.length ? e[0] : e, n.push(i));
                return n
            },
            getDataForArea: function (e) {
                e = this.getAllDataForArea(e, 1);
                return e && e[0] || null
            },
            getDataForKey: function (e) {
                return this.data[this._idFromKey(e)]
            },
            getKeysForGroup: function (e) {
                e = this.getDataForKey(e);
                return e ? e.isPrimary ? e.key : this.getPrimaryKeysForMapAreas(e.areas()).join(",") : ""
            },
            getPrimaryKeysForMapAreas: function (e) {
                var a = [];
                return y.each(e, function (e, t) {
                    y.inArray(t.keys[0], a) < 0 && a.push(t.keys[0])
                }), a
            },
            getData: function (e) {
                return "string" == typeof e ? this.getDataForKey(e) : e && e.mapster || d.isElement(e) ? this.getDataForArea(e) : null
            },
            ensureNoHighlight: function () {
                0 <= this.highlightId && (this.graphics.clearHighlight(), this.data[this.highlightId].changeState("highlight", !1), this.setHighlightId(-1))
            },
            setHighlightId: function (e) {
                this.highlightId = e
            },
            clearSelections: function () {
                y.each(this.data, function (e, t) {
                    t.selected && t.deselect(!0)
                }), this.removeSelectionFinish()
            },
            setAreaOptions: function (e) {
                for (var t, a, i = (e = e || []).length - 1; 0 <= i; i--)(t = e[i]) && (a = this.getDataForKey(t.key)) && (d.updateProps(a.options, t), d.isBool(t.selected) && (a.selected = t.selected))
            },
            drawSelections: function (e) {
                for (var t = d.asArray(e), a = t.length - 1; 0 <= a; a--) this.data[t[a]].drawSelection()
            },
            redrawSelections: function () {
                y.each(this.data, function (e, t) {
                    t.isSelectedOrStatic() && t.drawSelection()
                })
            },
            setBoundListProperties: function (a, e, i) {
                e.each(function (e, t) {
                    a.listSelectedClass && (i ? y(t).addClass(a.listSelectedClass) : y(t).removeClass(a.listSelectedClass)), a.listSelectedAttribute && y(t).prop(a.listSelectedAttribute, i)
                })
            },
            clearBoundListProperties: function (e) {
                e.boundList && this.setBoundListProperties(e, e.boundList, !1)
            },
            refreshBoundList: function (e) {
                this.clearBoundListProperties(e), this.setBoundListProperties(e, w.getBoundList(e, this.getSelected()), !0)
            },
            setBoundList: function (e) {
                var a, t = this.data.slice(0);
                e.sortList && (a = "desc" === e.sortList ? function (e, t) {
                    return e === t ? 0 : t < e ? -1 : 1
                } : function (e, t) {
                    return e === t ? 0 : e < t ? -1 : 1
                }, t.sort(function (e, t) {
                    return e = e.value, t = t.value, a(e, t)
                })), this.options.boundList = e.onGetList.call(this.image, t)
            },
            initialize: function () {
                var e, t, a, i, n, o, s, r, c = this,
                    l = c.options;
                if (!c.complete) {
                    for ((o = (s = y(c.image)).parent().attr("id")) && 12 <= o.length && "mapster_wrap" === o.substring(0, 12) ? (i = s.parent()).attr("id", c.wrapId()) : (i = y('<div id="' + c.wrapId() + '"></div>'), l.wrapClass && (!0 === l.wrapClass ? i.addClass(s[0].className) : i.addClass(l.wrapClass))), c.wrapper = i, c.scaleInfo = r = d.scaleMap(c.images[0], c.images[1], l.scaleMap), c.base_canvas = t = c.graphics.createVisibleCanvas(c), c.overlay_canvas = a = c.graphics.createVisibleCanvas(c), e = y(c.images[1]).addClass("mapster_el " + c.images[0].className).attr({
                            id: null,
                            usemap: null
                        }), (o = d.size(c.images[0])).complete && e.css({
                            width: o.width,
                            height: o.height
                        }), c.buildDataset(), r = y.extend({
                            display: "block",
                            position: "relative",
                            padding: 0
                        }, !0 === l.enableAutoResizeSupport ? {} : {
                            width: r.width,
                            height: r.height
                        }), l.wrapCss && y.extend(r, l.wrapCss), s.parent()[0] !== c.wrapper[0] && s.before(c.wrapper), i.css(r), y(c.images.slice(2)).hide(), n = 1; n < c.images.length; n++) i.append(c.images[n]);
                    i.append(t).append(a).append(s.css(w.canvas_style)), d.setOpacity(c.images[0], 0), y(c.images[1]).show(), d.setOpacity(c.images[1], 1), c.complete = !0, c.processCommandQueue(), !0 === l.enableAutoResizeSupport && c.configureAutoResize(), c.onConfigured()
                }
            },
            onConfigured: function () {
                var e = y(this.image),
                    t = this.options;
                t.onConfigured && "function" == typeof t.onConfigured && t.onConfigured.call(e, !0)
            },
            buildDataset: function (e) {
                var t, a, i, n, o, s, r, c, l, u, h, p, d, f, m = this,
                    g = m.options;

                function v(e, t) {
                    t = new w.AreaData(m, e, t);
                    return t.areaId = m._xref[e] = m.data.push(t) - 1, t.areaId
                }
                for (m._xref = {}, m.data = [], e || (m.mapAreas = []), (f = !g.mapKey) && (g.mapKey = "data-mapster-key"), t = w.hasVml() ? "area" : f ? "area[coords]" : "area[" + g.mapKey + "]", a = y(m.map).find(t).off(".mapster"), u = 0; u < a.length; u++)
                    if (n = 0, d = a[u], o = y(d), d.coords) {
                        for (f ? (s = String(u), o.attr("data-mapster-key", s)) : s = d.getAttribute(g.mapKey), e ? ((r = m.mapAreas[o.data("mapster") - 1]).configure(s), r.areaDataXref = []) : (r = new w.MapArea(m, d, s), m.mapAreas.push(r)), i = (l = r.keys).length - 1; 0 <= i; i--) c = l[i], g.mapValue && (h = o.attr(g.mapValue)), f ? (n = v(m.data.length, h), (p = m.data[n]).key = c = n.toString()) : 0 <= (n = m._xref[c]) ? (p = m.data[n], h && !m.data[n].value && (p.value = h)) : (n = v(c, h), (p = m.data[n]).isPrimary = 0 === i), r.areaDataXref.push(n), p.areasXref.push(u);
                        b(d = o.attr("href")) && !p.href && (p.href = d, p.hrefTarget = o.attr("target")), r.nohref || o.on("click.mapster", m.click).on("mouseover.mapster touchstart.mapster.noPreventDefault", m.mouseover).on("mouseout.mapster touchend.mapster.noPreventDefault", m.mouseout).on("mousedown.mapster", m.mousedown), o.data("mapster", u + 1)
                    } m.setAreaOptions(g.areas), g.onGetList && m.setBoundList(g), g.boundList && 0 < g.boundList.length && m.refreshBoundList(g), e ? (m.graphics.removeSelections(), m.graphics.refreshSelections()) : m.redrawSelections()
            },
            processCommandQueue: function () {
                for (var e; !this.currentAction && this.commands.length;) e = this.commands[0], this.commands.splice(0, 1), w.impl[e.command].apply(e.that, e.args)
            },
            clearEvents: function () {
                y(this.map).find("area").off(".mapster"), y(this.images).off(".mapster"), y(window).off(this.instanceEventNamespace()), y(window.document).off(this.instanceEventNamespace())
            },
            _clearCanvases: function (e) {
                e || y(this.base_canvas).remove(), y(this.overlay_canvas).remove()
            },
            clearMapData: function (e) {
                this._clearCanvases(e), y.each(this.data, function (e, t) {
                    t.reset()
                }), this.data = null, e || (this.image.style.cssText = this.imgCssText, y(this.wrapper).before(this.image).remove()), this.images.clear(), this.autoResizeTimer && clearTimeout(this.autoResizeTimer), this.autoResizeTimer = null, this.image = null, d.ifFunction(this.clearToolTip, this)
            },
            removeSelectionFinish: function () {
                var e = this.graphics;
                e.refreshSelections(), e.clearHighlight()
            }
        }
    }(jQuery),
    function (o) {
        "use strict";
        var a = o.mapster,
            n = a.utils;

        function s(e) {
            e = o(e);
            return n.hasAttribute(e, "nohref") || !n.hasAttribute(e, "href")
        }
        a.AreaData = function (e, t, a) {
            o.extend(this, {
                owner: e,
                key: t || "",
                isPrimary: !0,
                areaId: -1,
                href: "",
                hrefTarget: null,
                value: a || "",
                options: {},
                selected: null,
                staticStateOverridden: !1,
                areasXref: [],
                area: null,
                optsCache: null
            })
        }, a.AreaData.prototype = {
            constuctor: a.AreaData,
            select: function (e) {
                var t = this,
                    a = t.owner,
                    i = (n = !o.isEmptyObject(e)) ? o.extend(t.effectiveRenderOptions("select"), e, {
                        altImageId: a.images.add(e.altImage)
                    }) : null,
                    e = n && !(t.optsCache === i),
                    n = t.isSelectedOrStatic();
                a.options.singleSelect && (a.clearSelections(), n = t.isSelectedOrStatic()), e && (t.optsCache = i), i = t.updateSelected(!0), n && e ? (a.graphics.removeSelections(t.areaId), a.graphics.refreshSelections()) : n || t.drawSelection(), i && t.changeState("select", !0)
            },
            deselect: function (e) {
                var t = this,
                    a = t.updateSelected(!1);
                t.optsCache = null, t.owner.graphics.removeSelections(t.areaId), e || t.owner.removeSelectionFinish(), a && t.changeState("select", !1)
            },
            toggle: function (e) {
                return this.isSelected() ? this.deselect() : this.select(e), this.isSelected()
            },
            updateSelected: function (e) {
                var t = this.selected;
                return this.selected = e, this.staticStateOverridden = !!n.isBool(this.effectiveOptions().staticState), t !== e
            },
            areas: function () {
                for (var e = [], t = 0; t < this.areasXref.length; t++) e.push(this.owner.mapAreas[this.areasXref[t]]);
                return e
            },
            coords: function (a) {
                var i = [];
                return o.each(this.areas(), function (e, t) {
                    i = i.concat(t.coords(a))
                }), i
            },
            reset: function () {
                o.each(this.areas(), function (e, t) {
                    t.reset()
                }), this.areasXref = [], this.options = null
            },
            isSelectedOrStatic: function () {
                var e = this.effectiveOptions();
                return !n.isBool(e.staticState) || this.staticStateOverridden ? this.isSelected() : e.staticState
            },
            isSelected: function () {
                return n.isBool(this.selected) ? this.selected : !!n.isBool(this.owner.area_options.selected) && this.owner.area_options.selected
            },
            isSelectable: function () {
                return !n.isBool(this.effectiveOptions().staticState) && (!n.isBool(this.owner.options.staticState) && n.boolOrDefault(this.effectiveOptions().isSelectable, !0))
            },
            isDeselectable: function () {
                return !n.isBool(this.effectiveOptions().staticState) && (!n.isBool(this.owner.options.staticState) && n.boolOrDefault(this.effectiveOptions().isDeselectable, !0))
            },
            isNotRendered: function () {
                return s(this.area) || this.effectiveOptions().isMask
            },
            effectiveOptions: function (e) {
                e = n.updateProps({}, this.owner.area_options, this.options, e || {}, {
                    id: this.areaId
                });
                return e.selected = this.isSelected(), e
            },
            effectiveRenderOptions: function (e, t) {
                var a = this.optsCache;
                return a && "highlight" !== e || (t = this.effectiveOptions(t), a = n.updateProps({}, t, t["render_" + e]), "highlight" !== e && (this.optsCache = a)), o.extend({}, a)
            },
            changeState: function (e, t) {
                n.isFunction(this.owner.options.onStateChange) && this.owner.options.onStateChange.call(this.owner.image, {
                    key: this.key,
                    state: e,
                    selected: t
                }), "select" === e && this.owner.options.boundList && this.owner.setBoundListProperties(this.owner.options, a.getBoundList(this.owner.options, this.key), t)
            },
            highlight: function (e) {
                var t = this.owner;
                t.ensureNoHighlight(), this.effectiveOptions().highlight && t.graphics.addShapeGroup(this, "highlight", e), t.setHighlightId(this.areaId), this.changeState("highlight", !0)
            },
            drawSelection: function () {
                this.owner.graphics.addShapeGroup(this, "select")
            }
        }, a.MapArea = function (e, t, a) {
            var i;
            e && ((i = this).owner = e, i.area = t, i.areaDataXref = [], i.originalCoords = [], o.each(n.split(t.coords), function (e, t) {
                i.originalCoords.push(parseFloat(t))
            }), i.length = i.originalCoords.length, i.shape = n.getShape(t), i.nohref = s(t), i.configure(a))
        }, a.MapArea.prototype = {
            constructor: a.MapArea,
            configure: function (e) {
                this.keys = n.split(e)
            },
            reset: function () {
                this.area = null
            },
            coords: function (t) {
                return o.map(this.originalCoords, function (e) {
                    return t ? e : e + t
                })
            }
        }
    }(jQuery),
    function (x) {
        "use strict";
        var M = x.mapster.utils;
        M.areaCorners = function (e, t, a, i, n) {
            var o, s, r, c, l, u, h, p, d, f, m, g, v, y, w, b, k, A, S, C, _ = 0,
                T = 0,
                I = [];
            for (e = e.length ? e : [e], o = (a = x(a || document.body)).offset(), w = o.left, b = o.top, t && (_ = (o = x(t).offset()).left, T = o.top), y = 0; y < e.length; y++)
                if ("AREA" === (C = e[y]).nodeName) {
                    switch (k = M.split(C.coords, parseInt), M.getShape(C)) {
                        case "circle":
                        case "circ":
                            for (m = k[0], g = k[1], A = k[2], I = [], y = 0; y < 360; y += 20) S = y * Math.PI / 180, I.push(m + A * Math.cos(S), g + A * Math.sin(S));
                            break;
                        case "rectangle":
                        case "rect":
                            I.push(k[0], k[1], k[2], k[1], k[2], k[3], k[0], k[3]);
                            break;
                        default:
                            I = I.concat(k)
                    }
                    for (y = 0; y < I.length; y += 2) I[y] = parseInt(I[y], 10) + _, I[y + 1] = parseInt(I[y + 1], 10) + T
                } else o = (C = x(C)).position(), I.push(o.left, o.top, o.left + C.width(), o.top, o.left + C.width(), o.top + C.height(), o.left, o.top + C.height());
            for (r = c = h = d = 999999, l = u = p = f = -1, y = I.length - 2; 0 <= y; y -= 2) m = I[y], g = I[y + 1], m < r && (r = m, f = g), l < m && (l = m, d = g), g < c && (c = g, p = m), u < g && (u = g, h = m);
            return i && n && (s = !1, x.each([
                [p - i, c - n],
                [h, c - n],
                [r - i, f - n],
                [r - i, d],
                [l, f - n],
                [l, d],
                [p - i, u],
                [h, u]
            ], function (e, t) {
                if (!s && t[0] > w && t[1] > b) return v = t, !(s = !0)
            }), s || (v = [l, u])), v
        }
    }(jQuery),
    function (d) {
        "use strict";
        var f = d.mapster,
            m = f.utils,
            e = f.MapArea.prototype;
        f.utils.getScaleInfo = function (e, t) {
            var a;
            return t ? .98 < (a = e.width / t.width || e.height / t.height) && a < 1.02 && (a = 1) : (a = 1, t = e), {
                scale: 1 !== a,
                scalePct: a,
                realWidth: t.width,
                realHeight: t.height,
                width: e.width,
                height: e.height,
                ratio: e.width / e.height
            }
        }, f.utils.scaleMap = function (e, t, a) {
            e = m.size(e), t = m.size(t, !0);
            if (!t.complete()) throw "Another script, such as an extension, appears to be interfering with image loading. Please let us know about this.";
            return e.complete() || (e = t), this.getScaleInfo(e, a ? t : null)
        }, f.MapData.prototype.resize = function (t, a, i, n) {
            var o, s, r, c, e, l = this;

            function u(e, t, a) {
                f.hasCanvas() ? (e.width = t, e.height = a) : (d(e).width(t), d(e).height(a))
            }

            function h() {
                var e;
                u(l.overlay_canvas, t, a), 0 <= c && ((e = l.data[c]).tempOptions = {
                    fade: !1
                }, l.getDataForKey(e.key).highlight(), e.tempOptions = null), u(l.base_canvas, t, a), l.redrawSelections(), l.currentAction = "", m.isFunction(n) && n(), l.processCommandQueue()
            }

            function p() {
                d(l.image).css(r), l.scaleInfo = m.getScaleInfo({
                    width: t,
                    height: a
                }, {
                    width: l.scaleInfo.realWidth,
                    height: l.scaleInfo.realHeight
                }), d.each(l.data, function (e, t) {
                    d.each(t.areas(), function (e, t) {
                        t.resize()
                    })
                })
            }
            n = n || i, l.scaleInfo.width === t && l.scaleInfo.height === a || (c = l.highlightId, t || (e = a / l.scaleInfo.realHeight, t = Math.round(l.scaleInfo.realWidth * e)), a || (e = t / l.scaleInfo.realWidth, a = Math.round(l.scaleInfo.realHeight * e)), r = {
                width: String(t) + "px",
                height: String(a) + "px"
            }, f.hasCanvas() || d(l.base_canvas).children().remove(), e = d(l.wrapper).find(".mapster_el"), !0 !== l.options.enableAutoResizeSupport && (e = e.add(l.wrapper)), i ? (s = [], l.currentAction = "resizing", e.filter(":visible").each(function (e, t) {
                o = m.defer(), s.push(o), d(t).animate(r, {
                    duration: i,
                    complete: o.resolve,
                    easing: "linear"
                })
            }), e.filter(":hidden").css(r), o = m.defer(), s.push(o), m.when.all(s).then(h), p(), o.resolve()) : (e.css(r), p(), h()))
        }, f.MapData.prototype.autoResize = function (e, t) {
            this.resize(d(this.wrapper).width(), null, e, t)
        }, f.MapData.prototype.configureAutoResize = function () {
            var e = this,
                t = e.instanceEventNamespace();

            function a() {
                !0 === e.options.autoResize && e.autoResize(e.options.autoResizeDuration, e.options.onAutoResize)
            }
            d(e.image).on("load" + t, a), d(window).on("focus" + t, a), d(window).on("resize" + t, function () {
                e.autoResizeTimer && clearTimeout(e.autoResizeTimer), e.autoResizeTimer = setTimeout(a, e.options.autoResizeDelay)
            }), d(window).on("readystatechange" + t, a), d(window.document).on("fullscreenchange" + t, a), a()
        }, f.MapArea = m.subclass(f.MapArea, function () {
            this.base.init(), this.owner.scaleInfo.scale && this.resize()
        }), e.coords = function (e, t) {
            var a, i = [],
                n = e || this.owner.scaleInfo.scalePct,
                o = t || 0;
            if (1 === n && 0 === t) return this.originalCoords;
            for (a = 0; a < this.length; a++) i.push(Math.round(this.originalCoords[a] * n) + o);
            return i
        }, e.resize = function () {
            this.area.coords = this.coords().join(",")
        }, e.reset = function () {
            this.area.coords = this.coords(1).join(",")
        }, f.impl.resize = function (t, a, i, n) {
            return new f.Method(this, function () {
                var e = !t && !a;
                if (!(this.options.enableAutoResizeSupport && this.options.autoResize && e)) return !e && void this.resize(t, a, i, n);
                this.autoResize(i, n)
            }, null, {
                name: "resize",
                args: arguments
            }).go()
        }
    }(jQuery),
    function (c) {
        "use strict";
        var e = c.mapster,
            l = e.utils;

        function u(e, t, a) {
            var i;
            return t ? (i = "string" == typeof t ? c(t) : c(t).clone()).append(e) : i = c(e), i.css(c.extend(a || {}, {
                display: "block",
                position: "absolute"
            })).hide(), c("body").append(i), i.attr("data-opacity", i.css("opacity")).css("opacity", 0), i.show()
        }

        function h(e, t, a, i, n, o) {
            var s = ".mapster.tooltip",
                a = a + s;
            return 0 <= c.inArray(t, e) && (i.off(a).on(a, function (e) {
                n && !n.call(this, e) || (i.off(s), o && o.call(this))
            }), 1)
        }

        function p(e, t, a, i, n) {
            var o, s = {};
            return n = n || {}, t ? (o = l.areaCorners(t, a, i, e.outerWidth(!0), e.outerHeight(!0)), s.left = o[0], s.top = o[1]) : (s.left = n.left, s.top = n.top), s.left += n.offsetx || 0, s.top += n.offsety || 0, s.css = n.css, s.fadeDuration = n.fadeDuration, a = e, o = {
                left: (i = s).left + "px",
                top: i.top + "px"
            }, n = a.attr("data-opacity") || 0, s = a.css("z-index"), 0 !== parseInt(s, 10) && "auto" !== s || (o["z-index"] = 9999), a.css(o).addClass("mapster_tooltip"), i.fadeDuration && 0 < i.fadeDuration ? l.fader(a[0], 0, n, i.fadeDuration) : l.setOpacity(a[0], n), e
        }

        function d(e) {
            return e ? "string" == typeof e || e.jquery || l.isFunction(e) ? e : e.content : null
        }

        function f(e) {
            return e ? "string" == typeof e || e.jquery || l.isFunction(e) ? {
                content: e
            } : e : {}
        }
        c.extend(e.defaults, {
            toolTipContainer: '<div style="border: 2px solid black; background: #EEEEEE; width:160px; padding:4px; margin: 4px; -moz-box-shadow: 3px 3px 5px #535353; -webkit-box-shadow: 3px 3px 5px #535353; box-shadow: 3px 3px 5px #535353; -moz-border-radius: 6px 6px 6px 6px; -webkit-border-radius: 6px; border-radius: 6px 6px 6px 6px; opacity: 0.9;"></div>',
            showToolTip: !1,
            toolTip: null,
            toolTipFade: !0,
            toolTipClose: ["area-mouseout", "image-mouseout", "generic-mouseout"],
            onShowToolTip: null,
            onHideToolTip: null
        }), c.extend(e.area_defaults, {
            toolTip: null,
            toolTipClose: null
        }), e.MapData.prototype.clearToolTip = function () {
            this.activeToolTip && (this.activeToolTip.stop().remove(), this.activeToolTip = null, this.activeToolTipID = null, l.ifFunction(this.options.onHideToolTip, this))
        }, e.AreaData.prototype.showToolTip = function (e, t) {
            var a, i, n, o = this,
                s = o.owner,
                r = o.effectiveOptions();
            if (t = t ? c.extend({}, t) : {}, e = e || r.toolTip, a = t.closeEvents || r.toolTipClose || s.options.toolTipClose || "tooltip-click", n = void 0 !== t.template ? t.template : s.options.toolTipContainer, t.closeEvents = "string" == typeof a ? a = l.split(a) : a, t.fadeDuration = t.fadeDuration || (s.options.toolTipFade ? s.options.fadeDuration || r.fadeDuration : 0), i = o.area || c.map(o.areas(), function (e) {
                    return e.area
                }), s.activeToolTipID !== o.areaId) {
                s.clearToolTip();
                var e = l.isFunction(e) ? e({
                    key: this.key,
                    target: i
                }) : e;
                if (e) return s.activeToolTip = e = u(e, n, t.css), s.activeToolTipID = o.areaId, n = function () {
                    s.clearToolTip()
                }, h(a, "area-click", "click", c(s.map), null, n), h(a, "tooltip-click", "click", e, null, n), h(a, "image-mouseout", "mouseout", c(s.image), function (e) {
                    return e.relatedTarget && "AREA" !== e.relatedTarget.nodeName && e.relatedTarget !== o.area
                }, n), h(a, "image-click", "click", c(s.image), null, n), p(e, i, s.image, t.container, t), l.ifFunction(s.options.onShowToolTip, o.area, {
                    toolTip: e,
                    options: {},
                    areaOptions: r,
                    key: o.key,
                    selected: o.isSelected()
                }), e
            }
        }, e.impl.tooltip = function (s, r) {
            return new e.Method(this, function () {
                var e, t, a, i, n, o = this;
                s ? (n = (e = c(s)) && 0 < e.length ? e[0] : null, o.activeToolTipID !== n && (o.clearToolTip(), n && (a = d(r), (i = l.isFunction(a) ? a({
                    key: this.key,
                    target: e
                }) : a) && (t = (r = f(r)).closeEvents || o.options.toolTipClose || "tooltip-click", r.closeEvents = "string" == typeof t ? t = l.split(t) : t, r.fadeDuration = r.fadeDuration || (o.options.toolTipFade ? o.options.fadeDuration : 0), a = function () {
                    o.clearToolTip()
                }, o.activeToolTip = i = u(i, r.template || o.options.toolTipContainer, r.css), o.activeToolTipID = n, h(t, "tooltip-click", "click", i, null, a), h(t, "generic-mouseout", "mouseout", e, null, a), h(t, "generic-click", "click", e, null, a), o.activeToolTip = i = p(i, e, o.image, r.container, r))))) : o.clearToolTip()
            }, function () {
                c.isPlainObject(s) && !r && (r = s), this.showToolTip(d(r), f(r))
            }, {
                name: "tooltip",
                args: arguments,
                key: s
            }).go()
        }
    }(jQuery)
});
//# sourceMappingURL=jquery.imagemapster.min.js.map
