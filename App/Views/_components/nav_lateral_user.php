<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-heading">Pages</li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="/home">
                <i class="bi bi-bank2"></i>
                <span>Pagina Inicial</span>
            </a>
        </li><!-- End Dashboard Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="/forum">
                <i class="ri-discuss-fill"></i>
                <span>Perguntas e Repostas</span>
            </a>
        </li><!-- End Dashboard Nav -->
        <?php if (count($this->view->mentor) == 0) { ?>
        <li class="nav-item" style="cursor: pointer">
            <a class="nav-link collapsed" data-bs-toggle="modal" data-bs-target="#sejaMentor">
                <i class="ri-cloud-line"></i>
                <span>Seja Um Mentor</span>
            </a>
        </li><!-- End Dashboard Nav -->
        <?php } ?>
        <hr>
        <li class="nav-item">
            <a class="nav-link collapsed" href="/salvo">
                <i class="bi bi-grid"></i>
                <span>Salvo</span>
            </a>
        </li><!-- End Dashboard Nav -->
        <hr>
        <li class="nav-heading">Assuntos do seu interesse</li>
        <li class="nav-item" data-bs-toggle="modal" data-bs-target="#addAssunto">
            <a class="nav-link collapsed" href="#">
                <i class="bi bi-patch-plus"></i>
                <span>Adicionar um assunto</span>
            </a>
        </li><!-- Add Assunto do Seu interesse -->
        <?php foreach ($this->view->assunto as $key => $value) { ?>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#">
                <i class="bi bi-file-earmark"></i>
                <span>
                    <?= $value['area_fomrcao'] ?>
                </span>
            </a>
        </li><!-- End F.A.Q Page Nav -->
        <?php } ?>
    </ul>

</aside><!-- End Sidebar-->

<!--  Modal para definição do Assunto de Interesse-->
<div class="modal fade" id="addAssunto" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Um Assunto do Seu Interesse</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form_assunto">
                    <div class="row mb-3">
                        <label class="form-label">Aréa de Formação</label>
                        <div class="col-12">
                            <select class="form-select" name="area_formacao" id="area_formacao"
                                aria-label="Default select example" required>
                                <option selected="" value="">Selecionar</option>
                                <option>Ciências Exatas</option>
                                <option>Engenharia</option>
                                <option>Tecnologias de Comunicação e Informação</option>
                                <option>Ciências Biológicas</option>
                                <option>Artes e Design</option>
                                <option>Ciências Agrárias</option>
                                <option>Ciências Ambientais</option>
                                <option>Ciências Sociais Aplicadas</option>
                                <option>Ciências Físicas e Biológicas</option>
                                <option>Ciências Econômicas e Jurídicas</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-12 col-form-label">Nivél Académico/Formção</label>
                        <div class="col-12">
                            <select class="form-select" name="nivel_academico" id="nivel_academico"
                                aria-label="Default select example" required>
                                <option selected="" value="">Selinonar</option>
                                <option value="1">Ensino Médio</option>
                                <option value="2">Ensino Superior</option>
                                <option value="3">Mestrado/Pós-graduação</option>
                                <option value="4">Formação Profissional</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelar" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="salvarAssunto()">Salvar</button>
            </div>
        </div>
    </div>
</div>