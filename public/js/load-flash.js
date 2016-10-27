let f_overlay = $('.f_overlay');

let flashDiv;
let isCreateNew = false;
if(f_overlay.find('.alert').length == 0){
	flashDiv = $('<div class="alert alert-info"></div>');
	flashDiv.appendTo(f_overlay);
	flashDiv.addClass('hidden');
	isCreateNew = true;
}else
	flashDiv = $('.alert');

let flashDivClass = flashDiv.attr('class');
//only hide flashMsg when it NOT important
let isImportantMsg = flashDivClass.includes('alert-important');

if(!isCreateNew){
	let waitFor = 3000;
	let interval = setInterval(function(){
		let animation = 'animated fadeOutRight';
		if(isImportantMsg){
			animation = '';
		}
		flashDiv.addClass(animation);

		clearInterval(interval);
	}, waitFor);
}

let endAnimationSignal = 'animationend';
let animation = 'fadeOutRight';
let flash = function(msg, level){
	level = level || 'info';

	flashDiv.html(msg);
	flashDiv.attr('class', `alert alert-${level}`);

	if(level == 'important')
		return;

	let interval = setInterval(()=>{
		flashDiv.addClass(`animated  ${animation}`);
		clearInterval(interval);
	}, 3000);

	flashDiv.one(endAnimationSignal, ()=>{
		// flashDiv.removeClass();
		console.log('animation end');
	});
};

window.flash = flash;