<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>jQuery UI Dialog - Modal form</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <style>
        label, input { display:block; }
        input.text { margin-bottom:12px; width:95%; padding: .4em; }
        fieldset { padding:0; border:0; margin-top:25px; }
        h1 { font-size: 1.2em; margin: .6em 0; }
        div#users-contain { width: 350px; margin: 20px 0; }
        div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
        div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
        .ui-dialog .ui-state-error { padding: .3em; }
        .validateTips { border: 1px solid transparent; padding: 0.3em; }
    </style>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $( function() {
            var dialog, form,

                name = $( "#name" ),
                contenu = $( "#contenu" ),
                allFields = $( [] ).add( name ).add( contenu ),
                tips = $( ".validateTips" );

            function updateTips( t ) {
                tips
                    .text( t )
                    .addClass( "ui-state-highlight" );
                setTimeout(function() {
                    tips.removeClass( "ui-state-highlight", 1500 );
                }, 500 );
            }

            function checkLength( o, n, min, max ) {
                if ( o.val().length > max || o.val().length < min ) {
                    o.addClass( "ui-state-error" );
                    updateTips( "Le " + n + " doit contenir entre " +
                        min + " et " + max + " caractères." );
                    return false;
                } else {
                    return true;
                }
            }

            function addCom() {
                var valid = true;
                allFields.removeClass( "ui-state-error" );

                valid = valid && checkLength( name, "pseudo", 3, 16 );
                valid = valid && checkLength( contenu, "commentaire", 6, 250 );

                valid = valid && checkRegexp( name, /^[a-z]([0-9a-z_\s])+$/i, "Username may consist of a-z, 0-9, underscores, spaces and must begin with a letter." );
                valid = valid && checkRegexp( contenu, /^[a-z]([0-9a-z_\s])+$/i , "eg. ui@jquery.com" );

                if ( valid ) {

                    dialog.dialog( "close" );
                }
                return valid;
            }

            dialog = $( "#dialog-form" ).dialog({
                autoOpen: false,
                height: 400,
                width: 350,
                modal: true,

            });

            form = dialog.find( "form" ).on( "submit", function( event ) {
                event.preventDefault();
                addCom();
            });

            /*function toggleForm(){
                window.open(dialog.dialog);
            };*/
            $( "#ajouter-commentaire" ).button().on( "click", function() {
                dialog.dialog( "open" );
            });
        } );
    </script>
</head>
<body>

<div id="dialog-form" title="Ajouter un commentaire">
    <p class="validateTips">Tous les champs sont requis.</p>

    <form>
        <fieldset>
            <label for="name">Pseudo</label>
            <input type="text" name="name" id="name" value="" class="text ui-widget-content ui-corner-all">
            <label for="contenu">Commentaire</label>
            <textarea rows="6" cols="38" name="contenu" id="contenu" value="" class="text ui-widget-content ui-corner-all"></textarea>

            <input type="hidden" name="parentId" value=""/>
            <input type="submit" class="btn btn-default" value="Ajouter"/></form>
            <!-- Allow form submission with keyboard without duplicating the dialog button -->
            <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
        </fieldset>
    </form>
</div>



<button onclick="$( '#ajouter-commentaire' )" id="ajoutr-commentaire">Répondre</button>


</body>
</html></html>