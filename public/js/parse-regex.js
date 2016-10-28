(function(){
	// let age = 25;
	// let name = 'hoanganh';
	let _parseKeyword = function(keyword){
		/**
		 * 1. break through ||
		 * split
		 * remove empty string ''
		 */
		let expressions = keyword.split('||').filter(notEmpty => notEmpty);

		function parseBoundaryQuesMark(word){
			//@warn implicit, no case of keyword as *your day?||how
			word = word.includes('?')? word.replace('?', '\\?') : `\\b${word}\\b`;

			return word;
		}

		/**
		 * 2. expression is KEYWORD*
		 */
		let a = expressions.filter(expression => expression.endsWith('*')).map(word => word.replace('*', ''));
		console.log(a);
		let wordsStartWith =
			expressions
				.filter(expression => !expression.includes('&&'))
				.filter(expression => expression.endsWith('*') && !expression.startsWith('*'))
				.map(word => word.replace(/\*/g, ''))
				.map(word => parseBoundaryQuesMark(word))
				.join('|');

		if(wordsStartWith)
			wordsStartWith = `^(${wordsStartWith})`;

		/**
		 * 3. expression is *KEYWORD
		 */
		let wordsEndWith =
			expressions
				.filter(expression => !expression.includes('&&'))
				.filter(expression => expression.startsWith('*') && !expression.endsWith('*'))
				.map(word => word.replace(/\*/g, ''))
				.map(word => parseBoundaryQuesMark(word))
				.join('|');

		if(wordsEndWith)
			wordsEndWith = `(${wordsEndWith})$`;

		/**
		 * 5. expression is KEYWORD
		 */
		let wordsKeyword =
			expressions
				.filter(expression => !expression.includes('&&'))
				.filter(expression => expression.startsWith('*') && expression.endsWith('*'))
				.map(word => word.replace(/\*/g, ''))
				.map(word => parseBoundaryQuesMark(word))
				.join('|');

		/**
		 * 4. expression is & AND
		 */
		let andExpressions =
			expressions
				.filter(expression => expression.includes('&&'))
				.map(ex =>{
					let words = ex.split('&&').filter(notEmpty => notEmpty);
					let tmp = words.map(word => `(?=.*(${_parseKeyword(word)}))`).join('');

					return tmp;
				})
				.filter(notEmpty => notEmpty)
				.join('|');

		let phpPattern = [wordsStartWith, wordsEndWith, wordsKeyword, andExpressions];
		phpPattern = phpPattern.filter(notEmpty => notEmpty).join('|');

		return phpPattern;
	};

	let parseKeyword = function(keyword){
		let phpPattern = _parseKeyword(keyword);
		//bcs ? is specical character
		//add \
		// phpPattern = phpPattern.replace('?', '\\?');
		//when replace ?, FAIL on and-logic, which use (?=.*keyword)
		if(!phpPattern)
			phpPattern = '\'\'';

		console.log(phpPattern);
		return `/${phpPattern}/i`;
	}

	window.parseKeyword = parseKeyword;
})();

let test1 = ['hello*||hi*||hey*||yo*||*bye||', 'bye*&', '||I like*&', 'hello&anh&', '', '*?', 'hoanganh'];

let test2 = ['hello*||hi*||hey*||yo*||', 'bye*', 'I like*', '*?'];

console.log('run test 1');
test1.forEach(test=>{
	console.log(parseKeyword(test));
});

console.log('run test 2');
test2.forEach(test=>{
	console.log(parseKeyword(test));
});