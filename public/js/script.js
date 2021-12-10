document.onreadystatechange = () => {
    if (document.readyState === 'complete') {
      const getHeader = () => {
          return [
            ["Content-Type", "text/html"],
            ["Accept", "application/json"],
            ["X-Requested-With", "XMLHttpRequest"]
        ];
      };
      const validation = (operation) => {
        const regex = /^((add|concat|sortasc|sortdesc)\s+\d+(?:\s+\d+){0,5}|(add|concat|sortasc)\s+\d+(?:\s+\d+){0,5}\s)$/;
        if(!(m = regex.exec(operation) !== null)){
            return false;
        }
        return true;
      };
      const submit = (event) => {
        event.preventDefault();
        const operation = event.currentTarget.getElementsByTagName('input')[0].value;
          const errorBox = document.getElementById('error');
          if(!validation(operation)){
            errorBox.innerHTML = "Sorry but you have to much the exact pattern";
            errorBox.classList.add('error')
          }
          else {
            const operator = operation.split(/(\s+)/)[0];
            const digits = operation.replace(operator, "");
            errorBox.innerHTML = '';
            fetch('http://localhost:8085/' + operator, {
                method: 'POST',
                headers: getHeader(),
                body: JSON.stringify({digits: digits})
              })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        const result =  data.result ? data.result : null
                        if(result){
                            errorBox.innerText = "Result: " + result
                        }
                    });
          }

      };
  
      
      const form = document.getElementById('operation');
      form.addEventListener('submit', submit);
    }
  };
 