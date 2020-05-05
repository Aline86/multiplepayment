

{if isset($confirmation)}
  <div class='alert alert-success'>
  La configuration a bien été mise à jour
  </div>
{/if}

<form action='' method='POST' id='form'>
    <div class='form-group'>
        <label for='grade'>Configuration du nombre de paiement :</label>
        <br />
        <input id='grade' type='number' name='times' />
        <br />
        <label for='grade'>Configuration du montant à partir duquel le module s'affichera :</label>
        <br />
        <input type='number' name='from_price' />
        <br />
        <input class='submit' type='submit' name='submit_times' />
    </div>
    </form>
{if isset($configerror) && $configerror eq 'error'}
  <div class='error'>
  Veuillez revoir les paramètres de configuration.
  </div>
{/if}