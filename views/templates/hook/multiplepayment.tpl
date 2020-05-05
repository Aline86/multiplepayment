{if ($price >= $from_the_price) }
<div class='payment'>
    <div class='title'>
        <div class='line_times'></div>
        <div class='times'>ou payez en {$times}X</div>
        <div class='line_times'> </div>
    </div>
    {for $i=0 to $times-1}
    <div class='flex'>
        <div class='text'> {number_format($price*1.2/$times,2,',','')} € TTC</div>
        <div class='line'></div>
        <div class='text2'>{$array[$i]}</div><br />
    </div>
    <br />
    {/for}
</div>
{/if}
