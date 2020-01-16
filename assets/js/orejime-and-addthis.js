var Orejime = require('orejime');
const gaToken = 'UA-56533762-6';
const gtmToken = 'GTM-5JTHPTG';

// ----------- Orejime ------------------------------------------------------------------------------------------------

var orejimeInstance = Orejime.init({
    appElement: "#contenu-site",
    privacyPolicy: "http://www.bpi.fr/home/gestion/informations-sur-les-cookies.html",
    lang:"fr",
    translations: {
        fr: {
            // trad ---
            consentModal: {
                title: "Les informations que nous collectons",
                description: "En poursuivant votre navigation sur le site sans modifier vos paramètres, vous acceptez l'utilisation des cookies pour vous proposer des contenus et services adaptés à vos centres d’intérêt",
                privacyPolicy: {
                    name: "politique de confidentialit\xE9",
                    text: "Pour en savoir plus, merci de lire notre {privacyPolicy}.\n"
                }
            },
            consentNotice: {
                changeDescription: "Des modifications ont eu lieu depuis votre derni\xE8re visite, merci de mettre \xE0 jour votre consentement.",
                description: "Nous collectons et traitons vos informations personnelles dans le but suivant : {purposes}.\n",
                learnMore: "En savoir plus"
            },
            accept: "Accepter",
            acceptAll: "Tout accepter",
            save: "Sauvegarder",
            saveData: "Sauvegarder ma configuration sur les informations collect\xE9es",
            decline: "Refuser",
            declineAll: "Tout refuser",
            close: "Fermer",
            enabled: "Activ\xE9",
            disabled: "D\xE9sactiv\xE9",
            app: {
                optOut: {
                    title: "(opt-out)",
                    description: "Cette application est charg\xE9e par d\xE9faut (mais vous pouvez la d\xE9sactiver)"
                },
                required: {
                    title: "(toujours requis)",
                    description: "Cette application est toujours requise"
                },
                purposes: "Utilisations",
                purpose: "Utilisation"
            },
            poweredBy: "Propuls\xE9 par Orejime",
            newWindow: "nouvelle fen\xEAtre",

            // -- config ---
            "google-analytics": {
                description: "Ces cookies servent à mesurer et analyser l’audience de notre site (volume de fréquentation, pages vues, temps moyen par visite, etc.) ; et ce afin d’en améliorer la performance. En acceptant ces cookies, vous contribuez à l’amélioration de notre site.",
            },
            "google-tag-manager": {
                description: "Ces cookies servent à mesurer et analyser l’audience de notre site (volume de fréquentation, pages vues, temps moyen par visite, etc.) ; et ce afin d’en améliorer la performance. En acceptant ces cookies, vous contribuez à l’amélioration de notre site.",
            },
            "add-this": {
                description: "AddThis peut utiliser des cookies pour mémoriser vos informations de connexion, collecter des statistiques en vue d’optimiser la fonctionnalité du site et créer des documents marketing adaptés à vos centres d’intérêt.",
            },
            "always-on": {
                description: "Ces cookies sont nécessaires pour assurer le fonctionnement optimal du site et ne peuvent être paramétrés.<br/>" +
                    "Ils nous permettent de vous offrir les principales fonctionnalités du site, de vous conseiller en ligne ou encore de sécuriser notre site contre les fraudes éventuelles.",
            },
            purposes: {
                analytics: "Outils d'analyse",
                social: "Réseaux sociaux"
            }
        }
    },
    apps: [
        {
            name: "google-analytics",
            title: "Google Analytics",
            cookies: [
                "UIDR",
                "uid",
                "UID",
                "rv",
                "rrs",
                "rds",
                "ipinfo",
                "id",
                "FLC",
                "fc",
                "exchange_uid",
                "clid",
                "acs",
                "_drt_",
                "__utmz",
                "__utmt",
                "__utmc",
                "__utmb",
                "__utma",
                "__gads",
                "_ga",
                "_gat",
                "_gat_" + gaToken,
                "_gid"
            ],
            purposes: ["analytics"]
        },
        {
            name: "google-tag-manager",
            title: "Google Tag Manager",
            cookies: [
                "_ga",
                "_gat",
                "_gid",
                "__utma",
                "__utmb",
                "__utmc",
                "__utmt",
                "__utmz",
                "_gat_gtag_" + gtmToken,
                "_gat_" + gtmToken
            ],
            purposes: ["analytics"]
        },
        {
            name: "add-this",
            title: "AddThis",
            cookies: [
                "__atuvc",
                "__atuvs",
                "bku",
                "na_id",
                "na_id",
                "uid",
                "uvc",
                "na_tc",
                "notice_gdpr_prefs",
                "km_lv",
                "loc",
                "mus",
                "notice_preferences",
                "ouid"
            ],
            purposes: ["social"]
        },
        {
            name: "always-on",
            title: "Cookie de fonctionnement",
            purposes: [],
            required: true
        }
    ]
});

// ----------- Add-This ------------------------------------------------------------------------------------------------
var addthisInit = false,
    originalContent = null;
$('#modal-share')
    .on('shown.bs.modal', function(e) {
        if (addthisInit === false && orejimeInstance.internals.manager.getConsent('add-this')) {
            addthisInit = true;
            var addthis = require('addthis-snippet');
            // Config, voir > https://www.addthis.com/academy/the-addthis_config-variable/
            addthis({
                pubid: 'ra-5d8e40891e23b4a0',
                // data_ga_property: googleToken,
                // data_ga_social: true
            });
        }
        if (originalContent === null) {
            var $modal = $(this);
            originalContent = $modal.find(".modal-form").html();
        }
    })
    .on('hidden.bs.modal', function(e) {
        var $modal = $(this);
        $modal
            .removeData('bs.modal')
            .find('.modal-form')
            .html(originalContent);
    })
;
