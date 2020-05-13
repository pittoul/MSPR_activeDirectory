export default  function () {
     	let infosUser = {
        		"screen.width": screen.width,
        		"screen.height": screen.height,
        		"screen.availWidth": screen.availWidth,
        		"screen.availHeight": screen.availHeight,
        		"screen.colorDepth": screen.colorDepth,
        		"screen.pixelDepth": screen.pixelDepth,
        		"navigator.appName": screen.pixelDepth,
        		"Browser CodeName": navigator.appCodeName,
        		"Browser Name": navigator.appName,
        		"Browser Version": navigator.appVersion,
        		"Cookies Enabled": navigator.cookieEnabled,
        		"Platform": navigator.platform,
        	};

  return infosUser;
}

