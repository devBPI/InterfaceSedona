// =====================================
// ATTENTION, Fait doublon avec un ecouteur sur ".btn-see-more" du fichier search.js
// =====================================
document.voirPlusMoins = function(button) {
	if("Voir plus" == button.innerText) {
		button.innerText = "Voir moins";
		var divContainer = button.parentNode;
		var depliables = divContainer.getElementsByClassName("voirPlusMoins");
		for (var i = 0; i < depliables.length; i++) {
			depliables[i].classList.remove("plie");
		}
	} else {
		button.innerText = "Voir plus";
		var divContainer = button.parentNode;
		var depliables = divContainer.getElementsByClassName("voirPlusMoins");
		for (var i = 0; i < depliables.length; i++) {
			depliables[i].classList.add("plie");
		}
	}
};