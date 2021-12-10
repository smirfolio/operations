# Operation app

### Run the app
To be able to run this app please excute : `php -S localhost:8085`

### Assessment
open/closed principle: To be able to extend the operations:
- Add the operand to the regex rule
  `const regex = /^((add|concat|sortasc|sortdesc)\s+\d+(?:\s+\d+){0,5}|(add|concat|sortasc)\s+\d+(?:\s+\d+){0,5}\s)$/;`
- Adjust the help text
- Implement the method in the RequestHandler class that auto detect if the it implement the operation or not (handler())

### Requirements
- linux 
- php 7 >
- [compatible Browser](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API#browser_compatibility)