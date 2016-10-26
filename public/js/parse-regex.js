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

		/**
		 * 2. expression is KEYWORD*
		 */
		let wordsStartWith =
			expressions
				.filter(expression => expression.endsWith('*'))
				.map(word => `${word.replace('*', '')}`)
				.join('|');
		if(wordsStartWith != '')
			wordsStartWith = `^(${wordsStartWith})`;

		/**
		 * 3. expression is *KEYWORD
		 */
		let wordsEndWith =
			expressions
				.filter(expression => expression.startsWith('*'))
				.map(word => `${word.replace('*', '')}|`)
				.join('')
				.slice(0, -1);
		if(wordsEndWith != '')
			wordsEndWith = `(${wordsEndWith})$`;

		/**
		 * 5. expression is KEYWORD
		 */
		let wordsKeyword =
			expressions
				.filter(expression => !expression.includes('*') && !expression.includes('&'))
				.map(word => `\\b${word}\\b`)
				.join('|');

		/**
		 * 4. expression is & AND
		 */
		let andExpressions =
			expressions
				.filter(expression => expression.includes('&'))
				.map(ex => {
					let words = ex.split('&').filter(notEmpty => notEmpty);
					let tmp = words.map(word => `(?=.*${_parseKeyword(word)})`).join('');
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

		return `/${phpPattern}/`;
	}

	window.parseKeyword = parseKeyword;
})();

let test1 = ['hello*||hi*||hey*||yo*||*bye||', 'bye*&', '||I like*&', 'hello&anh&', '', '*?'];

test1.forEach(test=>{
	console.log(parseKeyword(test));
});

console.log(parseKeyword('hoanganh'));
