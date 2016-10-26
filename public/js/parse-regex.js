(function(){
	// let age = 25;
	// let name = 'hoanganh';
	let parseKeyword = function(keyword){
		// if(keyword){
		// 	return `hello ${name}, you're ${age}`;
		// }
		let words = keyword.split('||');
		//remove empty string ''
		words = words.filter(word => word);
		let wordsStartWith = words.filter(word => word.endsWith('*'))
			.map((word, index)=>{
				//remove * indicate startWith
				word = word.replace('*', '');
				//concat these word together by ||
				word = `|${word}`;
				if(index == 0)
					word = word.replace('|', '');

				return word;
			})
			.join('');
		//startWith regex: ^
		//result should be ^(\bhello\b||\bhi\b)
		wordsStartWith = `^(${wordsStartWith})`;




		let wordsEndWith = words.filter(word => !word.endsWith('*'));



		
		// return keyword;
		return `/${wordsStartWith}/`;
	};
	
	window.parseKeyword = parseKeyword;
})();

let test1 = ['hello*||hi*||hey*||yo*||*bye', 'bye*', 'I like*', '*?', 'hello&anh&'];

test1.forEach(test=>{
	console.log(parseKeyword(test));
});

console.log(parseKeyword('hoanganh'));
