const form = document.getElementById("form")
const inputMsg = document.getElementById("inputMsg")
const id_receptor = document.getElementById("receptor").value
const enviarBtn = document.getElementById("enviar")
const messages_chat = document.getElementById("messages_chat")

console.log(form);
console.log(inputMsg);
console.log(id_receptor);
console.log(enviarBtn);
console.log(messages_chat);
//Para não sair da pagina
form.onsubmit = (e) => {
    e.preventDefault()
}

enviarBtn.onclick = () => {
    var request = new XMLHttpRequest()

    request.open("POST", "/enviarMsg", true)
    request.onreadystatechange = () => {
        if (request.readyState === XMLHttpRequest.DONE) {
            if (request.status === 200) {
                inputMsg.value = ""
            }
        }
    }

    var formData = new FormData(form)
    request.send(formData)

}

setInterval(() => {
    var request = new XMLHttpRequest()

    request.open("GET", "/recuperarMsg?id_receptor=" + id_receptor, true)
    request.onreadystatechange = () => {
        if (request.readyState === XMLHttpRequest.DONE) {
            if (request.status === 200) {
                messages_chat.innerHTML = request.response
                console.log(request.response);

            }
        }
    }

    var formData = new FormData(form)
    request.send(formData)
}, 500);



