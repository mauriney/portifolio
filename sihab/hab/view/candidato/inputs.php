<?php include('template/topo.php'); ?>

<?php include('template/sidebar.php'); ?>

<!-- CSS
================================================== -->
<link rel="stylesheet" href="<?= PORTAL_URL; ?>assets/plugins/form-wizard/css/tmm_form_wizard_style_demo.css" />
<link rel="stylesheet" href="<?= PORTAL_URL; ?>assets/plugins/form-wizard/css/grid.css" />
<link rel="stylesheet" href="<?= PORTAL_URL; ?>assets/plugins/form-wizard/css/tmm_form_wizard_layout.css" />
<link rel="stylesheet" href="<?= PORTAL_URL; ?>assets/plugins/form-wizard/css/fontello.css" />

<section id="content">
  <div class="c-header">
    <div class="card icons-demo">
      <div class="card-header cw-header palette-Pink-700 bg">
        <div class="cwh-year">Candidato</div>
        <div class="cwh-day">Cadastro</div>

<!--        <a href="JavaScript: window.history.back()" class="btn palette-Light-Blue-500 bg btn-float waves-effect waves-circle waves-float"><i class="zmdi zmdi-chevron-left"></i></a>-->
      </div>
      <div class="card-body card-padding-sm">
        <div id="cw-body">
          <div class="card-body card-padding">
            <div id="content">

              <div class="form-container">

                <div id="tmm-form-wizard" class="substrate">

                  <div class="row stage-container">

                    <div class="stage tmm-success col-md-3 col-sm-3">
                      <div class="stage-header head-icon head-icon-lock"></div>
                      <div class="stage-content">
                        <h3 class="stage-title">Account Information</h3>
                        <div class="stage-info">
                          Enter your first time username
                          passworddetails
                        </div> 
                      </div>
                    </div><!--/ .stage-->

                    <div class="stage tmm-success col-md-3 col-sm-3">
                      <div class="stage-header head-icon head-icon-user"></div>
                      <div class="stage-content">
                        <h3 class="stage-title">Personal Information</h3>
                        <div class="stage-info">
                          Enter your first time username
                          passworddetails
                        </div>  
                      </div>
                    </div><!--/ .stage-->

                    <div class="stage tmm-success col-md-3 col-sm-3">
                      <div class="stage-header head-icon head-icon-payment"></div>
                      <div class="stage-content">
                        <h3 class="stage-title">Payment Information</h3>
                        <div class="stage-info">
                          Enter your first time username
                          passworddetails
                        </div>
                      </div>
                    </div><!--/ .stage-->

                    <div class="stage tmm-current col-md-3 col-sm-3">
                      <div class="stage-header head-icon head-icon-details"></div>
                      <div class="stage-content">
                        <h3 class="stage-title">Confirm Your Details</h3>
                        <div class="stage-info">
                          Enter your first time username
                          passworddetails
                        </div>  
                      </div>
                    </div><!--/ .stage-->

                  </div><!--/ .row-->

                  <div class="row">

                    <div class="col-xs-12">

                      <div class="form-header space-t-20">
                        <div class="form-title form-icon title-icon-payment">
                          <b>DOCUMENTO FAMILIAR</b>
                        </div>
                        <div class="steps">
                          Etapa 5 - 6
                        </div>
                      </div><!--/ .form-header-->

                    </div>

                  </div><!--/ .row-->

                  <form action="/" role="form">

                    <div class="form-wizard">
                        
                      <div class="">
                        <div class="card-body card-padding">
                            <div class="row space-t-10">
                                <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 1</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                      <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                      <div>
                                          <span class="btn btn-info btn-file">
                                              <span class="fileinput-new">Selecionar imagem</span>
                                              <span class="fileinput-exists">Alterar imagem</span>
                                              <input type="file" name="...">
                                          </span>
                                          <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                      </div>
                                  </div>
                                </div>

                                 <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 2</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>
                                </div>

                               <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 3</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>
                                </div>

                                 <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 4</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>
                                </div>                                                                                              
                            </div>


                              <div class="row space-t-10">
                                <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 5</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>

                                </div>
                                 <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 6</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">remover</a>
                                        </div>
                                    </div>

                                </div>

                               <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 7</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>

                                </div>

                                 <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 8</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>

                                </div>                                                                                              

                            </div>


                              <div class="row space-t-10">
                                <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 9</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>

                                </div>
                                 <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 10</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>

                                </div>

                               <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 11</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>

                                </div>

                                 <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 12</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>

                                </div>                                                                                              

                            </div>

                        <div class="form-header space-t-20 space-b-20">
                          <div class="form-title form-icon title-icon-payment">
                            <b>DOCUMENTO DO CÔNJUGE</b>
                          </div>
                          <div class="steps">
                            Etapa 5 - 6
                          </div>
                        </div><!--/ .form-header-->


                          <div class="row space-t-10">
                                <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 1</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>

                                </div>
                                 <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 2</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>

                                </div>

                               <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 3</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">remover</a>
                                        </div>
                                    </div>

                                </div>

                                 <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 4</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">remover</a>
                                        </div>
                                    </div>

                                </div>                                                                                              

                            </div>

                            <div class="row space-t-10">
                                <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 5</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>

                                </div>
                                 <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 6</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>

                                </div>

                               <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 7</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">remover</a>
                                        </div>
                                    </div>

                                </div>

                                 <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 8</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">remover</a>
                                        </div>
                                    </div>

                                </div>                                                                                              

                            </div>    

                            <div class="row space-t-10">
                                <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 9</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>

                                </div>
                                 <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 10</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>

                                </div>

                               <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 11/p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">remover</a>
                                        </div>
                                    </div>

                                </div>

                                 <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 12</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">remover</a>
                                        </div>
                                    </div>

                                </div>                                                                                              

                            </div> 

                            <div class="form-header space-t-20 space-b-20">
                                <div class="form-title form-icon title-icon-payment">
                                  <b>DOCUMENTO DO TITULAR</b>
                                </div>
                                <div class="steps">
                                  Etapa 5 - 6
                                </div>
                            </div><!--/ .form-header--> 

                            <div class="row space-t-10">
                                <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 1</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>

                                </div>
                                 <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 2</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>

                                </div>

                               <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 3</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">remover</a>
                                        </div>
                                    </div>

                                </div>

                                 <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 4</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">remover</a>
                                        </div>
                                    </div>

                                </div>                                                                                              

                            </div>    

                            <div class="row space-t-10">
                                <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 5</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>

                                </div>
                                 <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 6</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>

                                </div>

                               <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 7</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">remover</a>
                                        </div>
                                    </div>

                                </div>

                                 <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 8</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">remover</a>
                                        </div>
                                    </div>

                                </div>                                                                                              

                            </div>      

                            <div class="row space-t-10">
                                <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 9</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>

                                </div>
                                 <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 10</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remover</a>
                                        </div>
                                    </div>

                                </div>

                               <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 11</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar imagem</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">remover</a>
                                        </div>
                                    </div>

                                </div>

                                 <div class="col-sm-3">
                                   <p class="f-500 c-black m-b-20">Item 12</p>

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"></div>
                                        <div>
                                            <span class="btn btn-info btn-file">
                                                <span class="fileinput-new">Selecionar imagem</span>
                                                <span class="fileinput-exists">Alterar</span>
                                                <input type="file" name="...">
                                            </span>
                                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">remover</a>
                                        </div>
                                    </div>

                                </div>                                                                                              

                            </div>                            






                    </div> <!--/ .form-wizard-->

                      <div class="prev">
                          <button class="btn btn-primary btn-icon-text"><i class="zmdi zmdi-arrow-back" onclick="window.location.href = 'etapa5'"></i>Etapa Anterior</button>
                      </div>

                      <div class="next">
                          <button class="btn btn-primary btn-icon-text"><i class="zmdi zmdi-arrow-forward" onclick="window.location.href = 'etapa6'"></i>Próxima Etapa</button>
                      </div>


                  </form><!--/ form-->

                </div><!--/ .container-->

              </div><!--/ .form-container-->

            </div><!--/ #content-->

          </div>            
        </div>
      </div>
    </div>
  </div>
</section>

<?php include('template/rodape.php'); ?>

<!--[if lt IE 9]>
                <script src="<?= PORTAL_URL; ?>assets/plugins/js/respond.min.js"></script>
<![endif]-->

<script src="<?= PORTAL_URL; ?>assets/plugins/form-wizard/js/tmm_form_wizard_custom.js"></script>