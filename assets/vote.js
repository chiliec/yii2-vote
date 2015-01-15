var attempts = [];
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
function vote(model,target,act)
{
    if(['like','dislike'].indexOf(act) === -1) return;
    if (attempts[model+target] !== true) {
        attempts[model+target] = true;
        jQuery.ajax({
            url: '/vote/',
            type: "GET",
            data: {
                model_name: model,
                target_id: target,
                act: act
            },
            success: function (data) {
                if(data.successfully === true) {
                    if(act==='like') {
                        jQuery('#vote-up-'+model+target).text(parseInt(jQuery('#vote-up-'+model+target).text()) + 1);
                    } else {
                        jQuery('#vote-down-'+model+target).text(parseInt(jQuery('#vote-down-'+model+target).text()) + 1);
                    }
                }
                jQuery('#vote-response-'+model+target).html(data.content);
            }
        });
    } else {
        jQuery('#vote-response-'+model+target).html(smiles[Math.floor(Math.random()*smiles.length)]);
    }

}
