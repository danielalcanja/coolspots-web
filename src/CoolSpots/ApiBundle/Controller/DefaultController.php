<?php

namespace CoolSpots\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use CoolSpots\SiteBundle\Entity\CsSubscriptions;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return new Response();
    }
	
	public function subscriptionAction($object, $object_id)
	{		
		$repository = $this->getDoctrine()->getRepository('SiteBundle:CsSubscriptions');
		$subscription = $repository->findBy(array('object' => $object, 'objectId' => $object_id));
		
		if(count($subscription) > 0) return new Response('Duplicated subscription');
		
		$em = $this->getDoctrine()->getManager();
		$max_subscriptions = $this->container->getParameter('instagram_max_subscriptions');
		$rsInstagram = $em->createQuery('
					SELECT i FROM SiteBundle:VwInstagramApi i
					WHERE i.totalSubscriptions < :max
					ORDER BY i.id')
				->setParameter('max', $max_subscriptions)
				->setMaxResults(1)
				->getSingleResult();
		if(!$rsInstagram) return new Response('No more instagram client id for subscriptions');
		$client_id = $rsInstagram->getClientId();
		$client_secret = $rsInstagram->getClientSecret();
		
		$Instagram = $this->getDoctrine()->getRepository('SiteBundle:CsInstagramApi')->find($rsInstagram->getId());
		
		$attachment =  array(
			'client_id' => $client_id,
			'client_secret' => $client_secret,
			'object' => $object,
			'object_id' => $object_id,
			'aspect' => $this->container->getParameter('instagram_aspect'),
			'verify_token' => $this->container->getParameter('instagram_verify_token'),
			'callback_url'=> $this->container->getParameter('instagram_callback_url')
		);
		
		// URL TO THE INSTAGRAM API FUNCTION
		$url = $this->container->getParameter('instagram_subscription_api');

		$ch = curl_init();

		// EXECUTE THE CURL...
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $attachment);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //to suppress the curl output 
		$result = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($result, true);
		
		/*
		 * {"meta":{"code":200},"data":{"object":"location","object_id":"6620052","aspect":"media","callback_url":"http:\/\/api.coolspots.com.br\/instagram\/callback\/6620052","type":"subscription","id":"3829836"}}
		 */
		if($response['meta']['code'] == 200)
		{
			// success
			$Subscription = new CsSubscriptions();
			$Subscription->setObject($object);
			$Subscription->setObjectId($object_id);
			$Subscription->setChangedAspect(null);
			$Subscription->setTime(null);
			$Subscription->setUpdated('N');
			$Subscription->setCycleCount(0);
			$Subscription->setIdInstagramApi($Instagram);
			$Subscription->setSubscriptionId($response['data']['id']);
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($Subscription);
			$em->flush();
		}
		else
		{
			echo "<h2>Error:</h2>";
			echo "<pre>";
			print_r($response);
			echo "</pre>";
		}
		return new Response();
	}
	
	public function callbackAction()
	{
		$request = $this->getRequest();
		if($request->isMethod('GET'))
		{
			if($request->get('hub_challenge') && $request->get('hub_verify_token') == $this->container->getParameter('instagram_verify_token'))
			{
				return new Response($request->get('hub_challenge'));
			}
		}
		else
		{
			$items = json_decode($request->getContent(), true);
			try 
			{
				foreach($items as $item)
				{
					$em = $this->getDoctrine()->getManager();
					$subscription = $em->getRepository('SiteBundle:CsSubscriptions')->findOneBy(array('object' => $item['object'], 'objectId' => $item['object_id']));
					if(!$subscription) continue;
					$subscription->setChangedAspect($item['changed_aspect']);
					$ts = new \DateTime();
					$ts->setTimestamp($item['time']);
					$subscription->setTime($ts);
					$subscription->setUpdated('N');
					$subscription->setCycleCount($subscription->getCycleCount() + 1);
					$em->flush();
				}
			} 
			catch(Exception $e)
			{
				throw new Exception('Error updating subscription: ' . $e->getMessage());
			}

		}
		return new Response();
	}
}
