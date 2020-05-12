 /***
     * 
     * Récupération des informations sur la configuration matérielle de l'utilisateur
     * 
     * 
     * @type object
     */
    let infosUser = {
        "screen.width": screen.width,
        "screen.height": screen.height,
        "screen.availWidth": screen.availWidth,
        "screen.availHeight": screen.availHeight,
        "screen.colorDepth": screen.colorDepth,
        "screen.pixelDepth": screen.pixelDepth,
        "navigator.appName is": screen.pixelDepth,
        "Browser CodeName": navigator.appCodeName,
        "Browser Name": navigator.appName,
        "Cookies Enabled": navigator.cookieEnabled,
        "Platform": navigator.platform,
    };

    console.log(infosUser);