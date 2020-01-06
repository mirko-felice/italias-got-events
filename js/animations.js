function openTab(e, tab_to_open) {
    $(".tab_content").hide();
    $(".tab_links").removeClass("selected");
    e.currentTarget.className += " selected";
    $("#" + tab_to_open).fadeIn();
}

$(document).ready(function() {
    const menu = $("nav");
    const profile = $("#profile");
    const notifications = $("#notifications");
    const body = $("body");
    const toggle_events = $("#toggle_events");
    const open_menu = $("#open_menu");
    const open_profile = $("#open_profile");
    const toggle_notification = $("#toggle_notification");
    const toggle_categories= $("#toggle_categories");
    const arrow_events = $("#arrow_events");
    const arrow_categories = $("#arrow_categories");
    const open_search = $("#open_search");
    const search_bar = $("#search_bar");
    const input = $("main > form > input[type='password']");
    const username_login = $("#username_login");
    const go_to_login = $("#go_to_login");
    const buy_tickets = $("#buy_tickets");
    const order = $("#order");
    const events = $("#home_events > a");
    const toggle_filters = $("#toggle_filters");
    const filters = $("#filter");
    const show_more_categories = $("#show_more_categories");
    const show_more_managers = $("#show_more_managers");
    const hide_default_cat = $(".hide_default_cat");
    const hide_default_man = $(".hide_default_man");

    $(".image_loader").click(() => {
        $(".hidden_image_loader").click();
    });

    $(".hidden_image_loader").change((e) => {
        $(".image_loader").attr("src", URL.createObjectURL(e.target.files[0]));
    });

    let degrees_events = 180;
    let degrees_categories = 180;

    function setScrolling(var_to_set){
        if(body.width() < 768){
            body.css("overflow", "hidden");
        }
        var_to_set.css("overflow", "auto");
        $("#home_events > img:first-child").css("z-index", "-1");
        $("#home_events > img:last-child").css("z-index", "-1");
    }

    function resetScrolling(){
        if(body.width() < 768){
            body.css("overflow", "");
        }
        $("#home_events > img:first-child").css("z-index", "1");
        $("#home_events > img:last-child").css("z-index", "1");
    }

    open_menu.click(() => {
        let time = 0;
        if (notifications.hasClass("selected")){
            toggle_notification.click();
            time = 300;
        }
        setTimeout(() => {
            menu.addClass("selected");
            menu.animate({left: "0"}, "fast");
            setScrolling(menu);
        }, time);
    });

    $("#close_menu").click(() => {
        menu.removeClass("selected");
        menu.animate({left: "-100%"}, "fast");
        resetScrolling();
    }); 

    toggle_notification.click(() => {
        if (notifications.hasClass("selected")){
            notifications.removeClass("selected");
            notifications.animate({height: "0"}, "fast");
            setTimeout(() => notifications.css("border", "0px"), "200");
            resetScrolling();
            toggle_notification.attr("alt", "Apri Notifiche");
        } else {
            notifications.addClass("selected");
            notifications.css("border", "1px grey solid");
            notifications.animate({height: "70%"}, "fast");
            setScrolling(notifications);
            toggle_notification.attr("alt", "Chiudi Notifiche");
        }
    });

    open_profile.click(() => {
        let time = 0;
        if (notifications.hasClass("selected")){
            toggle_notification.click();
            time = 300;
        }
        setTimeout(() => {
            profile.addClass("selected");
            profile.animate({right: "0"}, "fast");
            setScrolling(profile);
            username_login.focus();
        }, time);
    });
    
    go_to_login.click(() =>{
        open_profile.click();
    });

    $("#close_profile").click(() => {
        profile.removeClass("selected");
        profile.animate({right: "-100%"}, "fast");
        resetScrolling();
    }); 

    toggle_events.click(() => {
        $("#events").slideToggle();
        arrow_events.css("transform", "rotate(" + degrees_events + "deg)");
        arrow_events.css("transition", "all 0.5s ease-out");
        degrees_events += 180;
    });

    toggle_categories.click(() =>{
        $("#menu_categories").slideToggle();
        arrow_categories.css("transform", "rotate(" + degrees_categories + "deg)");
        arrow_categories.css("transition", "all 0.5s ease-out");
        degrees_categories += 180;
    });

    $(document).mouseup((e) => {
        if (!notifications.is(e.target) && notifications.has(e.target).length === 0
            && notifications.hasClass("selected") && !toggle_notification.is(e.target)
            && !open_menu.is(e.target) && !open_profile.is(e.target) && !open_search.is(e.target)){
            toggle_notification.click();
        }
        if (!menu.is(e.target) && menu.has(e.target).length === 0
            && menu.hasClass("selected")){
            close_menu.click();
        }
        if (!profile.is(e.target) && profile.has(e.target).length === 0
            && profile.hasClass("selected")){
            $("#close_profile").click();
        }
        if (!search_bar.is(e.target) && search_bar.has(e.target).length === 0
            && search_bar.hasClass("selected")){
            $("#close_search").click();
        }
    });

    open_search.click(() => {
        let time = 0;
        if (notifications.hasClass("selected")){
            toggle_notification.click();
            time = 300;
        }
        setTimeout(() => {
            search_bar.addClass("selected");
            search_bar.animate({right: "0"}, "fast");
            setScrolling(search_bar);
            $("#search_bar input[type='search']").focus();
        }, time);
    });

    $("#close_search").click(() => {
        search_bar.removeClass("selected");
        search_bar.animate({right: "-100%"}, "fast");
        resetScrolling();
    });

    $("#toggle_password").on("change", () => {
        $(this).prop("checked") ? input.attr("type", "text") : input.attr("type", "password");
    });
    
    buy_tickets.click(() =>{
        if(confirm("Vuoi completare l'acquisto?")){
            window.location.href = "cart.php?bought=1";
        }
    });

    order.on("change", () => {
        $("form[action='search.php'").submit();
    });

    events.hide();
    events.first().addClass("selected").show();
    
    $("#home_events > img:first-of-type").click(function(){
        if (events.first().hasClass("selected")){
            events.first().removeClass("selected").hide();
            events.last().addClass("selected").fadeIn("slow");
        } else {
            events.each(function(){
                if ($(this).next().hasClass("selected")){
                    $(this).addClass("selected").fadeIn("slow");
                    $(this).next().removeClass("selected").hide();
                    return false;
                }
            });
        }
    });

    $("#home_events > img:last-child").click(function(){
        if (events.last().hasClass("selected")){
            events.last().removeClass("selected").hide();
            events.first().addClass("selected").fadeIn("slow");
        } else {
            events.each(function(){
                if ($(this).hasClass("selected")){
                    $(this).removeClass("selected").hide();
                    $(this).next().addClass("selected").fadeIn("slow");
                    return false;
                }
            });
        }
    });

    filters.hide();

    toggle_filters.click(() => {
        filters.slideToggle("fast", );
    });

    show_more_categories.click(() =>{
        if(show_more_categories.hasClass("selected")){
            show_more_categories.removeClass("selected");
            hide_default_cat.hide();
            show_more_categories.css("transform", "rotate(0)");
        } else {
            show_more_categories.addClass("selected");
            hide_default_cat.show();
            show_more_categories.css("transform", "rotate(180deg)");
        }
    });

    show_more_managers.click(() =>{
        if(show_more_managers.hasClass("selected")){
            show_more_managers.removeClass("selected");
            hide_default_man.hide();
            show_more_managers.css("transform", "rotate(0)");
        } else {
            show_more_managers.addClass("selected");
            hide_default_man.show();
            show_more_managers.css("transform", "rotate(180deg)");
        }
    });
});
