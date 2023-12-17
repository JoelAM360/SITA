"use strict";

var preloader = document.querySelector('#preloader');
var btnSubmit = document.getElementById("btnSubmit")
var btnSubmitRegister = document.getElementById("btnSubmitRegister")
var form = document.getElementById("form")
var container = document.getElementById("container")
var email =  document.getElementById("email")
var senha =  document.getElementById("senha")
var small =  document.getElementById("small")



form.onsubmit = (e) => {
    e.preventDefault()
}

if (btnSubmit) {
    btnSubmit.onclick = () => {
        var request = new XMLHttpRequest()
        
        container.className = "d-none"
        preloader.classList.remove("d-none") 
    
        request.open("POST", "/autenticar", true)

        request.onreadystatechange = () => {
            if (request.readyState === XMLHttpRequest.DONE) {
                if ( request.status === 200 && request.readyState === 4) {
                   location.href = request.response
                }
            }
        }
    
        var formData = new FormData(form)
        request.send(formData)
    
    } 

} else {

    btnSubmitRegister.onclick = () => {
        var senha_confirm =  document.getElementById("senha_confirm")
        var nome =  document.getElementById("nome")
        var tipo_conta =  document.getElementById("tipo_conta")
        var image =  document.getElementById("image")

        var request = new XMLHttpRequest()
    
        if ( email.value == "") {
            email.style.border = "#dc3545"
            alert("Preencha o campo Email")
            email.focus()

        } else if ( nome.value == "") {
            nome.style.borderColor = "#dc3545"
            alert("Preencha o campo Nome")
            nome.focus()

        } else if ( tipo_conta.value == "" ) {
            tipo_conta.style.borderColor = "#dc3545" 
            alert("Preencha o campo Tipo de Conta")
            tipo_conta.focus()

        } else if ( image.value == "") {
            image.style.borderColor =  "#dc3545" 
            alert("Faça o Upload de uma foto")
            image.focus()

        }else if ( senha.value == "") {
            senha.style.borderColor =  "#dc3545" 
            alert("Preencha o campo Senha")
            senha.focus()

        } else if(senha_confirm.value == "") {
            senha_confirm.style.borderColor = "#dc3545"
            alert("Preencha o campo Confirme Senha")
            senha_confirm.focus()

        } else {
            if (senha_confirm.value != senha.value) {
                alert("Senhas Diferentes, Confirme a sua senha")
                senha.value =""
                senha_confirm.value =""
                senha.focus()
    
            } else {
               //variavel para mostrar o Gif:
               var containerGif = document.getElementById("containerGif")

               container.className ="d-none"
               preloader.classList.remove("d-none") 

               request.open("POST", "/cadastrar", true)

               request.onreadystatechange = () => {
                   if (request.readyState === XMLHttpRequest.DONE) {
                       if ( request.status === 200 && request.readyState === 4) {
                          preloader.classList.add("d-none") 
                          if ( request.response == "/login?q=Faça o login") {
                            containerGif.classList.remove("d-none") 
                            setTimeout(() => {
                              containerGif.classList.add("d-none")
                              location.href = request.response
                            }, 2000);
                          } else {
                            container.classList.remove("d-none")
                            console.log(request.response);
                            small.innerText = request.response
                            

                          } 
                       }
                   }
               }
               var formData = new FormData(form)
               request.send(formData) 
           }
       }
        
    } 
}


