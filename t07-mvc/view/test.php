<?php
echo "<pre>parmetros passados para a view: ";
print_r(get_defined_vars());
echo "</pre>";
?>
<h1>Testewwww</h1>
<p>id = <?php echo isset($id) ? $id : 'Nenhum ID disponível'; ?></p>


<div class="from">
    <form action="/admin/teste-group" method="POST">
        <input type="text" name="name" id="name">
        <input type="text" name="email" id="email">
        <input type="text" name="password" id="password">
        <button type="submit">Enviar</button>
    </form>
</div>