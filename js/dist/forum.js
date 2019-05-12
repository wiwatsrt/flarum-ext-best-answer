module.exports=function(e){var t={};function s(r){if(t[r])return t[r].exports;var n=t[r]={i:r,l:!1,exports:{}};return e[r].call(n.exports,n,n.exports,s),n.l=!0,n.exports}return s.m=e,s.c=t,s.d=function(e,t,r){s.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},s.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},s.t=function(e,t){if(1&t&&(e=s(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(s.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var n in e)s.d(r,n,function(t){return e[t]}.bind(null,n));return r},s.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return s.d(t,"a",t),t},s.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},s.p="",s(s.s=15)}([function(e,t){e.exports=flarum.core.compat.app},function(e,t){e.exports=flarum.core.compat.extend},function(e,t){e.exports=flarum.core.compat["models/Discussion"]},function(e,t){e.exports=flarum.core.compat["components/CommentPost"]},function(e,t){e.exports=flarum.core.compat.Model},function(e,t){e.exports=flarum.core.compat["components/Button"]},function(e,t){e.exports=flarum.core.compat["components/DiscussionPage"]},function(e,t){e.exports=flarum.core.compat["components/Post"]},function(e,t){e.exports=flarum.core.compat["components/Badge"]},function(e,t){e.exports=flarum.core.compat["helpers/icon"]},function(e,t){e.exports=flarum.core.compat["components/PostMeta"]},function(e,t){e.exports=flarum.core.compat["helpers/username"]},function(e,t){e.exports=flarum.core.compat["helpers/userOnline"]},,,function(e,t,s){"use strict";s.r(t);var r=s(0),n=s.n(r),o=s(2),a=s.n(o),i=s(4),u=s.n(i),c=s(1),p=s(5),d=s.n(p),f=s(3),l=s.n(f),b=s(6),w=s.n(b),A=s(7),P=s.n(A),h=s(8),x=s.n(h),y=s(9),v=s.n(y),O=s(10),_=s.n(O),j=s(11),g=s.n(j),B=s(12),N=s.n(B);n.a.initializers.add("wiwatSrt-bestAnswer",function(){a.a.prototype.bestAnswerPost=u.a.hasOne("bestAnswerPost"),a.a.prototype.bestAnswerUser=u.a.hasOne("bestAnswerUser"),a.a.prototype.startUserId=u.a.attribute("startUserId",Number),a.a.prototype.firstPostId=u.a.attribute("firstPostId",Number),a.a.prototype.canSelectBestAnswer=u.a.attribute("canSelectBestAnswer"),Object(c.extend)(l.a.prototype,"actionItems",function(e){var t=this.props.post,s=this.props.post.discussion(),r=s.bestAnswerPost()&&s.bestAnswerPost().id()===t.id();t.pushAttributes({isBestAnswer:r}),!t.isHidden()&&1!==t.number()&&s.canSelectBestAnswer()&&n.a.session.user&&(r||n.a.forum.attribute("canSelectBestAnswerOwnPost")||t.user().id()!==n.a.session.user.id())&&e.add("bestAnswer",d.a.component({children:n.a.translator.trans(r?"flarum-best-answer.forum.remove_best_answer":"flarum-best-answer.forum.this_best_answer"),className:"Button Button--link",onclick:function(){r=!r,s.save({bestAnswerPostId:r?t.id():0,bestAnswerUserId:n.a.session.user.id(),relationships:r?{bestAnswerPost:t,bestAnswerUser:n.a.session.user}:delete s.data.relationships.bestAnswerPost}).then(function(){n.a.current instanceof w.a&&n.a.current.stream.update(),m.redraw()}),r&&m.route(n.a.route.post(s.posts()[0]))}}))}),Object(c.extend)(l.a.prototype,"headerItems",function(e){var t=this.props.post;t.discussion().bestAnswerPost()&&t.discussion().bestAnswerPost().id()===t.id()&&!t.isHidden()&&e.add("isBestAnswer",n.a.translator.trans("flarum-best-answer.forum.best_answer_button",{user:t.discussion().bestAnswerUser()}))}),Object(c.extend)(P.a.prototype,"attrs",function(e){var t=this.props.post;t.discussion().bestAnswerPost()&&t.discussion().bestAnswerPost().id()===t.id()&&!t.isHidden()&&(e.className+=" Post--bestAnswer")}),Object(c.extend)(a.a.prototype,"badges",function(e){this.bestAnswerPost()&&!e.has("hidden")&&e.add("bestAnswer",m(x.a,{type:"bestAnswer",icon:"fas fa-check",label:n.a.translator.trans("flarum-best-answer.forum.best_answer")}))}),Object(c.extend)(l.a.prototype,"footerItems",function(e){var t=this.props.post,s=t.discussion();if(s.bestAnswerPost()&&1===t.number()&&!t.isHidden()){var r=s.bestAnswerPost();if(r.isHidden())return;var o=r.user();e.add("bestAnswerPost",m("div",{className:"CommentPost"},m("div",{className:"Post-header"},m("ul",null,m("li",{className:"item-user"},m("div",{className:"PostUser"},N()(o),m("h3",null,m("a",{href:n.a.route.user(o),config:m.route},g()(o))))),m("li",{className:"item-meta"},_.a.component({post:r})),m("li",{className:"item-bestAnswerButton"},m("a",{href:n.a.route.post(r),config:m.route,"data-number":r.number()},v()("fas fa-check"),n.a.translator.trans("flarum-best-answer.forum.best_answer_button",{user:s.bestAnswerUser()}))))),m("div",{className:"Post-body"},m.trust(r.contentHtml()))),-10)}})})}]);
//# sourceMappingURL=forum.js.map