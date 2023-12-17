document.forms['form'].onsubmit = (e) => {
    e.preventDefault()

    let file = document.forms['form'].file
    console.log(file.files[0]);

    let ajax = new XMLHttpRequest()

    ajax.upload.addEventListener("progress", (e) => {
        let percent = (e.loaded/e.total)*100

        document.querySelector("progress").value = Math.round(percent)
    })

    
    let formData = new FormData(document.forms['form'])
    ajax.open("post", "script.php", true)
    ajax.send(formData)
}