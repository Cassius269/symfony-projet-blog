// On crée CK Editor sur la balise <textarea>  ayant l'attribut #editor 

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
       tokenUrl: ' https://112207.cke-cs.com/token/dev/00smS7TeKW0DQtCJMMAmYT5d4MBAHxM64A4v?limit=10', 
       uploadUrl: 'https://112207.cke-cs.com/easyimage/upload/' 
   }
   }) 
   .then(editor => { 
       let inputHiddenContent = document.querySelector("#article_content"); 

       if(inputHiddenContent.value){// Dans le cas où l'éditeur se trouve dans une situation de mise à jour, c-à-d input hidden avec valeur préremplie
        editor.setData(inputHiddenContent.value); // Préremplir l'éditeur du contenu présent en base de données
       }

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
   
    