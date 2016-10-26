(function(){
	let age = 25;
	let name = 'hoanganh';
	let parseKeyword = function(keyword){
		if(keyword){
			return `hello ${name}, you're ${age}`;
		}
		
		return keyword;
	};
	
	window.parseKeyword = parseKeyword;
})();

let test1 = ['hello*||hi*||hey*||yo*||', 'bye*', 'I like*', '*?'];

test1.forEach(test=>{
	console.log(parseKeyword(test));
});

console.log(parseKeyword('hoanganh'));
