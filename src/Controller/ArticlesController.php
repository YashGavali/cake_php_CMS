<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Http\Cookie\Cookie;
use Cake\Http\Cookie\CookieCollection;
use Cake\Mailer\Mailer;
use Cake\Mailer\MailerAwareTrait;
use DateTime;

class ArticlesController extends AppController
{
    use MailerAwareTrait;
    public function index()
    {
        $pagevisitsmade = $this->request->getCookie('pagevisits');
        //Creating a cookie

        // $cookie = (new Cookie('four'))
        //     ->withValue('Fourth edited Post')
        //     ->withExpiry(new DateTime('+1 year'))
        //     ->withPath('/')
        //     ->withDomain('')
        //     ->withSecure(false)
        //     ->withHttpOnly(true);

        //Create CookieCollection Object

        // $cookies = new CookieCollection([$cookie]);
        // $cookieCount = count($cookies);
        //Attaching cookieCollection to response

        // $this->response = $this->response->withCookieCollection($cookies);
        // $dat = $this->response->getCookie('remember_me');

        $this->loadComponent('Paginator');
        $articles = $this->Paginator->paginate($this->Articles->find());
        $favArticleVal  = $this->request->getCookie('favarticles');
        $this->set(compact('articles', 'favArticleVal'));
        //$session = $this->request->getSession()->read('Auth');


        //Cookie delete 
        // $this->response = $this->response->withExpiredCookie(new Cookie('four'));
    }

    public function view($slug = null)
    {

        $favArticles = $this->request->getCookie('favarticles');
        $favoriteThreshold = 3;
        $currentArticleSlug = $this->request->getParam("pass");
        $cookieObj = $this->request->getCookie($currentArticleSlug[0]);

        if ($cookieObj) {
            $pageVisitValue = (int)$cookieObj + 1;
            if ($pageVisitValue == $favoriteThreshold) {
                if ($favArticles) {
                    $favArticles = $favArticles . "|" . $currentArticleSlug[0];
                    $favoriteArticlesCookie = (new Cookie('favarticles'))
                        ->withValue($favArticles)
                        ->withExpiry(new DateTime('+1 year'))
                        ->withPath('/')
                        ->withDomain('')
                        ->withSecure(false)
                        ->withHttpOnly(true);
                    $this->response = $this->response->withCookie($favoriteArticlesCookie);
                } else {
                    $favArticles = $currentArticleSlug[0];
                    $favoriteArticlesCookie = (new Cookie('favarticles'))
                        ->withValue($favArticles)
                        ->withExpiry(new DateTime('+1 year'))
                        ->withPath('/')
                        ->withDomain('')
                        ->withSecure(false)
                        ->withHttpOnly(true);
                    $this->response = $this->response->withCookie($favoriteArticlesCookie);
                }
            } else {
                $favArticles = $currentArticleSlug[0];
            }

            $pageVisitValue = (string)$pageVisitValue;
        } else {
            $pageVisitValue = '1';
        };

        $cookieObj = $this->request->getCookie('pagevisits');

        $pagevisitsmade = $this->request->getCookie('pagevisits');
        $cookie = (new Cookie($currentArticleSlug[0]))
            ->withValue($pageVisitValue)
            ->withExpiry(new DateTime('+1 year'))
            ->withPath('/')
            ->withDomain('')
            ->withSecure(false)
            ->withHttpOnly(true);



        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        $this->response = $this->response->withCookie($cookie);

        // debug($cookieObj);
        $this->set(compact('article'));
        // debug($this->request->getCookie('four'));

        // if ($cookieObj) {
        //     debug($cookieObj);
        // } else {
        //     debug('not exist');
        // };
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

    public function sendMail()
    {

        // $mailer = new Mailer();
        $mailer = $this->getMailer('User');
        // // $mailer
        //     ->setTo('yg20298@gmail.com')
        //     ->setSubject('About testing mail in Cakephp')
        //     ->deliver('Test message');

        //$mailer->push('welcome', ['yg20298@gmail.com', 'josegonzalez']);
        $mailer->push('welcome', ['yg20298@gmail.com', 'test']);
    }
}
