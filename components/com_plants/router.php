<?php

defined('_JEXEC') or die;

class PlantsRouter extends JComponentRouterBase
{
	public function build(&$query)
	{
	    $segments = array();

		if (isset($query['task'])) {
			$segments[] = $query['task'];
			unset($query['task']);
		}

		if (isset($query['id'])) {
			$segments[] = $query['id'];
			unset($query['id']);
		}

		$total = count($segments);

		for ($i = 0; $i < $total; $i++) {
			$segments[$i] = str_replace(':', '-', $segments[$i]);
		}

		return $segments;
	}

	public function parse(&$segments)
	{
	    $vars = array();

		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$item = $menu->getActive();

		$count = count($segments);

		if (isset($item->query['view']))
		{
			switch ($item->query['view'])
            {
                case 'main':
                    if( $segments[0] == 'search' || ($segments[0] == 'edit' && isset( $segments[1]) && (int)$segments[1]) )
                        break;

                    throw new Exception(JText::_('JERROR_PAGE_NOT_FOUND'), 404);
                    break;

                case 'plants':
				    if ($count == 1)
					{
					    if( ctype_digit($segments[0]) )
                        {
                            $vars['view'] = 'plants';
                            $vars['id'] = $segments[0];
                            $vars['id'] = str_replace(":", "-", $vars['id']);
                        }
                        else
                        {
                            $vars['view'] = 'plants';
                            $vars['category'] = $segments[0];
                            $vars['category'] = str_replace(":", "-", $vars['category']);
                        }

                    }
				    else if ($count == 2)
				    {

				        $vars['view'] = 'plants';
                        $vars['id'] = $segments[0];
                        $vars['id'] = str_replace(":", "-", $vars['id']);
					    $vars['category'] = (string) $segments[1];
						$vars['category'] = str_replace(":", "-", $vars['category']);
					}
					break;

                case 'catalog':
                    if ($count == 1) {
                        $vars['view'] = 'catalog';
                        $vars['segment'] = (string) $segments[0];
                        $vars['segment'] = str_replace(":", "-", $vars['segment']);
                    }
                    break;
			}
		}

		if($segments[0] == 'edit')
        {
            if($segments[1])
            {
                $vars['view'] = 'plantsettings';
                $vars['id']   = $segments[1];
            }
        }

        //signup
        if ($segments[0] == 'fbSignup')
            $vars['task'] = 'signup.fbsignup';

        if ($segments[0] == 'gSignup')
            $vars['task'] = 'signup.gsignup';


        //signin
        if ($segments[0] == 'fbAuth')
            $vars['task'] = 'auth.fbAuth';

        if ($segments[0] == 'gAuth')
            $vars['task'] = 'auth.gAuth';


        if ($segments[0] == 'delete')
        {
            $vars['task'] = 'plant.deleteitem';
            $vars['id'] = $segments[1];
        }

        if($segments[0] == 'search')
        {
            $vars['view'] = 'catalog';
        }

        return $vars;
	}
}