// On crée CK Editor sur la div ayant l'attribut #editor 

ClassicEditor. create( document.querySelector( '#editor' ), { 
    cloudServices: { 
        tokenUrl: 'https://110564.cke-cs.com/token/dev/jjMCuDGURbNcVjNAS1ZXzZpawhe0IvLCXeX5?limit=10 ', 
        uploadUrl: 'https://110564.cke-cs.com/easyimage/upload/' 
    } 
    } ) 
    .then(editor => { 
        let inputHiddenContent = document.querySelector("#article_content"); 
        let form = document.querySelector("form"); 
        form.addEventListener('submit', (event) => { 
        event.preventDefault(); 
        inputHiddenContent.value = editor.getData(); 
        form.submit(); 
    }); 
    }) 
    .catch(error => { 
        console.error(error); 
    
    }); 
    
     