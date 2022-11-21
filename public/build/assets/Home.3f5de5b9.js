import{d as R,c as x,o as S,a as n,_ as G,u as L,b as N,r as b,e,w as o,f as P,g as A,h as D,i as U,j as t,p as V,k as H,l as a}from"./app.0da94194.js";import{_ as O}from"./logo.67c47849.js";const W={xmlns:"http://www.w3.org/2000/svg","xmlns:xlink":"http://www.w3.org/1999/xlink",viewBox:"0 0 488 512"},j=n("path",{d:"M488 261.8C488 403.3 391.1 504 248 504C110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6c98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z",fill:"currentColor"},null,-1),q=[j],z=R({name:"Google",render:function(c,_){return S(),x("svg",W,q)}}),Y="/build/assets/developer.74fa9643.png";const F={components:{Google:z},setup(){const d=A(),c=D(),_=L(),s=N(()=>d.getters.session||null),g=b(!1),f=b(null),m=b({name:"",email:"",suggestion:""}),u=b({suggestion:{name:{required:!0,message:"Please input your name",trigger:"blur"},email:{required:!0,message:"Please input your email",trigger:"blur"},suggestion:{required:!0,message:"Please write some suggestions",trigger:"blur"}}});return{session:s,googleSigninLoading:g,formRef:f,suggestion:m,rules:u,googleSignin:()=>{g.value=!0,U().then(r=>{r&&r.access_token&&axios.post("/api/authenticate",{token:r.access_token}).then(l=>{l.data.response=="success"?(d.dispatch("setSession",l.data.data.token),window.axios.defaults.headers.common.Authorization="Bearer "+l.data.data.token,window.axios.defaults.headers.common["Content-Type"]="application/json",c.replace("/")):_.error(l.data.message),g.value=!1}).catch(l=>{console.log("err",l),g.value=!1,_.error("Could not signin with Google. Try again!")})})},submitSuggestion:r=>{r.preventDefault(),console.log(f.value),f.value.validate(l=>{l?console.log(l):_.success("Thanks for your valuable opinion!")})}}}},i=d=>(V("data-v-0e726347"),d=d(),H(),d),K={id:"landing"},J={class:"landing-top"},Q={class:"centered-element-container"},X={class:"centered-element"},Z=a("Keep track of your money over the internet!"),$=a("Access it from anywhere in the world!"),ee={class:"landing-middle"},oe={class:"centered-element-container"},ne={class:"centered-element"},te=a("Where is your money going? To whom?"),se=a("Never loose track of your money!"),le={key:0,class:"get-started"},ae=i(()=>n("div",{class:"text"},"Get started now!",-1)),ie=a(" Continue with Google "),ce={class:"landing-bottom"},de=i(()=>n("p",null,"Have a cool suggestion? Let us know!",-1)),re=a("Submit"),ue=i(()=>n("div",null,"ABOUT - Track My Money",-1)),_e=i(()=>n("img",{class:"logo",src:O,alt:"Logo"},null,-1)),ge=i(()=>n("strong",null,"Track My Money",-1)),me=a(" Track My Money is an easy-to-use daily income, expanse and debt tracker mobile app for everyone. It can be used for both individual and/or a group of people. And it's totally free of cost. "),pe=i(()=>n("strong",null,"Individual",-1)),fe=a(" Individual \u2014 With this application you can keep track of your all individual incomes, expenses and debts. "),he=i(()=>n("strong",null,"Group",-1)),ve=a(" With this application you can keep track of all the contributions and bills for a group of people. "),ye=a(" So does the developer. That's why this application is ad-free. So no advertisement, no annoying pop-ups. "),be=i(()=>n("br",null,null,-1)),ke=i(()=>n("img",{class:"developer",src:Y,alt:"Logo"},null,-1)),we=i(()=>n("div",null,[n("a",{href:"/privacy-policy"},"Privacy Policy")],-1));function xe(d,c,_,s,g,f){const m=t("n-grid-item"),u=t("n-h2"),h=t("n-grid"),C=t("google"),r=t("n-icon"),l=t("n-button"),T=t("n-card"),M=t("n-space"),k=t("n-divider"),w=t("n-input"),v=t("n-form-item"),I=t("n-form"),E=t("n-gi"),y=t("n-descriptions-item"),B=t("n-descriptions");return S(),x("section",K,[n("div",J,[e(h,{cols:"1 m:2","item-responsive":"",responsive:"screen"},{default:o(()=>[e(m,{span:"0 m:1"}),e(m,null,{default:o(()=>[n("div",Q,[n("div",X,[e(u,null,{default:o(()=>[Z]),_:1}),e(u,null,{default:o(()=>[$]),_:1})])])]),_:1})]),_:1})]),n("div",ee,[e(h,{cols:"1 m:2","item-responsive":"",responsive:"screen"},{default:o(()=>[e(m,null,{default:o(()=>[n("div",oe,[n("div",ne,[e(u,null,{default:o(()=>[te]),_:1}),e(u,null,{default:o(()=>[se]),_:1})])])]),_:1})]),_:1})]),s.session==null?(S(),x("div",le,[e(M,{justify:"center"},{default:o(()=>[e(T,{hoverable:""},{default:o(()=>[ae,e(l,{class:"google-button",size:"large",type:"info",onClick:s.googleSignin,loading:s.googleSigninLoading},{icon:o(()=>[e(r,null,{default:o(()=>[e(C)]),_:1})]),default:o(()=>[ie]),_:1},8,["onClick","loading"])]),_:1})]),_:1})])):P("",!0),e(k),n("div",ce,[e(h,{"x-gap":"24",cols:"1 m:2",responsive:"screen"},{default:o(()=>[e(E,null,{default:o(()=>[e(T,{class:"user-feedback",title:"USER FEEDBACK"},{default:o(()=>[de,e(M,{vertical:""},{default:o(()=>[e(I,{ref:"formRef",model:s.suggestion,rules:s.rules},{default:o(()=>[e(v,{label:"Name",path:"suggestion.name"},{default:o(()=>[e(w,{value:s.suggestion.name,"onUpdate:value":c[0]||(c[0]=p=>s.suggestion.name=p),type:"text",placeholder:"Your name"},null,8,["value"])]),_:1}),e(v,{label:"Email",path:"suggestion.email"},{default:o(()=>[e(w,{value:s.suggestion.email,"onUpdate:value":c[1]||(c[1]=p=>s.suggestion.email=p),type:"email",placeholder:"Your email"},null,8,["value"])]),_:1}),e(v,{label:"Suggestion",path:"suggestion.suggestion"},{default:o(()=>[e(w,{value:s.suggestion.suggestion,"onUpdate:value":c[2]||(c[2]=p=>s.suggestion.suggestion=p),type:"textarea",placeholder:"Your thoughts or suggestions..."},null,8,["value"])]),_:1}),e(v,null,{default:o(()=>[e(l,{onClick:s.submitSuggestion},{default:o(()=>[re]),_:1},8,["onClick"])]),_:1})]),_:1},8,["model","rules"])]),_:1})]),_:1})]),_:1}),e(E,null,{default:o(()=>[e(B,{"label-placement":"top",column:1},{header:o(()=>[ue,_e]),default:o(()=>[e(y,null,{label:o(()=>[ge]),default:o(()=>[me]),_:1}),e(y,null,{label:o(()=>[pe]),default:o(()=>[fe]),_:1}),e(y,null,{label:o(()=>[he]),default:o(()=>[ve]),_:1})]),_:1}),e(k),e(B,{"label-placement":"top",column:1,title:"WORD FROM DEVELOPER"},{default:o(()=>[e(y,{label:"Don't like ads?",span:2},{default:o(()=>[ye,be,ke]),_:1})]),_:1})]),_:1})]),_:1}),we]),e(k)])}const Te=G(F,[["render",xe],["__scopeId","data-v-0e726347"]]);export{Te as default};
