import{d as F,c as _,o as c,a as n,_ as Y,u as I,r as i,b as e,w as t,m as P,g as l,F as B,B as E,l as V,t as u,i as R}from"./app.2b3a961c.js";import{h as T}from"./moment.9709ab41.js";import{C as U}from"./Check.ed9b0b27.js";const j={xmlns:"http://www.w3.org/2000/svg","xmlns:xlink":"http://www.w3.org/1999/xlink",viewBox:"0 0 512 512"},H=n("path",{d:"M464 64H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V112c0-26.51-21.49-48-48-48zm0 48v40.805c-22.422 18.259-58.168 46.651-134.587 106.49c-16.841 13.247-50.201 45.072-73.413 44.701c-23.208.375-56.579-31.459-73.413-44.701C106.18 199.465 70.425 171.067 48 152.805V112h416zM48 400V214.398c22.914 18.251 55.409 43.862 104.938 82.646c21.857 17.205 60.134 55.186 103.062 54.955c42.717.231 80.509-37.199 103.053-54.947c49.528-38.783 82.032-64.401 104.947-82.653V400H48z",fill:"currentColor"},null,-1),q=[H],A=F({name:"EnvelopeRegular",render:function(s,f){return c(),_("svg",j,q)}}),G={components:{EnvelopeRegular:A,Check:U},setup(){const b=P(),s=I(),f=i(!0),o=i(),k=i({email:""}),w=i(!1),m=i(!1),p=i(null),g=i({email:{required:!0,message:"Please enter email",trigger:["input","blur"]}}),h=()=>{f.value=!0,axios.get("/api/member/list",{params:{slug:b.params.slug}}).then(r=>{r.data.response=="success"?o.value=r.data.data:s.error("Error fetching group members. Please reload!"),f.value=!1})};h();const x=()=>{w.value=!0},C=r=>{r.preventDefault(),p.value.validate(M=>{M?console.log(M):(m.value=!0,axios.post("/api/invitation/invite",{group:b.params.slug,email:k.value.email}).then(v=>{v.data.response=="success"?(s.success(v.data.message),d()):s.error(v.data.message),m.value=!1}).catch(()=>{m.value=!1,s.error(`Could not ${editFlag.value?"edit":"create"} group!`)}))})},d=()=>{w.value=!1,k.value={email:""}};return{pageLoading:f,formSubmitLoading:m,members:o,member:k,showModalRef:w,formRef:p,rules:g,groupMembers:h,openModal:x,submitForm:C,closeModal:d,formatDate:r=>T(r).format("DD-MM-YYYY")}}},J={id:"group-members"},O=R(" Invite New Member "),K=n("thead",null,[n("tr",null,[n("th",null,"#"),n("th",null,"Name"),n("th",null,"Email"),n("th",null,"Mobile"),n("th",null,"Total Contribution"),n("th",null,"Total Bill"),n("th",null,"Joined At")])],-1),Q=R("Cancel"),W=R(" Send Invitation ");function X(b,s,f,o,k,w){const m=l("envelope-regular"),p=l("n-icon"),g=l("n-button"),h=l("n-skeleton"),x=l("n-table"),C=l("n-empty"),d=l("n-space"),y=l("n-card"),r=l("n-input"),M=l("n-form-item"),v=l("n-gi"),L=l("n-grid"),S=l("check"),N=l("n-form"),z=l("n-modal");return c(),_("section",J,[e(y,{title:"GROUP MEMBERS"},{"header-extra":t(()=>[e(g,{onClick:s[0]||(s[0]=a=>o.openModal())},{icon:t(()=>[e(p,null,{default:t(()=>[e(m)]),_:1})]),default:t(()=>[O]),_:1})]),default:t(()=>[e(d,{class:"data-container",vertical:""},{default:t(()=>[o.pageLoading?(c(),_(B,{key:0},[e(h,{height:"50px"}),(c(),_(B,null,E(10,a=>e(h,{key:a,height:"50px"})),64))],64)):o.members&&o.members.length>0?(c(),V(x,{key:1,striped:""},{default:t(()=>[K,n("tbody",null,[(c(!0),_(B,null,E(o.members,(a,D)=>(c(),_("tr",{key:D},[n("td",null,u(D+1),1),n("td",null,u(a.user.name),1),n("td",null,u(a.user.email),1),n("td",null,u(a.user.mobile?a.user.mobile.substring(4):"-"),1),n("td",null,u(a.total_contribution.toLocaleString("en-BD")),1),n("td",null,u(a.total_bill.toLocaleString("en-BD")),1),n("td",null,u(o.formatDate(a.joined_at)),1)]))),128))])]),_:1})):(c(),V(C,{key:2,class:"empty",size:"huge",description:"No group members found"}))]),_:1})]),_:1}),e(z,{show:o.showModalRef,"onUpdate:show":s[2]||(s[2]=a=>o.showModalRef=a),"mask-closable":!1,"auto-focus":!1},{default:t(()=>[e(y,{style:{width:"600px"},name:"Invite new member",size:"huge",role:"dialog","aria-modal":"true"},{default:t(()=>[e(d,{vertical:""},{default:t(()=>[e(N,{ref:"formRef",model:o.member,rules:o.rules},{default:t(()=>[e(L,{cols:"1"},{default:t(()=>[e(v,null,{default:t(()=>[e(M,{label:"Valid Email",path:"email"},{default:t(()=>[e(r,{value:o.member.email,"onUpdate:value":s[1]||(s[1]=a=>o.member.email=a),placeholder:"Enter member email"},null,8,["value"])]),_:1})]),_:1})]),_:1}),e(d,{justify:"end"},{default:t(()=>[e(g,{onClick:o.closeModal},{default:t(()=>[Q]),_:1},8,["onClick"]),e(g,{type:"primary",onClick:o.submitForm,loading:o.formSubmitLoading},{icon:t(()=>[e(p,null,{default:t(()=>[e(S)]),_:1})]),default:t(()=>[W]),_:1},8,["onClick","loading"])]),_:1})]),_:1},8,["model","rules"])]),_:1})]),_:1})]),_:1},8,["show"])])}const te=Y(G,[["render",X]]);export{te as default};
