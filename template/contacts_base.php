<table>
<caption>Contatti</caption>
    <tr>
        <th id="name">Nome</th>
        <td headers="name"><?php echo $user["nome"]; ?></td>
    </tr>
    <tr>
        <th id="surname">Cognome</th>
        <td headers="surname"><?php echo $user["cognome"]; ?></td>   
    </tr>
    <tr>
        <th id="email">Email</th>
        <td headers="email"><a href="mailto:<?php echo $user["email"]; ?>"><?php echo $user["email"]; ?></a></td>
    </tr>
    <tr>
        <th id="phone">Cellulare</th>
        <td headers="phone"><?php echo $user["cellulare"] === "" ? "Assente" : $user["cellulare"]; ?></td>
    </tr>
</table>
<a class="button" href="profile.php?id=<?php echo $user["id_utente"]; ?>">Vai al profilo</a>