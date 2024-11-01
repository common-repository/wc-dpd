window.dpdParcelShopPopup=function(){var e,t,p,a,c,o,r,s,n,d,u,i,l,h,v,m,y,_,f,g;function w(){t=".js-dpd-parcelshop-popup-form",popupParcelsListItemSelector=".js-dpd-parcelshop-popup-parcels-list li",u=".js-dpd-chosen-parcelshop-content",i=".js-dpd-chosen-parcelshop-chosen-parcelshop-text",l=".js-dpd-parcelshop-hidden-parcelshop-id",h=".js-dpd-parcelshop-hidden-parcelshop-pus-id",v=".js-dpd-parcelshop-hidden-parcelshop-name",m=".js-dpd-parcelshop-hidden-parcelshop-street",y=".js-dpd-parcelshop-hidden-parcelshop-city",_=".js-dpd-parcelshop-hidden-parcelshop-zip",f=".js-dpd-parcelshop-hidden-parcelshop-country-code",e=document.querySelector(".js-dpd-parcelshop-popup"),p=e.querySelector(t),a=document.querySelector(".js-dpd-parcelshop-popup-input-city"),c=document.querySelector(".js-dpd-parcelshop-popup-input-zip"),o=document.querySelector(".js-dpd-parcelshop-popup-input-country"),r=document.querySelector(".js-dpd-parcelshop-popup-parcels-list"),s=document.querySelector(".js-dpd-parcelshop-popup-response"),n=document.querySelector(".js-dpd-parcelshop-popup-search-btn"),d=document.querySelector(".js-dpd-parcelshop-popup-results"),document.addEventListener("submit",(function(e){e.target.matches(t)&&(e.preventDefault(),T())}),!1),document.addEventListener("click",(function(e){e.target.matches(".js-dpd-parcelshop-open-popup-btn")&&(e.preventDefault(),P())}),!1),document.addEventListener("click",(function(e){(e.target.matches(".js-dpd-parcelshop-popup-close-btn")||e.target.matches(".js-dpd-parcelshop-popup-container"))&&(e.preventDefault(),q())}),!1),document.addEventListener("keydown",(function(e){"Escape"===e.key&&q()}),!1),document.addEventListener("click",(function(e){e.target.matches(".js-dpd-parcelshop-popup-choose-parcelshop-btn")&&(e.preventDefault(),function(){b();var e=S();e?(L(e),q()):E(wc_dpd_parcelshop_popup_settings.select_parcelshop_error_message)}())}),!1),document.addEventListener("click",(function(e){if(e.target.matches(popupParcelsListItemSelector)){e.preventDefault();var t=document.querySelector(popupParcelsListItemSelector+".active");t&&t.classList.remove("active"),e.target.classList.add("active")}}),!1)}function S(){return document.querySelector(popupParcelsListItemSelector+".active")}function L(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null;if(e||(e=S()),e){var t=e.getAttribute("data-id"),a=e.getAttribute("data-pus-id"),c=e.getAttribute("data-name"),o=e.getAttribute("data-street"),r=e.getAttribute("data-zip"),s=e.getAttribute("data-city"),n=e.getAttribute("data-country-code");!function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"",t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"",a=arguments.length>2&&void 0!==arguments[2]?arguments[2]:"",c=arguments.length>3&&void 0!==arguments[3]?arguments[3]:"",o=arguments.length>4&&void 0!==arguments[4]?arguments[4]:"",r=arguments.length>5&&void 0!==arguments[5]?arguments[5]:"",s=arguments.length>6&&void 0!==arguments[6]?arguments[6]:"",n=new XMLHttpRequest;n.open("POST",wc_dpd_parcelshop_popup_settings.ajax_url,!0),n.setRequestHeader("Content-type","application/x-www-form-urlencoded"),n.onreadystatechange=function(){var e=4,t=200;n.readyState===e&&n.status===t&&document.body.dispatchEvent(new Event("update_checkout"))},n.send("action=wc_dpd_update_chosen_parcelshop&wp_nonce="+p.getAttribute("data-nonce")+"&wc_dpd_parcelshop_id="+e+"&wc_dpd_parcelshop_pus_id="+t+"&wc_dpd_parcelshop_name="+a+"&wc_dpd_parcelshop_street="+c+"&wc_dpd_parcelshop_zip="+o+"&wc_dpd_parcelshop_city="+r+"&wc_dpd_parcelshop_country_code="+s)}(t,a,c,o,r,s,n),document.querySelector(l).value=t,document.querySelector(h).value=a,document.querySelector(v).value=c,document.querySelector(m).value=o,document.querySelector(_).value=r,document.querySelector(y).value=s,document.querySelector(f).value=n,document.querySelector(i).innerHTML=e.innerHTML,document.querySelector(u).classList.add("active")}}function P(){b(),function(){if(!S()){var e=document.querySelector(l).value;if(e){var t=document.querySelector(popupParcelsListItemSelector+'[data-id="'+e+'"]');t&&t.classList.add("active")}}}(),e.classList.add("active")}function q(){e.classList.remove("active")}function j(){d.classList.remove("active"),r.innerHTML=""}function O(e){r.innerHTML=e,d.classList.add("active")}function b(){s.innerHTML="",s.classList.remove("active")}function E(e){s.innerHTML="<p>"+e+"</p>",s.classList.add("active")}function H(e,t,a){var c=new XMLHttpRequest;c.open("POST",wc_dpd_parcelshop_popup_settings.ajax_url,!0),c.setRequestHeader("Content-type","application/x-www-form-urlencoded"),n.classList.add("loading"),j(),c.onreadystatechange=function(){if(4===c.readyState){n.classList.remove("loading");var e=JSON.parse(c.responseText);if(200===c.status)if(e.success){if(e.data.hasOwnProperty("parcelshops")){var t=e.data.parcelshops,p="";for(var a in t){var o=t[a].hasOwnProperty("id")?t[a].id:"",r=t[a].hasOwnProperty("pusId")?t[a].pusId:"";if(o&&r){var s=t[a].hasOwnProperty("name")?t[a].name:"",d=t[a].hasOwnProperty("street")?t[a].street:"",u=t[a].hasOwnProperty("zip")?t[a].zip:"",i=t[a].hasOwnProperty("city")?t[a].city:"";p+='<li data-id="'+o+'" data-pus-id="'+r+'" data-name="'+s+'" data-street="'+d+'" data-zip="'+u+'" data-city="'+i+'" data-country-code="'+(t[a].hasOwnProperty("country")&&t[a].country.hasOwnProperty("code")?t[a].country.code:"")+'">'+[s,d,u+" "+i,t[a].hasOwnProperty("country")&&t[a].country.hasOwnProperty("name")?t[a].country.name:""].join(", ")+"</li>"}}O(p)}}else E(e.data.message);else e.data.hasOwnProperty("message")&&E(e.data.message)}},c.send("action=wc_dpd_parcelshop_search&wp_nonce="+p.getAttribute("data-nonce")+"&city="+e+"&zip="+t+"&country="+a)}function T(){b(),n.classList.contains("loading")||(a.value&&c.value&&o.value?H(a.value,c.value,o.value):E(wc_dpd_parcelshop_popup_settings.required_fields_error_message))}return g=function(){w()},"complete"===document.readyState||"interactive"===document.readyState?setTimeout(g,1):document.addEventListener("DOMContentLoaded",g),{openPopup:P,closePopup:q,setSelectedParcelShop:L,searchParcelShop:H,resetPopupParcels:j,triggerSearchParcelshops:T}}();
//# sourceMappingURL=dpd-parcelshop-popup.js.map