import{d as B,c as S,o as i,a as E,_ as F,u as N,C as V,r as d,b as e,w as a,f as R,m as j,e as M,g as l,l as f,F as D,B as H,i as L}from"./app.2b3a961c.js";import{C as O}from"./Check.ed9b0b27.js";const T={xmlns:"http://www.w3.org/2000/svg","xmlns:xlink":"http://www.w3.org/1999/xlink",viewBox:"0 0 512 512"},U=E("path",{d:"M497 273L329 441c-15 15-41 4.5-41-17v-96H152c-13.3 0-24-10.7-24-24v-96c0-13.3 10.7-24 24-24h136V88c0-21.4 25.9-32 41-17l168 168c9.3 9.4 9.3 24.6 0 34zM192 436v-40c0-6.6-5.4-12-12-12H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h84c6.6 0 12-5.4 12-12V76c0-6.6-5.4-12-12-12H96c-53 0-96 43-96 96v192c0 53 43 96 96 96h84c6.6 0 12-5.4 12-12z",fill:"currentColor"},null,-1),I=[U],q=B({name:"SignOutAlt",render:function(r,c){return i(),S("svg",T,I)}});const Y={components:{Check:O,SignOutAlt:q},setup(){const x=R();j();const r=M(),c=N(),n=V(),v=d(!0),_=d(!1),g=d(null),t=d({email:"",name:"",mobile:"",avatar:""}),h=d([{value:"first",src:"first.png"},{value:"football",src:"football.png"},{value:"dragon",src:"dragon.png"},{value:"smart",src:"smart.png"},{value:"pizza",src:"pizza.png"},{value:"ice-cream",src:"ice-cream.png"},{value:"male",src:"male.png"},{value:"female",src:"female.png"},{value:"male-2",src:"male-2.png"},{value:"female-2",src:"female-2.png"},{value:"penguin",src:"penguin.png"},{value:"jaguar",src:"jaguar.png"},{value:"viking",src:"viking.png"},{value:"ninja",src:"ninja.png"},{value:"anonymous",src:"anonymous.png"},{value:"scream",src:"scream.png"},{value:"iron-man",src:"iron-man.png"},{value:"simpson",src:"simpson.png"},{value:"mario",src:"mario.png"},{value:"walter-white",src:"walter-white.png"},{value:"male-3",src:"male-3.png"},{value:"female-3",src:"female-3.png"},{value:"male-4",src:"male-4.png"},{value:"female-4",src:"female-4.png"},{value:"sun",src:"sun.png"},{value:"fire",src:"fire.png"},{value:"wave",src:"wave.png"},{value:"soil",src:"soil.png"}]),w=d({name:{required:!0,message:"Please enter name",trigger:["input","blur"]}}),u=()=>{axios.get("/api/user/me").then(o=>{o.data.response=="success"?(t.value.email=o.data.data.email,t.value.name=o.data.data.name,t.value.mobile=o.data.data.mobile.substring(4),t.value.avatar=o.data.data.avatar):c.error(o.data.message),v.value=!1}).catch(o=>{v.value=!1,c.error("Could not fetch profile information")})};return u(),{pageLoading:v,formSubmitLoading:_,formRef:g,profile:t,avatars:h,rules:w,getProfile:u,changeAvatar:o=>{t.value.avatar=o.value},submitForm:o=>{o.preventDefault(),g.value.validate(m=>{m?c.error("Please enter proper information"):(_.value=!0,axios.post("/api/user/update",{name:t.value.name,mobile:"mob:"+t.value.mobile,avatar:t.value.avatar}).then(p=>{p.data.response=="success"?c.success(p.data.message):c.error(p.data.message),_.value=!1}).catch(p=>{_.value=!1,c.error("Could not save profile information")}))})},signout:()=>{n.error({title:"Signout",content:"Do you want to signout from your current session?",negativeText:"No",positiveText:"Yes",onPositiveClick:()=>{r.dispatch("removeSession"),x.replace("/home")}})}}}},G={id:"profile"},J=L(" Signout "),K=L(" Save Profile ");function Q(x,r,c,n,v,_){const g=l("sign-out-alt"),t=l("n-icon"),h=l("n-button"),w=l("check"),u=l("n-space"),y=l("n-skeleton"),b=l("n-input"),k=l("n-form-item"),o=l("n-gi"),m=l("n-grid"),p=l("n-form"),C=l("n-card"),z=l("n-grid-item"),P=l("n-avatar");return i(),S("section",G,[e(C,{title:"PROFILE"},{"header-extra":a(()=>[e(u,null,{default:a(()=>[e(h,{onClick:n.signout,type:"error"},{icon:a(()=>[e(t,null,{default:a(()=>[e(g)]),_:1})]),default:a(()=>[J]),_:1},8,["onClick"]),e(h,{onClick:n.submitForm,loading:n.formSubmitLoading},{icon:a(()=>[e(t,null,{default:a(()=>[e(w)]),_:1})]),default:a(()=>[K]),_:1},8,["onClick","loading"])]),_:1})]),default:a(()=>[e(m,{cols:"1 m:2","x-gap":"24",responsive:"screen"},{default:a(()=>[e(z,null,{default:a(()=>[e(C,null,{default:a(()=>[n.pageLoading?(i(),f(y,{key:0,height:"220px"})):(i(),f(u,{key:1,vertical:""},{default:a(()=>[e(p,{ref:"formRef",model:n.profile,rules:n.rules},{default:a(()=>[e(m,{cols:"1"},{default:a(()=>[e(o,null,{default:a(()=>[e(k,{label:"Email Address"},{default:a(()=>[e(b,{value:n.profile.email,"onUpdate:value":r[0]||(r[0]=s=>n.profile.email=s),placeholder:"Enter your email",disabled:""},null,8,["value"])]),_:1})]),_:1})]),_:1}),e(m,{cols:"1"},{default:a(()=>[e(o,null,{default:a(()=>[e(k,{label:"Name",path:"name"},{default:a(()=>[e(b,{value:n.profile.name,"onUpdate:value":r[1]||(r[1]=s=>n.profile.name=s),placeholder:"Enter your name",maxlength:"40","show-count":""},null,8,["value"])]),_:1})]),_:1})]),_:1}),e(m,{cols:"1"},{default:a(()=>[e(o,null,{default:a(()=>[e(k,{label:"Mobile No."},{default:a(()=>[e(b,{value:n.profile.mobile,"onUpdate:value":r[2]||(r[2]=s=>n.profile.mobile=s),placeholder:"Enter your mobile","input-props":{type:"number"}},null,8,["value"])]),_:1})]),_:1})]),_:1})]),_:1},8,["model","rules"])]),_:1}))]),_:1})]),_:1}),e(z,null,{default:a(()=>[e(C,null,{default:a(()=>[n.pageLoading?(i(),f(y,{key:0,height:"320px"})):(i(),f(u,{key:1,align:"flex-end"},{default:a(()=>[(i(!0),S(D,null,H(n.avatars,(s,A)=>(i(),f(P,{class:"avatar",key:A,size:64,round:s.value==n.profile.avatar,src:"http://track-my-money.xyz/avatar/"+s.src,onClick:W=>n.changeAvatar(s)},null,8,["round","src","onClick"]))),128))]),_:1}))]),_:1})]),_:1})]),_:1})]),_:1})])}const $=F(Y,[["render",Q],["__scopeId","data-v-28e65f93"]]);export{$ as default};
