<?php
namespace Drupal\api_module\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\node\Entity\Node;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;

class ApiController extends ControllerBase{
  function content($node_id,$key){
    $values = \Drupal::entityQuery('node')->condition('nid', $node_id)->execute();
    if(!empty($values)){
      $node =  Node::load($node_id)->toArray();
      $siteapi = \Drupal::configFactory()->getEditable('api_module.settings')->get('siteapikey'); 
      // To check the siteapikey and node is valid or not
      if(!empty($node) && $node['type'][0]['target_id'] == 'page' && $siteapi == $key){
       return new JsonResponse($node);
      }
      // If entered values is not valid then redirect to access denied.
      else{
        $redirect_url = new \Drupal\Core\Url('system.403');
        $response = new RedirectResponse($redirect_url->toString());
        $response->send();
        return $response; 
      }
    }else{
      $redirect_url = new \Drupal\Core\Url('system.403');
      $response = new RedirectResponse($redirect_url->toString());
      $response->send();
      return $response; 
    }
  }
}
