<?php

    define('_JEXEC', 1);
    define('DS', DIRECTORY_SEPARATOR);

    $curDir	= dirname(__FILE__);

    define('JPATH_BASE', $curDir);

    require_once ( JPATH_BASE.DS.'includes'.DS.'defines.php' );
    require_once ( JPATH_BASE.DS.'includes'.DS.'framework.php' );
    require_once ("vendor/autoload.php");

    use \DrewM\MailChimp\MailChimp;

    class Subscribers
    {
        private $api_key;
        private $db;
        private $MailChimp;
        private $list_id;
        private $config;


        /**
         * initialise
         */
        public function __construct()
        {
            $this->db = JFactory::getDbo();
            $this->config = JComponentHelper::getParams('com_plants');

            $this->api_key = $this->config->get('mail_chimp_api_key');
            $this->MailChimp = new MailChimp($this->api_key);

            $result = $this->MailChimp->get('lists');
            $this->list_id = $result['lists'][0]['id'];
        }


        /**
         * return subscribers from database
         */
        public function getSubscribers()
        {
            $query = $this->db->getQuery(true);
            $query->select('id, email');
            $query->from('#__z_plants_subscribers');
            $query->where('status = 0');
            $this->db->setQuery($query);

            return $this->db->loadObjectList();
        }


        /**
         * @param $subscribers
         * save subscribers to mailchimp list
         */
        public function saveSubscribers($subscribers)
        {
            foreach ($subscribers as $subscriber)
            {
                $this->MailChimp->post("lists/$this->list_id/members", [
                        'email_address' => $subscriber->email,
                        'status'        => 'subscribed',
                    ]
                );
            }
        }


        /**
         * @param $subscribers
         * update subscribers status
         */
        public function updateStatus($subscribers)
        {
            $ids = '0';

            foreach ($subscribers as $subscriber)
            {
                $ids .= ',' . $subscriber->id;
            }

            $query = $this->db->getQuery(true);
            $query->update('#__z_plants_subscribers');
            $query->set('status = 1');
            $query->where("id IN ($ids)");

            $this->db->setQuery($query);
            $this->db->execute();
        }


        /**
         * Create Campaign
         */
        public function createCampaign()
        {
            $params = JComponentHelper::getParams('com_plants');
            $email = $params->get('site_email');
            $subj  = $params->get('mail_from');

            $plants = $this->getLastPlants();
            $template_id = $this->config->get('mail_chimp_template_id');

            $this->MailChimp->post("campaigns", [
                'type' => 'regular',
                'recipients' => ['list_id' => $this->list_id],
                'settings' => ['subject_line' => 'Last Added Plants On Site',
                    'template_id' => (int) $template_id,
                    'reply_to' => $email,
                    'from_name' => $subj
                ]
            ]);

            $response = $this->MailChimp->getLastResponse();

            $responseObj = json_decode($response['body']);


            $result = $this->MailChimp->get('campaigns/' . $responseObj->id . '/content');

            $content = $this->getHtml($plants);

            $html = $result['html'];
            $html = str_replace('###', $content, $html);

            $this->MailChimp->put('campaigns/' . $responseObj->id . '/content', ['html' => $html]);
            $this->MailChimp->post('campaigns/' . $responseObj->id . '/actions/send');
        }


        /**
         * @param $plants
         * @return string
         * Message content
         */
        public function getHtml($plants)
        {
            $table = '<table cellpadding="10">';

            $counter = 1;

            foreach ($plants as $plant)
            {
                if($counter == 1)
                    $table .= '<tr>';

                $table .=   '<td style="vertical-align: top; text-align: center">';
                $table .=       '<a href="' . $this->config->get('site_address') . 'plant?id=' . $plant->id . '"><img src="' . $this->config->get('site_address') . 'images/plants/' . $plant->photo . '"></a>';
                $table .=       '<br>';
                $table .=       '<b><a href="' . $this->config->get('site_address') . 'plant?id=' . $plant->id . '">' . $plant->sort_name . '</a></b>';
                $table .=       '<p>'.$plant->description.'</p>';
                $table .=   '</td>';

                if($counter == 2)
                {
                    $table .= '</tr>';
                    $counter = 0;
                }

                $counter++;
            }

            $table .= '</table>';

            return $table;
        }


        /**
         * @return last added plants
         */
        public function getLastPlants()
        {
            $query = $this->db->getQuery(true);
            $query->select('p.id, p.sort_name, pp.src as photo, p.description');
            $query->from('#__z_plants_plants p');
            $query->innerJoin('#__z_plants_plant_photos pp ON pp.plant_id = p.id');
            $query->where('p.published = 1');
            $query->order('p.created_at DESC');
            $query->group('p.id');
            $query->setLimit(12);

            $this->db->setQuery($query);
            return $this->db->loadObjectList();
        }
    }

    $subs = new Subscribers();
    $subscribers = $subs->getSubscribers();

    if( !empty($subscribers) )
    {
        $subs->saveSubscribers($subscribers);
        $subs->updateStatus($subscribers);
    }

    $subs->createCampaign();
