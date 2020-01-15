var Orejime = require('orejime');
const googleToken = 'UA-56533762-6';

var obj = Orejime.init({
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
                "_gat_gtag_" + googleToken,
                "_gat_" + googleToken
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

$('#modal-share').on('shown.bs.modal', function(e) {
    if (obj.internals.manager.getConsent('add-this')) {
        $(document.body).append('<script type="text/javascript" src="/build/addthis_widget.js#pubid=ra-5d8e40891e23b4a0"></script>');
    }
});
