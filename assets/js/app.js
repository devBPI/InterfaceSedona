
            // see the annotated config in the README for details on how everything works
            window.orejimeConfig = {
                appElement: "#app",
                privacyPolicy: "http://www.bpi.fr/home/gestion/informations-sur-les-cookies.html",
                lang:"fr",
                translations: {
                    fr: {
                        consentModal: {
                            description: "En poursuivant votre navigation sur le site sans modifier vos paramètres, vous acceptez l'utilisation des cookies pour vous proposer des contenus et services adaptés à vos centres d’intérêt",
                        }
                    },
                    en: {
                        consentModal: {
                            description: "En poursuivant votre navigation sur le site sans modifier vos paramètres, vous acceptez l'utilisation des cookies pour vous proposer des contenus et services adaptés à vos centres d’intérêt",
                        },
                        "inline-tracker": {
                            description: "Example of an inline tracking script that sets a dummy cookie",
                        },
                        "external-tracker": {
                            description: "Example of an external tracking script that sets a dummy cookie",
                        },
                        "always-on": {
                            description: "this example app will not set any cookie",
                        },
                        "disabled-by-default": {
                            description: "this example app will not set any cookie",
                        },
                        purposes: {
                            analytics: "Analytics",
                            security: "Security",
                            ads: "Ads"
                        }
                    },
                    es: {
                        consentModal: {
                            description: "En poursuivant votre navigation sur le site sans modifier vos paramètres, vous acceptez l'utilisation des cookies pour vous proposer des contenus et services adaptés à vos centres d’intérêt",
                        },
                        purposes: {
                            analytics: "Analytics",
                            security: "Security",
                            ads: "Ads"
                        }
                    },


                },
                apps: [
                    {
                        name: "inline-tracker",
                        title: "Inline Tracker",
                        purposes: ["analytics"],
                        cookies: ["inline-tracker"],
                        onlyOnce: true,
                    }
                ],
            }

// since there is a orejimeConfig global variable in index.js, a window.orejime instance was created when including the lib
document.querySelector('.consent-modal-button').addEventListener('click', function() {
    orejime.show();
}, false);
