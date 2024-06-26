// On crée CK Editor sur la div ayant l'attribut #editor 

 ClassicEditor. create( document.querySelector( '#editor' ),  
 //{
//     //  toolbar:['heading','|','bold','italic','|','bulletedList','link'],
//       heading:{
//           options:[
//               {
//                   model:'paragraph',
//                   title: 'Paragraphe',
//                   class: 'ck-editor_paragraphe'
//               },
//               {
//                   model:'heading2',
//                   title: 'Sous titre principal',
//                   view: {
//                       name: 'h2',
//                       classes: 'ck-editor_heading2'
//                   },
//               },
//               {
//                   model:'heading3',
//                   title: 'Sous-titre secondaire',
//                   view: {
//                       name: 'h3',
//                       classes: 'ck-editor_heading3'
//                   }
//               },
//           ]
//       },
//     },
      { 
    cloudServices: { 
        tokenUrl: 'https://110564.cke-cs.com/token/dev/jjMCuDGURbNcVjNAS1ZXzZpawhe0IvLCXeX5?limit=10 ', 
        uploadUrl: 'https://110564.cke-cs.com/easyimage/upload/' 
    }
    }) 
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
    
     