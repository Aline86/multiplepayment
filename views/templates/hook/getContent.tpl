

{if isset($confirmation)}
  <div class='alert alert-success'>
  La configuration a bien été mise à jour
  </div>
{/if}

<form action='' method='POST' id='form'>
    <div class='form-group'>
        <label for='grade'>Configurer le module pour l'affichage du paiement en plusieurs fois</label>
        <select id='grade' class='form-control' name='times'>
             <option value=''>Payer en plusieurs fois</option>
             <option value='2' {if isset($thetime) && $thetime == '2'}selected="selected"{/if}>Paiement 2 X</option>
             <option value='3' {if isset($thetime) && $thetime == '3'}selected="selected"{/if}>Paiement 3 X</option>
             <option value='4' {if isset($thetime) && $thetime == '4'}selected="selected"{/if}>Paiement 4 X</option>
             <option value='5' {if isset($thetime) && $thetime == '5'}selected="selected"{/if}>Paiement 5 X</option>
          </select>
        <input class='submit' type='submit' name='submit_times' />
    </div>
    </form>
{if isset($configerror) && $configerror eq 'error'}
  <div class='error'>
  Veuillez revoir les paramètres de configuration.
  </div>
{/if}