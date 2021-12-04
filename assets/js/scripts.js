
window.onload = function() {

	const path = window.location.pathname.split('/');
	let lang = 'en';
	let langPrefix = '';
	
	path.pop().shift();
	if (path[0] == 'ja') {
		path.shift();
		lang = 'ja';
		langPrefix = '/ja';
	}

	console.log('building javascript has loaded');

};
