function printNotifications(notifiche){
    let result = "";

    notifiche.forEach(notifica => {
        let titolo = "";
        switch(notifica["id_notifica"]){
            case 1:
            case 2:
                titolo = "Acquisto Biglietto";
                break;
            case 3:
            case 4:
                titolo = "Rimborso Biglietto";
                break;
            case 5:
                titolo = "Promemoria Evento";
                break;
            case 6:
                titolo = "Compleanno";
                break;
            case 7:
            case 8:
                titolo = "Annullamento Evento";
                break;
            default:
                titolo = "Errore";
        }
        const year = notifica["timestamp"].slice(0, 4);
        const month = notifica["timestamp"].slice(5, 7)-1;
        const days = notifica["timestamp"].slice(8, 10);
        const hours = notifica["timestamp"].slice(11, 13);
        const minutes = notifica["timestamp"].slice(14, 16);
        const d = new Date(year, month, days, hours, minutes, 0, 0);
        let notifica_ = `
        <article>
            <h3>${titolo}</h3>
            <p>${notifica["messaggio_finale"]}</p>
            <h5> Ricevuta il ` + (d.getDate() > 9 ? d.getDate() : "0"+d.getDate())+ "/" + ((d.getMonth()+1) > 9 ? (d.getMonth()+1) : "0"+(d.getMonth()+1))+ "/" + d.getFullYear() + " - " + (d.getHours() > 9 ? d.getHours() : "0"+d.getHours())+ ":" + (d.getMinutes() > 9 ? d.getMinutes() : "0"+d.getMinutes()) + `</h5>
        </article>
        `;
        result += notifica_;
    });
    return result;
}

function goBack(){
    window.history.back();
}

function addToCart(id_evento, max_available){
    const number = prompt("Inserire quanti biglietti vuoi aggiungere", "0");
    if(!isNaN(parseInt(number)) && number > max_available){
        alert("Il numero massimo di biglietti disponibili è " + max_available + "!");
        addToCart(id_evento, max_available);
    } else if(!isNaN(parseInt(number)) && parseInt(number) > 0){
        window.location.href = "add_to_cart.php?id_evento=" + id_evento + "&number=" + number;
    } else if(number != null){
        alert("Il valore deve essere un numero positivo.");
        addToCart(id_evento, max_available);
    } 
}

function removeFromCart(id_evento, max_refundable){
    const number = prompt("Inserire quanti biglietti vuoi rimuovere", "0");
    if(!isNaN(parseInt(number)) && number > max_refundable){
        alert("Non puoi rimuovere più di " + max_refundable + " biglietti!");
        removeFromCart(id_evento, max_refundable);
    } else if(!isNaN(parseInt(number)) && parseInt(number) > 0){
        window.location.href = "remove_from_cart.php?id_evento=" + id_evento + "&number=" + number;
    } else if(number != null){
        alert("Il valore deve essere un numero positivo.");
        removeFromCart(id_evento);
    } 
}

function refundTickets(id_evento, max_refundable){
    const number = prompt("Inserire quanti biglietti vuoi rimborsare", "0");
    if(!isNaN(parseInt(number)) && number > max_refundable){
        alert("Non puoi rimborsare più di " + max_refundable + " biglietti!");
        refundTickets(id_evento, max_refundable);
    } else if(!isNaN(parseInt(number)) && parseInt(number) > 0){
        window.location.href = "refund.php?id=" + id_evento + "&number=" + number;
    } else if(number != null){
        alert("Il valore deve essere un numero positivo.");
        refundTickets(id_evento, max_refundable);
    } 
}

function getNotifications(){
    $.getJSON("notifications.php", function(data){
        let notifiche = printNotifications(data);
        const notifications = $("#notifications");
        if(notifiche == ""){
            notifications.html(`<h2>Notifiche</h2><h3>Non hai ancora ricevuto alcuna notifica.</h3>`);
        } else{
            notifications.html(`<h2>Notifiche</h2>` + notifiche);
        }
    });
}

function confirmDeleteUser(id){
    if(confirm("Sei sicuro di voler disabilitare l'utente?")){
        window.location.href = "delete_user.php?id=" + id;
    }
}

function confirmDeleteEvent(id){
    if(confirm("Sei sicuro di voler cancellare l'evento?")){
        window.location.href = "delete_event.php?id=" + id;
    }
}

function confirmEnableUser(id){
    if(confirm("Sei sicuro di voler riabilitare l'utente?")){
        window.location.href = "enable_user.php?id=" + id;
    }
}

function confirmEnableEvent(id){
    if(confirm("Sei sicuro di voler riabilitare l'evento?")){
        window.location.href = "enable_event.php?id=" + id;
    }
}

$(document).ready(function(){
    getNotifications();
    setInterval(getNotifications(), 3000);
});