<?php

defined('_JEXEC') or die;

class PlantsControllerPlantsettings extends JControllerLegacy
{
    protected $model;
    protected $app;
    protected $data;

    /**
     * PlantsControllerPlantsettings constructor.
     * @param array $config
     * @throws Exception
     */
    public function __construct(array $config = array())
    {
        parent::__construct($config);
        $this->model = $this->getModel('Plantsettings', 'PlantsModel');
        $this->app = JFactory::getApplication();
    }



    /**
     * delete plant photos handler
     */
    public function deletePhoto()
    {
        JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

        $filename = $this->input->post->get('filename', '', 'RAW');
    	$type     = $this->input->post->get('type');

    	if($filename && $type)
    	{
    		$this->model = $this->getModel('Plantsettings', 'PlantsModel');
    		$this->model->deletePlantsPhotos($filename, $type);
    		exit;
	    }
    }



    /**
     * delete plant handler
     */
    public function deletePlant()
    {
        $id = $this->input->get('id');

        if($id)
        {
            $this->model->deletePlant($id);
            JFactory::getApplication()->redirect('plants');
        }
    }



    /**
     * save plant data handler
     */
    public function save()
    {
        if(!JFactory::getUser()->guest)
            JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

        $this->data = $this->input->getArray(array(
            'jform' => array(
                'sort_name'            => 'string',
                'plant_category_id'    => 'int',
                'plant_type_id'        => 'int',
                'city_id'              => 'int',
                'barcode'              => 'string',
                'planting_date'        => 'string',
                'transplantation_date' => 'string',
                'purchased'            => 'string',
                'preseeding'           => 'int',
                'price'                => 'string',
                'manufactured'         => 'string',
                'seeds_in_package'     => 'int',
                'germinability'        => 'int',
                'yield'                => 'int',
                'author_recomends'     => 'int',
                'easy_care'            => 'int',
                'description'          => 'string',
                'id'                   => 'int',
                'custom_fields'        => 'raw'
            )
        ));

        $this->data['jform']['price'] = str_replace(',','.', $this->data['jform']['price']);


        $user_id = $this->getUserId();
        $formData = $this->data['jform'];
        $formData['seeds_photo']   = $this->input->files->get('seeds_photo');
        $formData['barcode_photo'] = $this->input->files->get('barcode_photo');

        $plantPhotos = $this->input->files->get('photo', false, 'array');

        //clear old photos data
        if($formData['id'])
        {

            $photos = [
                'seeds'   => $formData['seeds_photo'],
                'barcode' => $formData['barcode_photo']
            ];

            $this->model->clearOldData($photos, $formData['id']);
        }

        //save plant
        $plant_id = $this->model->savePlantData($formData);

        //save plant photo
        if( $plantPhotos )
            $this->model->savePlantPhotos($plantPhotos, $plant_id);

        //if new plant
        if(!$formData['id']) {

            $this->model->assignPlantToUser($plant_id, $user_id);

            //send email to user and admin about added plant
            $this->sendEmails($user_id);

            $this->app->enqueueMessage(JText::_('ADD_PLANT_MESSAGE'), 'message');

        } else {

            $this->app->enqueueMessage(JText::_('PLANT_DETAILS_SAVED'), 'message');
        }

        JFactory::getSession()->clear('jform');
        $this->app->redirect('/plants');
    }



    /**
     * @return user_id
     *
     * register user and return user_id
     * or
     * auth user and return user_id
     */
    private function getUserId()
    {
        //if user guest
        if( JFactory::getUser()->guest ) {

            $email = $this->input->get('email', false, 'RAW');
            $password = $this->input->get('password', false, 'RAW');

            //user registration
            if($email && $password) {

                //user isset
                if( $this->model->isUserIsset($email) ) {

                    //try login
                    $result = $this->model->login($email, $password);

                    if(!$result)
                    {
                        $this->model->dataToSession($this->data['jform']);
                        $this->app->redirect('/add');
                    }

                //user not found
                } else {

                    JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_plants/models', 'PlantsModel');
                    $model = JModelLegacy::getInstance('Signup', 'PlantsModel');

                    $data = [
                        'id'        => 0,
                        'email'     => $email,
                        'password'  => $password
                    ];

                    $user_id = $model->register($data, false, 'addplant');

                    return $user_id;
                }
            }
        }

        //if user authorized
        return JFactory::getUser()->id;
    }



    /**
     * @param $user_id
     */
    private function sendEmails($user_id)
    {
        //send email to administrator about new added plant
        $this->model->sendAdminNotify($user_id);

        //send email to user about added plant
        $this->model->sendUserNotify($user_id);
    }


    /**
     * get Cities for city field "selectize" by ajax
     */
    public function getCities()
    {
        $query = $this->input->get('query', '', 'RAW');
        $cities = $this->model->getCities($query);

        echo json_encode($cities);
        exit;
    }
}
