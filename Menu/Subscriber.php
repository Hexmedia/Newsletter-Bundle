<?php

namespace Hexmedia\NewsletterBundle\Menu;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Hexmedia\AdministratorBundle\Menu\Subscriber as SubscriberAbstract;
use Hexmedia\AdministratorBundle\Menu\Builder as MenuBuilder;
use Hexmedia\AdministratorBundle\Menu\Event as MenuEvent;

class Subscriber extends SubscriberAbstract implements EventSubscriberInterface
{

	public function addPositions(MenuEvent $event)
	{
        $menu = $event->getMenu()->addChild($this->translator->trans("Newsletter"), ['route' => 'HexMediaNewsletterDashboard', 'id' => 'HexMediaNewsletter'])
            ->setAttribute('icon', 'fa fa-envelope');
        $menu->addChild($this->translator->trans("Persons"), ['route' => 'HexMediaNewsletterPerson', 'under' => 'HexMediaNewsletter']);
        $menu->addChild($this->translator->trans("Mails"), ['route' => 'HexMediaNewsletterMail', 'under' => 'HexMediaNewsletter']);

        return $event->getMenu();
	}

	public static function getSubscribedEvents()
	{
		return array(
			MenuBuilder::MENU_BUILD_EVENT => array('addPositions', 5)
		);
	}

}

?>
