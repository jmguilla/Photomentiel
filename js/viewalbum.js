Array.prototype.contains = function (element) {
	for (var i = 0; i < this.length; i++) {
		if (this[i] == element) {
			return true;
		}
	}
	return false;
}

basketCookieVal = new Array();
function initBasket() {
	var val = readCookie(albumCookieName);
	if (val != ''){
		var values = val.split("-");
		if (values.length <= 1){
			clearCookie(albumCookieName);
		} else {
			for (var i=0;i<values.length-1;i++){
				addToBasketInit(values[i]);
				basketCookieVal[i] = values[i];
			}
			updateBasketInfo();
		}
	}
}
function addToBasketInit(picName){
	appendToBasketDiv(picName);
}
function addToBasket(picName){
	if (!basketCookieVal.contains(picName)) {
		appendToBasketDiv(picName);
		basketCookieVal.push(picName);
		writeCookie(albumCookieName,readCookie(albumCookieName)+picName+'-');
	} else {
		alert("Cette photo est déjà dans votre panier.\nLe nombre d'exemplaires est à préciser en validant le panier.");
	}
	updateBasketInfo();
}
function removeFromBasket(picName){
	var tmp = basketCookieVal;
	var tmpCookie = "";
	basketCookieVal = new Array();
	$("#basket").html("");
	for (var i = 0; i < tmp.length; i++) {
		if (tmp[i] != picName) {
			basketCookieVal.push(tmp[i]);
			tmpCookie += tmp[i]+"-";
			appendToBasketDiv(tmp[i]);
		}
	}
	writeCookie(albumCookieName,tmpCookie);
	updateBasketInfo();
}
function clearBasket(){
	$("#basket").html("");
	basketCookieVal = new Array();
	clearCookie(albumCookieName);
	updateBasketInfo();
}
function updateBasketInfo(){
	var tmp = basketCookieVal.length;
	tmp += ' photo' + (basketCookieVal.length>1?'s':'');
	var tmp_plus='';
	if (basketCookieVal.length>0){
		tmp += ' - <a href="javascript:submitViewBag();">Valider mon panier</a>';
		tmp_plus = '<a title="Vider mon panier" href="javascript:if(confirm(\'Souhaitez vous vraiment vider votre panier ?\')){clearBasket();}">Vider mon panier</a>';
	}
	$("#basket_info_plus").html(tmp_plus);
	$("#basket_info").html(tmp);
}
function appendToBasketDiv(picName){
	$("#basket").append('<div value="'+picName+'" class="basket_content"><a onClick="$(this).displayHidden();return false;" class="thickbox" rel="bag_content" title="Cliquer pour voir ou supprimer du panier" href="'+picsFullDir+picName+'"><img src="'+thumbsFullDir+picName+'"/></a></div>');
}

function submitViewBag(){
	var pics = readCookie(albumCookieName);
	document.getElementById("form_input").value=pics.substr(0,pics.length-1);
	$("#form_viewbag").submit();
}
