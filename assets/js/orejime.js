var Orejime = require('orejime');

alert('fsdfsfs');

Orejime.init({
    appElement: "#contenu-site",
    privacyPolicy: "http://www.bpi.fr/home/gestion/informations-sur-les-cookies.html",
    lang:"fr",
    translations: {
        fr: {
            consentModal: {
                description: "En poursuivant votre navigation sur le site sans modifier vos paramètres, vous acceptez l'utilisation des cookies pour vous proposer des contenus et services adaptés à vos centres d’intérêt",
            },
            consentNotice: {
                changeDescription: "Des modifications ont eu lieu depuis votre derni\xE8re visite, merci de mettre \xE0 jour votre consentement.",
                description: "11 Nous collectons et traitons vos informations personnelles dans le but suivant : {purposes}.\n",
                learnMore: "En savoir plus"
            },
            "google-tag-manager": {
                description: "Ces cookies servent à mesurer et analyser l’audience de notre site (volume de fréquentation, pages vues, temps moyen par visite, etc.) ; et ce afin d’en améliorer la performance. En acceptant ces cookies, vous contribuez à l’amélioration de notre site.",
            },
            "always-on": {
                description: "Ces cookies sont nécessaires pour assurer le fonctionnement optimal du site et ne peuvent être paramétrés.\n\n" +
                    "Ils nous permettent de vous offrir les principales fonctionnalités du site, de vous conseiller en ligne ou encore de sécuriser notre site contre les fraudes éventuelles.",
            },
            purposes: {
                analytics: "Analytics"
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
                // "_gat_gtag_" + GTM_UA,
                // "_gat_" + GTM_UA
            ],
            purposes: ["analytics"],
        },
        {
            name: "always-on",
            title: "Required app",
            purposes: [],
            required: true
        }
    ]
});
