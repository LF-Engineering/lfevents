!function(e){function t(r){if(n[r])return n[r].exports;var o=n[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,t),o.l=!0,o.exports}var n={};t.m=e,t.c=n,t.d=function(e,n,r){t.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:r})},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p="",t(t.s=0)}([function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});n(1)},function(e,t,n){"use strict";var r=n(2),o=(n.n(r),n(3)),__=(n.n(o),wp.i18n.__),l=wp.blocks.registerBlockType,c=wp.blockEditor,i=c.RichText,a=c.MediaUpload;l("cgb/block-text-on-image-block",{title:__("Text on Image Block"),icon:"welcome-widgets-menus",category:"common",keywords:[__("text-on-image-block")],attributes:{bodyContent:{type:"string",default:""},imgUrl:{type:"string",default:"https://via.placeholder.com/250x250/d9d9d9/000000"},imgId:{type:"number",default:0}},edit:function(e){var t=e.className,n=e.setAttributes,r=e.attributes,o=function(e){n({bodyContent:e})},l=function(e){var t=e.sizes;return(t.medium||t.full).url},c=function(e){n({imgId:e.id,imgUrl:l(e)})};return wp.element.createElement("div",{className:t},wp.element.createElement("div",{className:"media"},wp.element.createElement(a,{onSelect:c,render:function(e){var t=e.open;return wp.element.createElement("button",{onClick:t},wp.element.createElement("img",{src:r.imgUrl,alt:""}))}})),wp.element.createElement("div",{className:"copy"},wp.element.createElement(i,{className:"copy-bd",tagName:"div",placeholder:"Enter text here that will float on top of the image.",value:r.bodyContent,onChange:o,keepPlaceholderOnFocus:!0})))},save:function(){return null}})},function(e,t){},function(e,t){}]);