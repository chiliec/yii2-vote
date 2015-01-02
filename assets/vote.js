/**
 * Голосовалка
 */
var attempt = new Array();
var smiles = [
    "&#65381;_&#65381;",
    "&not;_&not;",
    "&#3232;_&#3232;",
    "&#3232;&#65343;&#3232;",
    "&#3232;&#30410;&#3232;",
    ">&#65343;<",
    "=&#65343;="
];

/**
 * @param model - model_name
 * @param target - target_id
 * @param act - 'like' or 'dislike'
 */
function vote(model,target,act) {
    if(['like','dislike'].indexOf(act) == -1 ) return;
    if (typeof attempt[target] == 'undefined') {
        attempt[target] = 1;
        jQuery.ajax({
            url: '/vote/',
            type: "GET",
            data: {
                model_name: model,
                target_id: target,
                act: act
            },
            success: function (data) {
                //todo: проверка на удачность голосования без привязки к тексту ответа
                if(act=='like') {
                    jQuery('#vote'+target+' .glyphicon-thumbs-up').text(parseInt(jQuery('#vote'+target+' .glyphicon-thumbs-up').text()) + 1);
                } else if(act=='dislike') {
                    jQuery('#vote'+target+' .glyphicon-thumbs-down').text(parseInt(jQuery('#vote'+target+' .glyphicon-thumbs-down').text()) + 1);
                }
                jQuery('#vote-response'+target).html(data);
            }
        });
    } else {
        attempt[target]++;
        if (attempt[target] < 5) {
            reply='Не шалите!'
        }
        else {
            reply = smiles[Math.floor(Math.random()*smiles.length)];
        }
        jQuery('#vote-response'+target).html(reply);
    }
}
