(()=>{var e={942:(e,t)=>{var o;!function(){"use strict";var s={}.hasOwnProperty;function n(){for(var e="",t=0;t<arguments.length;t++){var o=arguments[t];o&&(e=l(e,a(o)))}return e}function a(e){if("string"==typeof e||"number"==typeof e)return e;if("object"!=typeof e)return"";if(Array.isArray(e))return n.apply(null,e);if(e.toString!==Object.prototype.toString&&!e.toString.toString().includes("[native code]"))return e.toString();var t="";for(var o in e)s.call(e,o)&&e[o]&&(t=l(t,o));return t}function l(e,t){return t?e?e+" "+t:e+t:e}e.exports?(n.default=n,e.exports=n):void 0===(o=function(){return n}.apply(t,[]))||(e.exports=o)}()}},t={};function o(s){var n=t[s];if(void 0!==n)return n.exports;var a=t[s]={exports:{}};return e[s](a,a.exports,o),a.exports}o.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return o.d(t,{a:t}),t},o.d=(e,t)=>{for(var s in t)o.o(t,s)&&!o.o(e,s)&&Object.defineProperty(e,s,{enumerable:!0,get:t[s]})},o.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{"use strict";var e=o(942),t=o.n(e);const s=window.ReactJSXRuntime,{__}=wp.i18n,n=["core/list"],{createBlock:a}=wp.blocks,{createHigherOrderComponent:l}=wp.compose,{Fragment:i}=wp.element,{InspectorControls:r}=wp.blockEditor,{PanelBody:c,SelectControl:u}=wp.components;function d(e,t){let o,s=0,n=1e3;for(s in e){const a=Math.abs(t-e[s]);a<n&&(n=a,o=e[s])}return o}wp.hooks.addFilter("blocks.registerBlockType","core-block-customisations/set-list-attributes",((e,t)=>n.includes(t)?Object.assign({},e,{attributes:Object.assign({},e.attributes,{selectedIcon:{type:"string"},listGap:{type:"string"},iconSize:{type:"string"},columnCount:{type:"string"}})}):e)),wp.hooks.addFilter("blocks.registerBlockType","core-block-customisations/add-list-transforms",(function(e,t){if(!n.includes(t))return e;const o=e.transforms;void 0===o.from&&(o.from=[]);const s={type:"block",blocks:["lf/icon-list"],transform:e=>{const t={...e},o="<ul>"+e.values+"</ul>",s=wp.blocks.rawHandler({HTML:o})[0],n=t.selectedIcon.replace("is-style-list-","has-icon-");t.selectedIcon=n;const l=`has-list-gap-${d([0,10,15,20,30,40],t.listGap)}`;t.listGap=l;const i=t.columnCount;let r="";2===i&&(r="has-two-columns"),3===i&&(r="has-three-columns"),t.columnCount=r;const c=`has-icon-size-${d([0,10,15,20,30,40],t.iconSize)}`;t.iconSize=c,delete t.values,delete t.type;const u=s.innerBlocks;return u.forEach((e=>{e.innerBlocks.length>0&&e.innerBlocks.forEach((e=>e.attributes={...t}))}),u),a(s.name,{...t},u)}};return o.from.push(s),e.transforms=o,e}));const p=l((e=>t=>{if(!n.includes(t.name))return(0,s.jsx)(e,{...t});const{attributes:o,setAttributes:a}=t,{selectedIcon:l,columnCount:d,iconSize:p,listGap:b}=o;return(0,s.jsxs)(i,{children:[(0,s.jsx)(e,{...t}),(0,s.jsx)(r,{children:(0,s.jsxs)(c,{title:__("Additional Options"),children:[(0,s.jsx)(u,{label:__("No. of columns"),value:d,options:[{label:__("1 Column"),value:""},{label:__("2 Columns"),value:"has-two-columns"},{label:__("3 Columns"),value:"has-three-columns"}],onChange:e=>a({columnCount:""!==e?e:""})}),(0,s.jsx)(u,{label:__("List item gap"),value:b,options:[{label:__("Default"),value:""},{label:__("10px"),value:"has-list-gap-10"},{label:__("15px"),value:"has-list-gap-15"},{label:__("20px"),value:"has-list-gap-20"},{label:__("30px"),value:"has-list-gap-30"},{label:__("40px"),value:"has-list-gap-40"}],onChange:e=>a({listGap:""!==e?e:""})}),(0,s.jsx)(u,{label:__("List icon"),value:l,options:[{label:__("Default"),value:""},{label:__("Angle Right"),value:"has-icon-angle-right"},{label:__("Check"),value:"has-icon-check"},{label:__("Plus"),value:"has-icon-plus"}],onChange:e=>a({selectedIcon:""!==e?e:""})}),l&&(0,s.jsx)(u,{label:__("Icon Size"),value:p,options:[{label:__("10px"),value:"has-icon-size-10"},{label:__("15px"),value:"has-icon-size-15"},{label:__("20px"),value:"has-icon-size-20"},{label:__("30px"),value:"has-icon-size-30"},{label:__("40px"),value:"has-icon-size-40"}],onChange:e=>a({iconSize:""!==e?e:""})})]})})]})}),"addListSidebar");wp.hooks.addFilter("editor.BlockEdit","core-block-customisations/add-list-sidebar",p);const b=l((e=>o=>{if(!n.includes(o.name))return(0,s.jsx)(e,{...o});const{attributes:a}=o,{selectedIcon:l,columnCount:i,iconSize:r,listGap:c}=a;let u;return(l||i||r||c)&&(u=t()(l,i,r,c)),(0,s.jsx)(e,{...o,className:u})}),"addListSidebarProp");wp.hooks.addFilter("editor.BlockListBlock","core-block-customisations/add-list-sidebar-prop",b),wp.hooks.addFilter("blocks.getSaveContent.extraProps","core-block-customisations/save-list-attributes",((e,o,s)=>{if(n.includes(o.name)){const{selectedIcon:o,columnCount:n,iconSize:a,listGap:l}=s;(o||n||a||l)&&(e.className=t()(e.className,o,n,a,l))}return e}));const{__:v}=wp.i18n,{createHigherOrderComponent:h}=wp.compose,{Fragment:m}=wp.element,{InspectorControls:g,MediaUpload:k}=wp.blockEditor,{PanelBody:f,ToggleControl:x,Button:j}=wp.components,y=["core/cover"];wp.hooks.addFilter("blocks.registerBlockType","core-block-customisations/set-cover-attributes",((e,t)=>y.includes(t)?Object.assign({},e,{attributes:Object.assign({},e.attributes,{activateVideo:{type:"boolean",default:!1},videoBackground:{type:"number"},videoBackgroundName:{type:"string"}})}):e));const w=h((e=>t=>{if(!y.includes(t.name))return(0,s.jsx)(e,{...t});const{attributes:o,setAttributes:n}=t,{activateVideo:a,videoBackground:l,videoBackgroundName:i}=o;return(0,s.jsxs)(m,{children:[(0,s.jsx)(e,{...t}),(0,s.jsx)(g,{children:(0,s.jsxs)(f,{title:v("Video Options"),children:[(0,s.jsx)(x,{label:v("Use video background"),checked:a,onChange:()=>n({activateVideo:!a})}),a&&(0,s.jsxs)("div",{children:[(0,s.jsx)(k,{onSelect:e=>n({videoBackground:e.id,videoBackgroundName:e.title}),allowedTypes:["video"],value:l,render:({open:e})=>(0,s.jsx)(j,{isSecondary:!0,onClick:e,children:v(i?"Change video":"Select video")})}),i&&(0,s.jsx)("p",{children:`${v("Selected:")} ${i}`})]})]})})]})}),"addCoverSidebar");wp.hooks.addFilter("editor.BlockEdit","core-block-customisations/add-cover-sidebar",w),wp.hooks.addFilter("blocks.getSaveContent.extraProps","core-block-customisations/save-cover-attributes",((e,o,s)=>{if(y.includes(o.name)){const{activateVideo:o}=s;o&&(e.className=t()(e.className,"has-video-background"))}return e}))})()})();