<form action='' method='POST' id='form'>
    <div class='form-group'>
        <label for='number'>En combien de fois souhaitez-vous payer ?</label>
        <br />
        <input id='number' type='number' name='times' />
        <input type='submit' name='submit_times' />
    </div>
    <ul>
   
    {for $i=0 to $times-1}

    <strong>{$array[$i]} : {$price / $times}</strong>
    <br />
    {/for}

    </ul>
</div>

</form>