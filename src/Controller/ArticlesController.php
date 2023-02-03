<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Http\Cookie\Cookie;
use DateTime;

class ArticlesController extends AppController
{

    public function index()
    {

        $cookie = (new Cookie('trial'))
            ->withValue('testingcookie')
            ->withExpiry(new DateTime('+1 year'))
            ->withSecure(false)
            ->withPath('/')
            ->withHttpOnly(true);

        // $this->autoRender = false;
        $cookieValue = $cookie->getValue();
        $this->set(compact('cookieValue'));

        $this->loadComponent('Paginator');
        $articles = $this->Paginator->paginate($this->Articles->find());
        $this->set(compact('articles'));
        // echo '<pre>';
        $session = $this->request->getSession()->read('Auth');
        // $session->write('visitIndexArticle', 'true');
        // $session->write('testCookie', 'true');
        $response = $this->response;

        $name = $session->read('Auth');
        $response = $response->withType('application/json')
            ->withStringBody(json_encode($name));

        return $response;
        $this->response->withCookie($cookie);
    }

    public function view($slug = null)
    {
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        $this->set(compact('article'));
    }

    public function add()
    {
        $article = $this->Articles->newEmptyEntity();

        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());
            $article->user_id = $this->request->getAttribute('identity')->getIdentifier();
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }

            $this->Flash->error(__('Unable to add your article.'));
        }
        $tags = $this->Articles->Tags->find('list')->all();
        $this->set('tags', $tags);
        $this->set('article', $article);
    }

    public function edit($slug)
    {
        $article = $this->Articles->findBySlug($slug)->firstOrFail();

        if ($this->request->is(['post', 'put'])) {
            $this->Articles->patchEntity($article, $this->request->getData());
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update your article.'));
        }
        $tags = $this->Articles->Tags->find('list')->all();
        $this->set('tags', $tags);

        $this->set('article', $article);
    }

    public function delete($slug)
    {
        $this->request->allowMethod(['post', 'delete']);

        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('The {0} article has been deleted.', $article->title));
            return $this->redirect(['action' => 'index']);
        }
    }
}
