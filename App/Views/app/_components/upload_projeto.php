<!-- Modal de Upload de Projeto -->
<div class="modal fade" id="modalDialogScrollable" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Carregar Projeto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-secodary">
                <div class="card">
                    <div class="card-body">
                        <!-- General Form Elements -->
                        <form name="upload-form" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <div class="col-md-6 p-2">
                                    <label for="nome" class="form-label">Nome
                                        do Projeto</label>
                                    <input type="text" name="nome" id="nome" class="form-control">
                                </div>
                                <div class="col-md-6 p-2">
                                    <label for="curso" class="form-label">Curso</label>
                                    <input type="text" name="curso" id="curso" class="form-control">
                                </div>
                                <div class="col-md-6 p-2">
                                    <label class="form-label">Aréa de Formação</label>
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

                                <div class="col-md-6 p-2">
                                    <label class="form-label">Nivél Académico/Formação</label>
                                    <select class="form-select" name="nivel_academico" id="nivel_academico"
                                        aria-label="Default select example" required>
                                        <option selected="" value="">Selicionar</option>
                                        <option value="1">Ensino Médio</option>
                                        <option value="2">Ensino Superior</option>
                                        <option value="3">Mestrado/Pós-graduação</option>
                                        <option value="4">Formação Profissional</option>
                                    </select>
                                </div>

                                <div class="col-md-6 p-2">
                                    <label for="file_upload" class="form-label">Escolher Arquivo(.pdf)</label>
                                    <input class="form-control" name="file_upload" accept=".pdf" type="file"
                                        id="file_upload">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 p-2">
                                    <button type="submit" class="btn btn-primary" id="btnSumitForm"
                                        data-bs-toggle="modal" data-bs-target="#progressUpload">Salvar Material</button>
                                </div>
                            </div>

                        </form><!-- End General Form Elements -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div><!-- End Modal Dialog Scrollable-->