<!-- Modal -->
<div class="modal fade modal-default modal-calendar" id="modal-calendar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="co-close"></i></button>
      </div>
      <div class="modal-body">
        <h1>Novo Hábito</h1>
        <form class="" action="index.html" method="post">
          <div class="row">
            <div class="col-md-4">
              <!-- <div class="form-group modal-calendar-new">
                <label for="finish">Data</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="co-calendar"></i></span>
                  <input type="text" class="form-control" placeholder="00/00/0000">
                </div>
              </div> -->
              <div id="sandbox-container">
                <div></div>
              </div>
            </div>
            <div class="col-md-8">
              <div class="table-responsive">
            		<table class="table">
            			<thead>
            				<tr>
            					<th></th>
            					<th><span>Hábitos</span></th>
            					<th></th>
            				</tr>
            			</thead>
            			<tbody>
            				<tr>
            					<td><i class="co-brain-upper-view-outline"></i></td>
            					<td>Nome do Habito</td>
            					<td><input type="checkbox" /></td>
            				</tr>
            				<tr>
            					<td><i class="co-rose"></i></td>
            					<td>Nome do Habito</td>
            					<td><input type="checkbox" /></td>
            				</tr>
            				<tr>
            					<td><i class="co-science"></i></td>
            					<td>Nome do Habito</td>
            					<td><input type="checkbox" /></td>
            				</tr>
            			</tbody>
            		</table>
            	</div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 text-center">
              <button type="button" class="btn btn-primary btn-new btn-lg">Salvar</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$('#calendar-modal div').datepicker({
  format: "dd/mm/yyyy",
  todayBtn: "linked",
  language: "pt-BR",
  forceParse: false,
  calendarWeeks: true,
  autoclose: true,
  todayHighlight: true
});
</script>
