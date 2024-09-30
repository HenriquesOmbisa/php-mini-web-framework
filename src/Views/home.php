<?php
use App\Utils\Data;
?>
<html>
    <head>
        <title><?= $title ?></title>
        <link rel="stylesheet" href= "<?= ASSETS ?>css/style.css">
    </head>
    <body>
        <?php if(isset($_GET['error'])):?>
            <p><?=$_GET['error']?></p>
            <?php endif;?>
        <form action="create" method="post">
            <input type="text" name="name" placeholder="Nome">
            <input type="email" name="email" placeholder="E-mail">
            <input type="password" name="pass" id="" placeholder="Senha">
            <button type="submit" name="btn-create">Create</button>
        </form>
        <h1>Users list</h1>
        <h1> <?=$protocol?></h1>
        <?php foreach($users as $user){ ?>
        <p>[<?= $user['id'] ?>]  
        <b> <?= Data::htmlSpecial($user['name']) ?></b> - 
        <?= Data::htmlSpecial($user['email'])?></p>
        <?php } ?>
        <button id="submit">Criar</button>
    </body>

    <script>;
        let criar = document.querySelector('#submit');
        criar.addEventListener('click', ()=> {

            fetch('create', {
            method: 'POST', // método da requisição (pode ser GET, POST, PUT, DELETE, etc)
            headers: {
                'Content-Type': 'application/json' // especifica que o corpo da requisição é JSON
            },
            body: JSON.stringify({name:'Lú Ombisa', email: 'luombisa@gmail.com', password: 'password123' }) // converte o objeto JS em JSON
            })
            .then(response => response.json())
            .then(result => console.log(result)) // manipula o resultado
            .catch(error => console.error('Erro:', error));
        })

    </script>
</html>