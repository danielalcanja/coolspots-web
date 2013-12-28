<?php

namespace CoolSpots\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use CoolSpots\SiteBundle\Entity\CsUsers;

class LoginController extends Controller
{

	/**
	 * @Template("SiteBundle:Login:index.html.twig")
	 */
    public function indexAction(){
		$signin_url = str_replace(array('@CLIENT_ID@', '@REDIRECT_URI@'), array($this->container->getParameter('instagram_auth_client_id'), urldecode($this->container->getParameter('instagram_auth_redirect_url'))), $this->container->getParameter('instagram_auth_api'));
		return(array('signin_url' => $signin_url));
    }
	
	/**
	 * @Template("SiteBundle:Login:connect.html.twig")
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function connectAction() {
		$signin_url = str_replace(array('@CLIENT_ID@', '@REDIRECT_URI@'), array($this->container->getParameter('instagram_auth_client_id'), urldecode($this->container->getParameter('instagram_auth_redirect_url'))), $this->container->getParameter('instagram_auth_api'));
		$session = $this->getRequest()->getSession();
		$total_favorites = 0;
		$total_messages = 0;
		$total_friends = 0;
		if($session->has('userid')) {
			$em = $this->getDoctrine()->getManager();
			$Friends = $em->getRepository('SiteBundle:CsFriends')->findBy(array('idUser' => $session->get('userid')));
			$Favorites = $em->getRepository('SiteBundle:CsLocationFavorites')->findBy(array('idUser' => $session->get('userid'), 'deleted' => 'N'));
			$total_friends = count($Friends);
			$total_favorites = count($Favorites);
		}
		return(array('signin_url' => $signin_url, 'total_messages' => $total_messages, 'total_favorites' => $total_favorites, 'total_friends' => $total_friends));
	}
	
	public function authAction(){
		$request = $this->getRequest();
		$session = $request->getSession();
		if($request->get('error')) {
			return new Response('<h2>Error</h2><p>' . $request->get('error_description') . '</p>');
		}
		$code = $request->get('code');
		$ch = curl_init();
		$url = $this->container->getParameter('instagram_auth_token_url');
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array(
			'client_id' => $this->container->getParameter('instagram_auth_client_id'),
			'client_secret' => $this->container->getParameter('instagram_auth_client_secret'),
			'grant_type' => 'authorization_code',
			'redirect_uri' => $this->container->getParameter('instagram_auth_redirect_url'),
			'code' => $code
		));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close ($ch);
		$ret = json_decode($result, true);
		
		if(isset($ret['access_token'])) {
			// check if the user is already on database
			$em = $this->getDoctrine()->getManager();
			$user = $em->getRepository('SiteBundle:CsUsers')->findOneBy(array('username' => $ret['user']['username']));
			if(!$user) {
				$user = new CsUsers();
			}
			$user->setUsername($ret['user']['username']);
			$user->setFullName($ret['user']['full_name']);
			$user->setProfilePicture($ret['user']['profile_picture']);
			$user->setAccessToken($ret['access_token']);
			$user->setTokenDate(new \DateTime());
			$em->persist($user);
			$em->flush();
			
			// write cookies
			$session->set('username', $ret['user']['username']);
			$session->set('full_name', $ret['user']['full_name']);
			$session->set('profile_picture', $ret['user']['profile_picture']);
			$session->set('access_token', $ret['access_token']);
			$session->set('userid', $user->getId());
			return $this->redirect($this->generateUrl('main'));
		} else {
			return new Response('Authentication error!');
		}
	}
	
	public function disconnectAction() {
		$this->getRequest()->getSession()->clear();
		return $this->redirect($this->generateUrl('main'));
	}

}
