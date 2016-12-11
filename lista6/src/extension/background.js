chrome.browserAction.onClicked.addListener(function (tab) {
	chrome.tabs.executeScript(null, { file: "jquery-3.1.1.min.js" }, function(){
		chrome.tabs.executeScript(null, { file: 'inject.js' });
	});
});