(window.webpackJsonp_live_stream_gate_block=window.webpackJsonp_live_stream_gate_block||[]).push([[1],{6:function(e,t,n){}}]),function(e){function t(t){for(var o,c,a=t[0],s=t[1],i=t[2],u=0,p=[];u<a.length;u++)c=a[u],Object.prototype.hasOwnProperty.call(r,c)&&r[c]&&p.push(r[c][0]),r[c]=0;for(o in s)Object.prototype.hasOwnProperty.call(s,o)&&(e[o]=s[o]);for(b&&b(t);p.length;)p.shift()();return l.push.apply(l,i||[]),n()}function n(){for(var e,t=0;t<l.length;t++){for(var n=l[t],o=!0,a=1;a<n.length;a++){var s=n[a];0!==r[s]&&(o=!1)}o&&(l.splice(t--,1),e=c(c.s=n[0]))}return e}var o={},r={0:0},l=[];function c(t){if(o[t])return o[t].exports;var n=o[t]={i:t,l:!1,exports:{}};return e[t].call(n.exports,n,n.exports,c),n.l=!0,n.exports}c.m=e,c.c=o,c.d=function(e,t,n){c.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},c.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},c.t=function(e,t){if(1&t&&(e=c(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(c.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)c.d(n,o,function(t){return e[t]}.bind(null,o));return n},c.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return c.d(t,"a",t),t},c.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},c.p="";var a=window.webpackJsonp_live_stream_gate_block=window.webpackJsonp_live_stream_gate_block||[],s=a.push.bind(a);a.push=t,a=a.slice();for(var i=0;i<a.length;i++)t(a[i]);var b=s;l.push([7,1]),n()}([function(e,t){e.exports=window.wp.element},function(e,t){e.exports=window.wp.components},function(e,t){e.exports=window.wp.blockEditor},function(e,t){e.exports=window.wp.i18n},function(e,t){e.exports=window.wp.blocks},function(e,t){e.exports=window.wp.data},,function(e,t,n){"use strict";n.r(t);var o=n(4),r=(n(6),n(0)),l=n(3),c=n(2),a=n(1),s=n(5);Object(o.registerBlockType)("lf/live-stream-gate-block",{edit:function({attributes:e,setAttributes:t,isSelected:n}){const[o,i]=Object(r.useState)(),b=Object(s.useSelect)(e=>["\n\t\t\thtml,body,:root {\n\t\t\t\tmargin: 0 !important;\n\t\t\t\tpadding: 0 !important;\n\t\t\t\toverflow: visible !important;\n\t\t\t\tmin-height: auto !important;\n\t\t\t}\n\t\t",...Object(c.transformStyles)(e(c.store).getSettings().styles)],[]);return Object(r.createElement)("div",Object(c.useBlockProps)({className:"components-placeholder wp-block-embed is-large"}),Object(r.createElement)("div",{className:"components-placeholder__label"},Object(r.createElement)("span",{className:"block-editor-block-icon has-colors"},Object(r.createElement)(a.Dashicon,{icon:"embed-video"})),"Live Stream Gate"),Object(r.createElement)("div",{className:"components-placeholder__instructions"},"Paste your embed code below, it will be hidden behind an LF SSO gate unless the user is logged in. The SSO check can be disabled in the block Settings sidebar."),Object(r.createElement)(c.BlockControls,null,Object(r.createElement)(a.ToolbarGroup,null,Object(r.createElement)(a.ToolbarButton,{className:"components-tab-button",isPressed:!o,onClick:function(){i(!1)}},Object(r.createElement)("span",null,"Embed")),Object(r.createElement)(a.ToolbarButton,{className:"components-tab-button",isPressed:o,onClick:function(){i(!0)}},Object(r.createElement)("span",null,Object(l.__)("Preview"))))),Object(r.createElement)(c.InspectorControls,{key:"live-stream-gate-block-panel"},Object(r.createElement)(a.PanelBody,{title:Object(l.__)("Settings"),initialOpen:!0},Object(r.createElement)(a.ToggleControl,{label:"Disable SSO Check",checked:e.ssoDisabled,onChange:()=>t({ssoDisabled:!e.ssoDisabled}),help:e.ssoDisabled?"SSO check is disabled.":"SSO check is enabled."}))),Object(r.createElement)(a.Disabled.Consumer,null,s=>o||s?Object(r.createElement)(r.Fragment,null,Object(r.createElement)(a.SandBox,{html:e.content,styles:b}),!n&&Object(r.createElement)("div",{className:"block-library-html__preview-overlay"})):Object(r.createElement)("pre",null,Object(r.createElement)(c.PlainText,{value:e.content,onChange:e=>t({content:e}),placeholder:Object(l.__)("Paste your embed code here"),"aria-label":Object(l.__)("Paste your embed code here")}))))}})}]);