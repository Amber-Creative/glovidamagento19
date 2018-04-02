/**
 * Created by Артём on 14.07.2015.
 */
function delete_notifications(url){
    new Ajax.Request(url, {
        method: 'post',
        data: {
            form_key: FORM_KEY,
        },
        onSuccess: function(response) {
            document.location.href = response;
        }
    });
}