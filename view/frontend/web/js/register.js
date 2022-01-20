require(['jquery', 'jquery/ui'], function($){
    jQuery(document).ready( function() {
        $("#edit_privacy_on").click(function(){
            if (this.value=="Edit") {
                $('#edit_privacy').show();
                $('#privacy').hide();
                this.value = "Retrun";

            } else {
                $('#edit_privacy').hide();
                $('#privacy').show();
                this.value = "Edit";
            }
        });
    });
});
