if (document.getElementById("teste")) {
  console.log(document.getElementById("teste"));
  document.getElementById("teste").click()
}


// Função para carregar e exibir o PDF
function renderPDFPadrao(pageNumber, canvas, pdf, zoom) {

  pdf.getPage(pageNumber).then(function (page) {

    //Variavel Escala: define o tamanho dos quarto cantos do pdf (superior direito e esquerdo, inferior direito e esquerdo)
    var scale = zoom;
    //Viewport: Cada página PDF tem seu próprio viewport que define o tamanho em pixels e rotação incial.
    var viewport = page.getViewport({ scale: scale });

    var context = canvas.getContext('2d');

    //Definido o largura e altura do Canvas
    canvas.height = viewport.height;
    canvas.width = viewport.width;

    // Render PDF page into canvas context
    var renderContext = {
      canvasContext: context,
      viewport: viewport
    };

    var renderTask = page.render(renderContext);
    renderTask.promise.then(function () {
      console.log('Page rendered');
    });

  });

}

// Função para carregar e exibir o PDF
function criarCanvaNumPagePdf(url, id_pdf) {
  let container = document.getElementById("container" + id_pdf)

  console.log(container);
  container.innerHTML = ""
  // Carrega o documento PDF
  pdfjsLib.getDocument(url).promise.then(function (pdf) {
    //Criando Canvas 
    console.log(pdf.numPages);
    for (let i = 0; i < pdf.numPages; i++) {
      let canvas = document.createElement("canvas")
      let br = document.createElement("br")
      canvas.id = "canvasID_" + (i + 1)
      canvas.style.height = "98%"
      canvas.style.width = "98%"
      canvas.style.border = "1px solid red"
      container.appendChild(canvas)
      container.appendChild(br)
    }

    console.log(container);
  });
}



function previewPdf() {
  var canvasPreview = document.querySelectorAll(".canvasPreview")

  for (let i = 0; i < canvasPreview.length; i++) {
    console.log(canvasPreview[i].innerHTML);

    canvasPreview[i].style.height = "150px"
    canvasPreview[i].style.width = "98%"
    canvasPreview[i].style.objectFit = "cover"
    var aux_canvas = canvasPreview[i].innerHTML.split("#")

    var loadingTask = pdfjsLib.getDocument('/app/assets/upload/' + aux_canvas[1]);

    loadingTask.promise.then(function (pdf) {
      var totalPage = pdf.numPages;
      renderPDFUmaPagina(1, canvasPreview[i], pdf)

    })
  }

}

/**
 * View/Render Pages Pdf using PDF.js
 */
function limparBodyModal() {
  var body_containerPdf = document.getElementById("body_containerPdf")
  body_containerPdf.innerHTML = ""
}

function lerPdf(file, id_container, titulo) {
  limparBodyModal()

  var tituloPdf = document.getElementById('tituloPdf')
  tituloPdf.innerText = titulo

  var loadingTask = pdfjsLib.getDocument('/app/assets/upload/' + file);

  loadingTask.promise.then(function (pdf) {
    var totalPage = pdf.numPages;
    var zoom = 1.5;
    criarCanvasAutoPadrao(totalPage, id_container)
    var canvas_array = document.getElementsByClassName("canvas")

    for (let i = 1; i <= totalPage; i++) {
      renderPDFPadrao(i, canvas_array[i - 1], pdf, zoom)
    }
    /*document.getElementById("zoom_plus").addEventListener("click", (e) => {
      zoom += 0.1
      for (let i = 1; i <= totalPage; i++) {
        renderPDFPadrao(i, canvas_array[i - 1], pdf, zoom)
      }
    })
    document.getElementById("zoom_out").addEventListener("click", (e) => {
      zoom -= 0.1
      for (let i = 1; i <= totalPage; i++) {
        renderPDFPadrao(i, canvas_array[i - 1], pdf, zoom)
      }
    })*/
  });

}

/**
* Função Para Renderizar Pdfs
*/
function renderPDFUmaPagina(pageNumber, canvas, pdf) {

  pdf.getPage(pageNumber).then(function (page) {

    //Viewport: Cada página PDF tem seu próprio viewport que define o tamanho em pixels e rotação incial.
    var viewport = page.getViewport({ scale: 1.5 });

    var context = canvas.getContext('2d');

    //Definido o largura e altura do Canvas
    canvas.height = viewport.height;
    canvas.width = viewport.width;

    // Render PDF page into canvas context
    var renderContext = {
      canvasContext: context,
      viewport: viewport
    };

    var renderTask = page.render(renderContext);
    renderTask.promise.then(function () {
      console.log('Page rendered');
    });

  });

}


/**
* Criar Canvas Automaticamente dependedo do Total de Páginas do Pdf
*/
function criarCanvasAutoPadrao(qtdPagePdf, id_container) {

  var body_containerPdf = document.getElementById("body_containerPdf")
  var div = document.createElement("div")
  div.id = "container" + id_container

  body_containerPdf.innerHTML = ""
  body_containerPdf.appendChild(div)

  for (let i = 0; i < qtdPagePdf; i++) {
    //Criando Canvas
    var canvas = document.createElement("canvas")
    var br = document.createElement("br")

    canvas.classList.add("canvas")

    div.classList.add("container")
    div.classList.add("w-100")
    div.classList.add("h-100")

    div.appendChild(canvas)
    div.appendChild(br)

  }

}
/**
* Este função add um like ao projeto, com o id_projeto do user logado!
* Primeiro selecino as spna com id desejados
* 
*/
function avaliarProjeto(avaliacao, id) {
  var curtir = document.getElementById("curtir_" + id)
  var r_curtir = document.getElementById("r-curtir_" + id)
  var textLikes = document.getElementById("textLikes_" + id)

  if (avaliacao == "curtir") {

    curtir.classList.add("d-none")
    r_curtir.classList.remove("d-none")

  } else if (avaliacao == "r-curtir") {

    r_curtir.classList.add("d-none")
    curtir.classList.remove("d-none")

  }

  //Requisição para AppController para solicitar a função avaliarProjeto()
  var request = new XMLHttpRequest()

  request.open('GET', '/curtir?id_projeto=' + id + '&acao=' + avaliacao)

  request.onreadystatechange = () => {
    if (request.readyState == 4 && request.status == 200) {
      var reposta = request.responseText
      //var objJsonCambio = JSON.parse(jsonCambio)
    } else if (request.readyState == 4 && request.status == 404) {
      console.log('Erro na requisição')
    }
  }

  request.send()

  /**
   * Após Inserir ou Deletar um like da tb_likes, qtdLikes será recuperado e atualizada!
   */
  qtdLikesPorProjeto(id, textLikes)
}
/**
* Verificar se exites uma availiação no projeto
*/
function checkAvailiacao() {
  //Requisição para AppController para solicitar a função avaliarProjeto()
  var request = new XMLHttpRequest()

  request.open('GET', '/check')

  request.onreadystatechange = () => {
    if (request.readyState == 4 && request.status == 200) {
      var reposta = request.responseText
      var objJson = JSON.parse(reposta)
      var curtir = Array();
      var r_curtir = Array();
      for (const key in objJson) {

        const teste = objJson[key];
        var id_projeto = teste["id_projeto"]

        //Para Span Curtir 
        curtir.push(document.getElementById("curtir_" + id_projeto))

        //Para Span R-Curtir 
        r_curtir.push(document.getElementById("r-curtir_" + id_projeto))
      }
      for (let i = 0; i < curtir.length; i++) {
        if (curtir[i]) {
          curtir[i].classList.add("d-none")
          r_curtir[i].classList.remove("d-none")
        } else {
          continue
        }

        //qtdLikesPorProjeto(id_projeto,r_curtir[i])
      }
    } else if (request.readyState == 4 && request.status == 404) {
      console.log('Erro na requisição')
    }
  }

  request.send()
}
/**
* Função para seguir um determinado user:
* Se acao== seguir, significa que ainda não seguiu user selecianado
* E a partir do momento clicado passará a segui-ló
* Se ñ
*/

function qtdLikesPorProjeto(id, elememt) {
  //Requisição para AppController para solicitar a função avaliarProjeto()
  var request = new XMLHttpRequest()

  request.open('GET', '/qtdLikes?id_projeto=' + id)
  request.onreadystatechange = () => {
    if (request.readyState == 4 && request.status == 200) {
      var reposta = request.responseText
      elememt.innerText = reposta

    } else if (request.readyState == 4 && request.status == 404) {
      console.log('Erro na requisição')
    }
  }

  request.send()

}
/**
* Função para seguir um determinado user!
*/
function seguir(id_user, avaliacao) {
  //Seleciono Tags uteis
  var btnSeguir = document.getElementById("btnSeguir_" + id_user)
  var r_btnSeguir = document.getElementById("r-btnSeguir_" + id_user)

  if (avaliacao == "seguir") {

    btnSeguir.classList.add("d-none")
    r_btnSeguir.classList.remove("d-none")

  } else if (avaliacao == "r-seguir") {

    r_btnSeguir.classList.add("d-none")
    btnSeguir.classList.remove("d-none")

  }

  //Requisição para AppController para solicitar a função seguir()
  var request = new XMLHttpRequest()

  request.open('GET', '/seguir?id_seguidor=' + id_user + '&acao=' + avaliacao)

  request.onreadystatechange = () => {
    if (request.readyState == 4 && request.status == 200) {
      var reposta = request.responseText
    } else if (request.readyState == 4 && request.status == 404) {
      console.log('Erro na requisição')
    }
  }

  request.send()
}

//Unversidade e Faculdade
var cont = 0
function addInput() {

  var form_entidade = document.getElementById("form_entidade")
  var optionValue = document.getElementById("entidade").value
  var button_save = document.getElementById("button_save")

  //Criando Inptus a Cada Click em Add com suas resptivas classes
  var div = document.createElement("div")
  div.classList.add("row", "mb-3")

  var div_interna = document.createElement("div")
  div_interna.classList.add("col-md-8", "col-lg-9")

  var label = document.createElement("label")
  label.classList.add("col-md-4", "col-lg-3", "col-form-label")

  label.innerText = (optionValue == "Universidade") ? "Faculdade" : "Curso"

  var input = document.createElement("input")
  input.classList.add("form-control")


  var small = document.createElement("small")
  small.classList.add("text-primary")
  small.innerText = "Remover"
  small.style.cursor = "pointer"

  //Criando Hierarquia dos Elementos Criados
  div.appendChild(label)
  div.appendChild(div_interna)
  div_interna.appendChild(input)
  div_interna.appendChild(small)
  form_entidade.appendChild(div)

  /**
  * Se div para Input já existe e o option selecionado for diferente do que já foi selecionado, remove-lá de add no formulario 
  */
  if (optionValue == "Universidade") {
    div.classList.add("div-container-input-univ")

    var div_container_input = document.getElementsByClassName("div-container-input-fac")
    for (let index = 0; index < div_container_input.length; index++) {
      div_container_input[index].innerText = "";
      div_container_input[index].style.display = "none"

    }

  } else {
    div.classList.add("div-container-input-fac")

    var div_container_input_univ = document.getElementsByClassName("div-container-input-univ")
    for (let index = 0; index < div_container_input_univ.length; index++) {
      div_container_input_univ[index].innerText = "";
      div_container_input_univ[index].style.display = "none"

    }
  }

  //Inserir a Div.Input antes do Button Save
  form_entidade.insertBefore(div, button_save);
  small.onclick = () => {
    div.remove()

  }

  input.name = (optionValue == "Universidade") ? "faculdade[]" : "curso[]"

}
/**
 * Para Listar curso apos selecionar a entidade no momento de fazer o upload de file
 * Localiza-se no file "salvo em app"
 */
function addCurso(idEntidade) {

  var entidade = document.getElementById("entidade_" + idEntidade)
  var optionValue = entidade.value
  var cursoSelect = document.getElementById("curso_" + idEntidade)

  //Requisição para AppController para solicitar a função avaliarProjeto()
  var request = new XMLHttpRequest()

  request.open('GET', '/getCurso?nome_curso=' + optionValue)
  request.onreadystatechange = () => {
    if (request.readyState == 4 && request.status == 200) {
      var reposta = request.responseText
      cursoSelect.innerHTML = reposta
      console.log(reposta);
    } else if (request.readyState == 4 && request.status == 404) {
      console.log('Erro na requisição')
    }
  }

  request.send()

}

document.forms['upload-form'].onsubmit = (e) => {

  e.preventDefault()

  let file = document.querySelector("input#formFile").files[0]
  console.log(file);

  let request = new XMLHttpRequest()

  request.open("POST", "/upload", true)

  request.upload.addEventListener("progress", (e) => {
    let percent = (e.loaded / e.total) * 100
    document.querySelector("progress").value = Math.round(percent)

    if (percent == e.total) {

      console.log(percent);

    }
  })

  let formData = new FormData(document.forms['upload-form'])
  request.send(formData)
}

if (document.querySelector("#iconGrade")) {
  document.querySelector("body").onload = () => {
    if (document.getElementById("feedback")) {

      setTimeout(() => {
        document.getElementById("feedback").remove()
      }, 3000);
    }


    let folderList = document.querySelector(".folder-list")
    let folderGrade = document.querySelector(".folder-grade")
    let request = new XMLHttpRequest()

    request.open("GET", "/getValorListOrGrade", true)

    request.onreadystatechange = () => {
      if (request.readyState == 4 && request.status == 200) {
        var reposta = request.responseText

        teste = reposta
        if (reposta == "grade") {
          iconGrade.classList.add("d-none")
          folderGrade.classList.remove("d-none")

          iconList.classList.remove("d-none")
          folderList.classList.add("d-none")

        } else {
          iconGrade.classList.remove("d-none")
          folderGrade.classList.add("d-none")

          iconList.classList.add("d-none")
          folderList.classList.remove("d-none")

        }

      } else if (request.readyState == 4 && request.status == 404) {
        console.log('Erro na requisição')
      }
    }

    request.send()

  }
}


//Add mais uma pasta a lista de pasta para cada faculdade
function addPastaAEntidade(url, id_faculdade = 0) {

  var namePost = (url == "/inserirFaculdade") ? "faculdade" : "curso"
  var div = document.createElement("div")
  div.classList.add("icon", "col-md-3", "mt-2")
  div.style.cursor = "pointer"

  var div_label = document.createElement("div")
  div_label.classList.add("label")
  div_label.innerHTML =
    "<form id='formFac'> <input type='hidden' name='id_faculdade' value=" + id_faculdade + "><input type='text' class='form-control form-control-sm mb-2' name='" + namePost + "' placeholder ='Nome da faculdade'><button type='submit' class='btn btn-primary btn-sm' id='salvarFaculdade'>Salvar</button></form> "

  var i = document.createElement("i")
  i.style.fontSize = "80px"
  i.classList.add("bi", "bi-folder-fill")

  //Criar a Hierarquia do DOM
  div.appendChild(i)
  div.appendChild(div_label)

  //console.log(div);

  //Selecinando o Container onde vai ser add a div criada para pasta de cada faculdade ou curso
  var rowFolder = document.querySelector(".row .folder-grade")
  rowFolder.appendChild(div)

  if (document.getElementById("salvarFaculdade")) {

    document.getElementById("salvarFaculdade").onclick = (e) => {
      e.preventDefault()

      let request = new XMLHttpRequest()

      request.open("POST", url, true)

      request.onreadystatechange = () => {
        if (request.readyState == 4 && request.status == 200) {
          var reposta = request.responseText
          console.log(reposta);
          rowFolder.innerHTML = reposta
        } else if (request.readyState == 4 && request.status == 404) {
          console.log('Erro na requisição')
        }
      }

      let formData = new FormData(document.getElementById('formFac'))
      request.send(formData)

    }
  }
}

function listarCursos(id_faculdade, nome_faculdade, ative) {
  event.preventDefault();
  //variavel para mostrar o Gif:
  var rowFolder = document.querySelector(".teste")
  var containerGif = document.getElementById("containerGif")

  rowFolder.className = "d-none"
  containerGif.classList.remove("d-none")


  let request = new XMLHttpRequest()

  request.open("GET", "/listarCurso?curso=" + nome_faculdade + "&ative=" + ative + "&id_faculdade=" + id_faculdade, true)

  request.onreadystatechange = () => {
    if (request.readyState == 4 && request.status == 200) {
      var reposta = request.responseText

      containerGif.classList.add("d-none")
      rowFolder.classList.remove("d-none")
      rowFolder.innerHTML = reposta
      console.log(reposta);

    } else if (request.readyState == 4 && request.status == 404) {
      console.log('Erro na requisição')
    }
  }
  request.send()
}

function iconChange() {

  var iconGrade = document.querySelector("#iconGrade")
  var iconList = document.querySelector("#iconList")
  var folderList = document.querySelector(".folder-list")
  var folderGrade = document.querySelector(".folder-grade")

  //Valor para determinar se add um list ou grade
  let list_grade = "";


  if (iconList.offsetParent === null) {
    iconGrade.classList.add("d-none")
    folderGrade.classList.remove("d-none")

    iconList.classList.remove("d-none")
    folderList.classList.add("d-none")

    list_grade = "grade"

  } else {
    iconGrade.classList.remove("d-none")
    folderGrade.classList.add("d-none")

    iconList.classList.add("d-none")
    folderList.classList.remove("d-none")

    list_grade = "list"
  }

  let request = new XMLHttpRequest()

  request.open("GET", "/addListOrGrade?list_grade=" + list_grade, true)

  request.onreadystatechange = () => {
    if (request.readyState == 4 && request.status == 200) {
      var reposta = request.responseText

    } else if (request.readyState == 4 && request.status == 404) {
      console.log('Erro na requisição')
    }
  }
  request.send()


}

function salvarAssunto() {
  let formAssunto = document.querySelector('#form_assunto')
  let sidebarNav = document.querySelector("#sidebar-nav")
  let request = new XMLHttpRequest()


  //Criando Elemnetos
  let li = document.createElement("li")
  li.classList.add("nav-item")
  let a = document.createElement("a")
  a.classList.add("nav-link")
  a.classList.add("collapsed")

  let i = document.createElement("i")

  i.classList.add("bi")
  i.classList.add("bi-file-earmark")
  let span = document.createElement("span")
  span.innerText = document.querySelector('#area_formacao').value


  li.appendChild(a)
  a.appendChild(i)
  a.appendChild(span)
  let li_Item = li

  request.open("POST", "/salvarassunto", true)

  request.onreadystatechange = () => {
    if (request.readyState == 4 && request.status == 200) {
      var reposta = request.responseText

      if (reposta == "Sucesso") {
        sidebarNav.appendChild(li_Item)
        alert("Aréa Adiconada")

      } else {
        alert("Erro. Tente novamente!")

      }

      document.querySelector("#cancelar").click()
    } else if (request.readyState == 4 && request.status == 404) {
      console.log('Erro na requisição')
    }
  }

  let data = new FormData(formAssunto)
  request.send(data)

}

//

function fazerPerguntaForum(id_user, img_perfil) {
  //Criando Elementos 
  let tr = document.createElement("tr")
  let a_img = document.createElement("a")
  let img = document.createElement("img")
  let th = document.createElement("th")
  th.scope = "row"
  let a_titulo = document.createElement("a")
  let p = document.createElement("p")
  let a_ler = document.createElement("a")
  let td = document.createElement("td")
  let td_reposta = document.createElement("td")

  //Adiconando as Classes
  a_titulo.classList.add("text-primary")
  a_titulo.classList.add("fw-bold")
  a_titulo.classList.add("mb-1")

  let titulo = document.querySelector("#titulo")

  a_titulo.innerText = titulo.value
  td_reposta.classList.add("fw-bold")
  td_reposta.innerText = "Respostas(12)"

  img.src = "/app/assets/img/" + img_perfil

  tr.appendChild(th)
  th.appendChild(a_img)
  a_img.appendChild(img)
  tr.appendChild(td)
  tr.appendChild(td_reposta)
  td.appendChild(a_titulo)
  let detalhes_questao = document.querySelector(".ql-editor").innerHTML
  p.innerHTML = detalhes_questao + "<a href='#'> Ler mais</a>"
  td.appendChild(p)


  let nivel_academico = document.querySelector("#nivel_academico_forum")
  let area_formacao = document.querySelector("#area_formacao_forum")

  if (titulo.value == "") {
    alert("Preencha o campo Título!")
    titulo.focus()

  } else if (nivel_academico.value == "") {
    alert("Selencione o nivél!")
    nivel_academico.focus()

  } else if (area_formacao.value == "") {
    alert("Selencione a Área de Formação!")
    area_formacao.focus()

  } else {
    document.querySelector("#body_questao").appendChild(tr)
    let request = new XMLHttpRequest()

    request.open("POST", "/fazerpergunta", true)



    request.onreadystatechange = () => {
      if (request.readyState == 4 && request.status == 200) {
        var reposta = request.responseText

        if (reposta == "Sucesso") {
          alert("Pergunta Salva")
        } else {
          alert("Erro. Tente novamente!")
        }

        document.querySelector("#fechar_form").click()

      } else if (request.readyState == 4 && request.status == 404) {
        console.log('Erro na requisição')
      }
    }

    let data = JSON.stringify({
      nivel_academico: nivel_academico.value,
      area_formacao: area_formacao.value,
      titulo: titulo.value,
      detalhes_questao: p.innerHTML,
      id_user: id_user

    })

    request.send(data)

  }
}

function forumRespostas(id_pergunta, img_perfil) {
  //Criand Elementos
  let div = document.createElement("div")
  let a_img = document.createElement("a")
  let img = document.createElement("img")
  let a_nome = document.createElement("a")
  let a_editar = document.createElement("a")
  let a_remover = document.createElement("a")
  let p = document.createElement("p")
  let h4 = document.createElement("h4")

  //Adicionando Classes:
  div.classList.add("card-body")
  div.classList.add("mt-2")
  div.classList.add("pb-0")

  a_nome.classList.add("text-primary")
  a_nome.classList.add("fw-bold")
  a_nome.classList.add("mb-1")

  p.classList.add("text-black")
  p.classList.add("p-3")

  a_editar.classList.add("p-3")

  //Atributos e InnerText
  a_editar.innerText = "Editar"
  a_remover.innerText = "Remover"
  img.src = "/app/assets/img/" + img_perfil
  img.style.borderRadius = "50%";
  img.style.width = "50px";

  let detalhes_questao = document.querySelector(".ql-editor").innerHTML
  p.innerHTML = detalhes_questao


  //Criando Hierarquia dos Elementos:
  div.appendChild(a_img)
  a_img.appendChild(img)
  div.appendChild(a_nome)
  div.appendChild(h4)
  div.appendChild(p)
  div.appendChild(a_editar)
  div.appendChild(a_remover)


  document.querySelector("#card_container").insertBefore(div, document.querySelector("#card_footer"));

  let request = new XMLHttpRequest()

  request.open("POST", "/responderpergunta", true)



  request.onreadystatechange = () => {
    if (request.readyState == 4 && request.status == 200) {
      var reposta = request.responseText

      if (reposta == "Sucesso") {
        alert("Pergunta Salva")
      } else {
        alert("Erro. Tente novamente!")
      }

      document.querySelector("#fechar_form").click()

    } else if (request.readyState == 4 && request.status == 404) {
      console.log('Erro na requisição')
    }
  }



  let data = JSON.stringify({
    resposta: detalhes_questao,
    id_pergunta: id_pergunta

  })
  request.send(data)
}

function filtarPorNivelAcademico() {
  location.href = "/pesquisaForum?nivel_academico=" + document.querySelector("#nivel_academico_quest").value
}

function filtarPorAreaDeFormacao() {
  location.href = "/pesquisaForum?area_formacao=" + document.querySelector("#area_formacao_quest").value
}

function filtarPorPesquisa() {
  location.href = "/pesquisaForum?questao=" + document.querySelector("#pesquisa").value
}









