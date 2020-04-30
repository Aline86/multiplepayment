

{if isset($confirmation)}
  <div class='alert alert-success'>
  La configuration a bien été mise à jour
  </div>
{/if}

<form method='post' action='' class='defaultForm form-horizontal'>
  <div class='panel'>
    <div class='panel-heading'>
      <i class='icon-cogs'></i>
          La cofiguration de mon module
    </div>
    <div class='form-wrapper'>
      <div class='form-group'>
        <label class='control-label col-lg-3'>Activer les notes :</label>
        <div class='col-lg-9'>
          <img src='../img/admin/enabled.gif' alt='' />
          <input type='radio' id='enable_grades_1' name='enable_grades' value='1' {if $enable_grades eq '1'}checked{/if} />
          
          <label class='t' for='enable_grades_1'>Oui</label>
          <img src='../img/admin/disabled.gif' alt='' />
          <input type='radio' id='enable_grades_0' name='enable_grades' value='0' {if $enable_grades ne '1'}checked{/if} />
          <label class='t' for='enable_grades_0'>Non</label>
        </div>
      </div>
      <div class='form-group'>
        <label class='control-label col-lg-3'>Activer les commentaires :</label>
        <div class='col-lg-9'>
          <img src='../img/admin/enabled.gif' alt='' />
          <input type='radio' id='enable_comments_1' name='enable_comments' value='1' />
          <label class='t' for='enable_comments_1'>Oui</label>
          <img src='../img/admin/disabled.gif' alt='' />
          <input type='radio' id='enable_comments_0' name='enable_comments' value='0'
            checked />
          <label class='t' for='enable_comments_0'>Non</label>
        </div>
      </div>
    </div>
    <div class='panel-footer'>
    <button class='btn btn-default pull-right' name='submit_mymodcomments_form'
    value='1' type='submit'>
          <i class='process-icon-save'></i> Enregistrer
    </button>
  </div>
</div>
</form>