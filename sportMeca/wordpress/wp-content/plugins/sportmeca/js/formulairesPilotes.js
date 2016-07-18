jQuery(document).ready(function($){   
    $('#select_race').change(select_value_changed);
    
      /**
      * Permet d'effectuer la requête vers la base, afin d'afficher les engagements du pilote
      * @returns {undefined}
      */
    function select_value_changed(){
        //On post une requête, asynchrone. On ne peut pas appeler directement un scrip php, il faut passer par la page admin-ajax
        //en passant en paramètre un nom de fonction. Il suffira de faire un hook sur wp_ajax_nomDeLaFonction pour executer une fonction php
        $.ajax({
            cache : false,
            url : MyAjax.ajaxurl,//L'uri de la page admin-ajax
            type : 'POST',
            datatype : 'html',
            data : { //Les paramètres du post
                action : 'sml_get_engagements',
                race_name : $('#select_race').val()
            },
            success : function(data){ //La fonction appelée au retour
                $('#liste_engagements').html(data);
            }
        });
    }
    
    /**
     * On gère la validation des inputs
     */
    $("input[class='in_last_name sml_input']").on('change paste keyup',function(){
        test_text_input($(this));
        reinit_save_label($(this));
    });
    $("input[class='in_first_name sml_input']").on('change paste keyup',function(){
        test_text_input($(this));
        reinit_save_label($(this));
    });
    $("input[class='in_birth_date sml_input']").on('change paste keyup',function(){
        test_birthdate_input($(this));
        reinit_save_label($(this));
    });
    $("input[class='in_email sml_input']").on('change paste keyup',function(){
        test_email_input($(this));
        reinit_save_label($(this));
    });
    $("input[class='in_phone_number sml_input']").on('change paste keyup',function(){
        test_text_input($(this),10,10,'number');
        reinit_save_label($(this));
    });
    $("input[class='in_adress sml_input']").on('change paste keyup',function(){
        test_text_input($(this));
        reinit_save_label($(this));
    });
    $("input[class='in_cedex sml_input']").on('change paste keyup',function(){
        test_text_input($(this),5,5,'number');
        reinit_save_label($(this));
    });
    $("input[class='in_city sml_input']").on('change paste keyup',function(){
        test_text_input($(this));
        reinit_save_label($(this));
    });
    $("input[class='in_insurance sml_input']").on('change paste keyup',function(){
        test_text_input($(this));
        reinit_save_label($(this));
    });
    $("input[class='in_ffm_licence_number sml_input']").on('change paste keyup',function(){
        test_text_input($(this),6,6,'number');
        reinit_save_label($(this));
    });
    $("input[class='in_casm_number sml_input']").on('change paste keyup',function(){
        test_text_input($(this));
        reinit_save_label($(this));
    });
    $("input[class='in_team sml_input']").on('change paste keyup',function(){
        test_text_input($(this));
        reinit_save_label($(this));
    });
    $("input[class='in_category sml_input']").on('change paste keyup',function(){
        test_text_input($(this));
        reinit_save_label($(this));
    });
    $("input[class='in_vh_brand sml_input']").on('change paste keyup',function(){
        test_text_input($(this));
        reinit_save_label($(this));
    });
    $("input[class='in_vh_type sml_input']").on('change paste keyup',function(){
        test_text_input($(this));
        reinit_save_label($(this));
    });
    $("input[class='in_vh_displacement sml_input']").on('change paste keyup',function(){
        test_text_input($(this),2,4,'number');
        reinit_save_label($(this));
    });
    $("input[class='in_vh_year sml_input']").on('change paste keyup',function(){
        test_text_input($(this));
        reinit_save_label($(this));
    });    
    $("input[class='in_vh_chassis_number sml_input']").on('change paste keyup',function(){
        test_text_input($(this));
        reinit_save_label($(this));
    });
    //On vérifie les sélecteurs
    $("select[class='sel_country sml_select']").on('change',function(){
        test_sel_input($(this));
        reinit_save_label($(this));
    });
    $("select[class='sel_category sml_select']").on('change',function(){
        test_sel_input($(this));
        reinit_save_label($(this));
    }); 
    
     
    /**
     * On enregistre le formulaire
     */
    $("input[type='button']").on('click',function(){
        var ajaxurl = '<?php echo admin_url(\'admin-ajax.php\'); ?>';
        var form = $(this).closest('form'); //On récupère le formulaire auquel notre bouton appartient
        var form_index = $(form).attr('index');
        var form_ok = true;
        var lab_save_status = $(form).find('label.save_status');
        $('#' + form.attr('id') + ' :input').each(function(){ //On parcours tous les champs
            perform_test($(this)); //On lance une vérification sur le champ
            if($(this).attr("status") == "true"){ //Si le status est ok
                form_ok = form_ok & true; //On fait un et logique avec "vrai" (conserve la valeur précédente 
            } else {
                form_ok = form_ok & false; //On fait un et logique avec "faux" (override la valeur précédente)
            }
        });
        
        //On prépare la requête pour enregistrement des données
        if(form_ok){
            //On effectue la requête ajax
            $.ajax({
                url : MyAjax.ajaxurl,
                cache : false,
                type : 'POST',
                datatype : 'json',
                data : {                    
                    action : 'sml_save_engagement',
                    id : $(form).attr('id_pilote'),
                    //field_race_number : $(form).find('h4.race_number').attr('id'),
                    race_number : $('#numero_' + form_index).attr('race_number'),
                    eng_type : $(form).attr('eng_type'),
                    race_name : $(form).attr('race_name'),
                    team_key : $(form).attr('team_key'),
                    last_name : $('#in_last_name' + form_index).val(),
                    first_name : $('#in_first_name' + form_index).val(),
                    birth_date : $('#in_birth_date' + form_index).val(),
                    email : $('#in_email' + form_index).val(),
                    phone_number : $('#in_phone_number' + form_index).val(),
                    adress : $('#in_adress' + form_index).val(),
                    cedex : $('#in_cedex' + form_index).val(),
                    city : $('#in_city' + form_index).val(),
                    country : $('#sel_country' + form_index).val(),                    
                    insurance : $('#in_insurance' + form_index).val(),
                    ffm_licence_number : $('#in_ffm_licence_number' + form_index).val(),
                    casm_number : $('#in_casm_number' + form_index).val(),
                    team : $('#in_team' + form_index).val(),
                    category : $('#sel_category' + form_index).val(),
                    vh_brand : $('#in_vh_brand' + form_index).val(), 
                    vh_type : $('#in_vh_type' + form_index).val(),
                    vh_displacement : $('#in_vh_displacement' + form_index).val(),
                    vh_year : $('#in_vh_year' + form_index).val(),
                    vh_chassis_number : $('#in_vh_chassis_number' + form_index).val()
                },
                success : function(data){ //La fonction appelée au retour
                    if(data['success']){
                        lab_save_status.attr('status','true');
                        lab_save_status.text("Enregistrement effectué avec succès");
                        var str = "N° de course : " + data['race_number'];
                        $('#numero_' + form_index).text(str);
                        $('#numero_' + form_index).attr('race_number',data['race_number']);
                    } else{
                        lab_save_status.attr('status','false');
                        lab_save_status.text("Erreur lors de l'enregistrement de vos informations");                      
                    }
                }
            });

            //En fonction du résultat, on affiche un message
        } else {
            lab_save_status.attr('status','false');
            lab_save_status.text("Merci de bien vouloir renseigner tous les champs");
        }
    }); 
    
    /**
     * Permet de lancer manuellement le test sur un champ
     * @param {type} field Le champ sur lequel on veut lancer le test
     * @returns {undefined}
     */
    function perform_test(field){
        var str = field.attr('class');
        if(str){
            //On gère les cas particuliers en premier. Les champs text seront gérés dans le "else"
            if(str.toLowerCase().indexOf("in_email") >= 0){
                test_email_input(field);
            }
            else if(str.toLowerCase().indexOf("in_birth_date") >= 0){ //La date de naissance
                test_birthdate_input(field);
            }
            else if(str.toLowerCase().indexOf("in_phone_number") >= 0){ //Le numéro de téléphone
                test_text_input(field,10,10,'number');
            }
            else if(str.toLowerCase().indexOf("in_cedex") >= 0){ //Le code postal
                test_text_input(field,5,5,'number');
            }
            else if(str.toLowerCase().indexOf("in_ffm_licence_number") >= 0){ //Le numéro de licence ffm
                test_text_input(field,6,6,'number');
            }
            else if(str.toLowerCase().indexOf("in_vh_displacement") >= 0){ //Le numéro de licence ffm
                test_text_input(field,2,4,'number');
            }
            else if(str.toLowerCase().indexOf("sml_select") >= 0){ //Le numéro de licence ffm
                test_sel_input(field);
            }
            else { //Les champs qui n'ont pas de test spécifique
                test_text_input(field);
            }
        }
    }
    
    /**
     * Permet de changer l'état de validation d'un champ
     * @param {type} Le champ que dont on veut changer l'état de validation
     * @param {type} true or false
     */
    function validate(field, value){
        $(field).removeAttr("status");
        $(field).attr("status",value);
    }
    
    /**
     * Permet de remettre le label qui indique le status de l'enregistrement à "default"
     * @param {type} field Le champ appelant
     * @returns {undefined}
     */
    function reinit_save_label(field){
        var form = field.closest('form'); //On récupère le formulaire auquel notre bouton appartient
        var lab_save_status = $(form).find('label.save_status');
        lab_save_status.attr('status','default');
    }
    
    /**
     * Met en forme les input "email", en fonction de la validité
     * @param {type} field Le champ à tester 
     * @returns {undefined}
     */
    function test_email_input(field){
        var titre = '#titre_' + field.attr('id');
        var validation = '#valid_' + field.attr('id');
        var input = '#' + field.attr('id');
        if(!isValidEmailAddress(field.val())){ //Si email invalide            
            validate(titre,'false');
            validate(validation,'false');
            validate(input,'false');
        }
        else{
            validate(titre,'true');
            validate(validation,'true');
            validate(input,'true');
        }
    }
    
    /**
     * Permet de tester la validité d'une adresse email
     * @param {string} emailAddress
     * @returns {Boolean}
     */
    function isValidEmailAddress(emailAddress) {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        return pattern.test(emailAddress);
    }
    
    /**
     * Met en forme les input "birth_date" en fonction de la validité
     * @param {type} field Le champ à tester
     * @returns {undefined}
     */
    function test_birthdate_input(field){
        var titre = '#titre_' + field.attr('id');
        var validation = '#valid_' + field.attr('id');
        var input = '#' + field.attr('id');
        if(!(field.val())){ //Si chaine vide           
            validate(titre,'false');
            validate(validation,'false');            
            validate(input,'false');
        }
        else{
            if(calculate_age(field.val()) < 12){
                validate(titre,'false');
                validate(validation,'false');
                validate(input,'false');
            } else {
                validate(titre,'true');
                validate(validation,'true');
                validate(input,'true');                
            }
        }
    }
    
    /**
     * Calcul l'âge du pilote
     * @param {string} birthday La date de naissance
     * @returns {Number} L'âge
     */
    function calculate_age(string_birthday) { // birthday is a date
        var today = new Date();
        var birthDate = new Date(string_birthday);
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }
    
    /**
     * Permet de tester la validité des champs texte
     * @returns {undefined}
     */
    function test_text_input(field, min_length, max_length, type){        
        var titre = '#titre_' + field.attr('id');
        var validation = '#valid_' + field.attr('id');
        var input = '#' + field.attr('id');
        if(!(field.val())){ //Si chaine vide           
            validate(titre,'false');
            validate(validation,'false');
            validate(input,'false');
        }
        else{ //Si chaine non vide
            //On vérifie que l'on veut pas un nombre
            if(length_ok(field.val(), min_length, max_length) && type_ok(field.val(),type)){ //On vérifie la taille de la chaine, sa nature
                validate(titre,'true');
                validate(validation,'true');
                validate(input,'true');
            } else {          
                validate(titre,'false');
                validate(validation,'false');
                validate(input,'false');                
            }
        }
    }
    
    /**
     * Vérifie que la taille d'une chaine est bien comprise entre deux bornes
     * @param {string} str La chaine à tester
     * @param {int} min La taille mini
     * @param {int} max La taille maxi
     * @returns {boolean} Vrai si la chaine est de bonne taille, ou que les bornes ne sont pas définies
     */
    function length_ok(str,min,max){
        var is_ok = true;
        is_ok = is_ok && ((min && (str.length >= min)) || ! min); //Vrai si min n'est pas définit, ou s'il est définit et que la chaine est plus longue        
        is_ok = is_ok && ((max && (str.length <= max)) || ! max); //Vrai si max n'est pas définit, ou s'il est définit et que la chaine est plus courte
        return is_ok;
    }
    
    /**
     * Vérifie qu'une chaine est du bon type
     * @param {type} str La chaine à tester
     * @param {type} type Le type auquel on veut que la chaine corresponde
     * @returns {Boolean} Vrai si la chaine correspond au type demandé
     */
    function type_ok(str,type){
        var is_ok = true;
        is_ok = is_ok && ((type == 'number' && $.isNumeric(str)) || (type != 'number'));
        return is_ok;
    }
    
    function test_sel_input(field){
        var titre = '#titre_' + field.attr('id');
        var validation = '#valid_' + field.attr('id');
        var input = '#' + field.attr('id');
        if(field.attr('value') == '-1'){ //Si on a rien sélectionné         
            validate(titre,'false');
            validate(validation,'false');
            validate(input,'false');
        }
        else{ //Si chaine non vide
            validate(titre,'true');
            validate(validation,'true');
            validate(input,'true');

        }
    }
});