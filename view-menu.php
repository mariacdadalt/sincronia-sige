<div class="wrap">
    <h3>Atualizar produtos</h3>
    <p>Aqui se faz a conexão entre o site e o sistema SIGE. Verifica-se se o produto está ativo e cadastrado no site.</p>
    <p>Se o produto estiver inativo no SIGE, mas estiver cadastrado no site, o mesmo é excluido.</p>
    <p>Se o produto estiver ativo no SIGE e cadastrado no site, seu preço e estoque são atualizados.</p>
    <p>Se o produto estiver ativo no SIGE e não estiver cadastrado no site, o mesmo é cadastrado. </p>
    <p>Para inativar um produto no SIGE, colocar a letra "N" no campo "Prateleira".</p>
    <form method="post">
        <label for="min">Menor código </label>
        <input type="number" name="min" />
        <label for="max">Maior código </label>
        <input type="number" name="max" />
        <button>Enviar</button>
    </form>
</div>
