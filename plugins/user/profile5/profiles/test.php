<?php

defined('_JEXEC') or die;

jimport('joomla.form.formfield');

class JFormFieldTest extends JFormField
{
    protected $type = 'test';

    public function getInput()
    {
        $input = JFactory::getApplication()->input;
        $user_id = $input->get('id');


        if($user_id) {
            $db = JFactory::getDbo();
            $db->setQuery("SELECT profile_value AS src FROM #__user_profiles WHERE profile_key = 'profile5.photo' AND user_id = $user_id");
            $image = $db->loadObject();

            if ($image) {
                $src = JUri::root() . 'images/user_photos/' . str_replace("\"", "", $image->src);
                $html = '
            <div style="width: 200px">
                <img src="' . $src . '">
                <br><br>
                <button id="delete-user-photo" class="btn btn-default" data-user-id="' . $user_id . '" >delete photo</button>
            </div>
            
            <script>
                jQuery(document).ready(function(){
                    jQuery("#delete-user-photo").click(function(){
                        
                        var question = confirm("Delete photo?");
                        
                        if(question)
                        {
                            var userId = jQuery("#delete-user-photo").attr("data-user-id"); 
                        
                            jQuery.ajax({
                                type: "POST",
                                url: "' . JURI::root() . 'plugins/user/profile5/ajax.php",
                                data: "id="+userId,
                                success: function(result)
                                {
                                    location.reload();
                                }
                            });
                            
                            return false;
                        }
                        
                    });
                })
            </script>
            ';
            } else {
                $html = 'No photo';
            }

            return $html;
        }
    }
}