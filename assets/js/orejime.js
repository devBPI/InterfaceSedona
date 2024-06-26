var Orejime = require('orejime');
const gaToken = 'UA-56533762-6';
const gtmToken = 'GTM-WLTB479';

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
            name: "always-on",
            title: "Cookie de fonctionnement",
            purposes: [],
            required: true
        }
    ]
});

// ---------- Ajout d'une class pour Google Tag Manager
const declineButton = document.querySelector(".orejime-Button--decline");
if (declineButton) {
    declineButton.classList.add("gtm-decline-button");
}
