let keyword = 'hello*||hi*||hey*||yo*||';
let words = keyword.split('||');
words;
words = words.filter(word => word);
words;
let wordsStartWith = words.filter(word => word.endsWith('*'));
wordsStartWith;
wordsStartWith = words.filter(word => word.endsWith('*')) 			.map((word, index)=>{ 				word = `||\b${word}\b`; 			if(index == 0) 					word = word.replace('||', ''); 				return word; 			}) 		.join('');
