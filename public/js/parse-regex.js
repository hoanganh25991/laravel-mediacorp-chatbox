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
		let wordsStartWith =
			words.filter(word => word.endsWith('*'))
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


		let wordsEndWith =
			words.filter(word => word.startsWith('*'))
				.map((word, index) => {
					//remove * indicate endWith
					word = word.replace('*', '');
					//concat these word together by |
					word = `|${word}`;
					if(index == 0)
						word = word.replace('|', '');

					return word;
				})
				.join('');
		//endWith regex: $
		//result should be (hello|hi)$
		wordsEndWith = `(${wordsEndWith})$`;

		let test =
			words.filter(word => !word.includes('*'));
		console.log(test);

		let wordsKeyword =
			words.filter(word => !word.includes('*'))
			     .map((word, index) => {
				     //remove * indicate endWith
				     // word = word.replace('*', '');
				     //concat these word together by |
				     //for keyword, BOUND them as exactly
				     word = `|\\b${word}\\b`;
				     if(index == 0)
					     word = word.replace('|', '');

				     return word;
			     })
			     .join('');


		// return keyword;
		// return `/${wordsStartWith}/`;
		return `/${wordsKeyword}/`;
	};

	window.parseKeyword = parseKeyword;
})();

let test1 = ['hello*||hi*||hey*||yo*||*bye', 'bye*', 'I like*', '*?', 'hello&anh&'];

test1.forEach(test=>{
	console.log(parseKeyword(test));
});

console.log(parseKeyword('hoanganh'));
